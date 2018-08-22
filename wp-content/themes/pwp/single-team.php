<?php
global $header_transparent;

$header_transparent = true;

get_header();

if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();

    $position = get_field( 'bio_position' );
    $linkedin = get_field( 'bio_social_linkedin' );
    $email = get_field( 'bio_email' );
    $phone = get_field( 'bio_phone' );
    $image = get_field( 'bio_image' );
?>
<?php get_template_part( 'layouts/page_header', 'team_member' ); ?>
<div class="team_detail section_padded bg_white">
  <div class="fs-row fs-all-justify-center" data-checkpoint-animation="fade-up">
    <div class="padded_item fs-cell fs-md-half fs-lg-half">
      <?php pwp_responsive_image( pwp_image_team_bio( $image['id'] ), 'team_detail_image' ); ?>
    </div>
    <div class="padded_item fs-cell fs-md-half fs-lg-half">
      <div class="team_detail_content page_content">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
</div>
<?php get_template_part( 'layouts/partial-team_projects' ); ?>
<?php get_template_part( 'layouts/blocks', 'full_width' ); ?>
<?php
  endwhile;
endif;

get_footer();
