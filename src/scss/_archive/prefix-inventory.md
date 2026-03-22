# 接頭辞利用箇所 事前調査記録

作成日: 2026-03-22
目的: scss-directory-restructure-plan-v2.md Step 1

---

## 定義記録（SCSS）

行番号なし。ファイル移動・統合で無効になるため。

### l- 接頭辞

| クラス名 | 定義ファイル |
|---|---|
| `.l-header` | `layout/_header.scss` |
| `.l-header.js-scroll` | `layout/_header.scss` |
| `.l-header.js-open` | `layout/_header.scss` |
| `.l-header.js-scroll--up` | `layout/_header.scss` |
| `.l-nav` | `layout/_header.scss` |
| `.l-footer` | `layout/_footer.scss` |
| `.l-footernav` | `layout/_footer.scss` |
| `.l-main` | `layout/_content.scss` |
| `.l-blog__main` | `layout/_content.scss` |
| `.l-blog__sidebar` | `layout/_content.scss` |
| `.l-container-py` | `layout/_content.scss` |
| `.l-container-py--blog` | `layout/_content.scss`（`&--blog`） |
| `.l-container-py--search` | `layout/_content.scss`（`&--search`） |
| `.l-split__outer` | `layout/_content.scss` |
| `.l-split__outer--reverse` | `layout/_content.scss`（`&--reverse`） |
| `%l-split__thumbnail` | `layout/_content.scss`（placeholder） |
| `.l-split__thumbnail--left` | `layout/_content.scss`（`&--left`） |
| `.l-split__thumbnail--right` | `layout/_content.scss`（`&--right`） |
| `%l-split__content` | `layout/_content.scss`（placeholder） |
| `.l-split__content--left` | `layout/_content.scss`（`&--left`） |
| `.l-split__content--right` | `layout/_content.scss`（`&--right`） |
| `.l-split__content--mt` | `layout/_content.scss` |
| `.l-container` | `tailwind-base.css`（横幅制御） |

#### l- のクロスファイル参照

| 参照元 | 参照クラス | 備考 |
|---|---|---|
| `project/_header.scss` | `.l-header.js-open` | L476: project 側から layout クラスを参照 |
| `component/_login.scss` | `.l-main__nav-less` | L17: ログインページで使用 |

### c- 接頭辞

