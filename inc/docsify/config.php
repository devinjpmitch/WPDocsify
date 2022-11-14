<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Config extends WPDocsify {
    public function __construct($config = array()) {
		if(empty($config)) return;
		/* unset el */
		if(isset($config['el'])) unset($config['el']);
		/* basePath */
		if(isset($config['basePath'])) unset($config['basePath']);
		/* set config */
		parent::$config = $config;
    }
}
