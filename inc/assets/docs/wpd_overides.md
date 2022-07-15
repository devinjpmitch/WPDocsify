# Overides
There are multiple overides to the base implementation of WPDocsify most of wich will configure the base menu structure

## Admin Menu
To Change the naming and menu of WPDocsify can be done with the following class function

```php
/* functions.php */

/* Is not required rename the namespace using MBC\inc before the class is fine */
use MBC\inc as PLUGINS;

PLUGINS\WPDocsify::adminRegister(array(
    "domain"=>"MyDocs", //Domain Namespace
    "name"=>"documenttester", //Menu Name
    "slug"=>"testdocs", //Menu Slug
    "icon"=> __DIR__ .'/assets/icons/alarm.svg', //Not required Menu icon ( Direct Path ) ( Can be Dashicons string )
    "position"=> 98, //Not Required Menu Position
    "location"=> get_stylesheet_directory_uri().'/documentation/', //Not Required Default folder
    "restricted"=>array('administrator','bbp_keymaster',1), //Not required Restrictions
    "restrict_operator"=> 'and', //Not required Restriction Operator
    "config"=> array( //Not Required Docsify config
        'maxLevel'=> 4,
        'subMaxLevel'=> 2,
        'loadSidebar'=> "_sidebar.md",
        'homepage'=> "quickstart.md",
    )
));
/* Note this will return the admin page only if the user is an adiminstrator & has the 
capability of manage_woocommerce & the user id is 1 */
```
if you are fine with the default Admin page you can still edit some settings without remapping the menu
```php
/* Base Docsify Config */
PLUGINS\WPDocsify::config(array(
    "maxLevel"=> 4,
    "subMaxLevel"=> 2,
    "loadSidebar"=> "_sidebar.md",
    "homepage"=> "_coverpage.md",
));

/* Base Documentation Location */
PLUGINS\WPDocsify::base(get_stylesheet_directory_uri().'/documentation/');
```


## Documentation Sub Pages
To Extend the documentation menu to have multiple sub page documentation librarys you can use the following

```php
/* functions.php */

/* Is not required rename the namespace using MBC\inc before the class is fine */
use MBC\inc as PLUGINS;

PLUGINS\WPDocsify::register(
    array(
        array(
            'title'=>'Feature',
            'label'=>'Feature Documentation',
            'slug'=>'feature',
            'location'=> get_template_directory_uri().'/feature_docs/',
            /* Restricted ( Array. Roles || Capabilities || ID ) */
            'restricted'=> array('administrator','manage_woocommerce',1),
            /* Restriction Operator ( String. and || or ) */
            'restrict_operator'=> 'and',
            'config'=> array(
                'maxLevel'=> 4, 
                'subMaxLevel'=> 2,
                'loadSidebar'=> "_sidebar.md",
                'homepage'=> "quickstart.md",
            ),
        )
    )
);
/* Note this will return a page only if the user is an adiminstrator & has the capability of manage_woocommerce & the user id is 1 */

```

## Extend Stylesheet
To extend the style of Docisfy you can use the following class function  

```php
/* functions.php */

/* Is not required rename the namespace using MBC\inc before the class is fine */
use MBC\inc as PLUGINS;

PLUGINS\WPDocsify::stylesheet(
    get_template_directory_uri().'/assets/css/WPDocsify.css',
    true //Not Require Replace default style if true append if false ( True or false )
);
```

## Extend Plugins
Docsify allows for multiple custom created and propritery plugins you can also make your own plugins using the [Docsify plugin lifecycle](write-a-plugin.md#write-a-plugin)  

```php
/* functions.php */

/* Is not required rename the namespace using MBC\inc before the class is fine */
use MBC\inc as PLUGINS;


PLUGINS\WPDocsify::plugins(
    array(
        '//cdn.jsdelivr.net/npm/docsify/lib/plugins/ga.min.js', //Google Analytics
        '//cdn.jsdelivr.net/npm/docsify/lib/plugins/zoom-image.min.js' //Zoom image
    )
);
```

## Vue.js
By Default Vue3 Production is turned on however you can choose to disable this or change it to development or vue2 more information on Vue implementation [Vue Compatibility](vue.md)
```php
/* functions.php */

/* Is not required rename the namespace using MBC\inc before the class is fine */
use MBC\inc as PLUGINS;

/* Vue 3 Production ( Default ) */
PLUGINS\WPDocsify::vue('vue@3','production');
/* Vue 3 Development */
PLUGINS\WPDocsify::vue('vue@3','development');

/* Vue 2 Production */
PLUGINS\WPDocsify::vue('vue@2','production');
/* Vue 2 Development */
PLUGINS\WPDocsify::vue('vue@2','development');


/* Vue disabled */
PLUGINS\WPDocsify::vue(false);

```

## Lifecycles
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

## Globals
WPDocsify allows for globals in your markdown available through `window.WPDocsify` this allows you to pass information from php into your markdown.  
as an example passing through a list of users in the site or the current plugins installed.

## Registering Globals
To register globals you can use the following class function

```php
/* functions.php */

/* Is not required rename the namespace using MBC\inc before the class is fine */
use MBC\inc as PLUGINS;

PLUGINS\WPDocsify::globals(array(
    'installed_plugins'=> array_map(function($plugin){ 
        return $plugin['Name']; 
    }, get_plugins()),
    'example'=> 'Hello World'
));
```

## Using Globals
To globals in your markdown you can use the following prefix `[(*)]`

```md
[(example)]
```
**Result**
```html
Hello World
```
## Vue Implementation
If you would like use the global data in Vue within your documentation you can use in by passing it into the data

```html
<!-- vue.md -->
<div id="vue3app">
    <!-- Foreach plugin in global installed_plugins array with key of plugin -->
    <ul>
        <li v-for="plugin in global.installed_plugins" :key="plugin">
            {{plugin}}
        </li>
    </ul>
</div>
<script>
 Vue.createApp({
    data: () => ({
      global: window.WPDocsify.globals,
    }),
  }).mount('#vue3app');
</script>
```