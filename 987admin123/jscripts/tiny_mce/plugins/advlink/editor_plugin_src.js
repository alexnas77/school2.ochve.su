/**
 * $Id: editor_plugin_src.js 268 2007-04-28 15:52:59Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('advlink');

var TinyMCE_AdvancedLinkPlugin = {
	getInfo : function() {
		return {
			longname : 'Advanced link',
			author : 'Moxiecode Systems AB',
			authorurl : 'http://tinymce.moxiecode.com',
			infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/advlink',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	initInstance : function(inst) {
		inst.addShortcut('ctrl', 'k', 'lang_advlink_desc', 'mceAdvLink');
	},

	getControlHTML : function(cn) {
		switch (cn) {
			case "link":
				return tinyMCE.getButtonHTML(cn, 'lang_link_desc', '{$themeurl}/images/link.gif', 'mceAdvLink');
		}

		return "";
	},

	execCommand : function(editor_id, element, command, user_interface, value) {
		switch (command) {
			case "mceAdvLink":
				var inst = tinyMCE.getInstanceById(editor_id), anySelection = false;
				var focusElm = inst.getFocusElement(), selectedText = inst.selection.getSelectedText();

				if (tinyMCE.selectedElement)
					anySelection = (tinyMCE.selectedElement.nodeName.toLowerCase() == "img") || (selectedText && selectedText.length > 0);

				if (anySelection || (focusElm != null && focusElm.nodeName == "A")) {
					tinyMCE.openWindow({
						file : '../../plugins/advlink/link.htm',
						width : 480 + tinyMCE.getLang('lang_advlink_delta_width', 0),
						height : 400 + tinyMCE.getLang('lang_advlink_delta_height', 0)
					}, {
						editor_id : editor_id,
						inline : "yes"
					});
				}

				return true;
		}

		return false;
	},

	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
		if (node == null)
			return;

		do {
			if (node.nodeName == "A" && tinyMCE.getAttrib(node, 'href') != "") {
				tinyMCE.switchClass(editor_id + '_advlink', 'mceButtonSelected');
				return true;
			}
		} while ((node = node.parentNode));

		if (any_selection) {
			tinyMCE.switchClass(editor_id + '_advlink', 'mceButtonNormal');
			return true;
		}

		tinyMCE.switchClass(editor_id + '_advlink', 'mceButtonDisabled');

		return true;
	}
};

tinyMCE.addPlugin("advlink", TinyMCE_AdvancedLinkPlugin);


