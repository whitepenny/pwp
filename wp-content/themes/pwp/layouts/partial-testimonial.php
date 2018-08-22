<?php
$background_options = pwp_image_background_page_header( $image['ID'] );
?>
<div class="testimonial_block js-background" data-background-options="<?php echo pwp_json_options( $background_options ); ?>">
  <div class="fs-row fs-all-justify-center testimonial_block_row">
    <div class="fs-cell fs-md-5 fs-lg-9 fs-xl-8 testimonial_block_cell" data-checkpoint-animation="fade-up">
      <blockquote class="testimonial_block_blockquote">
        <p class="testimonial_block_quote section_title"><?php echo $quote; ?></p>
        <?php if ( ! empty( $content ) ) : ?>
        <div class="testimonial_block_content page_content">
          <p class="intro"><?php echo $content; ?></p>
        </div>
        <?php endif; ?>
        <footer class="testimonial_block_author">
          <cite class="testimonial_block_cite"><?php echo $author; ?></cite>
        </footer>
      </blockquote>
    </div>
  </div>
</div>
