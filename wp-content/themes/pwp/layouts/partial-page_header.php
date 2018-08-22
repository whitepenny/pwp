<?php
if ( $page_type == 'image' || $page_type == 'home' ||  $page_type == 'team' ) :
  if ( ! empty( $page_video ) ) :
    $image_file = pwp_get_image( $page_image['ID'], 'wide-large' );

    $background_options = array(
      'source' => array(
        'mp4' => $page_video,
        'poster' => $image_file['src'],
      ),
    );
  else :
    $background_options = pwp_image_background_page_header( $page_image['ID'] );
  endif;
?>
<div class="page_header <?php echo $page_type; ?>_header js-background" data-background-options="<?php echo pwp_json_options( $background_options ); ?>">
<?php
else:
?>
<div class="page_header bg_white">
<?php
endif;
?>
  <div class="fs-row fs-all-justify-center page_header_row" data-checkpoint-animation="fade-up">
    <div class="fs-cell fs-md-5 fs-lg-10 fs-xl-10 page_header_cell">
      <h1 class="page_title">
        <?php echo pwp_format_content( $page_title ); ?>
      </h1>
      <?php if ( ! empty( $page_subtitle ) ) : ?>
      <h2 class="page_subtitle">
        <?php echo pwp_format_content( $page_subtitle ); ?>
      </h2>
      <?php endif; ?>
      <?php if ( ! empty( $page_intro ) ) : ?>
      <div class="page_content page_intro">
        <p class="intro"><?php echo pwp_format_content( $page_intro ); ?></p>
      </div>
      <?php endif; ?>
      <?php if ( ! empty( $page_button ) ) : ?>
      <a href="<?php echo $page_button['url']; ?>" class="page_button button_arrow">
        <?php echo $page_button['title']; ?>
      </a>
      <?php endif; ?>
    </div>
  </div>
</div>
