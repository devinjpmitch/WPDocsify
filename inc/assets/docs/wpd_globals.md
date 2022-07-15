# Globals
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
if you are fine with the default Admin page you can still edit some settings without remapping the menu

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