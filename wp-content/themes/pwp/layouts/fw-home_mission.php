<?php
$title = get_sub_field( 'title' );
$content = get_sub_field( 'content' );
$badges = get_sub_field( 'badges' );
?>
<div class="content_intro section_padded bg_white">
  <div class="fs-row fs-all-justify-center content_intro_row" data-checkpoint-animation="fade-up">
    <div class="fs-cell fs-md-5 fs-lg-10 fs-xl-10 content_intro_cell">
      <span class="icon badge_logo content_intro_badge"></span>
      <h2 class="content_intro_title section_title">
        <?php echo pwp_format_content( $title ); ?>
      </h2>
      <?php if ( ! empty( $content ) ) : ?>
      <div class="content_intro_content page_content section_intro">
        <p class="intro"><?php echo $content; ?></p>
      </div>
      <?php endif; ?>
      <div class="content_badges">
        <?php
          foreach ( $badges as $badge ) :
            $image = pwp_get_image( $badge['badge']['ID'], 'scaled-small' );
        ?>
        <span class="content_badge">
          <?php if ( ! empty( $badge['link'] ) ) : ?>
          <a target="_blank" href="<?php echo $badge['link']['url']; ?>" class="content_badge_link">
            <img src="<?php echo $image['src']; ?>" alt="" class="content_badge_image">
          </a>
          <?php else : ?>
          <img src="<?php echo $image['src']; ?>" alt="" class="content_badge_image">
          <?php endif; ?>
        </span>
        <?php
          endforeach;
        ?>
      </div>
    </div>
  </div>
</div>
