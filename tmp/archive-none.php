
<?php if ( is_search() ) : ?>
    <div class="w-full md:w-8/12 lg:w-7/12 xl:w-6/12">
      <p class="p-typ lg:text-center">お探しのページが見つかりませんでした。<br>検索ワードに間違いがなければ<br class="sm:hidden">他のキーワードでお試しください。</p>

      <div class="mt-4">
          <?php  get_search_form(); ?>
        </div>

    </div>

<?php else : ?>
  <div class="w-full">
      <p>このページでは、<?php wp_title(''); ?>をご案内します。<br>現在準備中ですので、しばらくお待ちください。</p>
  </div>

<?php endif; ?>
