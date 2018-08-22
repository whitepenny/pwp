<?php
$title = get_sub_field( 'title' );
$content = get_sub_field( 'content' );
$button = get_sub_field( 'button' );
$list = get_sub_field( 'list' );
?>
<div class="content_list section_padded bg_white">
  <div class="fs-row fs-all-justify-center content_list_row" data-checkpoint-animation="fade-up">
    <div class="padded_item fs-cell fs-md-half fs-lg-7 content_list_cell">
      <h2 class="content_list_title section_title"><?php echo pwp_format_content( $title ); ?></h2>
      <?php if ( ! empty( $content ) ) : ?>
      <div class="content_list_content page_content">
        <p class="intro"><?php echo $content; ?></p>
      </div>
      <?php endif; ?>
      <?php if ( ! empty( $button ) ) : ?>
      <a href="<?php echo $button['url']; ?>" class="content_list_link button_arrow">
        <?php echo $button['title']; ?>
      </a>
      <?php endif; ?>
    </div>
    <div class="padded_item page_content fs-cell fs-md-half fs-lg-5">
      <ul class="content_list_items">
        <?php
          foreach ( $list as $item ) :
        ?>
        <li class="content_list_item"><?php echo $item['text']; ?></li>
        <?php
          endforeach;
        ?>
      </ul>
    </div>
  </div>
</div>
