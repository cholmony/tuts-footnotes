<?php

/**
 * Plugin Name: Footnotes Shortcode (by Tuts+ Code)
 * Plugin URI: https://github.com/barisunver/tuts-footnotes/
 * Description: A simple plugin utilizing shortcodes and custom fields to create footnotes in your articles.
 * Version: 1.0
 * Author: Baris Unver
 * Author URI: http://tutsplus.com/authors/baris-unver
 * License: GPL2
 */

function footnotes_sc( $atts ) {

	extract( shortcode_atts(
		array(
			'id' => '1',
		), $atts )
	);

    return '<a href="#ref-' . $id . '" rel="footnote"><sup>[' . $id . ']</sup></a>';
	
}

add_shortcode( 'ref', 'footnotes_sc' );

function make_footnotes( $title = '', $titletag = 'h3', $listtag = 'ol' ) {
	if( '' == $title )
		$title = __( 'Footnotes', 'tuts-footnotes' );

	$footnotes_array = array();
	
	for( $i = 1; i <= 100; $i++ ) {
		$get_post_meta = get_post_meta( get_the_ID(), 'ref-' . $i, true );
		if( '' == $get_post_meta )
			break;
		else
			$footnotes_array[] = array( 'ref-' . $i => $get_post_meta );
	}
	
	if( count( $footnotes_array ) > 0 ) {
		$output  = '<div class="tutsplus-footnotes">';
		$output .= '<' . $titletag . '>' . $title . '</' . $titletag . '>';
		$output .= '<' . $listtag . '>';
		
		foreach( $footnotes_array as $footnote ) {
			foreach( $footnote as $ref_id => $footnote_content ) {
				$output .= '<li id="' . $ref_id . '">' . $footnote_content . '</li>';
			}
		}
		
		$output .= '</' . $listtag . '>';
		$output .= '</div>';
	}
	
	if( is_singular() && is_main_query() )
		return $output;
	
}

function print_footnotes( $content ) {
	$content .= make_footnotes();
	return $content;
}

add_filter( 'the_content', 'print_footnotes' );

?>
