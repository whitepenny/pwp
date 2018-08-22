<?php
$title = get_sub_field( 'title' );
$content = get_sub_field( 'content' );
$items = get_sub_field( 'items' );
?>
<div class="home_intro section_padded bg_white">
  <div class="fs-row fs-all-justify-center home_intro_row" data-checkpoint-animation="fade-up">
    <div class="fs-cell fs-md-5 fs-lg-9 fs-xl-8 content_intro_cell home_intro_cell">
      <span class="icon badge_slogan home_intro_icon"></span>
      <h3 class="home_intro_title section_title"><?php echo pwp_format_content( $title ); ?></h3>
      <?php if ( ! empty( $content ) ) : ?>
      <div class="home_intro_content page_content">
        <p class="intro"><?php echo $content; ?></p>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="fs-row fs-all-justify-center home_intro_row link_blocks">
    <?php
      foreach ( $items as $item ) :
    ?>
    <div class="link_block padded_item fs-cell fs-md-half fs-lg-half" data-checkpoint-animation="fade-up">
      <a href="<?php echo $item['link']['url']; ?>" class="link_block_link">
        <div class="link_block_image">
          <?php pwp_responsive_image( pwp_image_link_block( $item['image']['ID'] ), '' ); ?>
        </div>
        <div class="link_block_container">
          <h3 class="link_block_title"><?php echo $item['link']['title']; ?></h3>
          <span class="link_block_icon">
            <span class="icon arrow_mint"></span>
          </span>
        </div>
      </a>
    </div>
    <?php
      endforeach;
    ?>
  </div>
</div>
