<?php
    declare(strict_types=1);

    namespace com\femastudios\utils\http\files;

    final class FilesUtils {

        private function __construct() {
            throw new \LogicException();
        }

        private static $reorderedFiles;

        /**
         * The <code>$_FILES</code> array is made in not very user-friendly way: passing, for example
         * <code>[info][avatar]</code> puts the array in the following condition:
         * <code>
         * user[name][info][avatar]
         * user[size][info][avatar]
         * user[....][info][avatar]
         * </code>
         * As you can see the each file parameter (name, size, etc) is grouped at the second level. So if we uploaded
         * multiple files we would end up with all names grouped together, all sizes, etc.
         *
         * This method takes the <code>$_FILES</code> super-global and puts each file parameter at the last level,
         * so it transforms the above structure into the following:
         * <code>
         * user[info][avatar][name]
         * user[info][avatar][size]
         * user[info][avatar][....]
         * </code>
         * Doing things this way is easier because we can then address each uploaded file array by its parameter name.
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
         *         "avatar": "image\/jpeg",
         *         "logo": "image\jpeg"
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
        *         "type": "image\/jpeg",
        *         "tmp_name": "C/tmp/phpB656.tmp",
        *         "error": 0,
        *         "size": 786492
        *       },
        *       "logo": {
        *         "name": "image2.png",
        *         "type": "image\/jpeg",
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
                            self::putKeyAtEnd($newHierarchy, $fieldName);
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
        private static function putKeyAtEnd(array &$array, string $name) : void {
            foreach ($array as $k => &$child) {
                if (\is_array($child)) {
                    self::putKeyAtEnd($child, $name);
                } else {
                    $array[$k] = [$name => $child];
                }
            }
        }
    }