| クラス名 | 定義ファイル |
|---|---|
| `.c-micromodal__outer` | `component/micromodal/_micromodal.scss` |
| `.c-micromodal` | `component/micromodal/_micromodal.scss` |
| `.c-micromodal__overlay` | `component/micromodal/_micromodal.scss` |
| `.c-micromodal__close` | `component/micromodal/_micromodal.scss`（推定、要確認） |
| `.c-micromodal__container` | `component/micromodal/_micromodal.scss`（推定、要確認） |
| `.c-button` | `component/_button.scss` |
| `.c-button__icon--arrow` | `component/_button.scss` |
| `.c-button__icon--external` | `component/_button.scss` |
| `.c-button__icon--tel` | `component/_button.scss` |
| `.c-button__grd` | `component/_button.scss` |
| `.c-button__clr1` | `component/_button.scss` |
| `.c-button__clr1--border` | `component/_button.scss` |
| `.c-button__clr1--toc` | `component/_button.scss` |
| `.c-button__clr2` | `component/_button.scss` |
| `.c-button__clr2--border` | `component/_button.scss` |
| `.c-button__clr3` | `component/_button.scss` |
| `.c-button__rakuten` | `component/_button.scss` |
| `.c-button__amazon` | `component/_button.scss` |
| `.c-button__tel--salon` | `component/_button.scss` |
| `.c-button__tel--family` | `component/_button.scss` |
| `.c-button--thin` | `component/_button.scss`（mixin 内） |
| `.c-gmap` | `component/_google-map.scss` |
| `.c-gmap__link` | `component/_google-map.scss` |
| `.c-pagenation` | `component/_pagenation.scss` |
| `.c-pagenation__icon` | `component/_pagenation.scss` |
| `.c-replace` | `component/_style.scss` |
| `.c-replace__content--left` | `component/_style.scss` |
| `.c-replace__content--right` | `component/_style.scss` |
| `.c-replace__pic--left` | `component/_style.scss` |
| `.c-replace__pic--right` | `component/_style.scss` |
| `.c-replace__detail` | `component/_style.scss` |
| `.c-replace__flex-start` | `component/_style.scss` |
| `.c-replace__flex-end` | `component/_style.scss` |
| `.c-button-2columns` | `component/_style.scss` |
| `.c-button-2columns__frame--button` | `component/_style.scss` |
| `.c-button-2columns__button` | `component/_style.scss` |
| `.c-button-2columns__button--low` | `component/_style.scss` |
| `.c-likepost` | `component/_style.scss` |
| `.c-likepost__item` | `component/_style.scss` |
| `.c-likepost__detail` | `component/_style.scss` |
| `.c-likepost__arrow` | `component/_style.scss` |
| `.c-likepost__thumbnail` | `component/_style.scss` |
| `.c-likepost__content` | `component/_style.scss` |
| `.c-likepost__lead` | `component/_style.scss` |
| `.c-likepost__list` | `component/_style.scss`（推定） |
| `.c-more` | `component/_style.scss` |
| `.c-categorylist` | `component/_style.scss` |
| `.c-thumbnail-list` | `component/_style.scss` |
| `.c-thumbnail-list__pic.is-link` | `component/_style.scss` |
| `.c-thumbnail-list__item` | `component/_style.scss`（推定） |

注: `_login.scss` には `c-` 接頭辞のクラス定義なし。WordPress ログインページ用の `body.login`, `#login` 等のスタイルのみ。

### p- 接頭辞

