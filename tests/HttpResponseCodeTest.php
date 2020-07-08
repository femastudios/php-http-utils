<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\tests;

    use com\femastudios\utils\http\HttpResponseCode;
    use com\femastudios\utils\http\HttpResponseCodeType;
    use PHPUnit\Framework\TestCase;

    final class HttpResponseCodeTest extends TestCase {

        public function testFromCode() : void {
            self::assertSame(HttpResponseCode::NOT_FOUND(), HttpResponseCode::fromCode(404));
        }

        public function testType() {
            self::assertSame(HttpResponseCodeType::INFORMATIONAL(), HttpResponseCode::CONTINUE()->getType());

            self::assertSame(HttpResponseCodeType::SUCCESSFUL(), HttpResponseCode::OK()->getType());
            self::assertSame(HttpResponseCodeType::SUCCESSFUL(), HttpResponseCode::CREATED()->getType());

            self::assertSame(HttpResponseCodeType::REDIRECTION(), HttpResponseCode::FOUND()->getType());
            self::assertSame(HttpResponseCodeType::REDIRECTION(), HttpResponseCode::MOVED_PERMANENTLY()->getType());

            self::assertSame(HttpResponseCodeType::CLIENT_ERROR(), HttpResponseCode::BAD_REQUEST()->getType());
            self::assertSame(HttpResponseCodeType::CLIENT_ERROR(), HttpResponseCode::NOT_FOUND()->getType());

            self::assertSame(HttpResponseCodeType::SERVER_ERROR(), HttpResponseCode::INTERNAL_SERVER_ERROR()->getType());
            self::assertSame(HttpResponseCodeType::SERVER_ERROR(), HttpResponseCode::BAD_GATEWAY()->getType());
        }

        public function testInfo()  {
            self::assertSame(200, HttpResponseCode::OK()->getCode());
            self::assertSame('OK', HttpResponseCode::OK()->getMessage());
            self::assertSame('200 OK', HttpResponseCode::OK()->getCodeAndMessage());

            self::assertSame(404, HttpResponseCode::NOT_FOUND()->getCode());
            self::assertSame('Not Found', HttpResponseCode::NOT_FOUND()->getMessage());
            self::assertSame('404 Not Found', HttpResponseCode::NOT_FOUND()->getCodeAndMessage());

            self::assertSame(502, HttpResponseCode::BAD_GATEWAY()->getCode());
            self::assertSame('Bad Gateway', HttpResponseCode::BAD_GATEWAY()->getMessage());
            self::assertSame('502 Bad Gateway', HttpResponseCode::BAD_GATEWAY()->getCodeAndMessage());
        }

        public function testHttpException() {
            $code = HttpResponseCode::NOT_FOUND();
            $message = 'User not found';
            $exceptionId = 'user_not_found';
            $cause = new \Exception();
            $extras = ['extra' => 'hello'];
            $exception = $code->httpException($message, $exceptionId, $extras, $cause);

            self::assertSame($code, $exception->getHttpCode());
            self::assertSame($code->getCode(), $exception->getCode());
            self::assertSame($message, $exception->getMessage());
            self::assertSame($exceptionId, $exception->getExceptionId());
            self::assertSame($extras, $exception->getExtras());
            self::assertSame($cause, $exception->getPrevious());
        }
    }
