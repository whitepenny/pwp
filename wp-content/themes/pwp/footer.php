<?php
$main_title = get_bloginfo( 'name' );

$address = get_field( 'global_address', 'option' );
$phone_number = get_field( 'global_phone_number', 'option' );
$email_address = get_field( 'global_email_address', 'option' );
$social_links = get_field( 'global_social_links', 'option' );
?>
        </main>
      </div>
    </div>
    <footer class="footer">
      <div class="fs-row fs-all-justify-center" data-checkpoint-animation="fade-in">
        <div class="fs-cell fs-md-half fs-lg-3 footer_column footer_address">
          <?php if ( ! empty( $address ) ) : ?>
          <?php echo $address; ?>
          <?php endif; ?>
        </div>
        <div class="fs-cell fs-md-half fs-lg-3 footer_column footer_contact">
          <?php if ( ! empty( $phone_number ) ) : ?>
          <a href="tel:<?php echo $phone_number; ?>" class="footer_link"><?php echo $phone_number; ?></a>
          <?php endif; ?>
          <?php if ( ! empty( $email_address ) ) : ?>
          <a href="tel:<?php echo $email_address; ?>" class="footer_link"><?php echo $email_address; ?></a>
          <?php endif; ?>
        </div>
        <div class="fs-cell fs-sm-half fs-md-half fs-lg-2 footer_column footer_nav">
          <?php pwp_main_navigation( 1 ); ?>
        </div>
        <div class="fs-cell fs-sm-half fs-md-half fs-lg-2 footer_column footer_social">
          <?php foreach ( $social_links as $social_link ) : ?>
          <a href="<?php echo $social_link['link']; ?>" class="footer_social_link" target="_blank">
            <?php echo ucwords( $social_link['service'] ); ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </footer>

    <?php wp_footer(); ?>

  </body>
</html>
