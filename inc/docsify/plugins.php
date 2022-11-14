<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Plugins extends WPDocsify {
    public static function enqueue(){
		add_action( 'admin_enqueue_scripts', array(__CLASS__, Plugins::register()),10);
	}
	public static function register(){
		if(!empty(parent::$plugins)){
			foreach(parent::$plugins as $plugin) {
				/* simplfy filename */
				$plugin_name = substr(basename(str_replace('.', '_', $plugin)), 0, strrpos(basename($plugin), '.'));
				/* enqueue plugin */
				wp_enqueue_script("wp-docsify-plugin-$plugin_name", $plugin);
			}
		}
	}
}
