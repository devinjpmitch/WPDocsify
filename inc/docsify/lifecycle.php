<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Lifecylce extends WPDocsify {
    public static function register(){
		$format = '(function () {
			var WPDocsify = function(hook, vm){hook.init(function(){%s}); hook.beforeEach(function(content){%s return content;}); hook.afterEach(function(html, next){%s next(html);}); hook.doneEach(function(){%s}); hook.mounted(function(){%s}); hook.ready(function(){%s});}
			$docsify = window.$docsify || {};
			$docsify.plugins = [].concat(WPDocsify, $docsify.plugins || []);
		})();';
		/* lifecycle array */
		$lifecycle = array();
		foreach(parent::$lifecycle as $key => $value) {
			/* allow key to be in lifecycle */
			$lifecycle[$key] = "";
			/* if not array continue */
			if(!is_array($value)) continue;
			/* single life cycle array */
			$single_lifecycle = array();
			/* require each javascript */
			foreach ($value as $key => $plugin_uri) {
				if(empty($plugin_uri) || !is_string($plugin_uri) || !file_exists($plugin_uri)) continue;
				$single_lifecycle[] = file_get_contents($plugin_uri);
			}
			if($key === 'beforeEach') $single_lifecycle[] = file_get_contents( plugin_dir_path(__DIR__).'assets/js/global.js' );
			if($key === 'afterEach') $single_lifecycle[] = file_get_contents( plugin_dir_path(__DIR__).'assets/js/footer.js' );
			/* combine all javascript to lifecycle key */
			$lifecycle[$key] = implode(" ", $single_lifecycle);
		}
		return sprintf(
			$format,
			$lifecycle['init'],
			$lifecycle['beforeEach'],
			$lifecycle['afterEach'],
			$lifecycle['doneEach'],
			$lifecycle['mounted'],
			$lifecycle['ready']
		);
	}
}
