jQuery(document).ready(function($) {  
        
		var mowsterTags_countTags    = mowsterVars.mowsterTags_countTags;
        var mowsterTags_fetchTags    = mowsterVars.mowsterTags_fetchTags;
        var mowsterTags_fetchingTags = mowsterVars.mowsterTags_fetchingTags;
        var mowsterTags_timeout      = mowsterVars.mowsterTags_timeout;
        var mowsterTags_error        = mowsterVars.mowsterTags_error;
		var mowsterTags_logo_path    = mowsterVars.mowsterTags_logo_path;
		
		var myArray=["5", "10", "15", "20", "30", "40", "50", "60", "70", "80", "90", "100"];
		var mowsterTags_select_options = '';
		
		for(i=0; i < myArray.length; i++){
			if (myArray[i] == mowsterTags_countTags) {
				var mowsterTags_select_options = mowsterTags_select_options + '<option value="' + myArray[i] + '" selected>' + myArray[i] + '</option>';
			} else mowsterTags_select_options = mowsterTags_select_options + '<option value="' + myArray[i] + '">' + myArray[i] + '</option>'; 
		}
         
        jQuery(".tagsdiv .ajaxtag").prepend("<div style=\"margin-bottom: 5px;\"><select name=\"mowsterTags_count\" id=\"mowsterTags_count\">" + mowsterTags_select_options + "</select> <input type=\"button\" class=\"button\" value=\"" + mowsterTags_fetchTags + "\" id=\"mowsterTags\" style=\"vertical-align: middle;\" /> " +
        " <a id=\"jobs\" href=\"http://jobs.mowster.net/\" target=\"_blank\"><img src=\"" + mowsterTags_logo_path + "\" alt=\"jobs.mowster.net\" title=\"jobs.mowster.net\" style=\"vertical-align: middle; margin-left: 3px;\" /></a></div>");

		jQuery("#mowsterTags").click(function(e) {
		
			var content = (typeof tinyMCE == "undefined" || 
                              typeof tinyMCE.getInstanceById("content") == "undefined" ||
                              tinyMCE.getInstanceById("content").isHidden()) ?
                              "<div>" + jQuery("#content").val() + "</div>" : 
                              tinyMCE.getInstanceById("content").getContent();
							  
			var text = jQuery("#title").val() + " " + 
                          (typeof content === 'string' ? jQuery(content).text() : "") + " " +
                          jQuery("#excerpt").val();
			
			var tags = jQuery("#new-tag-post_tag").val();
		
			
			
			if (typeof text === 'string') {
			
				if (text.length < 20) {
					alert(mowsterVars.mowsterTags_insuficient_text);
					jQuery("#mowsterTags").val(mowsterTags_fetchTags);
					return true;
				}
			
				jQuery(this).val(mowsterTags_fetchingTags);
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
						jQuery("#new-tag-post_tag").focus();
						jQuery("#new-tag-post_tag").val(data);
					},
					error: function (request, textStatus) {
						if ("timeout" == textStatus) {
						   alert(mowsterVars.mowsterTags_server_error);
						} else {
						   alert(mowsterVars.mowsterTags_misc_error);
						}
					},
					complete: function(request, textStatus) {
					jQuery("#mowsterTags").val(mowsterTags_fetchTags); 
					}
				});	
			}
		});
});
