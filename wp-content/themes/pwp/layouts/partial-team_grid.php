<?php

$items = get_posts( array(
  'post_type' => 'team',
  'numberposts' => -1,
  'orderby' => 'menu_order',
  'order' => 'ASC',
) );

if ( ! empty( $items ) ) :
?>
<div class="fs-row team_grid section_padded bg_white">
  <?php
    foreach ( $items as $post ) :
      setup_postdata( $post );
      // the_post();
      get_template_part( 'layouts/partial-team_item' );
    endforeach;
    wp_reset_postdata();
  ?>
</div>
<?php
else:
?>
<p>Sorry, no team members found.</p>
<?php
endif;
