<?php
$projects = get_field( 'projects' );

if ( ! empty( $projects ) ) :
?>
<div class="project_grid">
  <h2 class="section_label project_grid_label">Projects</h2>
  <div class="fs-row project_grid_row">
    <?php
      foreach ( $projects as $project ) :
        $video = get_field( 'video', $project->ID );
        $image = get_field( 'image', $project->ID );
        $sector = get_field( 'sector', $project->ID );
        $link = get_permalink( $project->ID );

        $video_url = pwp_get_oembed_url( $video );
    ?>
    <div class="project_grid_item padded_item fs-cell fs-md-half fs-lg-half">
      <a href="<?php echo $video_url; ?>" class="project_grid_item_media media_link js-lightbox">
        <?php pwp_responsive_image( pwp_image_media_element( $image['ID'] ), 'media_image' ); ?>
        <span class="media_icon">
          <span class="icon play_mint"></span>
        </span>
      </a>
      <a href="<?php echo $link; ?>" class="project_grid_item_link">
        <h3 class="project_grid_item_title"><?php echo $project->post_title; ?></h3>
        <span class="project_grid_item_label"><?php echo $sector; ?></span>
      </a>
    </div>
    <?php
      endforeach;
    ?>
  </div>
</div>
<?php
endif;
