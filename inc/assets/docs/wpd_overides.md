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