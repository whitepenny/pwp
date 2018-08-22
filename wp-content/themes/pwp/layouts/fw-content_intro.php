<?php
$label = get_sub_field( 'label' );
$title = get_sub_field( 'title' );
$content = get_sub_field( 'content' );
?>
<div class="content_intro section_padded bg_white">
  <div class="fs-row fs-all-justify-center content_intro_row" data-checkpoint-animation="fade-up">
    <div class="fs-cell fs-md-5 fs-lg-10 fs-xl-8 content_intro_cell">
      <?php if ( ! empty( $label ) ) : ?>
      <span class="content_intro_label">
        <?php echo $label; ?>
      </span>
      <?php endif; ?>
      <h2 class="content_intro_title section_title">
        <?php echo pwp_format_content( $title ); ?>
      </h2>
      <?php if ( ! empty( $content ) ) : ?>
      <div class="content_intro_content page_content section_intro">
        <p class="intro"><?php echo $content; ?></p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
