<?php
$items = get_sub_field( 'items' );

if ( ! empty( $items ) ) :
?>
<div class="media_columns section_padded bg_white">
  <div class="fs-row fs-all-justify-center media_columns_row">
    <div class="fs-cell fs-md-5 fs-lg-10 fs-xl-10 media_columns_cell">
      <div class="fs-row media_columns_items">
        <?php
          foreach ( $items as $item ) :
            $video_url = pwp_get_oembed_url( $item['video'] );
        ?>
        <div class="fs-cell fs-md-3 fs-lg-6 media_columns_item" data-checkpoint-animation="fade-up">
          <?php if ( ! empty( $video_url ) ) : ?>
          <a href="<?php echo $video_url; ?>" class="media_link js-lightbox">
          <?php endif; ?>
            <?php pwp_responsive_image( pwp_image_media_element_small( $item['image']['ID'] ), 'media_image' ); ?>
          <?php if ( ! empty( $video_url ) ) : ?>
            <span class="media_icon">
              <span class="icon play_mint"></span>
            </span>
          </a>
          <?php endif; ?>
          <?php if ( ! empty( $item['content'] ) ) : ?>
          <p class="media_caption"><?php echo $item['content']; ?></p>
          <?php endif; ?>
        </div>
        <?php
          endforeach;
        ?>
      </div>
    </div>
  </div>
</div>
<?php
endif;
