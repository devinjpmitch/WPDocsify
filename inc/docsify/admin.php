<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Admin extends WPDocsify {
    public static function init(){
		/* Confirm Setup */
		Setup::init();
		/* restricted documentation */
		if(!Restricted::check(parent::$adminpage)) return;
		//on init action wordpress
		self::register();
	}
	public static function register(){
		Footer::enqueue();
		/* register admin page */
		add_menu_page(
			__(parent::$adminpage['domain'], 'textdomain'),
			parent::$adminpage['name'],
			'manage_options',
			parent::$adminpage['slug'],
			function(){
				Handler::check(parent::$adminpage['location'],parent::$adminpage['config']);
			},
			Handler::icon(),
			parent::$adminpage['position']
		);
		$child_icon = file_get_contents(plugin_dir_path(__DIR__).'assets/img/icon.svg');
        $child_label_template = '<span class="with-icon">%s%s</span>';
		$child_label = sprintf($child_label_template, $child_icon,parent::$adminpage['name']);
		/* register admin page as submenu page also */
		add_submenu_page( 
			parent::$adminpage['slug'], //parent slug
			parent::$adminpage['name'], //page title
			$child_label, //menu title
			'manage_options', //capability
			parent::$adminpage['slug'], //
			function() {
				Handler::check(parent::$adminpage['location'],parent::$adminpage['config']);
			}, //callback
			0 //position
		);

		/* foreach gitPages */
		foreach(parent::$gitPages as $gitPage){
			$repo_icon = file_get_contents(plugin_dir_path(__DIR__).'assets/img/repoicon.svg');
			$repo_label_template = '<span class="with-icon">%s%s</span>';
			$repo_label = sprintf($repo_label_template, $repo_icon,$gitPage['menu']);
			add_submenu_page( 
				parent::$adminpage['slug'], //parent slug
				$gitPage['title'], //page title
				$repo_label, //menu title
				'manage_options', //capability
				$gitPage['slug'], //slug
				function() use ($gitPage) {
					Handler::gitRender($gitPage['callback_params']);
				} //callback
			);
		}

		/* add sub pages */
		foreach(parent::$pages as $sub_page){
			/* restricted sub page check */
			if(!Restricted::check($sub_page)) continue;
			/* sub page check */
			if(empty($sub_page) || isset($sub_page['title']) == false || isset($sub_page['location']) == false) continue;
			/* subpage slug defaults */
			$sub_page['slug'] = isset($sub_page['slug']) ? str_replace(" ", "-", $sub_page['slug']) : str_replace(" ", "_", $sub_page['title']);
			$sub_page['config'] = isset($sub_page['config']) ? $sub_page['config'] : parent::$config;
			/* add subpage */
			add_submenu_page( 
				parent::$adminpage['slug'],
				$sub_page['title'],
				isset($sub_page['label']) ? $sub_page['label'] : $sub_page['title'],
				'manage_options',
				$sub_page['slug'],
				function() use($sub_page) {
					Handler::check($sub_page['location'],$sub_page['config']);
				},
			);
		}
	}
}
