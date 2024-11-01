<?php

/*
Plugin Name: Yet another Twitter plugin
Version: 0.3
Plugin URI: http://wordpress.org/extend/plugins/yet-another-twitter-plugin/
Description: Displays your public Twitter messages for all to read. Based on <a href="http://cavemonkey50.com/code/pownce/">Pownce for Wordpress</a> by <a href="http://cavemonkey50.com/">Cavemonkey50</a>.
Author: Richard Brown
Author URI: http://code418.co.uk
*/

/*  Copyright 2010  Richard Brown (richard[at]code418.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//add options page
include WP_CONTENT_DIR . '/plugins/yet-another-twitter-plugin/options.php';

$cacheLength = get_option("yatp_cache");
if(!$cacheLength){
	$cacheLength = '600';
}

//change cache refresh to every 10 minutes
add_filter( 'wp_feed_cache_transient_lifetime', create_function('$a', 'return '.get_option("yatp_cache").';') );

// Display Twitter messages
function yatp_list_feed($username = '', $listid = '', $count = 5) {
	include_once(ABSPATH . WPINC . '/feed.php');
	$messages = fetch_feed('http://api.twitter.com/1/' . $username . '/lists/' . $listid . '/statuses.atom?per_page=' . $count);
	echo yatp_feed_output($messages,true,get_option("list_beforeTweet"),get_option("list_afterTweet"));
}

function yatp_user_feed($username = '', $count = 5) {
	include_once(ABSPATH . WPINC . '/feed.php');
	$messages = fetch_feed('http://api.twitter.com/1/statuses/user_timeline/' . $username . '.atom?count=' . $count);
	echo yatp_feed_output($messages,false,get_option("user_beforeTweet"),get_option("user_afterTweet"));
}


function yatp_feed_output($messages, $multiuser = true, $before, $after){
	if (!is_wp_error( $messages ) ) {
    		$maxitems = $messages->get_item_quantity(); 
    		$atom_items = $messages->get_items(0, $maxitems); 
	}

$outputString = '';
 if ($maxitems == 0) { 
		$outputString .= 'No tweets found.';
 } else {

	//print_r($atom_items);
    foreach ( $atom_items as $item ) {
	$avatar = $item->get_link (0,'image');
	$tweet = $item->get_title();
        //process tweet text
	$tweet = yatp_parse_hyperlinks($tweet);
	$tweet = yatp_parse_mentions($tweet);
	if (!$multiuser){
	$tweet = yatp_clear_user($tweet);
	} else {
	$tweet = yatp_link_user($tweet);
	}
	
	if(get_option('yatp_show_avatars')=='1'){
		$outputString .= '<img class="yatp_avatar left" src="' . $avatar .'" height="48" width="48" />';
	}
	
	$outputString .= $before;
	
	$outputString .= $tweet . '<br />';
	$outputString .= '<a class="yatp_tweet_time_link" href="' . $item->get_permalink() . '">';
	$outputString .= human_time_diff($item->get_date('U')) . ' ago';
	$outputString .= '</a>';
	$outputString .= $after;
	}

return $outputString;
}

}


function yatp_parse_hyperlinks($text) {
    // Props to Allen Shaw & webmancers.com
    // match protocol://address/path/file.extension?some=variable&another=asf%
    $text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"yatp_tweet_link\">$1</a>", $text);
    // match www.something.domain/path/file.extension?some=variable&another=asf%
    $text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"yatp_tweet_link\">$1</a>", $text);    
    // match name@address
    $text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"yatp_tweet_link\">$1</a>", $text);
    //mach #trendingtopics. Props to Michael Voigt
    $text = preg_replace('/([\.|\,|\:|\�|\�|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\" class=\"yatp_tweet_link\">#$2</a>$3 ", $text);
    return $text;
}

function yatp_parse_mentions($text) {
       $text = preg_replace('/([\.|\,|\:|\�|\�|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $text);
       return $text;
}     

function yatp_clear_user($text) {
	$text= preg_replace('/^.{1,15}: /m', '', $text, 1);
	return $text;
}

function yatp_link_user($text) {
	$text= preg_replace('/^(.{1,15}): /m', '<a href="http://twitter.com/$1">$1</a>: ', $text, 1);
	return $text;
}

?>