| クラス名 | 定義ファイル |
|---|---|
| `.p-header` 系 | `project/_header.scss` |
| `.p-header__row` | `project/_header.scss` |
| `.p-header__logo-block` | `project/_header.scss` |
| `.p-header__logo` | `project/_header.scss` |
| `.p-toolbar` 系 | `project/_header.scss` |
| `.p-toolbar__button` | `project/_header.scss` |
| `.p-toolbar__line` | `project/_header.scss` |
| `.p-toolbar__subtitle` | `project/_header.scss` |
| `.p-toolbar__login--typ` | `project/_header.scss` |
| `.p-nav__overlay` | `project/_header.scss` |
| `.p-nav` | `project/_header.scss` |
| `.p-nav__list` | `project/_header.scss` |
| `.p-nav__item` 系 | `project/_header.scss` |
| `.p-nav__button` 系 | `project/_header.scss` |
| `.p-nav__list--children` | `project/_header.scss` |
| `.p-footer` 系 | `project/_footer.scss` |
| `.p-footer__info` | `project/_footer.scss` |
| `.p-footer__links` | `project/_footer.scss` |
| `.p-footer__fade--button` | `project/_footer.scss` |
| `.p-footer-fix__reserve` | `project/_footer.scss` |
| `.p-footer__fade-link` | `project/_footer.scss` |
| `.p-footer__fade-icon` | `project/_footer.scss` |
| `.p-footernav__button` | `project/_footer.scss` |
| `.p-footernav` | `project/_footer.scss` |
| `.p-footer-modal` | `project/_footer.scss` |
| `.p-form` 系 | `project/_form.scss` |
| `.p-form-caution` 系 | `project/_form.scss` |
| `.p-form__group` 系 | `project/_form.scss` |
| `.p-form__ttl` 系 | `project/_form.scss` |
| `.p-form__input` 系 | `project/_form.scss` |
| `.p-form__control` 系 | `project/_form.scss` |
| `.p-form__address` 系 | `project/_form.scss` |
| `.p-form__radio-field` 系 | `project/_form.scss` |
| `.p-form__checkbox-field` 系 | `project/_form.scss` |
| `.p-submit__button` | `project/_form.scss` |
| `.p-submit__button--back` | `project/_form.scss` |
| `.p-entrystep` 系 | `project/_form.scss` |
| `.p-post__archive` 系 | `project/_post.scss` |
| `.p-sidebar` 系 | `project/_post.scss` |
| `.p-related` 系 | `project/_post.scss` |
| `.p-archive__list` | `project/_post.scss` |
| `.p-latest-card` 系 | `project/_post.scss` |
| `.p-post-feed` 系 | `project/_post.scss` |
| `.p-post-single` 系 | `project/_post-single.scss` |
| `.p-author` | `project/_post-single.scss` |
| `.p-search` 系 | `project/_search.scss` |
| `.p-ttl__rhombus` 系 | `project/_typ.scss` |
| `.p-ttl__horizontal` 系 | `project/_typ.scss` |
| `.p-ttl__underbar` | `project/_typ.scss` |
| `.p-ttl__bg-grd` | `project/_typ.scss` |
| `.p-ttl__bg-grd--wrap` | `project/_typ.scss` |
| `.p-ttl__ptn-dgnl` | `project/_typ.scss` |
| `.p-ttl__post--archives` | `project/_typ.scss` |
| `.p-ttl__post--widget` | `project/_typ.scss` |
| `.p-ttl__post--widget-related` | `project/_typ.scss` |
| `.p-ttl__post--widget-grid` | `project/_typ.scss` |
| `.p-ttl__post--single` | `project/_typ.scss` |
| `.p-ttl__post--single-h2` | `project/_typ.scss` |
| `.p-ttl__post--single-h3` | `project/_typ.scss` |
| `.p-ttl__widget` 系 | `project/_typ.scss` |
| `.p-ttl__search` 系 | `project/_typ.scss` |
| `.p-ttl__xsmall` | `project/_typ.scss` |
| `.p-ttl__small` | `project/_typ.scss` |
| `.p-ttl__medium` | `project/_typ.scss` |
| `.p-ttl__large` | `project/_typ.scss` |
| `.p-form__ttl`（typ 内） | `project/_typ.scss` |
| `.p-form__ttl-detail` | `project/_typ.scss` |
| `.p-typ` 系 | `project/_typ.scss` |
| `.p-typ__xs` | `project/_typ.scss` |
| `.p-typ__s` | `project/_typ.scss` |
| `.p-typ__m` | `project/_typ.scss` |
| `.p-typ__l` | `project/_typ.scss` |
| `.p-typ__marker` | `project/_typ.scss` |
| `.p-ttl__underline--ta` | `project/_typ.scss` |
| `.p-button__wrap` | `project/_button.scss` |
| `.p-button__wrap--readmore` | `project/_button.scss` |
| `.p-salon-info` 系 | `project/_style.scss` |
| `.p-menu` 系 | `project/_style.scss` |
| `.p-company-detail` 系 | `project/_style.scss` |
| `.p-recruit__feature--scrollhint` 系 | `project/_style.scss` |
| `.p-qa` 系 | `project/_style.scss` |
| `.p-sitemap__list` 系 | `project/_style.scss` |

---

## 利用記録（PHP）

行番号あり。置換箇所の特定に使用。

### l- 接頭辞（PHP）

