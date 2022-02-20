<?php

/**
 *
 * Dump and Die variable or whatever
 *
 * @param $dump
 *
 */
function logtivity_dd($dump) {
	echo "<pre>";
	var_export($dump);
	echo "</pre>";
	die();
}

/**
 * Load a view and pass variables into it
 *
 * To ouput a view you would want to echo it
 * 
 * @param  string $fileName excluding file extension
 * @param  array  $vars
 * @return string
 */
function logtivity_view($fileName, $vars = array()) {

    foreach ($vars as $key => $value) {
        
        ${$key} = $value;

    }

    ob_start();

    include( dirname(__FILE__) . '/../views/' . str_replace('.', '/', $fileName) . '.php');

    return ob_get_clean();
}

/**
 * Get Site API Key
 * 
 * @return string
 */
function logtivity_get_api_key() {
    return sanitize_text_field(
        (new Logtivity_Options)->getOption('logtivity_site_api_key')
    );
}

function logtivity_get_the_title($post_id) {
    $wptexturize = remove_filter( 'the_title', 'wptexturize' );
    
    $title = get_the_title($post_id);

    if ( $wptexturize ) {
        add_filter( 'the_title', 'wptexturize' );
    }

    return $title;
}

function logtivity_get_api_url()
{
    if (defined('LOGTIVITY_API_URL')) {
        return LOGTIVITY_API_URL;
    }

    return 'https://api.logtivity.io';
}

function logtivity_has_site_url_changed()
{
    $hash = (new Logtivity_Options)->urlHash();

    if (!$hash) {
        return false;
    }

    return $hash !== md5(home_url());
}