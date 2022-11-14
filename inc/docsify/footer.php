<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Footer extends WPDocsify {
	public static function enqueue(){
		/* add action to enqueue scripts */
		add_action( 'admin_footer', function($data){
			\MBC\docsify\Footer::register();
			return $data;
		},1,10);
	}
    public static function register(){
        $globals = parent::$globals;
		$wpdocsify_js = file_get_contents( plugin_dir_path(__DIR__).'assets/js/wpdocsify.min.js' );
		$format = '<script type="text/javascript">%s window.WPDocsify.globals = %s;</script>';
		echo sprintf(
			$format,
			$wpdocsify_js,
			json_encode($globals)
		);
	}
}
