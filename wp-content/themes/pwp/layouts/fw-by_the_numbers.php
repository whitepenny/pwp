<?php
$title = get_sub_field( 'title' );
$content = get_sub_field( 'content' );
$items = get_sub_field( 'items' );
?>
<div class="numbers_block section_padded">
  <div class="fs-row fs-all-justify-center numbers_block_row">
    <div class="fs-cell fs-md-5 fs-lg-10 fs-xl-8 numbers_block_cell">
      <h2 class="numbers_block_title section_title"><?php echo $title; ?></h2>
      <?php if ( ! empty( $content ) ) : ?>
      <div class="numbers_block_content page_content section_intro">
        <p class="intro"><?php echo pwp_format_content( $content ); ?></p>
      </div>
      <?php endif; ?>
    </div>
    <div class="fs-cell numbers_block_items">
      <?php
        foreach ( $items as $item ) :
      ?>
      <figure class="numbers_block_item">
        <span class="numbers_block_item_figure"><?php echo $item['figure']; ?></span>
        <span class="numbers_block_item_label"><?php echo $item['label']; ?></span>
      </figure>
      <?php
        endforeach;
      ?>
    </div>
  </div>
</div>
