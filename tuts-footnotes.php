<?php

/**
 * Plugin Name: Footnotes Shortcode (by Tuts+ Code)
 * Plugin URI: http://**GITHUB_REPO**
 * Description: A simple plugin utilizing shortcodes and custom fields to create footnotes in your articles.
 * Version: 1.0
 * Author: Baris Unver
 * Author URI: http://tutsplus.com/authors/baris-unver
 * License: GPL2
 */

function footnotes_sc( $atts ) {

	extract( shortcode_atts(
		array(
			'id' => '',
		), $atts )
	);

    return '<a href="#ref-' . $id . '"><sup>[' . $id . ']</sup></a>';
	
}

add_shortcode( 'ref', 'footnotes_sc' );

function make_footnotes( $title = 'Footnotes', $titletag = 'h3', $listtag = 'ol' ) {

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
	
	if( is_singular() )
		return $output;
	
}

function print_footnotes( $content ) {
	$content .= make_footnotes();
	return $content;
}

add_filter( 'the_content', 'print_footnotes' );

?>
