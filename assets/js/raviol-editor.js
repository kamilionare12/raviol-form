jQuery(function ($) {
	$( document ).ready(function() {
		var editorConfig = wp.editor.getDefaultSettings;
		//editorConfig.mediaButtons = true;
		//editorConfig.quicktags = true;
		editorConfig.tinymce = {
			theme:
				"modern",
			skin:
				"lightgray",
			language:
				"en",
			relative_urls: false,
			remove_script_host:
				false,
			convert_urls:
				false,
			browser_spellcheck:
				true,
			fix_list_elements:
				true,
			entities:
				"38,amp,60,lt,62,gt",
			entity_encoding:
				"raw",
			keep_styles:
				false,
			resize:
				"vertical",
			menubar:
				false,
			branding:
				false,
			preview_styles:
				"font-family font-size font-weight font-style text-decoration text-transform",
			end_container_on_empty_block:
				true,
			//wpeditimage_html5_captions:
				//true,
			wp_lang_attr:
				"en-US",
			wp_keep_scroll_position:
				false,
			wp_shortcut_labels:
				{
					"Heading 1":
						//"access1", "Heading 2":
						//"access2", "Heading 3":
						//"access3", "Heading 4":
						//"access4", "Heading 5":
						//"access5", "Heading 6":
						"access6", "Paragraph":
						//"access7", "Blockquote":
						"accessQ", "Underline":
						//"metaU", "Strikethrough":
						"accessD", "Bold":
						"metaB", "Italic":
						//"metaI", "Code":
						"accessX", "Align center":
						"accessC", "Align right":
						"accessR", "Align left":
						"accessL", "Justify":
						"accessJ", "Cut":
						"metaX", "Copy":
						"metaC", "Paste":
						"metaV", "Select all":
						"metaA", "Undo":
						"metaZ", "Redo":
						"metaY", "Bullet list":
						"accessU", "Numbered list":
						//"accessO", "Insert\/edit image":
						"accessM", "Remove link":
						"accessS", "Toolbar Toggle":
						"accessZ", "Insert Read More tag":
						"accessT", "Insert Page Break tag":
						"accessP", "Distraction-free writing mode":
						"accessW", "Add Media":
						"accessM", "Keyboard Shortcuts":
						"accessH"
				}
			,
			toolbar1:
				"formatselect,bold,italic,bullist,numlist",
			wpautop:
				true,
			placeholder: "Message",
			indent:
				true,
			elementpath:
				false,
			plugins:
				"charmap,colorpicker,hr,lists,paste,tabfocus,textcolor,wordpress,wpautoresize,wplink,wptextpattern",
			init_instance_callback: function (editor) {
				editor.on('dirty', function () {
					editor.save();
				});
			}
		};

		var textArea = $('.editor-init');

		textArea.each(function (index) {
			var textAreaId = $(this).attr('id');

			if (tinymce.get(textAreaId)) {
				wp.editor.remove(textAreaId);
			}

			wp.editor.initialize(textAreaId, editorConfig);

			var editor = tinymce.get(textAreaId);

			editor.on('blur', function () {
				$('#' + textAreaId).trigger('change');
			});
		});

	});
	
	// Add button "Update form"
	$('.raviol-info-sender').each(function() {
		var label = $(this).attr('data-update');
		var url = window.location.href.split("?")[0];
		$(this).after("<p><a href="+ url +">" + label + "</a></p>"); 
	});
	
	// validate form
});