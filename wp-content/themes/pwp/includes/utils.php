<?php

// Formatted content

function pwp_get_the_content( $post_id = false ) {
  if ( ! empty( $post_id ) ) {
    $post = get_page( $post_id );
    $content = $post->post_content;
  } else {
    $content = get_the_content( '' );
  }

	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	return $content;
}


// Check external link

function pwp_external_link( $link ) {
  global $pwp_domain;

  return ( strpos( $link, $pwp_domain ) === false );
}


// Draw Link Target

function pwp_link_target( $link ) {
  return pwp_external_link( $link ) ? ' target="_blank"' : '';
}


// Get Template Part w/ Variables

function pwp_template_part( $template, $args ) {
  // $path = locate_template( $template . '.php', true, false );
  $path = PWP_THEME_DIR . '/' . $template . '.php';

  if ( file_exists( $path ) ) {
    extract( $args );

    include $path;
  }
}


// Main Nav

function pwp_main_navigation( $depth = 2 ) {
  $args = array(
    'theme_location' => 'main-navigation',
    'depth'          => $depth
  );

  wp_nav_menu( $args );
}


// // Sub Nav
//
// function pwp_sub_navigation( $depth = 2 ) {
//   $args = array(
//     'theme_location' => 'main-navigation',
//     'depth'          => $depth
//   );
//
//   wp_nav_menu( $args );
// }


// Highlight CPTs in menu

function pwp_get_cpt() {
  $types = array(
    'project',
    'team',
  );

  foreach ( $types as $type ) {
    if ( is_post_type_archive( $type ) || is_singular( $type ) ) {
      return $type;
    }
  }

  return false;
}

//

function pwp_menu_item_classes( $classes, $item, $args ) {
  global $pwp_domain;
  global $pwp_page_url;

  $cpt = pwp_get_cpt();

  $cpts_in_nav = array(
    'service'
  );

  if ( ! empty( $cpt ) ) {
    $bcn_options = get_option( 'bcn_options' );
    $root_id = $bcn_options['apost_' . $cpt . '_root'];

    $item_path = str_ireplace( $pwp_domain, '', $item->url );
    $current_path = str_ireplace( $pwp_domain, '', $pwp_page_url );

    if ( $item->object_id == $root_id ) {

      if ( in_array( $cpt, $cpts_in_nav ) ) {
        if ( is_singular( $cpt ) && $current_path == $item_path ) {
          $classes[] = 'current-menu-item';
        } elseif ( is_post_type_archive( $cpt ) ) {
          $classes[] = 'current-menu-item';
        }
      } else {
		    $classes[] = 'current-menu-item';
      }
    }

    if ( strpos( $pwp_page_url, $item_path ) !== false ) {
      $classes[] = 'current-menu-ancestor';
    }
  }

	return array_unique( $classes );
}
add_filter( 'nav_menu_css_class', 'pwp_menu_item_classes', 10, 3 );


// Footer Nav

function pwp_footer_navigation( $depth = 1 ) {
  $args = array(
    'theme_location' => 'footer-navigation',
    'depth'          => $depth
  );

  wp_nav_menu( $args );
}


// Sub Footer Nav

function pwp_subfooter_navigation( $depth = 1 ) {
  $args = array(
    'theme_location' => 'subfooter-navigation',
    'depth'          => $depth
  );

  wp_nav_menu( $args );
}


// Format text content

function pwp_format_content( $text = '', $echo = true ) {
  $find    = array( '[br]', '{', '}' );
  $replace = array( '<br>', '<strong>', '</strong>' );
  $string  = str_ireplace( $find, $replace, $text );

  if ( $echo ) {
    echo $string;
  } else {
    return $string;
  }
}


// JSON Options

function pwp_json_options( $options = array(), $echo = true ) {
  $string = htmlspecialchars( json_encode( $options ) );

  if ( $echo ) {
    echo $string;
  } else {
    return $string;
  }
}


// oEmbed Data

function pwp_get_oembed_url( $html ) {
  if ( empty( $html ) ) {
    return;
  }

  preg_match( '/src="([^"]+)"/', $html, $match );

  return $match[1];
}

function pwp_get_oembed_data( $url ) {
  $cache_key = 'pc_oembed-' . md5( $url );
  $oembed_data = get_transient( $cache_key );

  if ( empty( $oembed_data ) ) {
    $oembed_obj = _wp_oembed_get_object();
    $oembed_data = $oembed_obj->get_data( $url );

    set_transient( $cache_key, $oembed_data, (60 * 60 * 24 * 30) );
  }

  return $oembed_data;
}


// Trim Length (alternative to wp_trim_words)

function pwp_trim_length( $string = '', $length ) {
  $ns = '';
  $opentags = array();
  $string = trim( $string );
  if ( strlen( html_entity_decode( strip_tags( $string ) ) ) < $length ) {
    return $string;
  }
  if ( strpos( $string,' ' ) === false && strlen( html_entity_decode( strip_tags( $string ) ) ) > $length ) {
    return substr( $string,0,$length ).'&hellip;';
  }
  $x = 0;
  $z = 0;
  while ( $z < $length && $x <= strlen( $string ) ) {
    $char = substr( $string, $x, 1 );
    $ns .= $char; // Add the character to the new string.
    if ( '<' == $char ) {
      // Get the full tag -- but compensate for bad html to prevent endless loops.
      $tag = '';
      while ( '>' !== $char && false !== $char ) {
        $x++;
        $char = substr( $string, $x, 1 );
        $tag .= $char;
      }
      $ns .= $tag;

      $tagexp = explode( ' ',trim( $tag ) );
      $tagname = str_replace( '>','',$tagexp[0] );

      // If it's a self contained <br /> tag or similar, don't add it to open tags.
      if ( '/' != $tagexp[1] && '/>' != $tagexp[1] ) {
        // See if we're opening or closing a tag.
        if ( substr( $tagname,0,1 ) == '/' ) {
          $tagname = str_replace( '/','',$tagname );
          // We're closing the tag. Kill the most recently opened aspect of the tag.
          $done = false;
          reset( $opentags );
          while ( current( $opentags ) && ! $done ) {
            if ( current( $opentags ) == $tagname ) {
              unset( $opentags[ key( $opentags ) ] );
              $done = true;
            }
            next( $opentags );
          }
        } else {
          // Open a new tag.
          $opentags[] = $tagname;
        }
      }
    } elseif ( '&' == $char ) {
      $entity = '';
      while ( ';' != $char && ' ' != $char && '<' != $char ) {
        $x++;
        $char = substr( $string,$x,1 );
        $entity .= $char;
      }
      if ( ';' == $char ) {
        $z++;
        $ns .= $entity;
      } elseif ( ' ' == $char ) {
        $z += strlen( $entity );
        $ns .= $entity;
      } else {
        $z += strlen( $entity );
        $ns .= substr( $entity,0,-1 );
        $x -= 2;
      }
    } else {
      $z++;
    }
    $x++;
  }
  while ( $x < strlen( $string ) && ! in_array( substr( $string,$x,1 ),array( ' ', '!', '.', ',', '<', '&' ) ) ) {
    $ns .= substr( $string,$x,1 );
    $x++;
  }
  if ( strlen( strip_tags( $ns ) ) < strlen( strip_tags( $string ) ) ) {
    $ns .= '&hellip;';
  }
  $opentags = array_reverse( $opentags );
  foreach ( $opentags as $key => $val ) {
    $ns .= '</'.$val.'>';
  }
  return $ns;
}
