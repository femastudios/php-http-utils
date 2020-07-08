<?php /** @noinspection PhpUnusedPrivateFieldInspection */
    declare(strict_types=1);

    namespace com\femastudios\utils\http;


    use com\femastudios\enums\ConstEnum;

    /**
     * Enum that defines different status codes defined by RFC 2616 and RFC 7231
     *
     * @see https://tools.ietf.org/html/rfc2616#section-10
     * @see https://developer.mozilla.org/it/docs/Web/HTTP/Status
     *
     * 1xx
     * @method static HttpResponseCode CONTINUE()
     * @method static HttpResponseCode SWITCHING_PROTOCOLS()
     * @method static HttpResponseCode PROCESSING()
     *
     * 2xx
     * @method static HttpResponseCode OK()
     * @method static HttpResponseCode CREATED()
     * @method static HttpResponseCode ACCEPTED()
     * @method static HttpResponseCode NON_AUTHORITATIVE_INFORMATION()
     * @method static HttpResponseCode NO_CONTENT()
     * @method static HttpResponseCode RESET_CONTENT()
     * @method static HttpResponseCode PARTIAL_CONTENT()
     * @method static HttpResponseCode MULTI_STATUS()
     * @method static HttpResponseCode ALREADY_REPORTED()
     * @method static HttpResponseCode IM_USED()
     *
     * 3xx
     * @method static HttpResponseCode MULTIPLE_CHOICES()
     * @method static HttpResponseCode MOVED_PERMANENTLY()
     * @method static HttpResponseCode FOUND()
     * @method static HttpResponseCode SEE_OTHER()
     * @method static HttpResponseCode NOT_MODIFIED()
     * @method static HttpResponseCode USE_PROXY()
     * @method static HttpResponseCode TEMPORARY_REDIRECT()
     * @method static HttpResponseCode PERMANENT_REDIRECT()
     *
     * 4xx
     * @method static HttpResponseCode BAD_REQUEST()
     * @method static HttpResponseCode UNAUTHORIZED()
     * @method static HttpResponseCode PAYMENT_REQUIRED()
     * @method static HttpResponseCode FORBIDDEN()
     * @method static HttpResponseCode NOT_FOUND()
     * @method static HttpResponseCode METHOD_NOT_ALLOWED()
     * @method static HttpResponseCode NOT_ACCEPTABLE()
     * @method static HttpResponseCode PROXY_AUTHENTICATION_REQUIRED()
     * @method static HttpResponseCode REQUEST_TIMEOUT()
     * @method static HttpResponseCode CONFLICT()
     * @method static HttpResponseCode GONE()
     * @method static HttpResponseCode LENGTH_REQUIRED()
     * @method static HttpResponseCode PRECONDITION_FAILED()
     * @method static HttpResponseCode PAYLOAD_TOO_LARGE()
     * @method static HttpResponseCode URI_TOO_LONG()
     * @method static HttpResponseCode UNSUPPORTED_MEDIA_TYPE()
     * @method static HttpResponseCode REQUESTED_RANGE_NOT_SATISFIABLE()
     * @method static HttpResponseCode EXPECTATION_FAILED()
     * @method static HttpResponseCode IM_A_TEAPOT()
     * @method static HttpResponseCode MISDIRECTED_REQUEST()
     * @method static HttpResponseCode UNPROCESSABLE_ENTITY()
     * @method static HttpResponseCode LOCKED()
     * @method static HttpResponseCode FAILED_DEPENDENCY()
     * @method static HttpResponseCode UPGRADE_REQUIRED()
     * @method static HttpResponseCode PRECONDITION_REQUIRED()
     * @method static HttpResponseCode TOO_MANY_REQUESTS()
     * @method static HttpResponseCode REQUEST_HEADER_FIELDS_TOO_LARGE()
     * @method static HttpResponseCode UNAVAILABLE_FOR_LEGAL_REASONS()
     *
     * 5xx
     * @method static HttpResponseCode INTERNAL_SERVER_ERROR()
     * @method static HttpResponseCode NOT_IMPLEMENTED()
     * @method static HttpResponseCode BAD_GATEWAY()
     * @method static HttpResponseCode SERVICE_UNAVAILABLE()
     * @method static HttpResponseCode GATEWAY_TIMEOUT()
     * @method static HttpResponseCode VERSION_NOT_SUPPORTED()
     * @method static HttpResponseCode VARIANT_ALSO_NEGOTIATES()
     * @method static HttpResponseCode INSUFFICIENT_STORAGE()
     * @method static HttpResponseCode LOOP_DETECTED()
     * @method static HttpResponseCode NOT_EXTENDED()
     * @method static HttpResponseCode NETWORK_AUTHENTICATION_REQUIRED()
     *
     * @method static HttpResponseCode[] getAll()
     *
     * @package com\femastudios\utils\http
     */
    final class HttpResponseCode extends ConstEnum {

        //1xx
        private const ENUM_CONTINUE = [100, 'Continue'];
        private const ENUM_SWITCHING_PROTOCOLS = [101, 'Switching Protocols'];
        private const ENUM_PROCESSING = [102, 'Processing'];

        //2xx
        private const ENUM_OK = [200, 'OK'];
        private const ENUM_CREATED = [201, 'Created'];
        private const ENUM_ACCEPTED = [202, 'Accepted'];
        private const ENUM_NON_AUTHORITATIVE_INFORMATION = [203, 'Non Authoritative Information'];
        private const ENUM_NO_CONTENT = [204, 'No content'];
        private const ENUM_RESET_CONTENT = [205, 'Reset Content'];
        private const ENUM_PARTIAL_CONTENT = [206, 'Partial Content'];
        private const ENUM_MULTI_STATUS = [207, 'Multi Status'];
        private const ENUM_ALREADY_REPORTED = [208, 'Already Reported'];
        private const ENUM_IM_USED = [226, "I'm Used"];

        //3xx
        private const ENUM_MULTIPLE_CHOICES = [300, 'Multiple Choices'];
        private const ENUM_MOVED_PERMANENTLY = [301, 'Moved Permanently'];
        private const ENUM_FOUND = [302, 'Found'];
        private const ENUM_SEE_OTHER = [303, 'See Other'];
        private const ENUM_NOT_MODIFIED = [304, 'Not Modified'];
        private const ENUM_USE_PROXY = [305, 'Use Proxy'];
        private const ENUM_TEMPORARY_REDIRECT = [307, 'Temporary Redirect'];
        private const ENUM_PERMANENT_REDIRECT = [308, 'Permanent Redirect'];

        //4xx
        private const ENUM_BAD_REQUEST = [400, 'Bad Request'];
        private const ENUM_UNAUTHORIZED = [401, 'Unauthorized'];
        private const ENUM_PAYMENT_REQUIRED = [402, 'Payment Required'];
        private const ENUM_FORBIDDEN = [403, 'Forbidden'];
        private const ENUM_NOT_FOUND = [404, 'Not Found'];
        private const ENUM_METHOD_NOT_ALLOWED = [405, 'Method Not Allowed'];
        private const ENUM_NOT_ACCEPTABLE = [406, 'Not Acceptable'];
        private const ENUM_PROXY_AUTHENTICATION_REQUIRED = [407, 'Proxy Authentication Required'];
        private const ENUM_REQUEST_TIMEOUT = [408, 'Request Timeout'];
        private const ENUM_CONFLICT = [409, 'Conflict'];
        private const ENUM_GONE = [410, 'Gone'];
        private const ENUM_LENGTH_REQUIRED = [411, 'Length Required'];
        private const ENUM_PRECONDITION_FAILED = [412, 'Precondition Failed'];
        private const ENUM_PAYLOAD_TOO_LARGE = [413, 'Payload Too Large'];
        private const ENUM_URI_TOO_LONG = [414, 'URI Too Long'];
        private const ENUM_UNSUPPORTED_MEDIA_TYPE = [415, 'Unsupported Media Type'];
        private const ENUM_RANGE_NOT_SATISFIABLE = [416, 'Range Not Satisfiable'];
        private const ENUM_EXPECTATION_FAILED = [417, 'Expectation Failed'];
        private const ENUM_IM_A_TEAPOT = [418, "I'm a Teapot"];
        private const ENUM_MISDIRECTED_REQUEST = [421, 'Misdirected Request'];
        private const ENUM_UNPROCESSABLE_ENTITY = [422, 'Unprocessable Entity'];
        private const ENUM_LOCKED = [423, 'Locked'];
        private const ENUM_FAILED_DEPENDENCY = [424, 'Failed Dependency'];
        private const ENUM_UPGRADE_REQUIRED = [426, 'Upgrade Required'];
        private const ENUM_PRECONDITION_REQUIRED = [428, 'Precondition Required'];
        private const ENUM_TOO_MANY_REQUESTS = [429, 'Too Many Requests'];
        private const ENUM_REQUEST_HEADER_FIELDS_TOO_LARGE = [431, 'Request Header Fields Too Large'];
        private const ENUM_UNAVAILABLE_FOR_LEGAL_REASONS = [451, 'Unavailable For Legal Reasons'];

        //5xx
        private const ENUM_INTERNAL_SERVER_ERROR = [500, 'Internal Server Error'];
        private const ENUM_NOT_IMPLEMENTED = [501, 'Not Implemented'];
        private const ENUM_BAD_GATEWAY = [502, 'Bad Gateway'];
        private const ENUM_SERVICE_UNAVAILABLE = [503, 'Service Unavailable'];
        private const ENUM_GATEWAY_TIMEOUT = [504, 'Gateway Timeout'];
        private const ENUM_VERSION_NOT_SUPPORTED = [505, 'Version Not Supported'];
        private const ENUM_VARIANT_ALSO_NEGOTIATES = [506, 'Variant Also Negotiates'];
        private const ENUM_INSUFFICIENT_STORAGE = [507, 'Insufficient Storage'];
        private const ENUM_LOOP_DETECTED = [508, 'Loop Detected'];
        private const ENUM_NOT_EXTENDED = [510, 'Not Extended'];
        private const ENUM_NETWORK_AUTHENTICATION_REQUIRED = [511, 'Network Authentication Required'];


        private $code, $message, $type;

        private function __construct(int $code, string $message) {
            parent::__construct();
            $this->code = $code;
            $this->message = $message;
            $this->type = $this->loadType();
        }

        private function loadType() {
            foreach (HttpResponseCodeType::getAll() as $type) {
                /** @var $type HttpResponseCodeType */
                if ($type->accepts($this)) {
                    return $type;
                }
            }
            throw new \LogicException('Code without type');
        }

        /**
         * @return int the code value as an integer
         */
        public function getCode() : int {
            return $this->code;
        }

        /**
         * @return string the message the usually accompanies the code
         */
        public function getMessage() : string {
            return $this->message;
        }

        /**
         * @return HttpResponseCodeType the type of the code
         */
        public function getType() : HttpResponseCodeType {
            return $this->type;
        }

        /**
         * Example: <code>404 Not Found</code>
         * @return string a string composed of the number of the code and its message, separated by a space
         */
        public function getCodeAndMessage() : string {
            return $this->code . ' ' . $this->message;
        }

        public static function fromCode(int $code) : HttpResponseCode {
            foreach (self::getAll() as $rc) {
                /** @var HttpResponseCode $rc */
                if($rc->getCode() === $code) {
                    return $rc;
                }
            }
            throw new \DomainException("Invalid HTTP response code $code");
        }

        public function httpException(?string $message = null, ?string $exceptionId = null, ?array $extras = null, ?\Throwable $cause = null) : HttpException {
            if ($message === null) {
                $message = $this->message;
            }
            return new HttpException($message, $this, $exceptionId, $extras, $cause);
        }
    }