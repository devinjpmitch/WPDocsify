<?php
/**
 * Wordpress Implementation of Docsify
 * This Class is used to implement Docsify and extend backend functionality with Wordpress
 *
 * @package MBC\Docsify
 */
namespace MBC\inc;
class WPDocsify {
	/* private string site url */
	private static $site_url = "";
	/* private string site ext */
	private static $site_ext = "";
	/* private array of pages */
	private static $pages = array();
	/* private array of admin page settings */
	private static $adminpage = array();
	/* private string for base directory URI */
	private static $baseDir = "";
	/* private docsify instance config */
	private static $config = array(
		/* default */
		"executeScript"=> true,
		"nativeEmoji"=> true,
		"maxLevel" => 4,
		"subMaxLevel" => 2,
		"loadSidebar" => "_sidebar.md",
		"homepage" => "_coverpage.md",
	);
	/* private string for custom stylesheet URI */
	private static $stylesheet = '';
	/* private array for custom plugin scripts URI */
	private static $plugins = array();
	/* private boolean for custom stylesheet replacement */
	private static $replace_stylesheet = false;
	/* prism language support array */
	private static $prism = array();
	/* prism default lts version */
	private static $prismlts = "1.28.0";
	/* Vue.js Config */
	private static $vue = array(
		"version"=> "vue@3",
		"control"=> "production",
		"enabled"=> true
	);
	/* plugin Docsify Lifecylce */
	private static $lifecycle = array(
		"init"=> array(),
		"beforeEach"=> array(),
		"afterEach"=> array(),
		"doneEach"=> array(),
		"mounted"=> array(),
		"ready"=> array()
	);
	/* globals wordpress */
	private static $globals = array();

	/* Setup for non configured */
	public static function Setup(){
		/* setup site url and extension */
		$ssl = is_ssl();
		$ssl = $ssl ? 'https://' : 'http://';
		self::$site_url = preg_replace("#^https?://#", "", get_site_url());
		if(strpos(self::$site_url, "/") !== false){
			$site_url_arr = explode("/", self::$site_url);
			self::$site_ext = $site_url_arr[count($site_url_arr)-1];
			self::$site_url = $ssl.$site_url_arr[0];
		} else self::$site_url = $ssl.self::$site_url;
		/* Set Default base directory */
		$location = '';
		if(empty(self::$baseDir)) {
			if(is_dir(get_stylesheet_directory() . '/documentation/')) $location = get_stylesheet_directory_uri() . '/documentation/';
			else {
				$location = plugins_url('/assets/docs/', __FILE__);
				self::$baseDir = plugins_url('/assets/docs/', __FILE__);
			}
		} else $location = self::$baseDir;
		/* if admin page is empty create default */
		if(empty(self::$adminpage)) self::$adminpage = array(
			"domain"=>"WPDocsify",
			"name"=>"WPDocsify",
			"slug"=>"wpdocsify",
			"location" => $location,
			"config" => self::$config,
			"icon"=>__DIR__.'/assets/img/icon.svg',
			"position"=> 99
		);
		/* setup prism */
		if(empty(self::$prism)) self::$prism = array(
			"version"=> self::$prismlts,
			"languages"=> array(
				"php"
			)
		);
	}
	/* configure docsify */
	public static function config($config = array()){
		if(empty($config)) return;
		/* unset el */
		if(isset($config['el'])) unset($config['el']);
		/* basePath */
		if(isset($config['basePath'])) unset($config['basePath']);
		/* set config */
		self::$config = $config;
	}

	/* register stylesheet */
	public static function stylesheet($stylesheet = '', $replace = false){
		if(empty($config)) return;
		/* set stylesheet */
		self::$stylesheet = $stylesheet;
		/* set stylesheet replace */
		self::$replace_stylesheet = $replace;
	}

	/* register plugins */
	public static function plugins($plugins = array()){
		if(empty($plugins)) return;
		foreach($plugins as $plugin) self::$plugins[] = $plugin;
	}

