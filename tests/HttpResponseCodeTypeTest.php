<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\tests;

    use com\femastudios\utils\http\HttpResponseCode;
    use com\femastudios\utils\http\HttpResponseCodeType;
    use PHPUnit\Framework\TestCase;

    final class HttpResponseCodeTypeTest extends TestCase {

        public function testAcceptsCode() {
            self::assertTrue(HttpResponseCodeType::CLIENT_ERROR()->acceptsCode(404));
            self::assertFalse(HttpResponseCodeType::CLIENT_ERROR()->acceptsCode(500));
        }

        public function testAccepts() {
            self::assertTrue(HttpResponseCodeType::INFORMATIONAL()->accepts(HttpResponseCode::CONTINUE()));
            self::assertFalse(HttpResponseCodeType::REDIRECTION()->accepts(HttpResponseCode::NOT_FOUND()));

        }

        public function testGetAllCodes() {
            $type = HttpResponseCodeType::REDIRECTION();
            $codes = $type->getAllCodes();
            foreach ($codes as $code) {
                self::assertTrue($type->accepts($code));
            }
            foreach (HttpResponseCode::getAll() as $code) {
                if(!\in_array($code, $codes, true)) {
                    self::assertFalse($type->accepts($code));
                }
            }
        }

    }
