
<?php if ( is_search() ) : ?>
    <div class="c-col--12 c-col__md--8 c-col__lg--7 c-col__xl--6">
      <p class="c-typ lg:text-center">お探しのページが見つかりませんでした。<br>検索ワードに間違いがなければ<br class="sm:hidden">他のキーワードでお試しください。</p>

      <div class="mt-4">
          <?php  get_search_form(); ?>
        </div>

    </div>

<?php else : ?>
  <div class="c-col--12">
      <p>このページでは、<?php wp_title(''); ?>をご案内します。<br>現在準備中ですので、しばらくお待ちください。</p>
  </div>

<?php endif; ?>
