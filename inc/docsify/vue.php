<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Vue extends WPDocsify {
    public static function enqueue(){
		/* add action to enqueue scripts */
		add_action( 'admin_enqueue_scripts', array(__CLASS__, Vue::register()),10);
	}
	public static function register(){
		/* enqueue docsify vue core */
		if(!empty(parent::$vue) && parent::$vue['enabled'] === true){
			switch(parent::$vue['version']){
				case 'vue@3':
					$control = parent::$vue['control'] === 'production' ? 'vue.global.prod' : 'vue.global';
					wp_enqueue_script("wp-docsify-vue3-core-".parent::$vue['control'], "https://cdn.jsdelivr.net/npm/vue@3/dist/$control.js");
					break;
				case 'vue@2':
					$control = parent::$vue['control'] === 'production' ? 'vue.min' : 'vue';
					wp_enqueue_script("wp-docsify-vue2-core-".parent::$vue['control'], "https://cdn.jsdelivr.net/npm/vue@2/dist/$control.js");
					break;
			}
		}
	}
}
