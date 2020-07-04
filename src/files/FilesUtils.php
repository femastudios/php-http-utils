<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\files;

    final class FilesUtils {

        private function __construct() {
            throw new \LogicException();
        }

        private static $reorderedFiles;

        /**
         * The <code>$_FILES</code> array is made in not very user-friendly way: passing, for example a parameter called
         * <code>user[info][avatar]</code> puts the array in the following condition:
         * <code>
         * user[name][info][avatar]
         * user[size][info][avatar]
         * user[....][info][avatar]
         * </code>
         * As you can see the each file parameter (name, size, etc) is grouped at the <b>second</b> level. Why? No one
         * knows. So if we uploaded multiple files we would end up with all names grouped together, all sizes, etc.
         *
         * This method takes the <code>$_FILES</code> super-global and puts each file parameter at the last level,
         * so it transforms the above structure into the following:
         * <code>
         * user[info][avatar][name]
         * user[info][avatar][size]
         * user[info][avatar][....]
         * </code>
         * Doing things this way is easier because we can then address each uploaded file array by its parameter name
         * and have an array containing the file info.
         *
         * Note that if the passed parameter names are not nested (instead of <code>user[info][avatar]</code> simply
         * <code>avatar</code> this method returns an array identical to <code>$_FILES</code>)
         *
         * To clarify, here's a complete example. HTML form:
         * <code>
         * <form action="?" enctype="multipart/form-data">
         *  <input type="file" name="user[info][avatar]"><br>
         *  <input type="file" name="user[info][logo]"><br>
         *  <input type="submit">
         * </form>
         * </code>
         * The <code>json_encode($_FILES)</code>, with the original array:
         * <code>
         * {
         *   "user": {
         *     "name": {
         *       "info": {
         *         "avatar": "image1.jpg",
         *         "logo": "image2.png"
         *       }
         *     },
         *     "type": {
         *       "info": {
         *         "avatar": "image/jpeg",
         *         "logo": "image/jpeg"
         *       }
         *     },
         *     "tmp_name": {
         *       "info": {
         *         "avatar": "/tmp/phpB656.tmp",
         *         "logo": "/tmp/phpB667.tmp"
         *       }
         *     },
         *     "error": {
         *       "info": {
         *         "avatar": 0,
         *         "logo": 0
         *       }
         *     },
         *     "size": {
         *       "info": {
         *         "avatar": 786492,
         *         "logo": 30045
         *       }
         *     }
         *   }
         * }
         * </code>
         *
         * And finally the <code>json_encode(FilesUtils::getReorderedFiles())</code>:
         * <code>
         * {
         *   "user": {
         *     "info": {
         *       "avatar": {
         *         "name": "image1.jpg",
         *         "type": "image/jpeg",
         *         "tmp_name": "C/tmp/phpB656.tmp",
         *         "error": 0,
         *         "size": 786492
         *       },
         *       "logo": {
         *         "name": "image2.png",
         *         "type": "image/jpeg",
         *         "tmp_name": "/tmp/phpB667.tmp",
         *         "error": 0,
         *         "size": 30045
         *       }
         *     }
         *   }
         * }
         * </code>
         *
         * @return array the reordered <code>$_FILES</code> array
         * @see FilesUtils::getUploadedFiles()
         */
        public static function getReorderedFiles() : array {
            if (self::$reorderedFiles === null) {
                self::$reorderedFiles = [];
                foreach ($_FILES as $firstLevelName => $firstLevel) {
                    // $firstLevelName e.g. user
                    // $firstLevel e.g. name[info][avatar]
                    foreach ($firstLevel as $fieldName => $hierarchy) {
                        // $fieldName e.g. name, size
                        // $hierarchy e.g. info[avatar] or 'file.jpg'
                        if (\is_array($hierarchy)) {
                            if (!isset(self::$reorderedFiles[$firstLevelName])) {
                                self::$reorderedFiles[$firstLevelName] = [];
                            }
                            $newHierarchy = $hierarchy;
                            self::convertLeaves($newHierarchy, $fieldName);
                            self::$reorderedFiles[$firstLevelName] = array_merge_recursive(self::$reorderedFiles[$firstLevelName], $newHierarchy);
                        } else {
                            self::$reorderedFiles[$firstLevelName][$fieldName] = $hierarchy;
                        }
                    }
                }
            }
            return self::$reorderedFiles;
        }

        /**
         * Converts each leaf (any item that is not an array) of the given array to an array with a single item that has
         * $name key and the previous value as value
         * @param array $array
         * @param string $name
         */
        private static function convertLeaves(array &$array, string $name) : void {
            foreach ($array as $k => &$child) {
                if (\is_array($child)) {
                    self::convertLeaves($child, $name);
                } else {
                    $array[$k] = [$name => $child];
                }
            }
        }

        private static $uploadedFiles;

        /**
         * Parses the array returned by {@link FilesUtils::getReorderedFiles()} and transforms each leaf array (i.e.
         * each array containing name, tmp_name, etc.) to a closure that creates an {@link UploadedFile} or throw
         * {@link UploadedFileException}, depending on the error property.
         *
         * Example usage:
         * <code>
         * $uploadedFiles = FileUtils::getUploadedFiles();
         * $uploadedFiles['user']['info']['avatar'](); // <-- this will either return an UploadedFile or throw UploadedFileException
         * </code>
         * @return array array with the same structure as {@link FilesUtils::getReorderedFiles()} but a closure
         * returning a {@link UploadedFile} as leaves
         */
        public static function getUploadedFiles() : array {
            if (self::$uploadedFiles === null) {
                self::$uploadedFiles = self::convertToUploadedFiles(self::getReorderedFiles());
            }
            return self::$uploadedFiles;
        }

        private static function convertToUploadedFiles(array $arr) {
            if (UploadedFile::isValidFilesArray($arr)) {
                return function () use ($arr) {
                    return UploadedFile::fromFilesArray($arr);
                };
            } else {
                $ret = [];
                foreach ($arr as $k => $v) {
                    $ret[$k] = is_array($v) ? self::convertToUploadedFiles($v) : $v;
                }
                return [];
            }
        }

        /**
         * Returns the {@link UploadedFile} at the specified path
         * @param string ...$path the path of the param name (e.g. user[info][avatar])
         * @return UploadedFile the {@link UploadedFile} instance, or null if the file with the given path does not exist
         * @throws UploadedFileException if the uploaded file has an error
         * @noinspection PhpDocRedundantThrowsInspection because of closure
         */
        public static function optUploadedFile(string ...$path) : ?UploadedFile {
            if (\count($path) === 0) {
                throw new \DomainException('Path must have at least one entry');
            }
            $ret = self::getUploadedFiles();
            foreach ($path as $item) {
                $ret = $ret[$item] ?? null;
            }
            if ($ret !== null) {
                $ret = $ret();
            }
            return $ret;
        }

        /**
         * Returns the {@link UploadedFile} at the specified path
         * @param string ...$path the path of the param name (e.g. user[info][avatar])
         * @return UploadedFile the {@link UploadedFile} instance
         * @throws UploadedFileException if the uploaded file has an error
         * @throws \LogicException if the file with the given path does not exist
         */
        public static function getUploadedFile(string ...$path) : UploadedFile {
            $ret = self::optUploadedFile(...$path);
            if ($ret === null) {
                throw new \LogicException('Unable to find uploaded file under path ' . implode('/', $path));
            } else {
                return $ret;
            }
        }
    }