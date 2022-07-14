# Adding Custom Pages
Custom Pages enables you to hold multiple Documentation librarys in the backend under sub pages. This can be useful if there is multiple Custom plugins you wish to document

## Creating a page
Creating a page is simple, We are calling the ```WPDocsify``` Class within a php file.  
For this example we will use the ```functions.php``` file located within your base theme  

```php
/* functions.php */

/* Is not required rename the namespace using MBC\inc before the class is fine */
use MBC\inc as PLUGINS;

/* Single Page Register */
PLUGINS\WPDocsify::register(
    array(
        /* Page Title */
        'title'=>'Feature',
        /* Page Label */
        'label'=>'Feature Documentation',
        /* Page Slug ( No spaces ) */
        'slug'=>'feature',
        /* Documentation Folder */
        'location'=> get_template_directory_uri().'/feature_docs/',
        /* Documentation Config ( more can be found Customizations/Configurations ) */
        'config'=> array(
            'maxLevel'=> 4, 
            'subMaxLevel'=> 2,
            'loadSidebar'=> "_sidebar.md",
            'homepage'=> "quickstart.md",
        ),
    )
);


/* Registering Multiple Pages */
PLUGINS\WPDocsify::register(
    array(
        /* Arrays Within a single Array */
        array(
            'title'=>'Feature',
            'label'=>'Feature Documentation',
            'slug'=>'feature',
            'location'=> get_template_directory_uri().'/feature_docs/',
            'config'=> array(
                'maxLevel'=> 4, 
                'subMaxLevel'=> 2,
                'loadSidebar'=> "_sidebar.md",
                'homepage'=> "quickstart.md",
            ),
        ),
        array(
            'title'=>'Custom',
            'label'=>'Custom Documentation',
            'slug'=>'custom',
            'location'=> get_template_directory_uri().'/custom_docs/',
            'config'=> array(
                'maxLevel'=> 4, 
                'subMaxLevel'=> 2,
                'loadSidebar'=> "_sidebar.md",
                'homepage'=> "quickstart.md",
            ),
        )
    )
);
```
## Restrictions
If you wish to restrict certain documentation behind specific user ```roles```,```ID``` or ```capabilities``` it is possible to do so by extended the pages configuration

```php
/* functions.php */

/* Is not required rename the namespace using MBC\inc before the class is fine */
use MBC\inc as PLUGINS;

/* Restricted Page Register */
PLUGINS\WPDocsify::register(
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
);
/* Note this will return a page only if the user is an adiminstrator & has the capability of manage_woocommerce & the user id is 1 */

```
