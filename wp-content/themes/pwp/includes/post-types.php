<?php

// Register Custom Post types

function pwp_register_post_types() {
  // Blog / Posts
  unregister_taxonomy_for_object_type( 'post_tag', 'post' );
  // unregister_taxonomy_for_object_type( 'category', 'post' );
  remove_post_type_support( 'post', 'comments' );

  $home_url = get_home_url();
  $np_options = get_option( 'nestedpages_posttypes' );

  $project_root_id = $np_options['project']['post_type_page_assignment_page_id'];
  $project_slug = trim( str_ireplace( $home_url, '', get_permalink( $project_root_id ) ), '/' );
  $team_root_id = $np_options['team']['post_type_page_assignment_page_id'];
  $team_slug = trim( str_ireplace( $home_url, '', get_permalink( $team_root_id ) ), '/' );

  $slugs = array(
    'project' => $project_slug,
    'project_id' => $project_root_id,
    'team' => $team_slug,
    'team_id' => $team_root_id,
  );

  // Project
  register_post_type( 'project', array(
    'labels'              => array(
      'name'              => 'Projects',
      'singular_name'     => 'Project',
      'add_new_item'      => 'Add New Project',
      'edit_item'         => 'Edit Project',
    ),
    'description'         => '',
    'menu_icon'           => 'dashicons-images-alt2',
    'public'              => true,
    'show_ui'             => true,
    'has_archive'         => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => false,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'capability_type'     => 'post',
    'capabilities'        => array(),
    'supports'            => array( 'title', 'editor', 'revisions' ),
    'map_meta_cap'        => true,
    'map_meta_cap'        => true,
    'hierarchical'        => false,
    'rewrite'             => array(
      'slug'              => $slugs['project'],
      'with_front'        => false,
    ),
    'query_var'           => true,
  ) );

  // Team
  register_post_type( 'team', array(
    'labels'              => array(
      'name'              => 'Team',
      'singular_name'     => 'Team Member',
      'add_new_item'      => 'Add New Team Member',
      'edit_item'         => 'Edit Team Member',
    ),
    'description'         => '',
    'menu_icon'           => 'dashicons-groups',
    'public'              => true,
    'show_ui'             => true,
    'has_archive'         => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => false,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'capability_type'     => 'post',
    'capabilities'        => array(),
    'supports'            => array( 'title', 'editor', 'revisions' ),
    'map_meta_cap'        => true,
    'map_meta_cap'        => true,
    'hierarchical'        => false,
    'rewrite'             => array(
      'slug'              => $slugs['team'],
      'with_front'        => false,
    ),
    'query_var'           => true,
  ) );

  $old_slugs = get_option( 'pwp_post_type_slugs' );
  if ( empty( $old_slugs ) || (
    $slugs['project'] != $old_slugs['project'] ||
    $slugs['team'] != $old_slugs['team']
  ) ) {
    flush_rewrite_rules();
    update_option( 'pwp_post_type_slugs', $slugs );
  }
}
add_action( 'init', 'pwp_register_post_types', 5 );


//

function pwp_save_casestudy_post( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  $post_type = get_post_type( $post_id );

  if ( $post_type == 'callout' ) {
    $type = get_field( 'type', $post_id );
    $title = get_field( 'title', $post_id );

    remove_action( 'save_post', 'pwp_save_casestudy_post', 99 );
    wp_update_post( array(
      'ID'         => $post_id,
      'post_title' => ucwords( $type ) . ' - ' . $title,
    ) );
    add_action( 'save_post', 'pwp_save_casestudy_post', 99 );
  }
}
add_action( 'save_post', 'pwp_save_casestudy_post', 99 );


//

function pwp_edit_callout_columns( $columns ) {
  $columns = array(
    'cb'    => '<input type="checkbox">',
    'title' => 'Title',
    'type'  => 'Type',
    'date'  => 'Date',
  );

  return $columns;
}
add_filter( 'manage_edit-callout_columns', 'pwp_edit_callout_columns' );

function pwp_manage_callout_columns( $column, $post_id ) {
  global $post;

  switch( $column ) {
    case 'type' :
      $type = get_field( 'type', $post_id );

      if ( empty( $type ) ) {
        echo '';
      } else {
        echo ucwords( $type );
      }

      break;
    default :
      break;
  }
}
add_action( 'manage_callout_posts_custom_column', 'pwp_manage_callout_columns', 10, 2 );
