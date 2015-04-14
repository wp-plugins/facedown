<?php
/**
 * @package FaceDown
 * @version 1.0
 */
/*
Plugin Name: FaceDown
Plugin URI: http://blog.gwannon.com/facedown/
Description: This plugin put facedown your blog.
Version: 1.0
Author: Gwannon
Author URI: http://blog.gwannon.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

//Cargamos el idioma del plugin
function facedown_init() {
	load_plugin_textdomain( 'facedown', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action('init', 'facedown_init');

//Cargamos javascript en el pie
function facedown_code() { 
	global $post;
	$rotate = get_option('facedown_rotate');
	$include = get_option('facedown_include');
	if($include != '' && in_array($post->ID, split(",", $include))) { ?>
		<!-- FaceDown Plugin -->
		<script type="text/javascript">jQuery(document).ready(function () { <?php if($rotate == 180) { ?>
		var fdheight = jQuery('body').outerHeight(); var fdwidth = jQuery('body').outerWidth(); jQuery('body').css("height", fdheight+"px"); jQuery('body').css("transform", "rotate(180deg)"); jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, 200);<?php } else if($rotate == 90) { ?>var fdheight = jQuery('body').outerHeight(); var fdwidth = jQuery('body').outerWidth(); jQuery('body').css("height", fdwidth+"px"); jQuery('body').css("transform", "rotate(270deg)");<?php } else if($rotate == 270) { ?>var fdheight = jQuery('body').outerHeight(); var fdwidth = jQuery('body').outerWidth(); jQuery('body').css("min-width", fdheight+"px"); jQuery('body').css("transform", "rotate(90deg)");
		<?php } ?> });</script>
	<?php }
}
add_action( 'wp_footer', 'facedown_code' );

//Página de settings
add_action( 'admin_menu', 'facedown_plugin_menu' );
function facedown_plugin_menu() {
	add_options_page( __('FaceDown', 'facedown'), __('FaceDown', 'facedown'), 'manage_options', 'facedown', 'facedown_page_settings' );
}

function facedown_page_settings() {
	if (isset($_REQUEST['facedown_rotate']) && $_REQUEST['facedown_rotate'] != '') {
		update_option( 'facedown_rotate', $_REQUEST['facedown_rotate']);
		update_option( 'facedown_include', $_REQUEST['facedown_include']);
	} 
	?>
	<div class="wrap">
		<h2><?php _e('FaceDown', 'facedown'); ?></h2>
		<form method="post" action="">
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e('Rotate', 'facedown'); ?></th>
					<td>
						<select name="facedown_rotate">
							<option value="0">0º</option>
							<option value="90"<?php if (get_option('facedown_rotate') == 90) { echo " selected='selected'"; } ?>>90º</option>
							<option value="180"<?php if (get_option('facedown_rotate') == 180) { echo " selected='selected'"; } ?>>180º</option>
							<option value="270"<?php if (get_option('facedown_rotate') == 270) { echo " selected='selected'"; } ?>>270º</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Use only in pages', 'facedown'); ?></th>
					<td>
						<input type="text" name="facedown_include" value="<?php echo get_option('facedown_include'); ?>" /> <?php _e("IDs separated by commas", "facedown"); ?>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php 
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'facedown_add_action_links' );
function facedown_add_action_links ( $links ) {
	$mylinks = array('<a href="' . admin_url( 'options-general.php?page=facedown' ) . '">'.__("Settings", "facedown").'</a>');
	return array_merge( $links, $mylinks );
}

?>
