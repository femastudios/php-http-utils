# http-utils
This library is a very simple collection of a few classes to facilitate handling HTTP requests.

## Enumerators
Enumerators are declared using the [femastudios/enums](https://github.com/femastudios/enums) library.
* `HttpRequestMethod`: defines the common request methods (`GET`, `POST`, etc.). They also have some properties (e.g. is cachable)
* `HttpResponseCode`: defines the common response codes (`SUCCESS`, `NOT_FOUND`, `FORBIDDEN`, etc.). They have the numerical code and the standard message.
* `HttpResponseCodeType`: defines the type of a response code (i.e. `INFORMATIONAL`, `SUCCESSFUL`, `REDIRECTION`, `CLIENT_ERROR` and `SERVER_ERROR`)

## Utility classes
* `HeaderUtils`: contains functions to read the headers sent by the requester (even in a context that does not define `getallheaders()`, like FPM)
* `ResponseHeaderUtils`: contains functions to add and read headers to be sent, also with support of comma-separated values (that can be added in different calls)

## Exceptions
* `HttpException`: class that wraps an `HttpResponseCode` enum, useful to bubble up an HTTP response code that need to be handled at a higher level.