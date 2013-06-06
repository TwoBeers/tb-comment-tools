var tbCommentTools = {

	init : function( in_modules ) {

		var modules = in_modules.split(',');

		for (i in modules) {

			switch(modules[i]) {

				case 'quote_this':
					tbCommentTools.init_quote_this();
					break;

				case 'tag_this':
					tbCommentTools.init_tag_this();
					break;

				default :
					//no default action
					break;

			}

		}

	},


	init_quote_this : function() {
		if ( document.getElementById('reply-title') && document.getElementById("comment") ) {
			var reply_title = document.getElementById('reply-title');
			var quote_div = document.createElement('small');
			quote_div.innerHTML = ' - <a id="quotethis" href="#" onclick="tbCommentTools.quote_this(); return false" title="' + tbCommentTools_l10n.quote_tip + '" >' + tbCommentTools_l10n.quote + '</a>';
			reply_title.appendChild(quote_div);
		}
	},


	init_tag_this : function() {
		var tag_div = document.getElementById("tb-comment-tools-tags");
		if (tag_div) { tag_div.style.display = 'block'; }
	},


	tag_this : function( tag_in, tag_out ='culo' ) {

		var textarea = document.getElementById("comment");

		if (document.selection) {
			// code for IE
			textarea.focus();
			var sel = document.selection.createRange();

			// Finally replace the value of the selected text with this new replacement one
			sel.text = tag_in + sel.text + tag_out;
		} else {
			// code for Mozilla
			var len = textarea.value.length;
			var start = textarea.selectionStart;
			var end = textarea.selectionEnd;
			var sel = textarea.value.substring(start, end);

			var replace = tag_in + sel + tag_out;

			// Here we are replacing the selected text with this one
			textarea.value =  textarea.value.substring(0,start) + replace + textarea.value.substring(end,len);
		}
	},


	quote_this : function() {
		var posttext = '';
		if (window.getSelection){
			posttext = window.getSelection();
		}
		else if (document.getSelection){
			posttext = document.getSelection();
		}
		else if (document.selection){
			posttext = document.selection.createRange().text;
		}
		else {
			return true;
		}
		posttext = posttext.toString().replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
		if ( posttext.length !== 0 ) {
			document.getElementById("comment").value = document.getElementById("comment").value + '<blockquote>' + posttext + '</blockquote>';
		} else {
			alert( tbCommentTools_l10n.quote_alert );
		}
	}

};

tbCommentTools.init( 'quote_this,tag_this' );