| クラス名 | ファイル | 行 |
|---|---|---|
| `l-header--static` | `header.php` | 37 |
| `l-main__nav-less` | `header.php` | 44 |
| `l-header`, `l-header--absolute` | `header.php` | 52 |
| `l-main` | `header.php` | 65 |
| `l-container` | 多数（下表） | -- |
| `l-container-py` | `category.php` | 29 |
| `l-container-py` | `index.php` | 13 |
| `l-container-py` | `archive.php` | 25 |
| `l-container-py` | `404.php` | 22 |
| `l-container-py` | `search.php` | 8 |
| `l-container-py--search` | `search.php` | 3 |
| `l-container-py` | `front-page.php` | 60 |
| `l-container-py--blog` | `tmp/single-blog.php` | 1 |
| `l-container-py` | `tmp/page-privacy-policy.php` | 12 |
| `l-container-py` | `tmp/page-form-contact.php` | 22 |
| `l-container-py` | `tmp/page-form-contact-thk.php` | 14 |
| `l-container-py` | `page-form-contact-chk.php` | 247 |
| `l-split__outer` | `front-page.php` | 62 |
| `l-split__thumbnail--left` | `front-page.php` | 63 |
| `l-split__content--right` | `front-page.php` | 66 |
| `l-split__outer--reverse` | `front-page.php` | 71 |
| `l-split__thumbnail--right` | `front-page.php` | 72 |
| `l-split__content--left` | `front-page.php` | 75 |
| `l-blog__main` | `tmp/tmp-post.php` | 3 |
| `l-blog__main` | `tmp/single-blog.php` | 4 |
| `l-blog__sidebar` | `tmp/tmp-post.php` | 18 |
| `l-blog__sidebar` | `tmp/single-blog.php` | 134 |
| `l-footer` | `footer.php` | 63, 71 |
| `l-footernav` | `footer.php` | 110 |
| `l-nav` | `tmp/tmp-nav-main.php` | 8 |
| `l-header--static` | `functions/admin_login.php` | 49 |
| `l-main__nav-less` | `functions/admin_login.php` | 56 |
| `l-footer` | `functions/admin_login.php` | 73 |

#### l-container（横幅制御）PHP 全件

| ファイル | 行番号 |
|---|---|
| `category.php` | 21 |
| `index.php` | 5 |
| `archive.php` | 16 |
| `404.php` | 11, 23, 41 |
| `search.php` | 12 |
| `front-page.php` | 8, 22, 41, 95, 133, 165, 190, 209, 216, 248, 267 |
| `page-form-contact-chk.php` | 236, 248, 257, 260 |
| `footer.php` | 64, 72 |
| `functions/admin_login.php` | 74 |
| `tmp/page-salon-kitatoyama.php` | 4, 18, 24 |
| `tmp/page-recruit.php` | 4, 10, 26 |
| `tmp/page-sitemap.php` | 3 |
| `tmp/page-menu.php` | 3 |
| `tmp/tmp-post.php` | 1, 14 |
| `tmp/page-privacy-policy.php` | 3, 13 |
| `tmp/page-company.php` | 3, 18, 22, 27, 60, 65 |
| `tmp/page-campaign.php` | 8, 23 |
| `tmp/page-qa.php` | 3, 18 |
| `tmp/single-staff.php` | 27, 35 |
| `tmp/single-blog.php` | 2 |
| `tmp/page-recruit-info.php` | 8, 20 |
| `tmp/page-form-contact.php` | 11, 23, 27, 37, 41, 165 |
| `tmp/page-form-contact-thk.php` | 3, 15, 26, 34 |
| `tmp/single-style.php` | 18, 31 |
| `tmp/content/sitemap.php` | 1 |
| `tmp/content/container-feed-post.php` | 3, 7, 16 |
| `tmp/content/salon-detail.php` | 12, 20, 160 |
| `tmp/content/menu-cut.php` | 1, 7 |

### c- 接頭辞（PHP）

