Coding Challenge.
--

MVC PHP Framework

Some Helpfu links I used
--
https://stackoverflow.com/questions/44387343/dependency-injection-not-working-with-league-route-and-league-container

https://www.youtube.com/watch?v=VlsCeP-c7o0

https://www.themoviedb.org/

https://github.com/vlucas/phpdotenv

https://twig.symfony.com/

https://solved-by-grid.now.sh/with-sidenav-footer-1

http://php.net/manual/en/class.pdo.php


Process
--
The main decision I made for the challenge is to go for (*1)PhpLeague Route packages for routing/dispatching the application in a MVC pattern way. 
This choice give me a rapid structure to start working in the application based in a well tested/ trusted code.

The PhpLeague Route packages require an implementation of PSR-7 (*2)HTTP Message to work.
This specification defines interfaces for the HTTP messages Psr\Http\Message\RequestInterface and Psr\Http\Message\ResponseInterface.

As per in the League\Route example I choose Zend\Diactoros implementation.


(*1) The PHPLeague is a distinguished group of developers who build solid, well tested PHP packages using modern coding standards for the community.

(*2)  An HTTP message is either a request from a client to a server or a response from a server to a client.


Fundamental pieces of an HTTP request/response pair.

￼
![http](https://user-images.githubusercontent.com/2448234/36694417-1e2e5ad2-1b1d-11e8-9f1a-de8af7bd7ac1.jpg)


Basic Requests Verbs
* GET: fetch an existing resource. The URL contains all the necessary information the server needs to locate and return the resource.
* POST: create a new resource. POST requests usually carry a payload that specifies the data for the new resource.
* PUT: update an existing resource. The payload may contain the updated data for the resource.
* DELETE: delete an existing resource.

Basic Response Status Code
* 2xx: Successful
* 3xx: Redirection
* 4xx: Client Error
* 5xx: Server Error
