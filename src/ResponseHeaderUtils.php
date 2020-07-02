<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http;

    /**
     * Utils class that contains methods useful for adding headers in the response.
     *
     * @package com\femastudios\utils\http
     */
    final class ResponseHeaderUtils {

        private function __construct() {
            throw new \LogicException();
        }

        /**
         * Parses the headers calling headers_list() and calls the given callable with the name and the value.
         * The callable shall return a boolean the indicates whether to halt the iteration.
         * @return boolean true if any callable halted the computation, false otherwise
         */
        private static function parseAll(callable $callable) : bool {
            foreach (headers_list() as $header) {
                $pos = strpos($header, ':');
                if ($pos === false) {
                    // Unable to find colon in set header
                    continue;
                } else {
                    $name = trim(substr($header, 0, $pos));
                    $value = trim(substr($header, $pos + 1, \strlen($header) - $pos - 1));
                    if ($name === '') {
                        // Empty header name
                        continue;
                    } elseif ($value === '') {
                        // Empty header value
                        continue;
                    }
                    if (!$callable($name, $value)) {
                        return true;
                    }
                }
            }
            return false;
        }

        /**
         * Returns an associative array where each item key is an header name and its value is the value of the header.
         * Any header that is not parsable (does not contain a colon, has empty name or value) is skipped and returned in this array.
         *
         * @return array the associative array of headers
         */
        public static function getAll() : array {
            $ret = [];
            static::parseAll(static function (string $name, string $value) use (&$ret) : bool {
                $ret[$name] = $value;
                return true;
            });
            return $ret;
        }

        /**
         * @param string $header an header name
         * @return bool whether the given header name is present
         * @see HeaderUtils::getAll()
         */
        public static function has(string $header) : bool {
            return static::parseAll(static function (string $name) use ($header) : bool {
                return strcasecmp($name, $header) !== 0;
            });
        }

        /**
         * @param string $header an header name (case insensitive)
         * @param string $defaultValue a default value
         * @return string the value associated with the given header name, or the default value
         * @throws \LogicException if an header with the given name cannot be found and the default value is null
         * @see HeaderUtils::opt()
         */
        public static function get(string $header, string $defaultValue = null) : string {
            $opt = static::opt($header);
            if ($opt !== null) {
                return $opt;
            } else {
                if ($defaultValue === null) {
                    throw new \LogicException("Header $header not found in response headers");
                } else {
                    return $defaultValue;
                }
            }
        }

        /**
         * @param string $header an header name (case insensitive)
         * @param string|null $defaultValue a default value
         * @return string|null the value associated with the given header name, or the default value
         * @see HeaderUtils::getAll()
         */
        public static function opt(string $header, ?string $defaultValue = null) : ?string {
            static::parseAll(static function (string $name, string $value) use ($header, &$ret) : bool {
                if (strcasecmp($name, $header) === 0) {
                    $ret = $value;
                    return false;
                } else {
                    return true;
                }
            });
            return $ret ?? $defaultValue;
        }

        /**
         * Adds the specified header/value pair to the list of headers to output.
         * If a header with the same name was already added, it is overwritten.
         * @param string $header the header name
         * @param string $value the header value
         * @throws \LogicException if headers have already been sent
         */
        public static function put(string $header, string $value) : void {
            self::checkSent();
            header($header . ': ' . $value);
        }

        /**
         * Removes the specified header
         * @param string $header the header name to remove
         * @throws \LogicException if headers have already been sent
         */
        public static function remove(string $header) : void {
            self::checkSent();
            header_remove($header);
        }

        /**
         * @return bool whether the headers have already been sent. If so, no headers can be added, removed or modified.
         */
        public static function sent() : bool {
            return headers_sent();
        }

        /**
         * Checks if the headers have been sent
         * @throws \LogicException if they have been
         */
        private static function checkSent() : void {
            if (self::sent()) {
                throw new \LogicException('Headers have already been sent');
            }
        }

        /**
         * Concatenates the given value to the specified header, separating it with the specified glue.
         * If the header wasn't already specified it puts the value without the glue.
         * @param string $header the header name
         * @param string $value the value to concatenate
         * @param string $glue the glue (defaults to empty string)
         * @throws \LogicException if headers have already been sent
         */
        public static function concat(string $header, string $value, string $glue = '') : void {
            $opt = static::get($header);
            if ($opt !== null) {
                $value = $opt . $glue . $value;
            }
            static::put($header, $value);
        }

        private static function parseCsv(?string $value) : ?array {
            return $value === null ? null : explode(',', $value);
        }

        /**
         * Similar to {@link ResponseHeaderUtils::opt()}, but parses the value as a comma separated value and returns an array
         * @param string $header the header name
         * @param array|null $defaultValue the default value
         * @return array|null an array containing the comma separated values, or null
         */
        public static function optCsv(string $header, ?array $defaultValue = null) : ?array {
            return static::parseCsv(static::opt($header)) ?? $defaultValue;
        }

        /**
         * Similar to {@link ResponseHeaderUtils::get()}, but parses the value as a comma separated value and returns an array
         * @param string $header the header name
         * @return array an array containing the comma separated values
         * @throws \LogicException if an header with the given name cannot be found
         */
        public static function getCsv(string $header) : array {
            return static::parseCsv(static::get($header));
        }

        /**
         * Adds to the given header the given values, separated by a comma.
         * @param string $header the header name
         * @param string ...$values the values to add. If none passed, the function does nothing.
         * @throws \LogicException if headers have already been sent
         */
        public static function addCsv(string $header, string ...$values) : void {
            self::checkSent();
            if (\count($values) > 0) {
                static::concat($header, implode(',', $values), ',');
            }
        }

        /**
         * Adds the header with the given name separating the given values by a comma.
         * @param string $header the header name
         * @param string ...$values the values. If none passed, the function removed any already present header with the given name.
         * @throws \LogicException if headers have already been sent
         */
        public static function putCsv(string $header, string ...$values) : void {
            if (\count($values) > 0) {
                static::put($header, implode(',', $values));
            } else {
                static::remove($header);
            }
        }

        /**
         * Removes from the given header the specified values, treating the existing header as a comma separated list.
         * @param string $header the header name
         * @param bool $caseSensitive whether to use case sensitive or insensitive comparision
         * @param string ...$values the values to remove. If none passed, the function does nothing.
         * @return array an associative array of removed headers
         * @throws \LogicException if headers have already been sent
         */
        public static function removeCsv(string $header, bool $caseSensitive, string ...$values) : array {
            self::checkSent();
            $opt = static::optCsv($header);
            $ret = [];
            if ($opt !== null) {
                foreach ($opt as $k => $value) {
                    $found = false;
                    foreach ($values as $toRemove) {
                        if ($caseSensitive ? $value === $toRemove : strcasecmp($value, $toRemove) === 0) {
                            $found = true;
                            break;
                        }
                    }
                    if ($found) {
                        $ret[$k] = $values;
                        unset($opt[$k]);
                    }
                }
                static::putCsv($header, ...$opt);
            }
            return $ret;
        }
    }