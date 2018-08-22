<?php
$video = get_field( 'video' );
$image = get_field( 'image' );
$intro = get_field( 'intro' );
$link = get_permalink();

$video_url = pwp_get_oembed_url( $video );
?>
<div class="fs-row fs-md-justify-center project_item" data-checkpoint-animation="fade-up" data-checkpoint-container=".team_grid">
  <div class="fs-cell fs-md-5 fs-lg-6 fs-xl-7">
    <a href="<?php echo $video_url; ?>" class="project_item_media media_link js-lightbox">
      <?php pwp_responsive_image( pwp_image_media_element( $image['ID'] ), 'media_image' ); ?>
      <span class="media_icon">
        <span class="icon play_mint"></span>
      </span>
    </a>
  </div>
  <div class="fs-cell fs-md-5 fs-lg-6 fs-xl-5 project_item_container">
    <h2 class="project_item_title"><?php the_title(); ?></h2>
    <div class="project_item_content page_content">
      <p><?php echo $intro; ?></p>
    </div>
    <a href="<?php echo $link; ?>" class="project_item_link link_arrow">
      See Project
    </a>
  </div>
</div>
