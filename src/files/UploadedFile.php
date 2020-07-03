<?php
	declare(strict_types=1);

	namespace com\femastudios\utils\http\files;

    /**
     * Class that represents a file that has been uploaded by the user.
     * The fields of this class are the same that can be found in the <code>$_FILES</code> array, except the error,
     * that should be handled with an exception before creation.
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
		public function getType() : string {
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

		/**
         * Creates a new {@link UploadedFile} from an array containing the following params: name, size, tmp_name, error
         * and type. Only type is optional. The array can be obtained from the <code>$_FILES</code> super-global, or
         * by calling {@link FilesUtils::getReorderedFiles()} if the parameter name contains nesting. (See function doc
         * for better explanation.
         *
		 * @param array $arr the array with the described keys. Unknown keys are ignored
		 * @return UploadedFile a new instance
		 * @throws UploadedFileException if the error field is !== UPLOAD_ERR_OK
         * @throws \DomainException if one of the non-optional fields is missing or null
		 */
		public static function fromFilesArray(array $arr) : UploadedFile {
			if (isset($arr['name'], $arr['size'], $arr['tmp_name'], $arr['error'])) {
				$errorCode = $arr['error'];
				if ($errorCode === UPLOAD_ERR_OK) {
					return new UploadedFile($arr['name'], $arr['type'] ?? null, $arr['size'], $arr['tmp_name']);
				} else {
					throw UploadedFileException::fromErrorCode($errorCode);
				}
			} else {
				throw new \DomainException('Missing params');
			}
		}
	}