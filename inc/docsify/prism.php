<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Prism extends WPDocsify {
    public static function enqueue(){
		/* add action to enqueue scripts */
		add_action( 'admin_enqueue_scripts', array(__CLASS__, Prism::register()),10);
	}
	public static function register(){
		/* enqueue prism styling */
		wp_enqueue_style('wp-prism-style', plugins_url('/assets/css/prism.min.css', __DIR__));
		/* enqueue Docsify Javascript */
		wp_enqueue_script('wp-docsify-minjs', plugins_url('/assets/js/docsify.min.js', __DIR__));
		/* enqueue prism languages */
		if(!empty(parent::$prism) && parent::$prism['languages']){
			$version = parent::$prism['version'];
			foreach(parent::$prism['languages'] as $language) wp_enqueue_script("wp-prism-$language", "https://cdnjs.cloudflare.com/ajax/libs/prism/$version/components/prism-$language.min.js");
			if(isset(parent::$prism['stylesheet'])) wp_enqueue_style('wp-prism-custom-stylesheet', parent::$prism['stylesheet']);
		}
	}
}
