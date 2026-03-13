
<?php
// var_dump( get_the_term_list($post->ID,"staff_salon") );
$staff_birth = get_field('staff_birth');
$staff_from = get_field('staff_from');
$staff_blood = get_field('staff_blood')[0];
$staff_hobby = get_field('staff_hobby');
$staff_comment = get_field('staff_comment');
$staff_salon = get_the_terms($post->ID,"staff_salon");
$salon_url = '';
if ($staff_salon && !is_wp_error($staff_salon)) {
  $first_term = reset($staff_salon);
  $staff_salon_slug = $first_term->slug;
  $salon_url = home_url('/salon/' . $staff_salon_slug);
};

function staffName() {
  echo '<p class="p-staff__name--position">' . get_field('staff_position') . '</p>';
  echo '<p class="p-staff__name">' . get_the_title() . '</p>';
  echo '<p class="p-staff__name--ruby">' . get_field('staff_ruby') . '</p>';
}

?>

<article class="" id="post-<?php the_ID(); ?>">
<div class="c-headline__outer">
    <div class="l-row--container c-gutter__row jc__start">
      <div class="c-headline__frame" >
        <h1 class="c-headline__title"><span class="">STAFF</span></h1>
      </div>
    </div>
  </div>

  <div class="p-staff__content">
  <div class="l-row--container c-gutter__row jc__start">
    <div class="p-staff__frame">
        <div class="p-staff__name-wrap--sp"><?php staffName() ?></div>

      <div class="p-staff__pic">
        <p class=""><?php echo wp_get_attachment_image( get_post_meta( $post->ID, 'staff_img', true ), 'full' ); ?></p>
      </div>


      <div class="p-staff__detail">
        <div class="p-staff__name-wrap--pc"><?php staffName() ?></div>

        <dl class="p-staff-detail__dl">
<?php
        if (isset($staff_birth) && $staff_birth !== '' ) {
          echo '<div class="p-staff-detail__dl--row">';
          echo '<dt>生年月日</dt>';
          echo '<dd>' . $staff_birth . '</dd>';
          echo '</div>';
        }
        if (isset($staff_from) && $staff_from !== '' ) {
          echo '<div class="p-staff-detail__dl--row">';
          echo '<dt>出身</dt>';
          echo '<dd>' . $staff_from . '</dd>';
          echo '</div>';
        }
        if (isset($staff_blood) && $staff_blood !== '' ) {
          echo '<div class="p-staff-detail__dl--row">';
          echo '<dt>血液型</dt>';
          echo '<dd>' . $staff_blood . '</dd>';
          echo '</div>';
        }
        if (isset($staff_hobby) && $staff_hobby !== '' ) {
          echo '<div class="p-staff-detail__dl--row">';
          echo '<dt>趣味</dt>';
          echo '<dd>' . $staff_hobby . '</dd>';
          echo '</div>';
        }
        if (isset($staff_comment) && $staff_comment !== '' ) {
          echo '<div class="p-staff-detail__dl--row">';
          echo '<dt>コメント</dt>';
          echo '<dd>' . $staff_comment . '</dd>';
          echo '</div>';
        }
?>
          </dl>
        <div class="p-staff__button">
<?php
  $args = array(
    'link_slug' => 'salon/' . $staff_salon_slug,
    'className' => 'c-button__grd',
    'text' => '勤務サロンへ',
);
component_buttonLink($args);
?>
        </div>
      </div>


    </div>
</div>
<!-- /.l-row -->
</div>

</article>


<?php get_template_part('tmp/content/article', 'more'); ?>