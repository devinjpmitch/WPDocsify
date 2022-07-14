# Prism Highlighter
[Prism](https://prismjs.com/) is a code highlighter that works with Markdown it is an estiental part of Docsify to highlight your code.  
By Default Docsify has Prismjs support but not all languages are installed.

## Using Prism
To highlight your code using prism we need to wrap your code around 3 backticks followed by the file type and another 3 backticks

<pre v-pre="" data-lang="md"><code class="lang-md">```php  
    function test(){
        echo "hello world";
    }
```</code></pre>

## Extending Prism
If the language you looking to use with prism is not highlighting you can extend prism to include the langauge if availble.  
A full list of languages can be found here [Prism Langauges](https://prismjs.com/#supported-languages) or [Prism CDN](https://cdnjs.com/libraries/prism)  

```php
/* functions.php */

/* Is not required rename the namespace using MBC\inc before the class is fine */
use MBC\inc as PLUGINS;

/* extend prism languages */
/* full list of languages and versions here https://cdnjs.com/libraries/prism */
PLUGINS\WPDocsify::prism(
    array(
        'version'=> "1.28.0", //not required unless you wish for a specific version
        "languages"=> array(
            "php",
            "csharp",
            "graphql"
        ),
        "stylesheet"=> get_stylesheet_uri() . "/assets/prism/style.css" // not required can use to extend the stylesheet
    )
);
```