	/* plugin lifecycle */
	public static function lifecycle($lifecycle = array()){
		if(empty($lifecycle)) return;
		foreach($lifecycle as $key => $value){
			if(!isset(self::$lifecycle[$key])) continue;
			self::$lifecycle[$key] = array_merge(self::$lifecycle[$key], $value);
		}
	}

	/* Enable Vue.js */
	public static function Vue($version = 'vue@3',$control = 'production'){
		/* disable vue */
		if($version === false) return self::$vue['enabled'] = false;
		self::$vue = array(
			"version"=> $version,
			"control"=> $control,
			"enabled"=> true
		);
	}

	/* Vue Global scope */
	public static function global($global = array()){
		if(empty($global)) return;
		self::$globals = array_merge(self::$globals,$global);
	}

	/* register prism langauges */
	public static function prism($languages = array()){
		if(empty($languages)) return;
		/* check version */
		if(!isset($languages['version'])) $languages['version'] = self::$prismlts;
		/* check stylesheet */
		if(!isset($languages['stylesheet'])) $languages['stylesheet'] = false;
		/* set languages */
		self::$prism = $languages;
	}

	public static function abs_path_to_url( $path = '' ) {
    $url = str_replace(
        wp_normalize_path( untrailingslashit( ABSPATH ) ),
        site_url(),
        wp_normalize_path( $path )
    );
    return esc_url_raw( $url );
}

	/* Register default Admin Documentation Page */
	public static function adminRegister($arr = array()){
		if(empty($arr)) return;
		/* check if Location set */
		if(!isset($arr['location'])) $arr['location'] = self::$baseDir;
		else {
			/* determine Base Directory */
			$dir_absolute = str_replace(self::$site_url, trailingslashit(ABSPATH), $arr['location']);

			/* fixing the mess */
			$dir_absolute = $arr['location'];
			self::$site_url = get_home_url();
			$arr['location'] = self::abs_path_to_url($arr['location']);
			
			/* if stylesheet directory documentation does not exist revert to $baseDir */
			if(!is_dir($dir_absolute)) $arr['location'] = self::$baseDir;
		}
		/* check if Config Set */
		if(!isset($arr['config'])) $arr['config'] = self::$config;
		/* set admin page */
		self::$adminpage = $arr;
	}


	/* Register new Sub Documention Pages */
	public static function register($arr) {
		/* Array of pages */
		if(is_array($arr)){
			/* is a single page */
			if( isset($arr['title']) ) array_push(self::$pages, $arr);
			/* is multiple pages */
			else {
				/* each page */
				foreach($arr as $page){
					/* is page an array of settings */
					if(is_array($page)) array_push(self::$pages, $page);
				}
			}
		}
	}

	/* Change current documentation base directory */
	public static function base($dir = ''){
		if(empty($dir)) return;
		/* set documentation directory */
		self::$baseDir = $dir;
	}

	/* Private restriction function */
	private static function restricted($page = array()){
		if(empty($page)) return;
		/* if not restricted */
		if(!isset($page['restricted'])) return true;
		/* restrictions */
		$restrictions = $page['restricted'];
		/* current operator default: or */
		$operator = isset($page['restrict_operator']) ? $page['restrict_operator'] : 'or';
		/* current user */
		$current_user = wp_get_current_user();
		/* is array of restrictions */
		if(is_array($restrictions)){
			/* operator is or */
			if($operator === 'or'){
				/* each restriction return if true */
				foreach($restrictions as $restriction){
					/* is current id same as restriction id */
					if(is_numeric($restriction) && $restriction === get_current_user_id()) return true;
					/* is current roles same as restriction roles */
					if(in_array($restriction, $current_user ? $current_user->roles : array())) return true;
					/* is current capabilities same as capabilities roles */
					if(in_array($restriction, $current_user ? $current_user->caps : array())) return true;
				}
			} 
			if($operator === 'and') {
				/* array of passed restrictions */
				$pass = array();
				foreach($restrictions as $restriction){
					/* is current id same as restriction id */
					if($restriction === get_current_user_id()) {
						$pass[] = true;
						continue;
					}
					/* is current roles same as restriction roles */
					if(in_array($restriction, $current_user ? $current_user->roles : array())) {
						$pass[] = true;
						continue;
					}
					/* is current capabilities same as capabilities roles */
					if(in_array($restriction, $current_user ? $current_user->caps : array())) {
						$pass[] = true;	
						continue;
					}
				}
				/* pass is equal to restrictions */
				return count($pass) === count($restrictions);
			}
		} 
		/* return false if nothing matched */
		return false;
	}

