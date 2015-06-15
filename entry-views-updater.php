<?php
/*
Plugin Name: Entry Views Updater
Plugin URI: http://www.pronamic.eu/plugins/entry-views-updater/
Description: This plugin will update the entry views count after the AJAX request so visitors don't see a cached value.
Author: Pronamic
Version: 1.0.0
Author URI: http://www.pronamic.eu/
*/

/**
 * AJAX
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
 * @see https://github.com/justintadlock/entry-views/blob/1.0.0/entry-views.php#L79-L80
 */
function pronamic_entry_views_update_ajax() {
	if ( function_exists( 'ev_get_post_view_count' ) ) {
		$post_id = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_STRING );

		$data = array(
			'post_id'         => $post_id,
			'post_view_count' => ev_get_post_view_count( $post_id ),
		);

		wp_send_json_success( $data );
	}
}

add_action( 'wp_ajax_entry_views',        'pronamic_entry_views_update_ajax', 20 );
add_action( 'wp_ajax_nopriv_entry_views', 'pronamic_entry_views_update_ajax', 20 );

/**
 * Footer
 *
 * @see https://github.com/justintadlock/entry-views/blob/master/entry-views.php#L169-L170
 */
function pronamic_entry_views_footer() {
	?>
	<script type="text/javascript">
		/* <![CDATA[ */ 

		jQuery( document ).ready( function( $ ) {
			$( document ).ajaxSuccess( function( event, xhr, settings ) {
				if ( settings.data.startsWith( 'action=entry_views' ) ) {
					var data = xhr.responseJSON.data;

					$( '#post-' + data.post_id + ' .entry-views' ).html( data.post_view_count );
				}
			} );
		} );

		/* ]]> */
	</script>
	<?php
}

add_action( 'wp_footer', 'pronamic_entry_views_footer', 1 );
