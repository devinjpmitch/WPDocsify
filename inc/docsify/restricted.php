<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Restricted extends WPDocsify {
    public static function check($page = array()){
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
}
