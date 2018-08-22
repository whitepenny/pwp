<?php
/*
Template Name: Contact
*/

get_header();

if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();

    $image = get_field( 'image' );
    $form = get_field( 'gravity_form' );

    $address = get_field( 'global_address', 'option' );
    $phone_number = get_field( 'global_phone_number', 'option' );
    $email_address = get_field( 'global_email_address', 'option' );
?>
<?php get_template_part( 'layouts/page_header' ); ?>

<?php
  pwp_template_part( 'layouts/partial-media_element', array(
    'image' => $image,
  ) );
?>

<div class="contact_info">
  <div class="fs-row fs-all-justify-center contact_info_row">
    <div class="fs-cell fs-md-4 fs-lg-8 fs-xl-7 contact_info_cell page_content">

      <div class="contact_info_block">
        <p>
          <?php if ( ! empty( $address ) ) : ?>
          <?php echo $address; ?>
          <?php endif; ?>
        </p>
      </div>
      <div class="contact_info_block">
        <p>
          <?php if ( ! empty( $phone_number ) ) : ?>
          <a href="tel:<?php echo $phone_number; ?>" class="footer_link"><?php echo $phone_number; ?></a>
          <?php endif; ?>
          <br>
          <?php if ( ! empty( $email_address ) ) : ?>
          <a href="mailto:<?php echo $email_address; ?>" class="footer_link"><?php echo $email_address; ?></a>
          <?php endif; ?>
        </p>
      </div>

    </div>
  </div>
</div>

<?php
  pwp_template_part( 'layouts/partial-gravity_form', array(
    'form' => $form,
  ) );
?>

<?php
  endwhile;
endif;

get_footer();
