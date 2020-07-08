<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\tests\files;

    use com\femastudios\utils\http\files\UploadedFile;
    use com\femastudios\utils\http\files\UploadedFileException;
    use PHPUnit\Framework\TestCase;

    final class UploadedFileTest extends TestCase {
        public function testIsValidFilesArray() {
            $arr = [
                'name' => 'hello.jpg',
                'error' => UPLOAD_ERR_OK,
                'size' => 1234,
                'tmp_name' => '/tmp/php123.tmp',
                'ignored' => 'hello'
            ];
            self::assertTrue(UploadedFile::isValidFilesArray($arr));

            // Invalid type
            $arr['type'] = 1234;
            self::assertFalse(UploadedFile::isValidFilesArray($arr));

            // OK type
            $arr['type'] = 'image/jpeg';
            self::assertTrue(UploadedFile::isValidFilesArray($arr));

            // Invalid name
            $arr['name'] = 1234;
            self::assertFalse(UploadedFile::isValidFilesArray($arr));
            $arr['name'] = 'hello.jpg';

            // Invalid size
            $arr['size'] = "wrong";
            self::assertFalse(UploadedFile::isValidFilesArray($arr));
            $arr['size'] = 1234;

            // Invalid tmp_name
            $arr['tmp_name'] = 1234;
            self::assertFalse(UploadedFile::isValidFilesArray($arr));
            $arr['name'] = '/tmp/php123.tmp';
        }

        /** @throws UploadedFileException */
        public function testFromFilesArray() {
            $uf = UploadedFile::fromFilesArray([
                'name' => 'hello.jpg',
                'error' => UPLOAD_ERR_OK,
                'size' => 1234,
                'tmp_name' => '/tmp/php123.tmp',
                'ignored' => 'hello'
            ]);
            self::assertSame($uf->getName(), 'hello.jpg');
            self::assertSame($uf->getSize(), 1234);
            self::assertSame($uf->getTmpName(), '/tmp/php123.tmp');
            self::assertNull($uf->getType());
        }

        /** @throws UploadedFileException */
        public function testFromFilesArrayWrongArray() {
            $this->expectException(\DomainException::class);
            UploadedFile::fromFilesArray([
                'name' => 132,
                'size' => 1234
            ]);
        }

        public function testFromFilesArrayError() {
            $this->expectException(UploadedFileException::class);
            $this->expectExceptionCode(UPLOAD_ERR_CANT_WRITE);
            UploadedFile::fromFilesArray([
                'name' => 'hello.jpg',
                'error' => UPLOAD_ERR_CANT_WRITE,
                'size' => 1234,
                'tmp_name' => '/tmp/php123.tmp',
                'ignored' => 'hello'
            ]);
        }
    }
