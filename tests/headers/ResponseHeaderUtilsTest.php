<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\tests\headers;

    use com\femastudios\utils\http\headers\ResponseHeaderUtils;
    use PHPUnit\Framework\TestCase;

    final class ResponseHeaderUtilsTest extends TestCase {

        private static function assertSameHeaders(array $expected, array $actual) : void {
            ksort($expected);
            ksort($actual);
            self::assertSame($expected, $actual);
        }

        /**
         * @runInSeparateProcess
         */
        public function testPut() : void {
            self::assertSame([], ResponseHeaderUtils::getAll());
            header('Content-Type: json');
            ResponseHeaderUtils::put('Content-Type', 'application/json');
            self::assertSameHeaders(['Content-Type' => 'application/json'], ResponseHeaderUtils::getAll());

            ResponseHeaderUtils::put('Content-Encoding', 'gzip');
            self::assertSameHeaders([
                'Content-Type'     => 'application/json',
                'Content-Encoding' => 'gzip',
            ], ResponseHeaderUtils::getAll());

            ResponseHeaderUtils::put('Content-Type', 'application/xml');
            self::assertSameHeaders([
                'Content-Type'     => 'application/xml',
                'Content-Encoding' => 'gzip',
            ], ResponseHeaderUtils::getAll());
        }

        /**
         * @runInSeparateProcess
         */
        public function testGet() : void {
            ResponseHeaderUtils::put('Content-Type', 'application/json');
            self::assertSame('application/json', ResponseHeaderUtils::get('Content-Type'));
            self::assertSame('none', ResponseHeaderUtils::get('Content-Encoding', 'none'));

            $this->expectException(\LogicException::class);
            ResponseHeaderUtils::get('Content-Encoding');
        }

        /**
         * @runInSeparateProcess
         */
        public function testOpt() : void {
            ResponseHeaderUtils::put('Content-Type', 'application/json');
            self::assertSame('application/json', ResponseHeaderUtils::opt('Content-Type'));
            self::assertSame('none', ResponseHeaderUtils::opt('Content-Encoding', 'none'));
            self::assertNull(ResponseHeaderUtils::opt('Content-Encoding'));
        }

        /**
         * @runInSeparateProcess
         */
        public function testRemove() : void {
            ResponseHeaderUtils::put('Content-Type', 'application/json');
            ResponseHeaderUtils::put('Content-Encoding', 'gzip');

            ResponseHeaderUtils::remove('Content-Encoding');
            self::assertSameHeaders(['Content-Type' => 'application/json'], ResponseHeaderUtils::getAll());
        }

        /**
         * @runInSeparateProcess
         */
        public function testConcat() : void {
            ResponseHeaderUtils::concat('X-Hello', 'hello');
            ResponseHeaderUtils::concat('X-Hello', 'world');
            self::assertSame('helloworld', ResponseHeaderUtils::get('X-Hello'));

            ResponseHeaderUtils::concat('X-Hello', 'ciaomondo', ',');
            self::assertSame('helloworld,ciaomondo', ResponseHeaderUtils::get('X-Hello'));
        }

        /**
         * @runInSeparateProcess
         */
        public function testCsv() : void {
            // ADD
            ResponseHeaderUtils::addCsv('X-Hello', 'hello');
            self::assertSame('hello', ResponseHeaderUtils::get('X-Hello'));

            ResponseHeaderUtils::addCsv('X-Hello', 'world');
            self::assertSame('hello,world', ResponseHeaderUtils::get('X-Hello'));

            ResponseHeaderUtils::addCsv('X-Hello', 'ciao', 'mondo');
            self::assertSame('hello,world,ciao,mondo', ResponseHeaderUtils::get('X-Hello'));

            // PUT
            ResponseHeaderUtils::putCsv('X-Hello', 'hello');
            self::assertSame('hello', ResponseHeaderUtils::get('X-Hello'));

            ResponseHeaderUtils::putCsv('X-Hello', 'world');
            self::assertSame('world', ResponseHeaderUtils::get('X-Hello'));

            ResponseHeaderUtils::putCsv('X-Hello', 'ciao', 'mondo');
            self::assertSame('ciao,mondo', ResponseHeaderUtils::get('X-Hello'));

            // OPT
            self::assertSame(['ciao', 'mondo'], ResponseHeaderUtils::optCsv('X-Hello'));
            self::assertSame(['default', 'value'], ResponseHeaderUtils::optCsv('X-Ciao', ['default', 'value']));
            self::assertNull(ResponseHeaderUtils::optCsv('X-Ciao'));


            // REMOVE
            ResponseHeaderUtils::putCsv('X-Hello', 'hello', 'world', 'ciao', 'mondo');
            self::assertSame(['ciao', 'mondo'], ResponseHeaderUtils::removeCsv('X-Hello', true, 'ciao', 'mondo', 'hola'));
            self::assertSame(['hello', 'world'], ResponseHeaderUtils::getCsv('X-Hello'));

            // GET
            self::assertSame(['hello', 'world'], ResponseHeaderUtils::getCsv('X-Hello'));
            self::assertSame(['default', 'value'], ResponseHeaderUtils::getCsv('X-Ciao', ['default', 'value']));
            $this->expectException(\LogicException::class);
            ResponseHeaderUtils::getCsv('X-Ciao');
        }

        /**
         * @runInSeparateProcess
         */
        public function testGetCsvNonExistent() : void {
            $this->expectException(\LogicException::class);
            ResponseHeaderUtils::getCsv('X-Ciao');
        }

        public function testSentCheck() : void {
            $this->expectException(\LogicException::class);
            echo "Force sending of headers";
            ResponseHeaderUtils::put('Content-Type', 'text/plain');
        }
    }
