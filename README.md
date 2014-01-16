Native5 Client SDK for helping you build adaptive mobile apps. [![Build Status](https://travis-ci.org/native5/native5-sdk-client-php.png)](https://travis-ci.org/native5/native5-sdk-client-php)

Core framework elements resides in *Native5/Core*
Controllers define the various page controllers and reside in module *Native/Core/Controllers*
UI templates resides in *templates* 

* "index.php" is the entry point and subsequently a controller is looked up which serves out content specific to the action invoked.
* Device Detection & routing are handled by "Native5/Core/Route"
* Templates are used to render either partial or full content, upto the developer to define this.

Running Pre-requisites :
 
 * Apache2 
 * PHP 5.2+ 
 * Pear (optional) 
