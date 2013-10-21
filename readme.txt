=== tags.mowster ===
Contributors: mowster
Tags: tag, tags, tagging, post, suggest, related, automatic, seo
Requires at least: 3.0
Tested up to: 3.6.1
Stable tag: 1.52
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Tags suggestions using YQL Yahoo Content Analysis API.

== Description ==

With this plugin, you will be able to use the YQL Yahoo Content Analysis API terms to get suggested tags for your posts. 

* Just click on `Fetch tags`. Title, content and optional excerpt of your post will be analyzed by Yahoo to find words that may be useful as tags. 

It currently works with `all languages supported by YQL Content Analysis API`. 

<a href="http://developer.yahoo.com/contentanalysis/">Yahoo Content Analysis API</a> platform

= New in version 1.52 =
* YQL Content Analysis API implemented replacing the deprecated Yahoo Term Extraction 
* Translation pt-PT and es-ES updated

= Translations =
* Portuguese pt-PT
* Spanish es-ES

= Support =
* If you have any suggestion or need assistance regarding this plugin, post your query in the support [Forum](http://wordpress.org/support/plugin/mowster-tags "Support")

<strong>Plugin Site</strong> <a href="http://wordpress.mowster.net">wordpress.mowster.net</a> | <strong>Credits</strong> <a href="http://jobs.mowster.net">jobs.mowster.net</a>


== Installation ==

1. Download the mowster-Tags.zip file to your computer.
2. Unzip the file.
3. Upload `mowster-tags` folder to the `/wp-content/plugins/` directory.
4. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Which languages are supported? =

All supported by <a href="http://developer.yahoo.com/contentanalysis/">YQL Yahoo Content Analysis API</a>

== Screenshots ==

1. Button to `Fetch tags`.

== Changelog ==  

= 1.52 =
* Plug-in description updated

= 1.51 =
* bug fixed: jQuery alert

= 1.5 =
* YQL Content Analysis API implemented to query tags, Yahoo Term Extraction deprecated. 
* Translation pt-PT and es-ES updated

= 1.40.4 =
* bug fixed: unserialize warning removed

= 1.40.3 =
* Optimized ajax call in administration dashboard

= 1.40.2 =
* Optimized routines for plug-in activation, deactivation and uninstall
* Options kept in serialized format
* Ajax and wp_enqueue_script improved
* New version upgrade check, admin_notices notifications
* Tested with Firefox, Chrome, Internet Explorer, Safari and Opera
* bug fixed: upgrade notification, translation

= 1.31 = 
* WordPress wp_enqueue_script optimized for scripts depending from jQuery

= 1.30 =
* Charset implementation for non english languages
* Faster tags fetch process through Yahoo API
* bug fixed: non lowercases tags added manually
* Spanish and Portuguese translations updated

= 1.22 =
* URL optimization
* Folders organization

= 1.21 =
* Security tweak

= 1.20 =
* Security improvement
* Spanish and Portuguese translations added

= 1.18 =
* small improvements to avoid conflicts with others plugins

= 1.17 =
* Security optimized

= 1.16 =
* bug fixed: callback function

= 1.15 =
* Hooks only loaded in new-page script

= 1.14 =
* Hooks improved, performance optimized

= 1.13 =
* bug fixed: jQuery Fetch button

= 1.12 =
* jQuery code optimized

= 1.11 =
* bug fixed: PHP versions [upto 5.3.0] that does not support anonymous functions

= 1.1 =
* Drop-down limit fixed
* jQuery code optimized

= 1.0 =
* Initial release
* Added localization (English, Portuguese)
* Tested with Internet Explorer, Firefox, Safari and Opera


== Upgrade Notice ==

= 1.52 =
YQL Yahoo Content Analysis API implemented to query tags, Yahoo Term Extraction deprecated.