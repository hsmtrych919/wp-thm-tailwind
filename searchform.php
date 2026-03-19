<div class="p-search">
  <form role="search" method="get" class="p-search__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <input type="search" class="p-search__control" placeholder="<?php echo esc_attr_x( '検索キーワードを入力', 'placeholder' ) ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ); ?>">
      <button type="submit" class="p-search__submit">
        <svg class="p-search__icon" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"></path></svg>
      </button>

<input type="hidden" name="post_type" value="post">

  </form>
</div>