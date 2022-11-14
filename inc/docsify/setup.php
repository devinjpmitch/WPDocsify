<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Setup extends WPDocsify {
    public static function init(){
		/* setup site url and extension */
		$ssl = is_ssl();
		$ssl = $ssl ? 'https://' : 'http://';
		parent::$site_url = preg_replace("#^https?://#", "", get_site_url());
		if(strpos(parent::$site_url, "/") !== false){
			$site_url_arr = explode("/", parent::$site_url);
			parent::$site_ext = $site_url_arr[count($site_url_arr)-1];
			parent::$site_url = $ssl.$site_url_arr[0];
		} else parent::$site_url = $ssl.parent::$site_url;
		/* Set Default base directory */
		$location = '';
		if(empty(parent::$baseDir)) {
			if(is_dir(get_stylesheet_directory() . '/documentation/')) $location = get_stylesheet_directory_uri() . '/documentation/';
			else {
				$location = plugins_url('inc/assets/docs/', plugin_dir_path(__DIR__));
				parent::$baseDir = plugins_url('inc/assets/docs/', plugin_dir_path(__DIR__));
			}
		} else $location = parent::$baseDir;
		/* if admin page is empty create default */
		if(empty(parent::$adminpage)) parent::$adminpage = array(
			"domain"=>"WPDocsify",
			"name"=>"WPDocsify",
			"slug"=>"wpdocsify",
			"location" => $location,
			"config" => parent::$config,
			"icon"=>plugin_dir_path(__DIR__).'assets/img/icon.svg',
			"position"=> 99
		);
		/* setup prism */
		if(empty(parent::$prism)) parent::$prism = array(
			"version"=> parent::$prismlts,
			"languages"=> array(
				"php"
			)
		);


		/* foreach git-repos post */
		$repos = get_posts(array(
			'post_type' => 'git-repos',
			'posts_per_page' => -1
		));
		$gitPages = array();
		foreach($repos as $repo){
			$meta = get_post_meta($repo->ID);
			$repo_output = array(
				"title" => $repo->post_title,
				"menu" => $repo->post_title,
				"callback_params" => array(),
				"slug" => sanitize_title($repo->post_title)
			);
			foreach($meta as $meta_key => $meta_single){
				if(strpos($meta_key, 'git-repo-') !== false){
					if(strpos($meta_key, 'prism') !== false) $meta_single_output = explode(',',$meta_single[0]);
					else $meta_single_output = $meta_single[0];
					$meta_key_output = str_replace('git-repo-', '', $meta_key);
					$repo_output['callback_params'][$meta_key_output] = $meta_single_output;
				}
			}
			$gitPages[] = $repo_output;
		}
		parent::$gitPages = $gitPages;
	}
}
