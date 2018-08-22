<?php
if ( ! empty( $form ) && function_exists( 'gravity_form' ) ) :
?>
<div class="gravityform_block section_padded">
  <div class="fs-row fs-all-justify-center gravityform_block_row">
    <div class="fs-cell fs-md-5 fs-lg-10 fs-xl-9 gravityform_block_cell">

      <div class="gravityform_block_container gravityform_container" data-checkpoint-animation="fade-up">
        <?php gravity_form( $form, false, false ); ?>
      </div>

    </div>
  </div>
</div>
<?php
endif;
