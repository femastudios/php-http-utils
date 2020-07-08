<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\files;

    /**
     * Class that represents a file that has been uploaded by the user.
     * The fields of this class are the same that can be found in the <code>$_FILES</code> array, except the error,
     * that must be handled with an exception before creation.
     *
     * @see https://www.php.net/manual/en/features.file-upload.post-method.php
     * @package com\femastudios\utils\http
     */
    final class UploadedFile {

        private $name, $type, $size, $tmpName;

        public function __construct(string $name, ?string $type, int $size, string $tmpName) {
            $this->name = $name;
            $this->type = $type;
            $this->size = $size;
            $this->tmpName = $tmpName;
        }

        /**
         * @return string the name of the file (e.g. "image.jpg")
         */
        public function getName() : string {
            return $this->name;
        }

        /**
         * @return string the mime type of the file (e.g. "image/jpeg")
         */
        public function getType() : ?string {
            return $this->type;
        }

        /**
         * @return string the temporary full path where the file is currently located (e.g. "/tmp/php1234.tmp")
         */
        public function getTmpName() : string {
            return $this->tmpName;
        }

        /**
         * @return int the size, in bytes, of the file
         */
        public function getSize() : int {
            return $this->size;
        }

        public static function isValidFilesArray(array $arr) : bool {
            return isset($arr['name'], $arr['size'], $arr['tmp_name'], $arr['error']) && // Required params
                is_string($arr['name']) && // name must be a string
                is_int($arr['size']) && // size must be an int
                is_string($arr['tmp_name']) && // tmp_name must be a string
                is_int($arr['error']) && // error must be an int
                (!isset($arr['type']) || is_string($arr['type'])); // type, if exists, must be a string
        }

        /**
         * Creates a new {@link UploadedFile} from an array containing the following params: name, size, tmp_name, error
         * and type. Only type is optional. The array can be obtained from the <code>$_FILES</code> super-global, or
         * by calling {@link UploadedFilesUtils::getReorderedFiles()} if the parameter name contains nesting. (See function doc
         * for better explanation.
         *
         * @param array $arr the array with the described keys. Unknown keys are ignored
         * @return UploadedFile a new instance
         * @throws UploadedFileException if the error field is !== UPLOAD_ERR_OK
         * @throws \DomainException if one of the non-optional fields is missing or null or if some params are not of
         * the right type
         */
        public static function fromFilesArray(array $arr) : UploadedFile {
            if (self::isValidFilesArray($arr)) {
                $errorCode = $arr['error'];
                if ($errorCode === UPLOAD_ERR_OK) {
                    return new UploadedFile($arr['name'], $arr['type'] ?? null, $arr['size'], $arr['tmp_name']);
                } else {
                    throw UploadedFileException::fromErrorCode($errorCode);
                }
            } else {
                throw new \DomainException('Missing or invalid params');
            }
        }
    }