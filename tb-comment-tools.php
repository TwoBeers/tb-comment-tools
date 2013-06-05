<?php
/*
Plugin Name: tb-comment-tools
Plugin URI:
Description:
Author: Jimo
Version: 1.0
*/

class TB_Comment_Tools {

	function __construct() {

		add_action( 'comment_form_before', array( $this, 'script' ) );

		add_filter( 'comment_form_defaults', array( $this, 'add_buttons' ) );

	}

	function script() {

		wp_enqueue_script( 'tb-comment-tools-script', plugins_url( 'script.js', __FILE__ ), false, '1.0', true );

		$data = array(
			'quote_tip'			=> esc_js( __( 'Add selected text as a quote', 'tb_comment_tools' ) ),
			'quote'				=> esc_js( __( 'Quote', 'tb_comment_tools' ) ),
			'quote_alert'		=> esc_js( __( 'Nothing to quote. First of all you should select some text...', 'tb_comment_tools' ) ),
		);

		wp_localize_script( 'tb-comment-tools-script', 'tbCommentTools_L10', $data );

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
		//' - <a id="tagthis" href="#" onclick="tbCommentTools.tag_this(); return false" title="' + tbCommentTools_L10.tag_tip + '" >' + tbCommentTools_L10.tag + '</a>'
		return '<p id="tb-comment-tools-tags" class="form-allowed-tags hide-if-no-js" style="display:none;">Allowed tags: ' . implode( ' - ', $allowed ) . '</p>';

	}

}

new TB_Comment_Tools;