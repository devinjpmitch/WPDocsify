<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Assets extends WPDocsify {
    public static function enqueue(){
		/* add action to enqueue scripts */
		add_action( 'admin_enqueue_scripts', array(__CLASS__, Assets::register()),10);
	}
	public static function register(){
		/* enqueue Wordpress styling */
		wp_enqueue_style('wp-docsify-wpstyle', plugins_url('/assets/css/style.css', __DIR__));
		/* check if stylesheet loaded */
		if(!empty(parent::$stylesheet) && parent::$replace_stylesheet){
			/* enqueue custom stylesheet */
			wp_enqueue_style('wp-docsify-custom-style', parent::$stylesheet);
		} else {
			/* enqueue custom stylesheet */
			if(!empty(parent::$stylesheet)) wp_enqueue_style('wp-docsify-custom-style', parent::$stylesheet);
			/* enqueue Docsify styling */
			wp_enqueue_style('wp-docsify-minstyle', plugins_url('/assets/css/wpdocsify.min.css', __DIR__));
		}
		/* enqueue Docsify Javascript */
		wp_enqueue_script('wp-docsify-minjs', plugins_url('/assets/js/docsify.min.js', __DIR__));
	}
}
