<?php

// ADMIN

function chai_admin_init() {
  add_editor_style( 'style-admin.css' );
}
add_action( 'admin_init', 'chai_admin_init' );


function pwp_admin_head() {
  $screen = get_current_screen();

  // Callouts
  $post_types = array(
    'callout',
  );

  if ( is_object( $screen ) && in_array( $screen->post_type, $post_types) ) {
    ?>
    <style>
      #titlewrap { height: 0px !important; overflow: hidden !important; }
      #post-body-content { margin-bottom: 1px !important; }
    </style>
    <?php
  }

  // ACF Content Editor
  ?>
  <script>
    (function($) {
      $(document).ready(function() {
        var fields = [
          ".acf-field-5adba8b1f0c91",
        ];
        var $postContent = $( fields.join(", ") );

        if ( $postContent.length ) {
          $postContent.find(".acf-input").append( $("#postdivrich") );
        }
      });
    })(jQuery);
  </script>
  <style type="text/css">
    .acf-field #wp-content-editor-tools {
      background: transparent;
      padding-top: 0;
    }
  </style>
  <?php
}
add_action( 'admin_head', 'pwp_admin_head' );


// Set menu order

function pwp_admin_menu_order() {
  global $menu;

  $pages_position    = 0;
  $posts_position    = 0;
  $comments_position = 0;
  $media_position    = 0;
  $gf_position       = 0;
  $callouts_position = 0;

  foreach ( $menu as $position => $item ) {
    if ( $item[0] == 'Pages' ) {
      $pages_position = $position;
    }
    if ( $item[0] == 'Posts' ) {
      $posts_position = $position;
    }
    if ( $item[0] == 'Callouts' ) {
      $callouts_position = $position;
    }
    if ( $item[2] == 'edit-comments.php' ) {
      $comments_position = $position;
    }
    if ( $item[2] == 'upload.php' ) {
      $media_position = $position;
    }
    if ( $item[2] == 'gf_edit_forms' ) {
      $gf_position = $position;
    }
  }

  // Move Pages Up
  $menu[ $posts_position - 1 ] = $menu[ $pages_position ];
  unset( $menu[ $pages_position ] );

  // Remove Posts
  unset( $menu[ $posts_position ] );
  // Rename Posts
  // $menu[ $posts_position ][0] = 'News';

  // Remove Comments
  unset( $menu[ $comments_position ] );

  // Move Gravity Forms down
  $menu['39'] = $menu[ $gf_position ];
  unset( $menu[ $gf_position ] );

  // Move Media down
  $menu['40'] = $menu[ $media_position ];
  unset( $menu[ $media_position ] );
}
add_action( 'admin_menu', 'pwp_admin_menu_order', 999 );


// TinyMCE Styles

function pwp_mce_buttons( $buttons ) {
  $blocks = array_shift( $buttons );

  array_unshift( $buttons, 'styleselect' );
  array_unshift( $buttons, $blocks );

	return $buttons;
}
add_filter( 'mce_buttons', 'pwp_mce_buttons' );

function pwp_tiny_mce_before_init( $opts ) {
	$opts['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Pre=pre';

	$style_formats = array(
		array(
			'title' => 'Intro Paragraph',
			'block' => 'p',
			'classes' => 'intro',
		),
	);

	$opts['style_formats'] = json_encode( $style_formats );

	return $opts;
}
add_filter( 'tiny_mce_before_init', 'pwp_tiny_mce_before_init' );


// Modify ACF JSON directory

function pwp_acf_json_save_directory( $path ) {
  $path = get_stylesheet_directory() . '/fields';

  return $path;
}
add_filter( 'acf/settings/save_json', 'pwp_acf_json_save_directory' );

function pwp_acf_json_load_directory( $paths ) {
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/fields';

    return $paths;
}
add_filter( 'acf/settings/load_json', 'pwp_acf_json_load_directory' );


// Populate gravity forms in ACF

function pwp_populate_gravity_forms( $field ) {
  $field['choices'] = array();

  if ( class_exists( 'GFAPI' ) ) {
    $forms = GFAPI::get_forms();

    foreach ( $forms as $form ) {
      $field['choices'][ $form['id'] ] = $form['title'];
    }
  }

  return $field;
}
add_filter( 'acf/load_field/name=gravity_form', 'pwp_populate_gravity_forms' );
add_filter( 'acf/load_field/name=blog_gravity_form', 'pwp_populate_gravity_forms' );
add_filter( 'acf/load_field/name=footer_gravity_form', 'pwp_populate_gravity_forms' );
