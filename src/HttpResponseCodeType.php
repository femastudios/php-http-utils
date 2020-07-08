<?php /** @noinspection PhpUnusedPrivateFieldInspection */
	declare(strict_types=1);

	namespace com\femastudios\utils\http;

	use com\femastudios\enums\ConstEnum;

	/**
     * Enum that defines the different types of status codes defined by RFC 2616 and RFC 7231.
     *
     * Currently there are five types of codes:
     * <ul>
     *  <li>1xx: informational</li>
     *  <li>2xx: successful</li>
     *  <li>3xx: redirection</li>
     *  <li>4xx: client error</li>
     *  <li>5xx: server error</li>
     * </ul>
     *
     * @see https://tools.ietf.org/html/rfc2616#section-10
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
     *
	 * @method static HttpResponseCodeType INFORMATIONAL()
	 * @method static HttpResponseCodeType SUCCESSFUL()
	 * @method static HttpResponseCodeType REDIRECTION()
	 * @method static HttpResponseCodeType CLIENT_ERROR()
	 * @method static HttpResponseCodeType SERVER_ERROR()
     *
     * @method static HttpResponseCodeType[] getAll()
     *
     * @package com\femastudios\utils\http
	 */
	final class HttpResponseCodeType extends ConstEnum {

		private const ENUM_INFORMATIONAL = [1];
		private const ENUM_SUCCESSFUL = [2];
		private const ENUM_REDIRECTION = [3];
		private const ENUM_CLIENT_ERROR = [4];
		private const ENUM_SERVER_ERROR = [5];

		private $hundreds;

		private function __construct(int $hundreds) {
		    parent::__construct();
			$this->hundreds = $hundreds;
		}

		/**
		 * @return HttpResponseCode[] an array of codes belonging to this type
		 */
		public function getAllCodes() : array {
			$ret = [];
			foreach (HttpResponseCode::getAll() as $responseCode) {
				/** @var $responseCode HttpResponseCode */
				if ($this->accepts($responseCode)) {
					$ret[] = $responseCode;
				}
			}
			return $ret;
		}

        /**
         * @param HttpResponseCode $code a response code
         * @return bool true if the given response code is of this type
         */
		public function accepts(HttpResponseCode $code) : bool {
			return $this->acceptsCode($code->getCode());
		}

        /**
         * @param int $code a response code
         * @return bool true if the given response code is of this type
         */
        public function acceptsCode(int $code) : bool {
            return intdiv($code, 100) === $this->hundreds;
        }

	}