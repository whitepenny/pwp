<?php
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$items = array(
  'post_type' => 'project',
  'posts_per_page' => 20,
  'orderby' => 'menu_order',
  'order' => 'ASC',
  'paged' => $paged,
);

if ( ! empty( $items ) ) :
?>
<div class="project_list section_padded bg_white">
  <?php
    
    $itemQuery = new WP_Query( $items );
    $temp_query = $wp_query;
    $wp_query   = NULL;
    $wp_query   = $itemQuery;
    
    if($itemQuery->have_posts()):
      while($itemQuery->have_posts()) : $itemQuery->the_post();

      get_template_part( 'layouts/partial-project_item' );
      
      endwhile;
    endif;

    echo '<div class="fs-row">';
    echo '<div class="fs-cell fs-all-12">';
    
    $paginationArgs = array('prev_text' => __( '<i class="fa fa-chevron-left"></i>', 'textdomain' ),
        'next_text' => __( '<i class="fa fa-chevron-right"></i>', 'textdomain' ) );

    the_posts_pagination($paginationArgs);
    echo '</div>';
    echo '</div>';

    $wp_query = NULL;
    $wp_query = $temp_query;
    wp_reset_postdata();
  ?>
</div>
<?php
else:
?>
<p>Sorry, no projects found.</p>
<?php
endif;
