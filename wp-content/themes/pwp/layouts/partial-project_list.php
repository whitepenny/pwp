<?php

$items = get_posts( array(
  'post_type' => 'project',
  'numberposts' => -1,
  'orderby' => 'menu_order',
  'order' => 'ASC',
) );

if ( ! empty( $items ) ) :
?>
<div class="project_list section_padded bg_white">
  <?php
    foreach ( $items as $post ) :
      setup_postdata( $post );

      get_template_part( 'layouts/partial-project_item' );
    endforeach;
    wp_reset_postdata();
  ?>
</div>
<?php
else:
?>
<p>Sorry, no projects found.</p>
<?php
endif;
