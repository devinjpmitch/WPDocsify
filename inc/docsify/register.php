<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Register extends WPDocsify {
    public function __construct($arr = array()) {
      	/* Array of pages */
		if(is_array($arr)){
			/* is a single page */
			if(isset($arr['title'])) array_push(parent::$pages, self::setIcon($arr));
			/* is multiple pages */
			else {
				/* each page */
				foreach($arr as $page){
					/* is page an array of settings */
					if(is_array($page)) array_push(parent::$pages, self::setIcon($page));
				}
			}
		}
    }
    public function base($dir = '') {
        if(empty($dir)) return;
		/* set documentation directory */
		parent::$baseDir = $dir;
    }
	public static function setIcon($page){
		/* set icon */
		$default_icon = plugin_dir_path( __DIR__ ).'assets/img/defaulticon.svg';
		if(!isset($page['icon']) || empty($page['icon']) || !file_exists($page['icon'])) $page['icon'] = $default_icon;
		$icon = file_get_contents($page['icon']);
        $icon_template = '<span class="with-icon">%s%s</span>';
		$page['label'] = sprintf($icon_template, $icon,$page['label']);
		return $page;
	}
}
