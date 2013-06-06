<?php
/*
Plugin Name: TB Comment Tools
Plugin URI: https://github.com/TwoBeers/tb-comment-tools
Description: The plugin will add some usefull links to the comment form. It adds a "quote" link besides the #reply-title and converts the allowed tags into clickable links that wrap the selected text in the comment form.
Author: Jimo
Version: 1.1
*/

class TB_Comment_Tools {

	function __construct() {

		add_action( 'comment_form_before', array( $this, 'script' ) );

		add_filter( 'comment_form_defaults', array( $this, 'add_buttons' ) );

		add_action( 'plugins_loaded', array( $this, 'l10n' ) );

	}

	function l10n() {

		load_plugin_textdomain( 'tb_comment_tools', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	function script() {

		wp_enqueue_script( 'tb-comment-tools-script', plugins_url( 'script.js', __FILE__ ), false, '1.0', true );

		$data = array(
			'quote_tip'			=> esc_js( __( 'Add selected text as a quote', 'tb_comment_tools' ) ),
			'quote'				=> esc_js( __( 'Quote', 'tb_comment_tools' ) ),
			'quote_alert'		=> esc_js( __( 'Nothing to quote. First of all you should select some text...', 'tb_comment_tools' ) ),
		);

		wp_localize_script( 'tb-comment-tools-script', 'tbCommentTools_l10n', $data );

	}

	function add_buttons( $defaults ) {

		$defaults['comment_notes_after'] = $this->allowed_tags();

		return $defaults;

	}

	function allowed_tags() {
		global $allowedtags;

		$allowed = array();

		foreach ( (array) $allowedtags as $tag => $attributes ) {

			$allowed_tag = '<' . $tag;
			if ( 0 < count($attributes) ) {
				foreach ( $attributes as $attribute => $limits ) {
					$allowed_tag .= ' ' . $attribute . '=""';
				}
			}
			$allowed_tag .= '>';
			$allowed_tag = htmlentities( $allowed_tag );

			$allowed[] = '<a href="#" onclick="tbCommentTools.tag_this(\'' . $allowed_tag . '\',\'&lt;/' . $tag . '&gt;\'); return false" title="' . $allowed_tag . '">' . htmlentities( '<' . $tag . '>' ) . '</a>';

		}

		$separator = apply_filters( 'tb_comment_tools_separator', ' ' );

		$label = apply_filters( 'tb_comment_tools_label', __( 'Allowed tags', 'tb_comment_tools' ) . ': ' );

		//' - <a id="tagthis" href="#" onclick="tbCommentTools.tag_this(); return false" title="' + tbCommentTools_l10n.tag_tip + '" >' + tbCommentTools_l10n.tag + '</a>'
		return '<p id="tb-comment-tools-tags" class="form-allowed-tags hide-if-no-js" style="display:none;">' . $label . implode( $separator, $allowed ) . '</p>';

	}

}

new TB_Comment_Tools;