| クラス名 | ファイル | 行 |
|---|---|---|
| `c-micromodal__outer` | `footer.php` | 161 |
| `c-micromodal__overlay` | `footer.php` | 162 |
| `c-micromodal__container` | `footer.php` | 163 |
| `c-micromodal__close` | `footer.php` | 174 |
| `c-button__clr1` | `404.php` | 46 |
| `c-button__clr1` | `tmp/content/container-feed-post.php` | 21 |
| `c-button__clr1--border` | `tmp/page-campaign.php` | 143 |
| `c-button__clr1--border` | `tmp/archive-post.php` | 53 |
| `c-button__clr1--border` | `tmp/content/salon-detail.php` | 176 |
| `c-button__clr1--border` | `footer.php` | 227 |
| `c-button__grd` | `front-page.php` | 148, 195, 253, 288 |
| `c-button__grd` | `tmp/page-recruit.php` | 32, 42 |
| `c-button__grd` | `tmp/page-form-contact-thk.php` | 39 |
| `c-button__grd` | `tmp/single-staff.php` | 85 |
| `c-button__grd` | `tmp/content/salon-detail.php` | 166 |
| `c-button`, `c-button__tel` | `footer.php` | 242-243 |
| `c-button`, `c-button__tel` | `tmp/content/salon-detail.php` | 7, 43 |
| `c-button`, `c-button__tel` | `tmp/content/recruit-info-guiches.php` | 4, 46 |
| `c-button` | `functions/component/button-link.php` | 10 |
| `c-button` | `functions/component/button-link-external.php` | 9 |
| `c-button--thin` | `functions/component/button-link-thin.php` | 10 |
| `c-button__icon--arrow` | `functions/component/button-link.php` | 15 |
| `c-button__icon--arrow` | `functions/component/button-link-thin.php` | 15 |
| `c-replace__flex-start` | `front-page.php` | 8 |
| `c-replace__content--left` | `front-page.php` | 9 |
| `c-replace__pic--right` | `front-page.php` | 11 |
| `c-replace__detail` | `front-page.php` | 14 |
| `c-replace__flex-end` | `front-page.php` | 22 |
| `c-replace__content--right` | `front-page.php` | 23 |
| `c-replace__pic--left` | `front-page.php` | 25 |
| `c-replace__detail` | `front-page.php` | 28 |
| `c-button-2columns__frame--button` | `tmp/page-recruit.php` | 27 |
| `c-button-2columns__button` | `tmp/page-recruit.php` | 28 |
| `c-button-2columns__button--low` | `tmp/page-recruit.php` | 38 |
| `c-button-2columns__frame--button` | `tmp/content/salon-detail.php` | 161 |
| `c-button-2columns__button` | `tmp/content/salon-detail.php` | 162 |
| `c-button-2columns__button--low` | `tmp/content/salon-detail.php` | 172 |
| `c-likepost__list` | `front-page.php` | 97 |
| `c-likepost__item` | `functions/component/item-likepost.php` | 15 |
| `c-likepost__thumbnail` | `functions/component/item-likepost.php` | 16 |
| `c-likepost__content` | `functions/component/item-likepost.php` | 17 |
| `c-likepost__lead` | `functions/component/item-likepost.php` | 18 |
| `c-likepost__detail` | `functions/component/item-likepost.php` | 19 |
| `c-likepost__arrow` | `functions/component/item-likepost.php` | 21 |
| `c-pagenation` | `functions.php` | 257 |
| `c-pagenation__icon` | `functions.php` | 291 |
| `c-gmap`, `c-gmap__link` | `tmp/content/salon-detail.php` | 153-154 |
| `c-thumbnail-list__item` | `tmp/archive-style.php` | 7 |
| `c-thumbnail-list__pic` | `tmp/archive-style.php` | 8 |
| `c-thumbnail-list__item` | `tmp/archive-staff.php` | 7 |
| `c-thumbnail-list__pic` | `tmp/archive-staff.php` | 8 |
| `c-headline__title` | `archive.php` | 18 |
| `c-headline__title` | `index.php` | 7 |
| `c-headline__title` | `category.php` | 23 |
| `c-headline__title` | `404.php` | 13 |

注: `c-headline__title` は SCSS 定義が見つからない。Tailwind 側またはインラインで定義されている可能性。要確認。

