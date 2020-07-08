# http-utils
This library is a very simple collection of a few classes to facilitate handling HTTP requests.

## Installation
Available as composer package `femastudios/http-utils`. PHP 7.3 or greater required.

## Contents & Usage

### Core
Very base classes. Enumerators are declared using the [femastudios/enums](https://github.com/femastudios/enums) library.

* [`HttpRequestMethod`](https://github.com/femastudios/http-utils/blob/master/src/HttpRequestMethod.php): enumerator that defines the common request methods (`GET`, `POST`, etc.). They also have some properties (e.g. is cachable)
* [`HttpResponseCode`](https://github.com/femastudios/http-utils/blob/master/src/HttpResponseCode.php): enumerator that defines the common response codes (`SUCCESS`, `NOT_FOUND`, `FORBIDDEN`, etc.). They have the numerical code and the standard message.
* [`HttpResponseCodeType`](https://github.com/femastudios/http-utils/blob/master/src/HttpResponseCodeType.php): enumerator that defines the type of a response code (i.e. `INFORMATIONAL`, `SUCCESSFUL`, `REDIRECTION`, `CLIENT_ERROR` and `SERVER_ERROR`)
* [`HttpException`](https://github.com/femastudios/http-utils/blob/master/src/HttpException.php): exception class that wraps an `HttpResponseCode` enum, useful to bubble up an HTTP response code that need to be handled at a higher level.
    
### Header utils
These utilities allow to better handle request and response HTTP headers.
* [`RequestHeaderUtils`](https://github.com/femastudios/http-utils/blob/master/src/headers/RequestHeaderUtils.php): contains functions to read the headers sent by the requester (even in a context that does not define `getallheaders()`, like FPM).
* [`ResponseHeaderUtils`](https://github.com/femastudios/http-utils/blob/master/src/headers/ResponseHeaderUtils.php): contains functions to add and read headers to be sent, also with support of comma-separated values (that can be added in different calls).

Example usage:
```php
RequestHeaderUtils::get('Content-Type', 'none'); // Get the header, or default value

ResponseHeaderUtils::put('Content-Type', 'application/json'); // Put the header or throws if they have already been sent
ResponseHeaderUtils::addCsv('Vary', 'Origin'); // Treats the header value as a comma-separated value and adds "Origin"
```

### Uploaded files utils
Here we have utilities that help handling file uploads. The main reason for this utility is:
1. Handle the errors with an exception;
2. Untangle the mess that is the `$_FILES` array when the param name is nested (e.g. `user[info][avatar]`).
For more on this see the doc of [`UploadedFilesUtils::getReorderedFiles()`](https://github.com/femastudios/http-utils/blob/master/src/files/UploadedFilesUtils.php#L14-L110).

The classes are:

* [`UploadedFile`](https://github.com/femastudios/http-utils/blob/master/src/files/UploadedFile.php): class that contains the info on a single uploaded file (e.g. name, size, etc.)
* [`UploadedFileException`](https://github.com/femastudios/http-utils/blob/master/src/files/UploadedFileException.php): exception thrown when an error uploading a file is detected. It cotains the error code and a description of the error as message.
* [`UploadedFilesUtils`](https://github.com/femastudios/http-utils/blob/master/src/files/UploadedFilesUtils.php): contains static functions to retrieve the uploaded files

Example usage:
```php
$uf = UploadedFilesUtils::getUploadedFile('user', 'info', 'avatar'); // Returns an UploadedFile or throws UploadedFileException
$uf->getTmpName(); // Return the file temp filename (e.g. /tmp/php1324.tmp)
``` 
 
