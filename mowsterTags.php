<?php
/*
Plugin Name: mowsterTags
Plugin URI: http://development.mowster.net
Description: Wordpress plugin for tag suggestions using Yahoo! Term Extraction API
Author: PedroDM
Version: 1.0
License: GPL
Author URI: http://jobs.mowster.net
*/
?>
<?php
/*
Copyright (C) 2010 Mowster Group Ltd

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function mowsterTags_scripts () {
   $uri = $_SERVER['REQUEST_URI'];
   $pathinfo = pathinfo($uri);
   $ajax_path = get_option('siteurl') . '/wp-content/plugins/mowster-tags/mowsterTagsAjax.php';
   $logo_path = get_option('siteurl') . '/wp-content/plugins/mowster-tags/mowsterTags_logo.gif';
   
   
   $html_add_to = '.tagsdiv .ajaxtag';
   $newtags = '#new-tag-post_tag';
   $taginput = '#tax-input[post_tag]';
    
   
   $version = get_bloginfo('version');
   $count = get_option('mowsterTags_count'); 
   if (!$count) $count = 20;
   
   load_plugin_textdomain('mowsterTags', 'wp-content/plugins/mowster-tags');

   if (strpos($pathinfo["basename"], 'post.php') === 0 || strpos($pathinfo["basename"], 'post-new.php') === 0) {
      echo 
      '<script type="text/javascript">      
         /*<![CDATA[*/
         var mowsterTags_fetchTags    = "' . __("Fetch tags", "mowsterTags") . '";
         var mowsterTags_fetchingTags = "' . __("Fetching tags...", "mowsterTags") . '";
         var mowsterTags_timeout      = "' . __("mowsterTags: Yahoo server seems to be down at the moment. Please try again later.", "mowsterTags") . '";
         var mowsterTags_error        = "' . __("mowsterTags: An error occurred. Please inform the creator of this plugin.", "mowsterTags") . '";
         
         addLoadEvent(function() {
            jQuery("' . $html_add_to . '").prepend("<div style=\"margin-bottom: 5px;\"><select name=\"mowsterTags_count\" id=\"mowsterTags_count\"><option value=\"5\">5</option><option value=\"10\">10</option><option value=\"15\">15</option><option value=\"20\">20</option><option value=\"30\">30</option><option value=\"40\">40</option><option value=\"50\">50</option><option value=\"60\">60</option><option value=\"70\">70</option><option value=\"80\">80</option><option value=\"90\">90</option><option value=\"100\">100</option></select> <input type=\"button\" class=\"button\" value=\"" + mowsterTags_fetchTags + "\" id=\"mowsterTags\" style=\"vertical-align: middle;\" /> " +
            " <a id=\"jobs\" href=\"http://jobs.mowster.net/\" target=\"_blank\"><img src=\"' . $logo_path . '\" alt=\"jobs.mowster.net\" title=\"jobs.mowster.net\" style=\"vertical-align: middle; margin-left: 3px;\" /></a></div>");

            jQuery("#mowsterTags_count").val("' . $count . '");
            jQuery("#mowsterTags").click(function(e) {

               var content = (typeof tinyMCE == "undefined" || 
                              typeof tinyMCE.getInstanceById("content") == "undefined" ||
                              tinyMCE.getInstanceById("content").isHidden()) ?
                              "<div>" + jQuery("#content").val() + "</div>" : 
                              tinyMCE.getInstanceById("content").getContent();


               var text = jQuery("#title").val() + " " + 
                          ((content.search(/\\S/) != -1) ? jQuery(content).text() : "") + " " +
                          jQuery("#excerpt").val();
                          
               var tags = jQuery("'.$newtags.'").val();

               if (text.search(/\\S/) != -1) {

                  jQuery(this).val(mowsterTags_fetchingTags);
                  var count = jQuery("#mowsterTags_count").val();
                  
                  jQuery.ajax({
                     type: "POST",
                     dataType: "text",
                     async: true,
                     timeout: 15000,
         			   data: { text: text, count: count, tags: tags },
         			   dataFilter: function(data, type) {
         			      return data.replace(/<\/?[^>]+>/gi, "");
         			   },         			   
         			   url: "' . $ajax_path . '",
         			   success: function(data, textStatus) {
                                                
                        jQuery("' . $newtags . '").focus();
                 				jQuery("' . $newtags . '").val(data);
                        
         			   },
         			   error: function (request, textStatus, errorThrown) {
                        if ("timeout" == textStatus) {
                           alert("' . __("mowsterTags: Yahoo server seems to be down at the moment. Please try again later.", "mowsterTags") . '");
                        } else {
                           alert("' . __("mowsterTags: An error occurred. Please inform the creator of this plugin.", "mowsterTags") . '");
                        }
                     },
                     complete: function(request, textStatus) {
                        jQuery("#mowsterTags").val(mowsterTags_fetchTags); 
                     }
                  });
               }
            });
         });

         /*]]>*/
      </script>';
   }
   
}
add_action('admin_print_scripts', 'mowsterTags_scripts');

function change_header() {
		echo '<style type="text/css">
		#new-tag-post_tag { width:100%; }
  	</style>';
}
add_action('admin_head', 'change_header', 11);
?>