	/* Private return Admin Icon Function */
	private static function icon(){
		/* default icon */
		$default = 'dashicons-media-document';
		/* if icon is not dashicons and is a svg */
		if(strpos(self::$adminpage['icon'], "dashicons-") === false && pathinfo(self::$adminpage['icon'], PATHINFO_EXTENSION) === "svg"){
			/* get icon */
			$icon = @file_get_contents(self::$adminpage['icon']);
			/* return svg image base64 encoded */
			if($icon) return 'data:image/svg+xml;base64,' . base64_encode($icon);
			/* else return default icon */
			else return $default;
		} 
		/* else return default icon */
		else return $default;
	}

	/* Public function Admin Page Setup */
	public static function adminPage(){
		/* Confirm Setup */
		self::Setup();
		/* restricted documentation */
		if(!self::restricted(self::$adminpage)) return;
		/* add admin page */
		add_menu_page(
			__(self::$adminpage['domain'], 'textdomain'),
			self::$adminpage['name'],
			'read',
			self::$adminpage['slug'],
			function() {
				self::Handler(self::$adminpage['location'],self::$adminpage['config']);
			},
			self::icon(),
			self::$adminpage['position']
		);
		/* add sub pages */
		foreach(self::$pages as $sub_page){
			/* restricted sub page check */
			if(!self::restricted($sub_page)) continue;
			/* sub page check */
			if(empty($sub_page) || isset($sub_page['title']) == false || isset($sub_page['location']) == false) continue;
			/* subpage slug defaults */
			$sub_page['slug'] = isset($sub_page['slug']) ? str_replace(" ", "-", $sub_page['slug']) : str_replace(" ", "_", $sub_page['title']);
			$sub_page['config'] = isset($sub_page['config']) ? $sub_page['config'] : self::$config;
			/* add subpage */
			add_submenu_page( 
				self::$adminpage['slug'],
				$sub_page['title'],
				isset($sub_page['label']) ? $sub_page['label'] : $sub_page['title'],
				'read',
				$sub_page['slug'],
				function() use($sub_page) {
					self::Handler($sub_page['location'],$sub_page['config']);
				},
			);
		}
	}
	
