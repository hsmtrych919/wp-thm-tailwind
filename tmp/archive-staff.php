
<li>
<?php
$classForItem = get_field_object( 'staff_single' )['value'] ? '' : ' ignore';
$classForPic = get_field_object( 'staff_single' )['value'] ? ' is-link' : '';
?>
<a href='<?php the_permalink(); ?>' class="thumbnail-list__item<?php echo esc_attr($classForItem); ?>">
    <p class="thumbnail-list__pic<?php echo esc_attr($classForPic); ?>"><?php echo wp_get_attachment_image( get_post_meta( $post->ID, 'staff_img', true ), 'full' ); ?></p>
    <p class="stafflist__name"><?php the_title(); ?></p>
    <p class="stafflist__position"><?php the_field( 'staff_position' ); ?></p>
  </a>
</li>
