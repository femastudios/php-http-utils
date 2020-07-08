<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http;

    /**
     * An exception that contains an {@link HttpResponseCode}.
     *
     * Can be useful if a piece of code knows the type of HTTP failure the page has got to have and needs to propagate
     * this information up in the call stack.
     *
     * This class implements the {@link \JsonSerializable} interface.
     *
     * @package com\femastudios\utils\http
     */
    final class HttpException extends \RuntimeException implements \JsonSerializable {

        private $httpCode, $exceptionId, $extras;

        /**
         * @param string|null $message the message of the exception. If null the default message of the {@link HttpResponseCode} is used.
         * @param HttpResponseCode|null $httpCode the {@link HttpResponseCode}. If null {@link HttpResponseCode::INTERNAL_SERVER_ERROR()} will be used. The numerical code will also be used as code for the exception.
         * @param string|null $exceptionId a string that can differentiate between exceptions with the same code. For instance an exception with code 401 could be because of a wrong username or password. Can be null.
         * @param array|null $extras a map that will be added under the "extras" key in the JSON serialization. Can be null.
         * @param \Throwable|null $cause the cause exception. Can be null.
         */
        public function __construct(?string $message = null, ?HttpResponseCode $httpCode = null, ?string $exceptionId = null, ?array $extras = null, ?\Throwable $cause = null) {
            if ($httpCode === null) {
                $httpCode = HttpResponseCode::INTERNAL_SERVER_ERROR();
            }
            if ($message === null) {
                $message = $httpCode->getMessage();
            }
            parent::__construct($message, $httpCode->getCode(), $cause);
            $this->httpCode = $httpCode;
            $this->exceptionId = $exceptionId;
            $this->extras = $extras;
        }

        public function getExceptionId() : ?string {
            return $this->exceptionId;
        }

        public function getHttpCode() : HttpResponseCode {
            return $this->httpCode;
        }

        public function getExtras() : ?array {
            return $this->extras;
        }

        public function jsonSerialize() {
            return [
                'code'         => $this->getCode(),
                'message'      => $this->getMessage(),
                'exception_id' => $this->getExceptionId(),
                //Casted to object because in case of empty array it must remain an empty object ({})
                'extras'       => (object)$this->extras,
            ];
        }
    }
