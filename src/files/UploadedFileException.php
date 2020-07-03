<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\files;

    /**
     * Exception that wraps an upload error code
     *
     * @see https://www.php.net/manual/en/features.file-upload.errors.php
     * @package com\femastudios\utils\http\files
     */
    final class UploadedFileException extends \Exception {

        /**
         * Creates a new exception instance from an error code
         * @param int $errorCode the error code
         * @return UploadedFileException the exception
         * @see https://www.php.net/manual/en/features.file-upload.errors.php
         */
        public static function fromErrorCode(int $errorCode) : UploadedFileException {
            return new UploadedFileException(self::errorCodeToMessage($errorCode) ?? 'Unknown upload error', $errorCode);
        }

        /**
         * @param int $errorCode An error code
         * @return string a string with the description of that error code, or null if the code is unknown
         * @see https://www.php.net/manual/en/features.file-upload.errors.php
         */
        public static function errorCodeToMessage(int $errorCode) : ?string {
            switch ($errorCode) {
                case UPLOAD_ERR_INI_SIZE:
                    return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                case UPLOAD_ERR_FORM_SIZE:
                    return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                case UPLOAD_ERR_PARTIAL:
                    return 'The uploaded file was only partially uploaded';
                case UPLOAD_ERR_NO_FILE:
                    return 'No file was uploaded';
                case UPLOAD_ERR_NO_TMP_DIR:
                    return 'Missing a temporary folder';
                case UPLOAD_ERR_CANT_WRITE:
                    return 'Failed to write file to disk';
                case UPLOAD_ERR_EXTENSION:
                    return 'File upload stopped by extension';
                default:
                    return null;
            }
        }
    }