注: `c-more`, `c-categorylist`, `c-login` は PHP から参照なし。SCSS 定義のみ存在。

### p- 接頭辞（PHP）

p- は非常に多数のため、ファイル別の参照数のみ記載。詳細行番号は各 Step の作業時に `toolu_01RmjVnKpXNupJ7ZNj2qjzFm.txt` を参照。

| ファイル | p- 参照数（概算） |
|---|---|
| `header.php` | 8 |
| `footer.php` | 40+ |
| `front-page.php` | 30+ |
| `searchform.php` | 5 |
| `search.php` | 3 |
| `sidebar-latest.php` | 20+ |
| `sidebar-blogs.php` | 10+ |
| `sidebar-search.php` | 1 |
| `functions.php` | 2 |
| `page-form-contact-chk.php` | 30+ |
| `404.php` | 4 |
| `archive.php` | 1 |
| `index.php` | 1 |
| `category.php` | 1 |
| `functions/admin_login.php` | 8 |
| `functions/component/button-link.php` | 0（c- のみ） |
| `functions/component/button-link-thin.php` | 0（c- のみ） |
| `functions/component/button-link-external.php` | 0（c- のみ） |
| `functions/component/item-likepost.php` | 0（c- のみ） |
| `functions/component/item-menu.php` | 3 |
| `functions/component/item-entrystep.php` | 4 |
| `tmp/page-form-contact.php` | 40+ |
| `tmp/page-form-contact-thk.php` | 10+ |
| `tmp/page-recruit.php` | 10+ |
| `tmp/page-salon-kitatoyama.php` | 8 |
| `tmp/page-company.php` | 15+ |
| `tmp/page-campaign.php` | 5+ |
| `tmp/page-qa.php` | 5+ |
| `tmp/page-sitemap.php` | 1 |
| `tmp/page-menu.php` | 1 |
| `tmp/page-privacy-policy.php` | 15+ |
| `tmp/page-recruit-info.php` | 3 |
| `tmp/single-blog.php` | 20+ |
| `tmp/single-staff.php` | 0（c- のみ） |
| `tmp/single-style.php` | 1 |
| `tmp/tmp-post.php` | 0（l- のみ） |
| `tmp/tmp-nav-main.php` | 30+ |
| `tmp/tmp-form-caution.php` | 3 |
| `tmp/archive-post.php` | 15+ |
| `tmp/archive-style.php` | 0（c- のみ） |
| `tmp/archive-staff.php` | 0（c- のみ） |
| `tmp/archive-none.php` | 1 |
| `tmp/content/feed-post-dl.php` | 2 |
| `tmp/content/feed-post-grid.php` | 10+ |
| `tmp/content/container-feed-post.php` | 3 |
| `tmp/content/salon-detail.php` | 20+ |
| `tmp/content/menu-cut.php` | 5+ |
| `tmp/content/sitemap.php` | 20+ |
| `tmp/content/recruit-info-guiches.php` | 5+ |

---

## 利用記録（JS）

| クラス名 | ファイル | 行 |
|---|---|---|
| `p-nav__overlay` | `src/js/header.js` | 12 |
| `p-toolbar__line` | `src/js/header.js` | 16 |
| `p-nav__` (prefix match) | `src/js/header.js` | 17 |
| `p-nav__item.js-nav--dropdown` | `src/js/app.js` | 48 |
| `p-nav__list--children` | `src/js/app.js` | 48 |
| `p-advantage__message` | `src/js/gsap.js` | 317, 325 |
| `p-instagram__feed` | `src/js/gsap.js` | 341 |

注: `p-advantage__message` と `p-instagram__feed` は現行 SCSS build graph 内に定義なし。JS 内の死んだセレクタ参照の可能性があるが、対応する PHP テンプレートも存在しないことを確認済み。接頭辞除外時に JS 側も置換する。

---
