jQuery(document).ready(function ($) {

	var myArray = ["5", "10", "15", "20", "30", "40", "50", "60", "70", "80", "90", "100"];
	var mwtags_select_options = '';
	
	for (i = 0; i < myArray.length; i++) {
		if (myArray[i] == mowsterVars.mwtags_countTags) {
			mwtags_select_options += '<option value="' + myArray[i] + '" selected>' + myArray[i] + '</option>';
		} else
			mwtags_select_options += '<option value="' + myArray[i] + '">' + myArray[i] + '</option>';
	}

	jQuery(mowsterVars.mwtags_html_add_to).prepend("<div style=\"margin-bottom: 5px;\"><input type=\"button\" class=\"button\" value=\"" + mowsterVars.mwtags_fetchTags + "\" id=\"mwtags\" style=\"vertical-align: middle;\" /> <select name=\"mwtags_count\" id=\"mwtags_count\">" + mwtags_select_options + "</select>  " +
		" <a id=\"mowster_jobs_link\" href=\"http://jobs.mowster.net/\" target=\"_blank\">" + 
		
		"<img src=\"data:image/gif;base64,R0lGODlhcQAOANUAAAAAAKWlpf8AAFxcXP9fXzMzM+fn5/+vr/8zM4KCgiAgINPT0/+Pj3p6esXFxUpKSg8PD/8PD7y8vP///0JCQv/MzP/f3/9/f/8/P5mZmWpqavf39/9PT/+/v/+fn6+vr/8fH1JSUnNzc9/f3ycnJ8zMzP9vb5CQkLOzs2ZmZu/v7zw8PI+Pj////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHAC0ALAAAAABxAA4AAAb/wJZwSCwaj8ikcslsOpOTaDTwWEivk6d2y+0ysQ2IZCIRmR1Rr3rNXmIDoU1GkTqJCqhs63IZdkwYBB1DfHwMFUMWgBweQwmPjpBCIwkUIQFCEgmYLR8JJUKPIy1wFCKjmo+qLaqqI5StoENXGxoQDwVoUQUNegICQgS/wyZCw8N9FhHHCBYtANCjJdAAQgHU0AUtEgDaLQoAIi0q0C0i2AAsCejV7BLc6CyzUrUAClZ3JCkGvsAdAhEOtPCwDNGvDh0YCACxRwCHFhYIFHsGTR4LauMgpFMhoUA6iipGZOsEgAJFUB8KlBgh4WKBdxTfveNYUsg0CPOiqHgAwUoI/woSFqhI0+JXCxMC+gi5IKCY0RYVBCA4KtXDoCElIYRoEQIChWofambq1uKrhGvgKG2EJkLCqCHcTAqBJhNUXCHkqgmh1+DehBADhl4xBgyDgKst/mEoegwEogrLiM21hFPrV1YAEhApty7BgMwAAnwF9ZmaAll352IzyU3Bo69yW5BJEIKETwoOVg5m3IKDAIFCDghY/IsP0qktNjAgYFhAo5IX14mudlEc3nLcQigoQG4ABJyZKoFTMDY2NFWY4FGjoMIRBQ2nNjx4MGHdgA1SCLdQ+FCIYQa8+XfYBo0IgVQfJYkUzWXTKNAeZls9o5E4BWgUoQSy5FUeVnrBVbDTOuRhlUIUBlCwAn7nUMBPFCYgAFByy2BwgWERbMCYcb9s4CIIF5hQ0DMmjVeWXl+5VpoEQnwFAJLnfBQWAANUAsCFYlFEwZUUBHCXCuBwss18AxSQAooalCCBYBPQWGAFzUmFCGPDICAQm8NEUGBN54hz2Til2ePlOuDBI0sCGkHD3oaqYZNAaq09OMEIAWiJ3wa6YYGEBR0408QGHbz5BEeyaGHmg22UauqpqCIRBAA7\"" + 
		" style=\"vertical-align: middle; margin-left: 3px;\" alt=\"jobs.mowster.net\" id=\"mowster_jobs_link_image\" title=\"jobs.mowster.net\" style=\"vertical-align: middle; margin-left: 3px;\" /></a>" +
		
		"<img id=\"mowster_tags_ajax\" src=\"data:image/gif;base64,R0lGODlhEAALAPQAAP///wAAANra2tDQ0Orq6gYGBgAAAC4uLoKCgmBgYLq6uiIiIkpKSoqKimRkZL6+viYmJgQEBE5OTubm5tjY2PT09Dg4ONzc3PLy8ra2tqCgoMrKyu7u7gAAAAAAAAAAACH+GkNyZWF0ZWQgd2l0aCBhamF4bG9hZC5pbmZvACH5BAALAAAAIf8LTkVUU0NBUEUyLjADAQAAACwAAAAAEAALAAAFLSAgjmRpnqSgCuLKAq5AEIM4zDVw03ve27ifDgfkEYe04kDIDC5zrtYKRa2WQgAh+QQACwABACwAAAAAEAALAAAFJGBhGAVgnqhpHIeRvsDawqns0qeN5+y967tYLyicBYE7EYkYAgAh+QQACwACACwAAAAAEAALAAAFNiAgjothLOOIJAkiGgxjpGKiKMkbz7SN6zIawJcDwIK9W/HISxGBzdHTuBNOmcJVCyoUlk7CEAAh+QQACwADACwAAAAAEAALAAAFNSAgjqQIRRFUAo3jNGIkSdHqPI8Tz3V55zuaDacDyIQ+YrBH+hWPzJFzOQQaeavWi7oqnVIhACH5BAALAAQALAAAAAAQAAsAAAUyICCOZGme1rJY5kRRk7hI0mJSVUXJtF3iOl7tltsBZsNfUegjAY3I5sgFY55KqdX1GgIAIfkEAAsABQAsAAAAABAACwAABTcgII5kaZ4kcV2EqLJipmnZhWGXaOOitm2aXQ4g7P2Ct2ER4AMul00kj5g0Al8tADY2y6C+4FIIACH5BAALAAYALAAAAAAQAAsAAAUvICCOZGme5ERRk6iy7qpyHCVStA3gNa/7txxwlwv2isSacYUc+l4tADQGQ1mvpBAAIfkEAAsABwAsAAAAABAACwAABS8gII5kaZ7kRFGTqLLuqnIcJVK0DeA1r/u3HHCXC/aKxJpxhRz6Xi0ANAZDWa+kEAA7AAAAAAAAAAAA\"" +
		" style=\"vertical-align: middle; margin-left: 3px;\" /></div></div>");

	jQuery("#mowster_tags_ajax").hide();

	jQuery("#mwtags").click(function (e) {	
		
		var content = (
			typeof tinyMCE == "undefined" ||
			tinyMCE.activeEditor == null ||
			tinyMCE.activeEditor.isHidden() != false) ?
		"<div>" + jQuery("#content").val() + "</div>" :
		tinyMCE.get("content").getContent();
		
		var text = jQuery("#title").val() + " " +
			jQuery("#excerpt").val() + " " +
			(typeof content === 'string' ? jQuery(content).text() : "") + " ";
		
		var tags = jQuery(mowsterVars.mwtags_newtags).val();

		if (typeof text === 'string') {
		
			jQuery(this).val(mowsterVars.mwtags_fetchingTags);
			jQuery("#mowster_jobs_link").hide();
			jQuery("#mowster_tags_ajax").show();

			var count = jQuery("#mwtags_count").val();

			var data = {
				action : 'join_post_mwtags',
				text : text,
				count : count,
				tags : tags,
				timeout : 10
			};

			jQuery.post(mowsterVars.mwtags_ajax_path, data,
				function (response) {

				var obj = jQuery.parseJSON(response);

				if (obj.status == 'error') {
					alert(obj.message);
					console.log(obj.message);

					jQuery("#mwtags").val(mowsterVars.mwtags_fetchTags);
					jQuery("#mowster_jobs_link").show();
					jQuery("#mowster_tags_ajax").hide();
					return false;
				}
				
				if (obj.status == 'warning') {
					alert(obj.message);
					console.log(obj.message);
				}

				jQuery(mowsterVars.mwtags_newtags).focus();
				jQuery(mowsterVars.mwtags_newtags).val(obj.tags);

				jQuery("#mwtags").val(mowsterVars.mwtags_fetchTags);
				jQuery("#mowster_jobs_link").show();
				jQuery("#mowster_tags_ajax").hide();

				return false;
			});

		}
	});
});