	private static function getLifecycle(){
		$format = '(function () {
			var WPDocsify = function(hook, vm){hook.init(function(){%s}); hook.beforeEach(function(content){%s return content;}); hook.afterEach(function(html, next){%s next(html);}); hook.doneEach(function(){%s}); hook.mounted(function(){%s}); hook.ready(function(){%s});}
			$docsify = window.$docsify || {};
			$docsify.plugins = [].concat(WPDocsify, $docsify.plugins || []);
		})();';
		/* lifecycle array */
		$lifecycle = array();
		foreach(self::$lifecycle as $key => $value) {
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
			if($key === 'beforeEach') $single_lifecycle[] = file_get_contents( __DIR__.'/assets/js/global.js' );
			if($key === 'afterEach') $single_lifecycle[] = file_get_contents( __DIR__.'/assets/js/footer.js' );
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


	
	private static function WPDocsify(){
		$globals = self::$globals;
		add_action('admin_footer', function () use ($globals) {
			$wpdocsify_js = file_get_contents( __DIR__.'/assets/js/wpdocsify.min.js' );
			$format = '"<script type="text/javascript">%s window.WPDocsify.globals = %s;</script>"';
			echo sprintf(
				$format,
				$wpdocsify_js,
				json_encode(self::$globals)
			);
		});
	}

	/* Private Handler for documentation library page */
	private static function Handler($dir,$config){
		self::WPDocsify();
		/* check if directory exists */
		$dir_absolute = str_replace(self::$site_url, untrailingslashit(get_home_path()), $dir);
		//if $dir_absolute is a file
		if(!is_file($dir_absolute.$config['homepage'])){
			$config['homepage'] = 'quickstart.md';
			if(!is_file($dir_absolute.$config['homepage'])) {
				$error = array(
					"title"=>"Oops!",
					"error"=>"Quick quickstart.md or _coverpage.md not found. please set your coverpage."
				); 
				return include __dir__ . "/assets/error.php"; 
			} 
		}
		if(!is_dir($dir_absolute)){ 
			$error = array(
				"title"=>"Oops!",
				"error"=>"This documentation directory does not exist."
			); 
			return include __dir__ . "/assets/error.php"; 
		}
		/* if directory exists but is empty */
		if(is_dir($dir_absolute) && count(scandir($dir_absolute)) <= 2){
			$error = array(
				"title"=>"No Markdown files",
				"error"=>"This documentation directory exist but is empty."
			);
			return include __dir__ . "/assets/error.php";
		}
		/* enqueue Wordpress styling */
		wp_enqueue_style('wp-docsify-wpstyle', plugins_url('/assets/css/style.css', __FILE__));
		/* check if stylesheet loaded */
		if(!empty(self::$stylesheet) && self::$replace_stylesheet){
			/* enqueue custom stylesheet */
			wp_enqueue_style('wp-docsify-custom-style', self::$stylesheet);
		} else {
			/* enqueue custom stylesheet */
			if(!empty(self::$stylesheet)) wp_enqueue_style('wp-docsify-custom-style', self::$stylesheet);
			/* enqueue Docsify styling */
			wp_enqueue_style('wp-docsify-minstyle', plugins_url('/assets/css/wpdocsify.min.css', __FILE__));
		}
		/* enqueue prism styling */
		wp_enqueue_style('wp-prism-style', plugins_url('/assets/css/prism.min.css', __FILE__));
		/* enqueue Docsify Javascript */
		wp_enqueue_script('wp-docsify-minjs', plugins_url('/assets/js/docsify.min.js', __FILE__));
		/* enqueue prism languages */
		if(!empty(self::$prism) && self::$prism['languages']){
			$version = self::$prism['version'];
			foreach(self::$prism['languages'] as $language) wp_enqueue_script("wp-prism-$language", "https://cdnjs.cloudflare.com/ajax/libs/prism/$version/components/prism-$language.min.js");
			if(isset(self::$prism['stylesheet'])) wp_enqueue_style('wp-prism-custom-stylesheet', self::$prism['stylesheet']);
		}
		/* enqueue docsify plugins */
		if(!empty(self::$plugins)){
			foreach(self::$plugins as $plugin) {
				/* simplfy filename */
				$plugin_name = substr(basename(str_replace('.', '_', $file)), 0, strrpos(basename($file), '.'));
				/* enqueue plugin */
				wp_enqueue_script("wp-docsify-plugin-$plugin_name", $plugin);
			}
		}
		/* enqueue docsify vue core */
		if(!empty(self::$vue) && self::$vue['enabled'] === true){
			switch(self::$vue['version']){
				case 'vue@3':
					$control = self::$vue['control'] === 'production' ? 'vue.global.prod' : 'vue.global';
					wp_enqueue_script("wp-docsify-vue3-core-".self::$vue['control'], "https://cdn.jsdelivr.net/npm/vue@3/dist/$control.js");
					break;
				case 'vue@2':
					$control = self::$vue['control'] === 'production' ? 'vue.min' : 'vue';
					wp_enqueue_script("wp-docsify-vue2-core-".self::$vue['control'], "https://cdn.jsdelivr.net/npm/vue@2/dist/$control.js");
					break;
			}
		}
		/* display docsify */
		$format = '<section id="wpdocsify"><div id="docsify"></div></section><script>
		window.$docsify = %s;
		%s
		</script>';
		echo sprintf(
			$format,
			/* merge docsify config */
			json_encode(
				array_merge(array(
					"el"=> "#docsify", /* docsify element */
					"basePath"=> $dir,  /* base directory */
				),$config)
			),
			self::getLifecycle()
		);
		/* return early */
		return;
	}
}
