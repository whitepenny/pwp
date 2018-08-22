<?php
$content = get_sub_field( 'content' );
?>
<div class="content_break section_padded bg_white">
  <div class="fs-row fs-all-justify-center content_break_row" data-checkpoint-animation="fade-up">
    <div class="fs-cell fs-md-5 fs-lg-9 fs-xl-7 content_break_cell">
      <?php if ( ! empty( $content ) ) : ?>
      <div class="content_break_content page_content">
        <?php echo $content; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
