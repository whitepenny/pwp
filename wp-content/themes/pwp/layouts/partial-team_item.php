<?php
$post_image = get_field( 'post_image' );
$position = get_field( 'bio_position' );
$image = get_field( 'bio_image' );
?>
<div class="team_item padded_item padded_item_sm_half fs-cell fs-xs-full fs-sm-half fs-md-half fs-lg-half" data-checkpoint-animation="fade-up">
  <a href="<?php the_permalink(); ?>" class="team_item_link">
    <div class="team_item_image">
      <?php pwp_responsive_image( pwp_image_team_grid( $image['ID'] ), '' ); ?>
      <span class="team_item_label button_arrow">
        Read More
      </span>
    </div>
    <div class="team_item_content">
      <h2 class="team_item_name"><?php the_title(); ?></h2>
      <span class="team_item_position"><?php echo $position; ?></span>
    </div>
  </a>
</div>
