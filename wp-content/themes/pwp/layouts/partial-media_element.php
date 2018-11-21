<?php
if ( ! empty( $video ) ) :
  $video_url = pwp_get_oembed_url( $video );
endif;
?>

<?php if (! $hide == true): ?>
  

<div class="media_element section_padded bg_white">
  <div class="fs-row fs-all-justify-center media_element_row" data-checkpoint-animation="fade-up">
    <div class="fs-cell fs-md-5 fs-lg-10 fs-xl-10 media_element_cell">
      <?php if ( ! empty( $video_url ) ) : ?>
      <a href="<?php echo $video_url; ?>" class="media_link js-lightbox">
      <?php endif; ?>
        <?php pwp_responsive_image( pwp_image_media_element( $image['ID'] ), 'media_image' ); ?>
      <?php if ( ! empty( $video_url ) ) : ?>
        <span class="media_icon">
          <span class="icon play_mint"></span>
        </span>
      </a>
      <?php endif; ?>
      <?php if ( ! empty( $content ) ) : ?>
      <p class="media_caption"><?php echo $content; ?></p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php endif ?>
