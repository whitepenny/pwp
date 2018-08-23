<?php
$label = get_sub_field( 'label' );
$title = get_sub_field( 'title' );
$content = get_sub_field( 'content' );
$button_1 = get_sub_field( 'button_1' );
$button_2 = get_sub_field( 'button_2' );
$clients = get_sub_field( 'clients' );
?>
<div class="clients_block section_padded">
  <h2 class="section_label project_grid_label"><?php echo $label; ?></h2>
  <div class="fs-row fs-all-justify-center clients_block_row" data-checkpoint-animation="fade-up">
    <div class="padded_item padded_item_md_full fs-cell fs-lg-7 clients_block_cell">
      <h3 class="clients_block_title section_title"><?php echo pwp_format_content( $title ); ?></h3>
      <?php if ( ! empty( $content ) ) : ?>
      <div class="clients_block_content page_content">
        <p class="intro"><?php echo $content; ?></p>
      </div>
      <?php endif; ?>
    </div>
    <div class="padded_item padded_item_md_full fs-cell fs-lg-5">
      <div class="clients_block_links">
        <?php if ( ! empty( $button_1 ) ) : ?>
        <a href="<?php echo $button_1['url']; ?>" class="clients_block_link button_arrow">
          <?php echo $button_1['title']; ?>
        </a>
        <?php endif; ?>
        <?php if ( ! empty( $button_2 ) ) : ?>
        <a href="<?php echo $button_2['url']; ?>" class="clients_block_link button_arrow button_arrow_black">
          <?php echo $button_2['title']; ?>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <div class="fs-cell fs-lg-11">
      <div class="clients_items">
        <?php
          foreach ( $clients as $client ) :
            $image = pwp_get_image( $client['logo']['ID'], 'scaled-small' );
        ?>
        <span class="clients_item">
          <?php if ( ! empty( $client['link'] ) ) : ?>
          <a target="_blank" href="<?php echo $client['link']['url']; ?>" class="clients_item_link">
            <img src="<?php echo $image['src']; ?>" alt="" class="clients_item_image">
          </a>
          <?php else : ?>
          <img src="<?php echo $image['src']; ?>" alt="" class="clients_item_image">
          <?php endif; ?>
        </span>
        <?php
          endforeach;
        ?>
      </div>
    </div>
  </div>
</div>
