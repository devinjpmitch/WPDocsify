<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Wordpress extends WPDocsify {
    public static function homepath(){
        /* get home path */
        $home    = set_url_scheme( get_option( 'home' ), 'http' );
        $siteurl = set_url_scheme( get_option( 'siteurl' ), 'http' );
        if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
            $wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
            $pos                 = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
            $home_path           = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
            $home_path           = trailingslashit( $home_path );
        } else {
            $home_path = ABSPATH;
        }
        return str_replace( '\\', '/', $home_path );
    }
}
