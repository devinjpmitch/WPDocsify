<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
require 'docsify.php';
use MBC\inc;

add_action('admin_menu', function() {
    inc\WPDocsify::adminPage();
});

