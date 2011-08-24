jQuery(document).ready(function($) { 

		
		var myArray=["5", "10", "15", "20", "30", "40", "50", "60", "70", "80", "90", "100"];
		var mowsterTags_select_options = '';
		
		for(i=0; i < myArray.length; i++){
			if (myArray[i] == mowsterVars.mowsterTags_countTags) {
				var mowsterTags_select_options = mowsterTags_select_options + '<option value="' + myArray[i] + '" selected>' + myArray[i] + '</option>';
			} else mowsterTags_select_options = mowsterTags_select_options + '<option value="' + myArray[i] + '">' + myArray[i] + '</option>'; 
		}
         
        jQuery(mowsterVars.mowsterTags_html_add_to).prepend("<div style=\"margin-bottom: 5px;\"><select name=\"mowsterTags_count\" id=\"mowsterTags_count\">" + mowsterTags_select_options + "</select> <input type=\"button\" class=\"button\" value=\"" + mowsterVars.mowsterTags_fetchTags + "\" id=\"mowsterTags\" style=\"vertical-align: middle;\" /> " +
        " <a id=\"mowster_jobs_link\" href=\"http://jobs.mowster.net/\" target=\"_blank\"><img src=\"" + mowsterVars.mowsterTags_logo_path + "\" alt=\"jobs.mowster.net\" id=\"mowster_jobs_link_image\" title=\"jobs.mowster.net\" style=\"vertical-align: middle; margin-left: 3px;\" /></a>" +
		"<img id=\"mowster_tags_ajax\" src=\"" + mowsterVars.mowsterTags_loader_path + "\" style=\"vertical-align: middle; margin-left: 3px;\" /></div></div>");

		jQuery("#mowster_tags_ajax").hide();
		
		
		jQuery("#mowsterTags").click(function(e) {
		
			var content = (typeof tinyMCE == "undefined" || 
                              typeof tinyMCE.getInstanceById("content") == "undefined" ||
                              tinyMCE.getInstanceById("content").isHidden()) ?
                              "<div>" + jQuery("#content").val() + "</div>" : 
                              tinyMCE.getInstanceById("content").getContent();
							  
			var text = jQuery("#title").val() + " " + 
                          (typeof content === 'string' ? jQuery(content).text() : "") + " " +
                          jQuery("#excerpt").val();
			
			var tags = jQuery(mowsterVars.mowsterTags_newtags).val();
		
			
			
			if (typeof text === 'string') {
			
				if (text.length < 20) {
					alert(mowsterVars.mowsterTags_insuficient_text);
					jQuery("#mowsterTags").val(mowsterVars.mowsterTags_fetchTags);
					return true;
				}
			
				jQuery(this).val(mowsterVars.mowsterTags_fetchingTags);
				jQuery("#mowster_jobs_link").hide();
				jQuery("#mowster_tags_ajax").show();
				var count = jQuery("#mowsterTags_count").val();
				
				jQuery.ajax({
					type: "POST",
					dataType: "text",
					async: true,
					timeout: 15000,
					data: { 
						action: 'join_post_tags', text: text, count: count, tags: tags 
					},
					dataFilter: function(data, type) {
						return data.replace(/<\/?[^>]+>/gi, "");
					},         			   
					url: mowsterVars.mowsterTags_ajax_path,
					success: function(data, textStatus) {
						jQuery(mowsterVars.mowsterTags_newtags).focus();
						jQuery(mowsterVars.mowsterTags_newtags).val(data);
					},
					error: function (request, textStatus) {
						if ("timeout" == textStatus) {
						   alert(mowsterVars.mowsterTags_server_error);
						} else {
						   alert(mowsterVars.mowsterTags_misc_error);
						}
					},
					complete: function(request, textStatus) {
						jQuery("#mowsterTags").val(mowsterVars.mowsterTags_fetchTags); 
						jQuery("#mowster_jobs_link").show();
						jQuery("#mowster_tags_ajax").hide();
					}
				});	
			}
		});
});
