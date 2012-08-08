=== mowsterTags ===
Contributors: mowster
Tags: tag, tags, tagging, post, suggest, related, automatic, seo
Requires at least: 3.0
Tested up to: 3.4
Stable tag: 1.31
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Wordpress plugin for tag suggestions using Yahoo API.

== Description ==

With this plugin, you will be able to use the Yahoo API terms to get suggested tags for your posts. 

* Just click on `Fetch tags`. Title, content and optional excerpt of your post will be analyzed by Yahoo in order to find words that could be useful as tags. 

It currently works with `all languages supported by Yahoo! Term Extraction`. 


<strong>Plugin Site</strong> <a href="http://wordpress.mowster.net">wordpress.mowster.net</a> | <strong>Credits</strong> <a href="http://jobs.mowster.net">jobs.mowster.net</a>

If you have any suggestion or need assistance regarding this plugin, post your query in the support [forum](http://wordpress.org/support/plugin/mowster-tags "Support")


== Installation ==

1. Download the mowster-Tags.zip file to your computer.
2. Unzip the file.
3. Upload `mowster-tags` folder to the `/wp-content/plugins/` directory.
4. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Which languages are supported? =

All supported by Yahoo! Term Extraction

== Screenshots ==

1. Button to `Fetch tags`.

== Changelog ==  

= 1.31 = 
* WordPress wp_enqueue_script optimized for scripts depending from jQuery

= 1.30 =
* Charset implementation for non english languages
* Faster tags fetch process through Yahoo API
* bug fixed with non lowercases tags added manually
* Spanish and Portuguese translations updated

= 1.22 =
* URL optimization
* Folders organization

= 1.21 =
* Security improvement

= 1.20 =
* Security improvement
* Spanish and Portuguese translations added

= 1.18 =
* minor improvals to avoid conflicts with others plugins

= 1.17 =
* Security fix

= 1.16 =
* bug fixed in callback function

= 1.15 =
* minor improval, hooks only loaded in new-page script

= 1.14 =
* Hooks improved, performance optimized

= 1.13 =
* bug fixed jQuery Fetch button

= 1.12 =
* jQuery code optimized

= 1.11 =
* bug fixed for PHP versions (< 5.3.0) that does not support anonymous functions

= 1.1 =
* Drop-down limit fixed
* jQuery code optimized

= 1.0 =
* Initial release
* Added localization (English, Portuguese)
* Tested with Internet Explorer, Firefox, Safari and Opera


== Upgrade Notice ==

= 1.30 =
Stable version for WordPress 3.4