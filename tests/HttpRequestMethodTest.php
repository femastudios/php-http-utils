<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\tests;

    use com\femastudios\utils\http\HttpRequestMethod;
    use PHPUnit\Framework\TestCase;

    final class HttpRequestMethodTest extends TestCase {

        public function testRequestCanHaveBody() {
            self::assertFalse(HttpRequestMethod::GET()->requestCanHaveBody());
            self::assertTrue(HttpRequestMethod::POST()->requestCanHaveBody());
        }

        public function testSuccessfulResponseCanHaveBody() {
            self::assertTrue(HttpRequestMethod::GET()->successfulResponseCanHaveBody());
            self::assertFalse(HttpRequestMethod::HEAD()->successfulResponseCanHaveBody());
        }

        public function testIsSafe() {
            self::assertTrue(HttpRequestMethod::GET()->isCacheable());
            self::assertFalse(HttpRequestMethod::DELETE()->isCacheable());
        }

        public function testIsCacheable() {
            self::assertTrue(HttpRequestMethod::GET()->isSafe());
            self::assertFalse(HttpRequestMethod::POST()->isSafe());
        }

        public function testIsIdempotent() {
            self::assertTrue(HttpRequestMethod::GET()->isIdempotent());
            self::assertFalse(HttpRequestMethod::POST()->isIdempotent());
        }

        public function testIsAllowedInHTMLForms() {
            self::assertTrue(HttpRequestMethod::GET()->isAllowedInHTMLForms());
            self::assertFalse(HttpRequestMethod::PUT()->isAllowedInHTMLForms());
        }
    }
