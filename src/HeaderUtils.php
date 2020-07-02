<?php
	declare(strict_types=1);

	namespace com\femastudios\utils\http;

	/**
	 * Utils class that contains methods useful for dealing with request headers.
     *
     * @package com\femastudios\utils\http
	 */
	final class HeaderUtils {

		private function __construct() {
			throw new \LogicException();
		}

		/**
		 * @param string $key a header key. Must-Be-In-First-Letter-Uppercase-Like-This
		 * @return bool true if the header exists, false otherwise
		 */
		public static function has(string $key) : bool {
			return array_key_exists($key, static::getAll());
		}

		/**
		 * @param string $key a header key. Must-Be-In-First-Letter-Uppercase-Like-This
		 * @return string the header value or the <code>$defaultValue</code>
         * @param string|null $defaultValue the value to return if the header is not found.
         * @throws \LogicException if the key is not found and the default value is null
		 */
		public static function get(string $key, ?string $defaultValue = null) : string {
			if (!static::has($key)) {
			    if($defaultValue === null) {
				    throw new \LogicException("The header '$key' doesn't exists");
                } else {
			        return $defaultValue;
                }
			} else {
				return static::getAll()[$key];
			}
		}

		/**
		 * @param string $key a header key. Must-Be-In-First-Letter-Uppercase-Like-This
		 * @param string|null $defaultValue the value to return if the header is not found. Default is <code>null</code>
		 * @return string|null the header value the found value or <code>$defaultValue</code> if it is not found
		 */
		public static function opt(string $key, ?string $defaultValue = null) : ?string {
			if (!static::has($key)) {
				return $defaultValue;
			} else {
				return static::getAll()[$key];
			}
		}

		/** @var string[] */
		private static $headers;

		/**
		 * This function returns an associative array where the key is the header name (each part is in Title-Case) and
         * the value is its value.
         *
         * Since the function <code>getallheaders()</code> is not always available in PHP (e.g. in FPM) this function
         * parses the $_SERVER super global: all keys starting with "HTTP_" are considered as a header and converted in
         * the already mentioned Title-Case. In addition to this it will also search for well-known keys such as
         * "CONTENT_TYPE" or "PHP_AUTH_USER".
         *
		 * @return string[] returns an associative array of header key => value. The-Key-Is-In-First-Letter-Uppercase-Like-This
		 */
		public static function getAll() : array {
			if (static::$headers === null) {
				static::$headers = static::loadAllHeaders();
			}
			return static::$headers;
		}

		/**
		 * @return string[] parses the headers as described in the doc of {@link HeaderUtils::getAll()}
		 */
		private static function loadAllHeaders() : array {
			$headers = [];
			$copyServer = [
				'CONTENT_TYPE'   => 'Content-Type',
				'CONTENT_LENGTH' => 'Content-Length',
				'CONTENT_MD5'    => 'Content-Md5',
			];
			foreach ($_SERVER as $key => $value) {
				if (strpos($key, 'HTTP_') === 0) {
					$key = substr($key, 5);
					if (!isset($copyServer[$key], $_SERVER[$key])) {
						$key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
						$headers[$key] = $value;
					}
				} elseif (isset($copyServer[$key])) {
					$headers[$copyServer[$key]] = $value;
				}
			}
			if (!isset($headers['Authorization'])) {
				if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
					$headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
				} elseif (isset($_SERVER['PHP_AUTH_USER'])) {
					$basicPass = $_SERVER['PHP_AUTH_PW'] ?? '';
					$headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basicPass);
				} elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
					$headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
				}
			}
			return $headers;
		}
	}