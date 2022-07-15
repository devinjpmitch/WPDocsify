# Lifecycles
Docsify has a set of defined [Lifecycles](write-a-plugin.md#lifecycle-hooks) simply the cycles allow for you to change and interact with the documentation through out there respect lifecycle
this can be useful when creating a plugin that alters the way the documentation is presented.

## Registering LifeCycles
To register lifecyles you can use the following class function.  

!> Note: all lifecyles are written in javascript so that must be required in.

```php
/* functions.php */

/* Is not required rename the namespace using MBC\inc before the class is fine */
use MBC\inc as PLUGINS;

PLUGINS\WPDocsify::lifecycle(array(
    /* Variables available: hook, vm */
    "init"=> array(
        /* example we wish to load init.js */
        stylesheet_directory_uri().'/assets/js/init.js',
        //stylesheet_directory_uri().'/assets/js/test.js'
    ),
    "beforeEach"=> array(
        /* Variables available: content */
    ),
    "afterEach"=> array(
        /* Variables available: html, next */
        stylesheet_directory_uri().'/assets/js/voul_count.js'
    ),
    "doneEach"=> array(),
    "mounted"=> array(),
    "ready"=> array()
));
```
```js
/* init.js */
(function () {
    /* console log vm */
    console.log('init vm:',vm);
})();
```
```js
/* voul_count.js */
console.log((html.match(/[aeiou]/gi) || []).length);
```
