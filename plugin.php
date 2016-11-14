<?php

/**
 * Plugin Name: AltMedia
 * Description: MultiSite configs and extensions
 * Author: AltDriver
 * Author URI: http://www.altdriver.com
 * Version: 1
 * Plugin URI:
 * License: GPL2+
 */

include_once(dirname( __FILE__ ) . '/altmedia.php');

$altmedia_config = new AltMedia_Config();

function altmedia_admin_update_tags(){
    $newtags = $_POST['newtags'];
    $postname = $_POST['postname'];
    $postID = $_POST['postID'];
    $append = true;

    $add_tags = wp_set_post_tags($postID, $newtags, $append);

    return $add_tags;
}
add_action('wp_ajax_altmedia_admin_update_tags', 'altmedia_admin_update_tags');


function altmedia_admin_update_post(){

    $field_keys = $_POST['field_keys'];
    $field_values = $_POST['field_values'];
    $newtags = $_POST['newtags'];
    $postID = $_POST['postID'];
    $append = true;

    $i = 0;
    foreach($field_keys as $field){
        $field_key = $field_keys[$i];
        $field_value = $field_values[$i];
        update_field($field_key, $field_value, $postID);
        $i++;
    }

    $add_tags = wp_set_post_tags($postID, $newtags, $append);

}
add_action('wp_ajax_altmedia_admin_update_post', 'altmedia_admin_update_post');

/*
TODO: remove old explicit from quick edit
*/