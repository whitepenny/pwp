<?php

// Image Utils

// Map image sizing for on-the-fly generation

function pwp_add_image_size( $name, $width, $height, $crop = false ) {
  if ( function_exists( 'pwad_add_image_size' ) ) {
    pwad_add_image_size( array(
      'key' => $name,
      'name' => $name,
      'width' => $width,
      'height' => $height,
      'crop' => $crop
    ) );
  } else if ( function_exists( 'fly_add_image_size' ) ) {
    fly_add_image_size( $name, $width, $height, $crop );
  } else {
    add_image_size( $name, $width, $height, $crop );
  }
}

function pwp_get_image( $image, $size ) {
  if ( function_exists( 'pwad_get_attachment_image_src' ) ) {
    return pwad_get_attachment_image_src( $image, $size );
  } else if ( function_exists( 'fly_get_attachment_image_src' ) ) {
    return fly_get_attachment_image_src( $image, $size );
  } else {
    $image = wp_get_attachment_image_src( $image, $size );

    if ( empty( $image ) ) {
      return false;
    }

    return array(
      'src'    => $image[0],
      'width'  => $image[1],
      'height' => $image[2],
    );
  }
}


// Draw responsive image markup

function pwp_responsive_image( $images, $class = '', $alt = '', $echo = true ) {
  $images = array_reverse( $images );
  $html_all = array();

  foreach ( $images as $media => $image ) {
    if ( 'fallback' !== $media ) {
      $html_all[] = '<source media="' . $media . '" srcset="' . $image . '">';
    } else {
      $fallback = $image;
      $html_all[] = '<source media="(min-width: 0px)" srcset="' . $image . '">';
    }
  }

  $html  = '';
  $html .= '<picture class="js-responsive responsive_image ' . $class . '">';
  $html .= '<!--[if IE 9]><video style="display: none;"><![endif]-->';
  $html .= implode( '', $html_all );

  $html .= '<!--[if IE 9]></video><![endif]-->';
  $html .= '<img src="' . $fallback . '" alt="' . $alt . '" draggable="false">';
  $html .= '</picture>';

  if ( $echo ) {
    echo $html;
  } else {
    return $html;
  }
}


// Get image orientation

function pwp_get_image_orientation( $image_id ) {
  $path = get_attached_file( $image_id );

  if ( empty( $path ) ) {
    return false;
  }

  list( $width, $height ) = getimagesize( $path );

  $ratio = $height / $width;
  echo '<!-- ' . $ratio . ' -->';

  if ( $ratio < 0.2 ) {
    return 'wide';
  } else if ( $ratio < 0.4 ) {
    return 'landscape';
  } else if ( $ratio <= 0.7 ) {
    return 'squarish';
  } else if ( $ratio <= 1 ) {
    return 'square';
  } else {
    return 'portrait';
  }
}


// Check min image size

function pwp_check_image_size( $image_id, $image_crop, $min_width = 0, $min_height = 0 ) {
  $details = pwp_get_image( $image_id, $image_crop );

  return ( $details['width'] >= $min_width && $details['height'] >= $min_height );
}


// Responsive Images

function pwp_image_media_element( $image_id ) {
  $wide_xsmall = pwp_get_image( $image_id, 'wide-xsmall' );
  $wide_small  = pwp_get_image( $image_id, 'wide-small' );
  $wide_medium = pwp_get_image( $image_id, 'wide-medium' );
  $wide_large  = pwp_get_image( $image_id, 'wide-large' );

  return array(
    'fallback'            => $wide_xsmall['src'],
    '(min-width: 500px)'  => $wide_small['src'],
    '(min-width: 980px)'  => $wide_medium['src'],
    '(min-width: 1220px)' => $wide_large['src'],
  );
}

function pwp_image_media_element_small( $image_id ) {
  $wide_xsmall = pwp_get_image( $image_id, 'wide-xsmall' );
  $wide_small  = pwp_get_image( $image_id, 'wide-small' );

  return array(
    'fallback'           => $wide_xsmall['src'],
    '(min-width: 500px)' => $wide_small['src'],
    '(min-width: 980px)' => $wide_xsmall['src'],
  );
}

function pwp_image_team_grid( $image_id ) {
  $tall_medium = pwp_get_image( $image_id, 'tall-medium' );

  return array(
    'fallback' => $tall_medium['src'],
  );
}

function pwp_image_team_bio( $image_id ) {
  $square_large   = pwp_get_image( $image_id, 'square-large' );
  $standard_small = pwp_get_image( $image_id, 'standard-small' );
  $tall_xsmall    = pwp_get_image( $image_id, 'tall-xsmall' );
  $tall_small     = pwp_get_image( $image_id, 'tall-small' );
  $tall_medium    = pwp_get_image( $image_id, 'tall-medium' );

  return array(
    'fallback'           => $square_large['src'],
    '(min-width: 500px)' => $standard_small['src'],
    '(min-width: 740px)' => $tall_medium['src'],
  );
}

function pwp_image_link_block( $image_id ) {
  $square_large = pwp_get_image( $image_id, 'square-large' );
  $wide_small   = pwp_get_image( $image_id, 'wide-small' );
  $tall_medium  = pwp_get_image( $image_id, 'tall-medium' );

  return array(
    'fallback'           => $square_large['src'],
    '(min-width: 500px)' => $wide_small['src'],
    '(min-width: 740px)' => $tall_medium['src'],
  );
}


// Background Images

function pwp_image_background_page_header( $image_id ) {
  $square_large    = pwp_get_image( $image_id, 'square-large' );
  $square_xlarge   = pwp_get_image( $image_id, 'square-xlarge' );
  $standard_medium = pwp_get_image( $image_id, 'standard-medium' );
  $standard_large  = pwp_get_image( $image_id, 'standard-large' );

  return array(
    'source' => array(
      '0px'   => $square_large['src'],
      '500px' => $square_xlarge['src'],
      '740px' => $standard_medium['src'],
      '980px' => $standard_large['src'],
    ),
  );
}
