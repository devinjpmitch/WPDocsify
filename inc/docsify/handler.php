<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Handler extends WPDocsify {
    public static function check($dir,$config){
        /* check if directory exists */
        $dir_absolute = str_replace(parent::$site_url, untrailingslashit(Wordpress::homepath()), $dir);
          
        /* if $dir_absolute is a file */
        if(!is_file($dir_absolute.$config['homepage'])){
            $config['homepage'] = 'quickstart.md';
            if(!is_file($dir_absolute.$config['homepage'])) {
                $error = array(
                    "title"=>"Oops!",
                    "error"=>"Quick quickstart.md or _coverpage.md not found. please set your coverpage."
                ); 
                return include plugin_dir_path(__DIR__) . "assets/error.php"; 
            } 
        }
        if(!is_dir($dir_absolute)){ 
            $error = array(
                "title"=>"Oops!",
                "error"=>"This documentation directory does not exist."
            ); 
            return include plugin_dir_path(__DIR__) . "assets/error.php"; 
        }
        /* if directory exists but is empty */
        if(is_dir($dir_absolute) && count(scandir($dir_absolute)) <= 2){
            $error = array(
                "title"=>"No Markdown files",
                "error"=>"This documentation directory exist but is empty."
            );
            return include plugin_dir_path(__DIR__) . "assets/error.php";
        }
        self::render($dir,$config);
    }
    public static function render($dir,$config){
		/* enqueue Docsify Assets */
		Assets::enqueue();
        /* enqueue prism styling */
        Prism::enqueue();
		/* enqueue docsify plugins */
        Plugins::enqueue();
		/* enqueue docsify vue core */
		Vue::enqueue();
		/* display docsify */
		echo sprintf(
			'<section id="wpdocsify"><div id="docsify"></div><section><script>window.$docsify = %s;%s</script>',
			/* merge docsify config */
			json_encode(
				array_merge(array(
					'search'=> array(
						'maxAge' => 86400000, // Expiration time, the default one day
						'paths' => 'auto', // or 'auto'
						'placeholder' => 'Type to search',
					),
					"el"=> "#docsify", /* docsify element */
					"basePath"=> $dir,  /* base directory */
				),$config)
			),
			Lifecylce::register()
		);
		/* return early */
		return;
	}
    public static function gitRender($config){
		/* enqueue Docsify Assets */
		Assets::enqueue();
        /* enqueue prism styling */
        Prism::enqueue();
		/* enqueue docsify plugins */
        Plugins::enqueue();
		/* enqueue docsify vue core */
		Vue::enqueue();
		/* display docsify */

		/**
		 * configurate url
		 */
		if(strpos($config['url'], 'github.com') !== false) {
			$config['url'] = str_replace('github.com', 'raw.githubusercontent.com', $config['url']);
			$config['url'] = preg_replace('/\/blob/', '', $config['url']);
		}
		
		echo sprintf(
			'<section id="wpdocsify"><div id="docsify"></div><section><script>window.$docsify = %s;%s</script>',
			/* merge docsify config */
			json_encode(
				array(
					'search' => 'auto', // enabled search
					"el"=> "#docsify", /* docsify element */
  					"basePath"=> $config['url'],  /* base directory */
					"sidebar" => $config['loadsidebar'], /* sidebar */
				)
			),
			Lifecylce::register()
		);
		/* return early */
		return;
	}
    public static function icon(){
        /* default icon */
		$default = 'dashicons-media-document';
		/* if icon is not dashicons and is a svg */
		if(strpos(parent::$adminpage['icon'], "dashicons-") === false && pathinfo(parent::$adminpage['icon'], PATHINFO_EXTENSION) === "svg"){
			/* get icon */
			$icon = @file_get_contents(parent::$adminpage['icon']);
			/* return svg image base64 encoded */
			if($icon) return 'data:image/svg+xml;base64,' . base64_encode($icon);
			/* else return default icon */
			else return $default;
		} 
		/* else return default icon */
		else return $default;
    }
}
