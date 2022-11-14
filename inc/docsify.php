<?php
/**
 * Wordpress Implementation of Docsify
 * This Class is used to implement Docsify and extend backend functionality with Wordpress
 *
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class WPDocsify {
	/* public string site url */
	public static $site_url = "";
	/* public string site ext */
	public static $site_ext = "";
	/* public array of pages */
	public static $pages = array();
	/* public array of admin page settings */
	public static $adminpage = array();
	/* public array of git pages */
	public static $gitPages = array();
	/* public string for base directory URI */
	public static $baseDir = "";
	/* public docsify instance config */
	public static $config = array(
		/* default */
		"executeScript"=> true,
		"nativeEmoji"=> true,
		"maxLevel" => 4,
		"subMaxLevel" => 2,
		"loadSidebar" => "_sidebar.md",
		"homepage" => "_coverpage.md",
	);
	/* public string for custom stylesheet URI */
	public static $stylesheet = '';
	/* public array for custom plugin scripts URI */
	public static $plugins = array(
		'//cdn.jsdelivr.net/npm/docsify/lib/plugins/search.min.js', // search plugin
	);
	/* public boolean for custom stylesheet replacement */
	public static $replace_stylesheet = false;
	/* prism language support array */
	public static $prism = array();
	/* prism default lts version */
	public static $prismlts = "1.29.0";
	/* Vue.js Config */
	public static $vue = array(
		"version"=> "vue@3",
		"control"=> "production",
		"enabled"=> true
	);
	/* plugin Docsify Lifecylce */
	public static $lifecycle = array(
		"init"=> array(),
		"beforeEach"=> array(),
		"afterEach"=> array(),
		"doneEach"=> array(),
		"mounted"=> array(),
		"ready"=> array()
	);
	/* globals wordpress */
	public static $globals = array();
	public static function init() {
		Setup::init();
	}
	public static function admin(){
		Admin::init();
	}
}
foreach (glob(__DIR__ . "/docsify/*.php") as $filename) {
	require_once $filename;
}

add_action('init', function(){
	add_action( 'admin_enqueue_scripts',function($hook){
		wp_enqueue_style('wp-docsify-mainstyle', plugins_url('/inc/assets/css/main.css', __DIR__));
	},10);	
	WPDocsify::init();
	add_action('admin_menu', function(){
		WPDocsify::admin();
	},10);
	Git::register();
},10);


      