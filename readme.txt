=== Yet another Twitter plugin ===
Contributors: code418
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4P7WVLQJBX3BG
Tags: twitter, twitter list
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 0.3

Simple plugin for displaying public twitter messages of a single user or multiple users on a public twitter list.

== Description ==

This is a simple plugin for displaying an easily customisable twitter feed on your Wordpress installation. Adapted from the excellent Twitter for Wordpress plugin [(http://rick.jinlabs.com/code/twitter)](http://rick.jinlabs.com/code/twitter) the main feature addition at the moment is support for a twitter lists to display feeds containing multiple users.

== Installation ==

1. Upload `yet-another-twitter-plugin` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Usage ==

Place the following code where desired in your Wordpress Theme

**Single Twitter User**

`<?php yatp_user_feed(username, count); ?>`

Where 'username' is the twitter username of the twitter user you wish to display, and 'count' is the number of recent tweets to display.

**Twitter List**

`<?php yatp_list_feed(username, listid, count); ?>`

Where 'username' is the twitter username of the owner of the list, 'listid' is the name of the list inself, and 'count' is the number of recent tweets to display.

The HTML markup surrounding each tweet can be customised via Settings->YaTp

== Changelog ==

= 0.3 =
* Added option to adjust cache duration
* Bug fix with options display

= 0.2 =
* Added option to display twitter user avatars

= 0.1 =
* Initial Release


== Upgrade Notice ==

= 0.3 =
Bug fix for custom before/after html settting for Twitter Lists

= 0.2 =
Twitter avatar display option

= 0.1 =
Initial Release