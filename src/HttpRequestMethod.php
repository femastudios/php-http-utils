<?php /** @noinspection PhpUnusedPrivateFieldInspection */
    declare(strict_types=1);

    namespace com\femastudios\utils\http;


    use com\femastudios\enums\ConstEnum;

    /**
     * Enum that defines a default HTTP request method according RFC 7231 and RFC 5789.
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods
     *
     * @method static HttpRequestMethod GET()
     * @method static HttpRequestMethod HEAD()
     * @method static HttpRequestMethod POST()
     * @method static HttpRequestMethod PUT()
     * @method static HttpRequestMethod DELETE()
     * @method static HttpRequestMethod CONNECT()
     * @method static HttpRequestMethod OPTIONS()
     * @method static HttpRequestMethod TRACE()
     * @method static HttpRequestMethod PATCH()
     *
     * @method static HttpRequestMethod[] getAll()
     *
 	 * @package com\femastudios\utils\http
     */
    final class HttpRequestMethod extends ConstEnum {

        private const ENUM_GET = [false, true, true, true, true, true];
        private const ENUM_HEAD = [false, false, true, true, true, false];
        private const ENUM_POST = [true, true, false, false, false, true];
        private const ENUM_PUT = [true, false, false, true, false, false];
        private const ENUM_DELETE = [true, true, false, true, false, false];
        private const ENUM_CONNECT = [false, true, false, false, false, false];
        private const ENUM_OPTIONS = [false, true, true, true, false, false];
        private const ENUM_TRACE = [false, false, false, true, false, false];
        private const ENUM_PATCH = [true, true, false, false, false, false];

        /** @var bool */
        private $requestCanHaveBody, $successfulResponseCanHaveBody, $safe, $idempotent, $cacheable, $allowedInHTMLForms;

        private function __construct(
            bool $requestCanBody,
            bool $successfulResponseCanHaveBody,
            bool $safe,
            bool $idempotent,
            bool $cacheable,
            bool $allowedInHTMLForms
        ) {
            parent::__construct();
            $this->requestCanHaveBody = $requestCanBody;
            $this->successfulResponseCanHaveBody = $successfulResponseCanHaveBody;
            $this->safe = $safe;
            $this->idempotent = $idempotent;
            $this->cacheable = $cacheable;
            $this->allowedInHTMLForms = $allowedInHTMLForms;
        }

        /**
         * @return bool whether a request of this type can have a body
         */
        public function requestCanHaveBody() : bool {
            return $this->requestCanHaveBody;
        }

        /**
         * @return bool whether a response to a request of this type can have a body
         */
        public function successfulResponseCanHaveBody() : bool {
            return $this->successfulResponseCanHaveBody;
        }

        /**
         * @return bool whether a request of this type alters the state of the server
         * @see https://developer.mozilla.org/en-US/docs/Glossary/Safe
         */
        public function isSafe() : bool {
            return $this->safe;
        }

        /**
         * @return bool whether a request of this type can be made several times with the same effect while leaving the server in the same state
         * @see https://developer.mozilla.org/en-US/docs/Glossary/Idempotent
         */
        public function isIdempotent() : bool {
            return $this->idempotent;
        }

        /**
         * @return bool whether a request of this type can can be stored to be retrieved and used later
         * @see https://developer.mozilla.org/en-US/docs/Glossary/Cacheable
         */
        public function isCacheable() : bool {
            return $this->cacheable;
        }

        /**
         * @return bool whether this request method is allowed in HTML forms
         * @see https://developer.mozilla.org/en-US/docs/Learn/Forms
         */
        public function isAllowedInHTMLForms() : bool {
            return $this->allowedInHTMLForms;
        }
    }