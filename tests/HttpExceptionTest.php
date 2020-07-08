<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\tests;

    use com\femastudios\utils\http\HttpException;
    use com\femastudios\utils\http\HttpResponseCode;
    use PHPUnit\Framework\TestCase;

    final class HttpExceptionTest extends TestCase {

        public function testCreation() : void {
            $exception = new HttpException('My message', HttpResponseCode::NOT_FOUND(), 'user_not_found', [
                'id' => 1234,
            ]);
            self::assertSame(HttpResponseCode::NOT_FOUND(), $exception->getHttpCode());
            self::assertSame(404, $exception->getCode());
            self::assertSame('My message', $exception->getMessage());
            self::assertSame('user_not_found', $exception->getExceptionId());
            self::assertSame(['id' => 1234], $exception->getExtras());
            self::assertNull($exception->getPrevious());

            $json = $exception->jsonSerialize();
            self::assertSame(404, $json['code']);
            self::assertSame('My message', $json['message']);
            self::assertSame('user_not_found', $json['exception_id']);
            self::assertSame(['id' => 1234], (array)$json['extras']);
        }
    }
