jQuery(document).ready(function ($) {

	var myArray = ["5", "10", "15", "20", "30", "40", "50", "60", "70", "80", "90", "100"];
	var mwtags_select_options = '';

	for (i = 0; i < myArray.length; i++) {
		if (myArray[i] == mowsterVars.mwtags_countTags) {
			var mwtags_select_options = mwtags_select_options + '<option value="' + myArray[i] + '" selected>' + myArray[i] + '</option>';
		} else
			mwtags_select_options = mwtags_select_options + '<option value="' + myArray[i] + '">' + myArray[i] + '</option>';
	}

	jQuery(mowsterVars.mwtags_html_add_to).prepend("<div style=\"margin-bottom: 5px;\"><input type=\"button\" class=\"button\" value=\"" + mowsterVars.mwtags_fetchTags + "\" id=\"mwtags\" style=\"vertical-align: middle;\" /> <select name=\"mwtags_count\" id=\"mwtags_count\">" + mwtags_select_options + "</select>  " +
		" <a id=\"mowster_jobs_link\" href=\"http://jobs.mowster.net/\" target=\"_blank\"><img src=\"" + mowsterVars.mwtags_logo_path + "\" alt=\"jobs.mowster.net\" id=\"mowster_jobs_link_image\" title=\"jobs.mowster.net\" style=\"vertical-align: middle; margin-left: 3px;\" /></a>" +
		"<img id=\"mowster_tags_ajax\" src=\"" + mowsterVars.mwtags_loader_path + "\" style=\"vertical-align: middle; margin-left: 3px;\" /></div></div>");

	jQuery("#mowster_tags_ajax").hide();

	jQuery("#mwtags").click(function (e) {

		var content = (typeof tinyMCE == "undefined" ||
			typeof tinyMCE.getInstanceById("content") == "undefined" ||
			tinyMCE.getInstanceById("content").isHidden()) ?
		"<div>" + jQuery("#content").val() + "</div>" :
		tinyMCE.getInstanceById("content").getContent();

		var text = jQuery("#title").val() + " " +
			(typeof content === 'string' ? jQuery(content).text() : "") + " " +
			jQuery("#excerpt").val();

		var tags = jQuery(mowsterVars.mwtags_newtags).val();

		if (content.length < 20) {
			alert(mowsterVars.mwtags_insuficient_text);
			jQuery("#mwtags").val(mowsterVars.mwtags_fetchTags);
			return;
		}

		if (typeof text === 'string') {

			jQuery(this).val(mowsterVars.mwtags_fetchingTags);
			jQuery("#mowster_jobs_link").hide();
			jQuery("#mowster_tags_ajax").show();
			var count = jQuery("#mwtags_count").val();

			jQuery.ajax({
				type : "POST",
				dataType : "text",
				async : true,
				timeout : 15000,
				data : {
					action : 'join_post_mwtags',
					text : text,
					count : count,
					tags : tags
				},
				dataFilter : function (data, type) {
					return data.replace(/<\/?[^>]+>/gi, "");
				},
				url : mowsterVars.mwtags_ajax_path,
				success : function (data, textStatus) {
					jQuery(mowsterVars.mwtags_newtags).focus();
					jQuery(mowsterVars.mwtags_newtags).val(data);
				},
				error : function (request, textStatus) {
					if ("timeout" == textStatus) {
						alert(mowsterVars.mwtags_server_error);
					} else {
						alert(mowsterVars.mwtags_misc_error);
					}
				},
				complete : function (request, textStatus) {
					jQuery("#mwtags").val(mowsterVars.mwtags_fetchTags);
					jQuery("#mowster_jobs_link").show();
					jQuery("#mowster_tags_ajax").hide();
				}
			});
		}
	});
});
