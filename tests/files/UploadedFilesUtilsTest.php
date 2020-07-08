<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\tests\files;

    use com\femastudios\utils\http\files\UploadedFilesUtils;
    use com\femastudios\utils\http\files\UploadedFile;
    use com\femastudios\utils\http\files\UploadedFileException;
    use PHPUnit\Framework\TestCase;

    final class UploadedFilesUtilsTest extends TestCase {

        protected function setUp() : void {
            $_FILES = [
                'user' => [
                    'name'     => [
                        'info'      => [
                            'avatar' => 'photo.jpg',
                        ],
                        'logo'      => 'logo.png',
                        'signature' => 'signature.png',
                    ],
                    'type'     => [
                        'info'      => [
                            'avatar' => 'image/jpeg',
                        ],
                        'logo'      => 'image/png',
                        'signature' => 'image/png',
                    ],
                    'tmp_name' => [
                        'info'      => [
                            'avatar' => '/tmp/phpA6H4.tmp',
                        ],
                        'logo'      => '/tmp/phpL8H4.tmp',
                        'signature' => '/tmp/phpZ44E.tmp',
                    ],
                    'error'    => [
                        'info'      => [
                            'avatar' => UPLOAD_ERR_OK,
                        ],
                        'logo'      => UPLOAD_ERR_OK,
                        'signature' => UPLOAD_ERR_FORM_SIZE,
                    ],
                    'size'     => [
                        'info'      => [
                            'avatar' => 1354716,
                        ],
                        'logo'      => 354987,
                        'signature' => 18596478,
                    ],
                ],
            ];
        }

        public function testGetReorderedFiles() : void {
            self::assertSame([
                'user' => [
                    'info'      => [
                        'avatar' => [
                            'name'     => 'photo.jpg',
                            'type'     => 'image/jpeg',
                            'tmp_name' => '/tmp/phpA6H4.tmp',
                            'error'    => UPLOAD_ERR_OK,
                            'size'     => 1354716,
                        ],
                    ],
                    'logo'      => [
                        'name'     => 'logo.png',
                        'type'     => 'image/png',
                        'tmp_name' => '/tmp/phpL8H4.tmp',
                        'error'    => UPLOAD_ERR_OK,
                        'size'     => 354987,
                    ],
                    'signature' => [
                        'name'     => 'signature.png',
                        'type'     => 'image/png',
                        'tmp_name' => '/tmp/phpZ44E.tmp',
                        'error'    => UPLOAD_ERR_FORM_SIZE,
                        'size'     => 18596478,
                    ],
                ],
            ], UploadedFilesUtils::getReorderedFiles());
        }

        private static function checkAvatar($avatar) : void {
            self::assertInstanceOf(UploadedFile::class, $avatar);
            self::assertSame($avatar->getName(), 'photo.jpg');
            self::assertSame($avatar->getType(), 'image/jpeg');
            self::assertSame($avatar->getTmpName(), '/tmp/phpA6H4.tmp');
            self::assertSame($avatar->getSize(), 1354716);
        }

        private static function checkLogo($logo) : void {
            self::assertInstanceOf(UploadedFile::class, $logo);
            self::assertSame($logo->getName(), 'logo.png',);
            self::assertSame($logo->getType(), 'image/png');
            self::assertSame($logo->getTmpName(), '/tmp/phpL8H4.tmp',);
            self::assertSame($logo->getSize(), 354987);
        }

        public function testGetUploadedFiles() : void {
            $uploadedFiles = UploadedFilesUtils::getUploadedFiles();
            self::assertIsCallable($uploadedFiles['user']['info']['avatar']);
            self::assertIsCallable($uploadedFiles['user']['logo']);
            self::assertIsCallable($uploadedFiles['user']['signature']);

            /** @var UploadedFile $avatar */
            $avatar = $uploadedFiles['user']['info']['avatar']();
            self::checkAvatar($avatar);

            /** @var UploadedFile $logo */
            $logo = $uploadedFiles['user']['logo']();
            self::checkLogo($logo);

            $this->expectException(UploadedFileException::class);
            $this->expectExceptionCode(UPLOAD_ERR_FORM_SIZE);
            $uploadedFiles['user']['signature']();
        }

        /** @throws UploadedFileException */
        public function testOptUploadedFile() : void {
            self::checkAvatar(UploadedFilesUtils::optUploadedFile('user', 'info', 'avatar'));
            self::checkLogo(UploadedFilesUtils::optUploadedFile('user', 'logo'));

            $this->expectException(UploadedFileException::class);
            $this->expectExceptionCode(UPLOAD_ERR_FORM_SIZE);
            UploadedFilesUtils::optUploadedFile('user', 'signature');

            self::assertNull(UploadedFilesUtils::optUploadedFile('user', 'hello'));
            self::assertNull(UploadedFilesUtils::optUploadedFile('user', 'signature', 'type'));
        }

        /** @throws UploadedFileException */
        public function testGetUploadedFile() : void {
            self::checkAvatar(UploadedFilesUtils::getUploadedFile('user', 'info', 'avatar'));
            self::checkLogo(UploadedFilesUtils::getUploadedFile('user', 'logo'));

            $this->expectException(UploadedFileException::class);
            $this->expectExceptionCode(UPLOAD_ERR_FORM_SIZE);
            UploadedFilesUtils::getUploadedFile('user', 'signature');
        }

        /** @throws UploadedFileException */
        public function testGetUploadedFileNotFound() : void {
            $this->expectException(\LogicException::class);
            UploadedFilesUtils::getUploadedFile('user', 'hello');
        }

        public function testHasUploadedFile() : void {
            self::assertTrue(UploadedFilesUtils::hasUploadedFile('user', 'info', 'avatar'));
            self::assertTrue(UploadedFilesUtils::hasUploadedFile('user', 'logo'));
            self::assertTrue(UploadedFilesUtils::hasUploadedFile('user', 'signature'));
            self::assertFalse(UploadedFilesUtils::hasUploadedFile('user', 'hello'));
            self::assertFalse(UploadedFilesUtils::hasUploadedFile('123'));
            self::assertFalse(UploadedFilesUtils::hasUploadedFile('user', 'info', 'avatar', 'name'));
        }


        protected function tearDown() : void {
            $_FILES = [];
        }
    }
