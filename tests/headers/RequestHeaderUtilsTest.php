<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\tests\headers;

    use com\femastudios\utils\http\headers\RequestHeaderUtils;
    use PHPUnit\Framework\TestCase;

    final class RequestHeaderUtilsTest extends TestCase {

        protected function setUp() : void {
            $_SERVER = [
                'CONTENT_TYPE'  => 'application/json',
                'HTTP_X_HELLO'  => 'hello_world',
                'HTTP_X_WORLD'  => 'world_hello',
                'PHP_AUTH_USER' => 'usr',
                'PHP_AUTH_PW'   => 'passwd',
            ];
        }

        public function testGetAll() : void {
            $headers = [
                'Authorization' => 'Basic ' . base64_encode('usr:passwd'),
                'Content-Type'  => 'application/json',
                'X-Hello'       => 'hello_world',
                'X-World'       => 'world_hello',
            ];
            $getAll = RequestHeaderUtils::getAll();
            ksort($headers);
            ksort($getAll);
            self::assertSame($headers, $getAll);
        }

        public function testGetAllLower() : void {
            $headers = [
                'authorization' => 'Basic ' . base64_encode('usr:passwd'),
                'content-type'  => 'application/json',
                'x-hello'       => 'hello_world',
                'x-world'       => 'world_hello',
            ];
            $getAll = RequestHeaderUtils::getAllLowerCase();
            ksort($headers);
            ksort($getAll);
            self::assertSame($headers, $getAll);
        }

        public function testHas() : void {
            self::assertTrue(RequestHeaderUtils::has('Content-Type'));
            self::assertTrue(RequestHeaderUtils::has('cOnTent-TYpe'));
            self::assertTrue(RequestHeaderUtils::has('X-Hello'));
            self::assertTrue(RequestHeaderUtils::has('Authorization'));
            self::assertFalse(RequestHeaderUtils::has('X-Hello-World'));
            self::assertFalse(RequestHeaderUtils::has('Content-Encoding'));
        }

        public function testOpt() : void {
            self::assertSame('application/json', RequestHeaderUtils::opt('cOnTent-Type'));
            self::assertSame('hello_world', RequestHeaderUtils::opt('x-HELLo'));

            self::assertSame('default', RequestHeaderUtils::opt('X-Hello-World', 'default'));
            self::assertNull(RequestHeaderUtils::opt('X-Hello-World'));
            self::assertNull(RequestHeaderUtils::opt('Content-Encoding'));
        }

        public function testGet() : void {
            self::assertSame('application/json', RequestHeaderUtils::get('cOnTent-Type'));
            self::assertSame('hello_world', RequestHeaderUtils::get('x-HELLo'));

            self::assertSame('default', RequestHeaderUtils::get('Content-Encoding', 'default'));

            $this->expectException(\LogicException::class);
            self::assertNull(RequestHeaderUtils::get('Content-Encoding'));
        }
    }
