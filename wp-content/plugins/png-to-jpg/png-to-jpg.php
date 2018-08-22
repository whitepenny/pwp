<?php
/*
	Plugin Name: PNG to JPG
	Plugin URI: http://kubiq.sk
	Description: Convert PNG images to JPG, free up web space and speed up your webpage
	Version: 2.5
	Author: Jakub Novák
	Author URI: http://kubiq.sk
*/

if (!class_exists('png_to_jpg')) {
	class png_to_jpg {
		var $domain = 'png_to_jpg';
		var $plugin_admin_page;
		var $settings;
		var $tab;

		function __construct(){
			$mo = plugin_dir_path(__FILE__) . 'languages/' . get_locale() . '.mo';
			load_textdomain($this->domain, $mo);
			add_action( 'admin_menu', array( &$this, 'plugin_menu_link' ) );
			add_action( 'init', array( &$this, 'plugin_init' ) );
			add_action( 'admin_notices', array( &$this, 'server_gd_library' ) );
			add_filter( 'wp_handle_upload', array( &$this, 'upload_converting' ) );
			add_action( 'wp_ajax_hasTransparency', array( &$this, 'hasTransparency' ) );
			add_action( 'wp_ajax_convert_old_png', array( &$this, 'convert_old_png' ) );
		}

		function activate() {
			if( ! get_option('png_to_jpg_settings', 0) ){
				update_option( "png_to_jpg_settings", array( "general" => array( "upload_convert" => 0, "jpg_quality" => "90", "leave_original" => "checked", "autodetect" => "checked" ) ) );
			}
		}
		
		function filter_plugin_actions($links, $file) {
			$settings_link = '<a href="tools.php?page=' . basename(__FILE__) . '">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}
		
		function plugin_menu_link() {
			$this->plugin_admin_page = add_submenu_page(
				'tools.php',
				__( 'PNG to JPG', $this->domain ),
				__( 'PNG to JPG', $this->domain ),
				'manage_options',
				basename(__FILE__),
				array( $this, 'admin_options_page' )
			);
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'filter_plugin_actions'), 10, 2 );
		}
		
		function plugin_init(){
			$this->settings = get_option('png_to_jpg_settings');
		}
		
		function plugin_admin_tabs( $current = 'general' ) {
			$tabs = array( 'general' => __('General'), 'convert' => __('Convert existing PNGs', $this->domain), 'info' => __('Help') ); ?>
			<h2 class="nav-tab-wrapper">
			<?php foreach( $tabs as $tab => $name ){ ?>
				<a class="nav-tab <?php echo ( $tab == $current ) ? "nav-tab-active" : "" ?>" href="?page=<?php echo basename(__FILE__) ?>&amp;tab=<?php echo $tab ?>"><?php echo $name ?></a>
			<?php } ?>
			</h2><br><?php
		}

		function admin_options_page() {
			if ( get_current_screen()->id != $this->plugin_admin_page ) return;
			$this->tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';
			if(isset($_POST['plugin_sent'])) $this->settings[ $this->tab ] = $_POST;
			update_option( "png_to_jpg_settings", $this->settings ); ?>
			<div class="wrap">
				<h2><?php _e( 'PNG to JPG', $this->domain ); ?></h2>
				<?php if(isset($_POST['plugin_sent'])) echo '<div class="updated"><p>'.__( 'Settings saved.' ).'</p></div>'; ?>
				<form method="post" action="<?php admin_url( 'tools.php?page=' . basename(__FILE__) ); ?>">
					<input type="hidden" name="plugin_sent" value="1"><?php
					$this->plugin_admin_tabs( $this->tab );
					switch ( $this->tab ) :
						case 'general' :
							$this->tab_general();
							break;
						case 'convert' :
							$this->tab_convert();
							break;
						case 'info' :
							$this->tab_info();
							break;
					endswitch; ?>
				</form>
			</div><?php
		}

		function server_gd_library() {
			if( !function_exists('imagecreatefrompng') ) {
				echo '<div class="error"><p>' . __( "<em>PNG to JPG</em> requires gd library enabled!", $this->domain ) . '</p></div>' . "\n";
			}
		}
		
		function tab_general(){ ?>
			<table class="form-table">
				<tr>
					<th>
						<label for="q_field_1"><?php _e("PNG to JPG convert quality", $this->domain) ?></label> 
					</th>
					<td>
						<input type="number" min="1" max="100" step="1" name="jpg_quality" placeholder="90" value="<?php echo $this->settings[ $this->tab ]["jpg_quality"]; ?>" id="q_field_1"> %
					</td>
				</tr>
				<tr>
					<th>
						<label for="q_field_2"><?php _e("Convert PNG to JPG during upload", $this->domain) ?></label> 
					</th>
					<td><?php
						$this->q_select(array(
							"name" => "upload_convert",
							"id" => "q_field_2",
							"value" => $this->settings[ $this->tab ]["upload_convert"],
							"options" => array(
								__("No") => 0,
								__("Yes") => 1,
								__("Yes, but only images without transparency", $this->domain) => 2
							)
						)); ?>
					</td>
				</tr>
				<tr>
					<th>
						<label for="q_field_3"><?php _e("Leave original PNG images on the server", $this->domain) ?></label> 
					</th>
					<td>
						<input type="checkbox" name="leave_original" value="checked" id="q_field_3" <?php echo isset( $this->settings[ $this->tab ]["leave_original"] ) ? $this->settings[ $this->tab ]["leave_original"] : "" ?>>
					</td>
				</tr>
				<tr>
					<th>
						<label for="q_field_4"><?php _e("Autodetect transparency for existing PNG images", $this->domain) ?></label> 
					</th>
					<td>
						<input type="checkbox" name="autodetect" value="checked" id="q_field_4" <?php echo isset( $this->settings[ $this->tab ]["autodetect"] ) ? $this->settings[ $this->tab ]["autodetect"] : "" ?>>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" class="button button-primary button-large" value="<?php _e( 'Save' ) ?>"></p><?php
		}

		function tab_convert(){
			global $wpdb;
			$nonce = wp_create_nonce('convert_old_png');
			wp_enqueue_media();
			$query_images = new WP_Query(array(
				'post_type' => 'attachment',
				'post_mime_type' => 'image/png',
				'post_status' => 'inherit',
				'posts_per_page' => -1,
				'no_found_rows' => 1
			)); ?>
			<div class="below-h2 error">
				<p>
					<?php _e('Converted images will be fixed only in these tables: ', $this->domain) ?>
					<em><?php echo "{$wpdb->prefix}posts, {$wpdb->prefix}postmeta, {$wpdb->prefix}options, {$wpdb->prefix}revslider_slides, {$wpdb->prefix}toolset_post_guid_id"; ?></em>. 
					<?php _e('If you need support for more database tables from various plugins, let me know by mail to info@kubiq.sk', $this->domain) ?>
				</p>
			</div>
			<div class="below-h2 error"><p><?php _e('Do you have backup? This operation will alter your original images and cannot be undone!', $this->domain) ?></p></div>
			<?php if( isset( $this->settings["general"]["autodetect"] ) ): ?>
				<div id="transparency_status_message" class="below-h2 updated"><p><img src="<?php echo admin_url('/images/loading.gif') ?>" alt="" style="vertical-align:sub">&emsp;<span><?php _e("Please wait, I'm getting transparency status for images...",$this->domain) ?></span></p></div>
			<?php endif ?>
			<br>
			<button type="button" class="button button-primary convert-pngs"><?php _e("Convert selected to JPG",$this->domain) ?></button>
			&emsp;
			<button type="button" class="button button-default select-transparent"><?php _e("Select all transparent PNGs",$this->domain) ?></button>
			&emsp;
			<button type="button" class="button button-default select-non-transparent"><?php _e("Select all non-transparent PNGs",$this->domain) ?></button>
			<br><br>
			<table class="wp-list-table widefat striped media">
				<thead>
					<tr>
						<th class="check-column"><input type="checkbox"></th>
						<th><?php _e('Media') ?></th>
						<?php if( isset( $this->settings["general"]["autodetect"] ) ): ?>
							<th><?php _e('Has transparency', $this->domain) ?></th>
						<?php endif ?>
					</tr>
				</thead>
				<tbody><?php
					foreach ( $query_images->posts as $image ) {
						$image->link = wp_get_attachment_url( $image->ID ); ?>
						<tr data-id="<?php echo $image->ID ?>" data-url="<?php echo $image->link ?>" data-transparency="-">
							<th scope="row" class="check-column">
								<input type="checkbox" name="media[]" value="<?php echo $image->ID ?>" <?php if( isset( $this->settings["general"]["autodetect"] ) ) echo 'disabled="disabled"' ?>>
							</th>
							<td class="title column-title has-row-actions column-primary">
								<strong class="has-media-icon">
									<a href="<?php echo $image->link ?>">
										<span class="media-icon image-icon">
											<?php echo wp_get_attachment_image( $image->ID, 'thumbnail' ) ?>
										</span>
										<?php echo $image->post_title ?>
									</a>
								</strong>
								<p class="filename">
									<?php echo basename($image->link) ?>
								</p>
							</td>
							<?php if( isset( $this->settings["general"]["autodetect"] ) ): ?>
								<td class="transparency"></td>
							<?php endif ?>
						</tr><?php
					} ?>
				</tbody>
			</table>
			<br>
			<button type="button" class="button button-primary convert-pngs"><?php _e("Convert selected to JPG",$this->domain) ?></button>
			&emsp;
			<button type="button" class="button button-default select-transparent"><?php _e("Select all transparent PNGs",$this->domain) ?></button>
			&emsp;
			<button type="button" class="button button-default select-non-transparent"><?php _e("Select all non-transparent PNGs",$this->domain) ?></button>

			<div id="png_preview" class="media-modal wp-core-ui" style="display:none">
				<button type="button" class="button-link media-modal-close"><span class="media-modal-icon"></span></button>
				<div class="media-modal-content">
					<div class="edit-attachment-frame mode-select hide-menu hide-router">
						<div class="media-frame-title"><h1><?php _e('Attachment Details') ?></h1></div>
						<div class="media-frame-content"></div>
					</div>
				</div>
			</div>

			<style type="text/css" media="screen">
				.widefat thead .check-column{
					padding: 10px 0 0 4px;
				}
				#png_preview .media-frame-content{
					background: url(<?php echo plugins_url( 'images/bgt.gif', __FILE__ ); ?>) top left repeat;
				}
			</style>

			<script>
				jQuery(document).ready(function($) {
					$(".has-media-icon a").click(function(event) {
						event.preventDefault();
						$("#png_preview .media-frame-content").html('<img src="'+this.href+'" alt="">');
						$("#png_preview").show();
					});
					$(document).keyup(function(event) {
						if( $("#png_preview").is(":visible") ){
							var keycode = (event.keyCode ? event.keyCode : event.which);
							if( keycode == 27 ){
								$("#png_preview").hide();
							}
						}
					});
					$("#png_preview .media-modal-close").click(function(event) {
						event.preventDefault();
						$("#png_preview").hide();
					});
					$(".select-transparent").click(function(event) {
						event.preventDefault();
						$("tr[data-transparency] input").prop("checked",false);
						$("tr[data-transparency=1] input").prop("checked","checked");
					});
					$(".select-non-transparent").click(function(event) {
						event.preventDefault();
						$("tr[data-transparency] input").prop("checked",false);
						$("tr[data-transparency=0] input").prop("checked","checked");
					});
					$(".convert-pngs").click(function(event) {
						event.preventDefault();
						$("#transparency_status_message span").text("<?php _e("Please wait, I'm converting your PNG images...", $this->domain) ?>");
						$("#transparency_status_message").show();
						$("tbody tr input").prop("disabled", "disabled");
						delete_selected_pngs();
					});

					<?php if( isset( $this->settings["general"]["autodetect"] ) ): ?>
					get_transparency();

					function get_transparency(){
						var $el = $('tbody tr[data-transparency="-"]').first();
						if( $el.length ){
							$.post( "<?php echo admin_url('admin-ajax.php'); ?>", {
								action: "hasTransparency",
								id: $el.attr("data-id"),
								png_url: $el.attr("data-url")
							}, function(response){
								var transparency = parseInt(response);
								$el.attr("data-transparency", transparency);
								$el.find(".transparency").html( transparency == 1 ? "YES" : "NO" );
								get_transparency();
							});
						}else{
							$("#transparency_status_message").hide();
							$("tbody tr input").prop("disabled", false);
						}
					}
					<?php endif; ?>

					function delete_selected_pngs(){
						var $el = $("tbody tr input:checked").first();
						if( $el.length ){
							var $tr = $el.parent().parent();
							$.post( "<?php echo admin_url('admin-ajax.php'); ?>", {
								action: "convert_old_png",
								id: $tr.attr("data-id"),
								nonce: "<?php echo $nonce ?>"
							}, function(response){
								$tr.remove();
								delete_selected_pngs();
							});
						}else{
							$("#transparency_status_message").html('<p><?php _e('Done') ?>.</p>');
							$("tbody tr input").prop("disabled", false);
						}
					}
				});
			</script><?php
		}
		
		function tab_info(){ ?>
			<p><?php _e('Any ideas, problems, issues?', $this->domain) ?></p>
			<p>Ing. Jakub Novák</p>
			<p><a href="mailto:info@kubiq.sk" target="_blank">info@kubiq.sk</a></p>
			<p><a href="https://kubiq.sk/" target="_blank">https://kubiq.sk</a></p><?php
		}

		function q_select( $field_data = array(), $print = 1, $cols = array( 'value' => 'ID', 'text' => 'post_title' ) ){
			if(!is_object($field_data)) $field_data = (object)$field_data;
			$field_data->value = is_array($field_data->value) ? $field_data->value : array($field_data->value);
			$select = "<select name='{$field_data->name}' id='{$field_data->id}'".( isset($field_data->multiple) ? " multiple" : "").( isset($field_data->size) ? " size='{$field_data->size}'" : "").">";
			if( isset($field_data->placeholder) ) $select .= "<option value='' disabled>{$field_data->placeholder}</option>";
			foreach($field_data->options as $option => $value){
				if( isset( $value->ID ) || isset( $value->term_id ) ){
					$post_id = isset( $value->ID ) ? $value->ID : $value->term_id;
					$value = (array)$value;
					if ( class_exists( 'PLL_Model' ) ){
						$post_lang = pll_get_post_language( $post_id );
						if( pll_default_language() != $post_lang ) continue;
					}
					$select .= "<option value='".$value[ $cols['value'] ]."'".( in_array( $value[ $cols['value'] ] , $field_data->value ) ? " selected" : "").">".$value[ $cols['text'] ]."</option>";
				}else{
					$select .= "<option value='{$value}'".( in_array($value, $field_data->value) ? " selected" : "").">{$option}</option>";
				}
			}
			$select .= "</select>";
			if($print)
				echo $select;
			else
				return $select;
		}

		function upload_converting( $params ){
			if( $params['type'] == 'image/png' ) {
				if( $this->settings["general"]["upload_convert"] == 1 ){
					$params = $this->convert_image( $params );
				}elseif( $this->settings["general"]["upload_convert"] == 2 ){
					if( ! $this->hasTransparency( $params ) ){
						$params = $this->convert_image( $params );
					}
				}
			}
			return $params;
		}

		function convert_image( $params ){
			$img = imagecreatefrompng($params['file']);
			$bg = imagecreatetruecolor(imagesx($img), imagesy($img));
			imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
			imagealphablending($bg, 1);
			imagecopy($bg, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
			$newPath = preg_replace("/\.png$/", ".jpg", $params['file']);
			$newUrl = preg_replace("/\.png$/", ".jpg", $params['url']);
			if ( imagejpeg( $bg, $newPath, $this->settings["general"]["jpg_quality"] ) ){
				if( ! isset( $this->settings["general"]["leave_original"] ) ){
					unlink($params['file']);
				}
				$params['file'] = $newPath;
				$params['url'] = $newUrl;
				$params['type'] = 'image/jpeg';
				return $params;
			}
			return 0;
		}

		function hasTransparency( $params ) {
			$transparent = 0;
			if ( isset($_POST['png_url']) ){
				$image = $this->getFullPath( $_POST['png_url'] );
			}else{
				$image = $params['file'];
			}
			$contents = file_get_contents( $image );
			if ( ord ( file_get_contents( $image, false, null, 25, 1 ) ) & 4 ) $transparent = 1;
			if ( stripos( $contents, 'PLTE' ) !== false && stripos( $contents, 'tRNS' ) !== false ) $transparent = 1;
			if ( isset($_POST['png_url']) ){
				echo $transparent;
				exit();
			}else{
				return $transparent;
			}
		}

		function getFullPath( $url ){
			return str_replace( home_url("/"), ABSPATH, $url );
		}

		function convert_old_png(){
			if ( defined('DOING_AJAX') && DOING_AJAX ){
				if ( ! wp_verify_nonce( $_POST['nonce'], 'convert_old_png' ) ) die ( 'Wrong nonce!');
				$image = get_post($_POST['id']);
				$image->link = wp_get_attachment_url( $image->ID );
				$image->path = $this->getFullPath( $image->link );
				$params = array(
					"ID" => $image->ID,
					"file" => $image->path,
					"url" => $image->link,
				);
				if( $this->convert_image( $params ) ){
					$this->update_image_data( $image );
				}
			}
			exit();
		}

		function update_image_data( $image ){
			global $wpdb;

			$replaces = array( basename( $image->link ) );

			$thumbs = wp_get_attachment_metadata( $image->ID );
			foreach ( $thumbs['sizes'] as $img ) {
				if( file_exists( dirname($image->path)."/".$img['file'] ) ){
					$replaces[] = $img['file'];
					unlink( dirname($image->path)."/".$img['file'] );
				}
			}

			wp_update_post(array( 'ID' => $image->ID, 'post_mime_type' => 'image/jpeg' ));
			
			$wpdb->update( 
				$wpdb->posts, 
				array( 'guid' => preg_replace("/\.png$/", ".jpg", $image->guid) ),
				array( 'ID' => $image->ID ), 
				array( '%s' ), 
				array( '%d' ) 
			);

			$meta = get_post_meta( $image->ID, '_wp_attached_file', 1 );
			$meta = preg_replace("/\.png$/", ".jpg", $meta);
			update_post_meta( $image->ID, '_wp_attached_file', $meta );

			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$newPath = preg_replace("/\.png$/", ".jpg", $image->path);
			$attach_data = wp_generate_attachment_metadata( $image->ID, $newPath );
			wp_update_attachment_metadata( $image->ID, $attach_data );

			foreach( $replaces as $image ){
				$new_image = substr( $image, 0, -3 ) . "jpg";
				// WP: wp_posts
				$wpdb->query("
					UPDATE {$wpdb->posts} 
					SET post_content = REPLACE( post_content, '{$image}', '{$new_image}') 
					WHERE post_content LIKE '%{$image}%'
				");
				// WP: wp_postmeta
				$wpdb->query("
					UPDATE {$wpdb->postmeta} 
					SET meta_value = REPLACE( meta_value, '{$image}', '{$new_image}') 
					WHERE meta_value LIKE '%{$image}%'
				");
				// WP: wp_options
				$wpdb->query("
					UPDATE {$wpdb->options} 
					SET option_value = REPLACE( option_value, '{$image}', '{$new_image}') 
					WHERE option_value LIKE '%{$image}%'
				");
				// Revolution Slider: wp_revslider_slides
				$table_name = $wpdb->prefix.'revslider_slides';
				if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name ){
					$wpdb->query("
						UPDATE $table_name 
						SET params = REPLACE( params, '{$image}', '{$new_image}'), 
							layers = REPLACE( layers, '{$image}', '{$new_image}') 
						WHERE params LIKE '%{$image}%' 
							OR layers LIKE '%{$image}%'
					");
				}
				// Toolset Types: wp_toolset_post_guid_id
				$table_name = $wpdb->prefix.'toolset_post_guid_id';
				if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name ){
					$wpdb->query("
						UPDATE $table_name 
						SET guid = REPLACE( guid, '{$image}', '{$new_image}') 
						WHERE guid LIKE '%{$image}%'
					");
				}
			}
		}
	}
}

if (class_exists('png_to_jpg')) { 
	$png_to_jpg_var = new png_to_jpg();
	register_activation_hook( __FILE__, array( $png_to_jpg_var, 'activate' ) );
}