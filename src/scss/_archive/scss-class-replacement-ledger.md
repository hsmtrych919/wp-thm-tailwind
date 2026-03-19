# SCSS Class Replacement Ledger

作成日: 2026-03-18

## 目的

このファイルは、SCSS 再構成の実装前に `c-*` から `p-*` へ寄せる対象 class の PHP 利用箇所を整理するための置換台帳である。

この台帳は、実装時に次を防ぐために使う。

- 置換漏れ
- 同一要素共存と親子関係の取り違え
- 今回の整理対象外クラスの誤変更

## 重要な前提

1. この台帳の対象は、現在の再構成計画に含めている class 群だけである
2. repo 内には他にも多数の `c-*` があるが、それらは今回の整理対象外である
3. 実装時は、この台帳を起点に再度 grep してダブルチェックする
4. 行数は判断材料にしない。見るべきものは「どの class が、どの PHP で、どういう関係で使われているか」である

## 今回の整理対象

- `c-header__*`
- `c-toolbar__*`
- `c-nav__*`
- `c-footer__*`
- `c-footernav__*`
- `c-post__*`
- `c-post-feed__*`
- `c-search*`
- `c-ttl__*`
- `c-form__ttl*`
- `c-typ*`

## 今回の整理対象外

今回の計画では、次のような `c-*` は対象外。

- `c-headline__*`
- `c-headline-salon__*`
- `c-headline-pic__*`
- `c-replace__*`
- `c-bgi_list`
- `c-likepost__*`
- `c-button*`
- `c-pagenation*`
- `c-thumbnail-list*`
- `c-micromodal__*`

対象外は今回の rename/統合では触らない。

## 関係種別の定義

- `same-element`: 同一 HTML 要素に `c-*` と `p-*` が共存
- `parent-child`: `c-*` と `p-*` は親子要素で分担
- `single`: `c-*` のみ使用。rename 前提で移管

## 実装単位の定義

この台帳では、実装時の 1 単位を「1 class」ではなく「1 class 群 + その受け皿 SCSS + 参照 PHP 群」とする。

1 単位が完了したと見なせる条件は次の通り。

1. 受け皿になる `p-*` 側 SCSS が先に確定している
2. 同じ単位に属する PHP 上の `c-*` がすべて置換されている
3. 元の `c-*` セレクタを削除しても、その単位に属する画面が崩れない状態になっている
4. `style.scss` から見た import 構成が破綻していない

## 全体の置換順序

実装時は次の順で進める。理由は「影響範囲が閉じた feature から先に片付け、横断利用の大きい typ 系を最後に回す」ため。

1. Search
2. Header / Toolbar / Nav
3. Footer / Footernav
4. Post / Post Feed
5. Post Titles / Widget Titles / Search Titles
6. Global Typ / Generic Title / Form Title

### この順序にする理由

- Search は専用テンプレート 1 枚で閉じており、最小単位で検証しやすい
- Header と Footer は feature として閉じているが、レイアウト全体に出るので typ より先に片付けた方が差分追跡しやすい
- Post 系は archive / sidebar / related / feed にまたがるため、header/footer より後にまとめて扱う
- title 系は post 系の受け皿命名と一体で決める必要があるため、post 構造の後に回す
- generic typ 系はテンプレート横断で最も影響範囲が広いので最後に回す

## 同時修正の基本ルール

以下は全領域共通の必須ルール。

1. PHP の class 置換より先に、受け皿となる `p-*` セレクタを target SCSS 側で成立させる
2. `same-element` は「target SCSS へ吸収定義追加」と「PHP から `c-*` 削除」を同じ作業単位で行う
3. `single` / `parent-child` は「source SCSS から target SCSS への移管」と「PHP rename」を同じ作業単位で行う
4. `component/_*.scss` の削除や import 整理は、その partial 内の対象 class が全廃できる最後の段階まで行わない

## 1. Header / Toolbar / Nav

### SCSS 起点と受け皿

- source: src/scss/component/_header.scss
- target: src/scss/project/_header.scss
- PHP: header.php, functions/admin_login.php, tmp/tmp-nav-main.php

### 対象 class 群

- `c-header__logo-block`
- `c-header__logo-block--login`
- `c-header__logo`
- `c-toolbar__button`
- `c-toolbar__line`
- `c-nav__button`
- `c-nav__button--recruit`
- `c-nav__buton--children`
- `c-nav__close-button`

### 利用箇所

| class | 関係 | 利用箇所 | 置換方針 |
|---|---|---|---|
| `c-header__logo-block` | `single` | header.php:L39, header.php:L56, header.php:L58, functions/admin_login.php:L51 | `p-header__logo-block` 系へ rename |
| `c-header__logo-block--login` | `single` | header.php:L39, functions/admin_login.php:L51 | `p-header__logo-block--login` 系へ rename |
| `c-header__logo` | `single` + `same-element` | header.php:L40, header.php:L60, functions/admin_login.php:L52 | `p-header__logo` に吸収 |
| `c-toolbar__button` | `single` | tmp/tmp-nav-main.php:L3 | `p-toolbar__button` 系へ rename |
| `c-toolbar__line` | `single` | tmp/tmp-nav-main.php:L3 | `p-toolbar__line` 系へ rename |
| `c-nav__button` | `parent-child` | tmp/tmp-nav-main.php:L10, tmp/tmp-nav-main.php:L11, tmp/tmp-nav-main.php:L12, tmp/tmp-nav-main.php:L25, tmp/tmp-nav-main.php:L26, tmp/tmp-nav-main.php:L27, tmp/tmp-nav-main.php:L28, tmp/tmp-nav-main.php:L29, tmp/tmp-nav-main.php:L30, tmp/tmp-nav-main.php:L31 | `p-nav__button` 系へ rename |
| `c-nav__button--recruit` | `parent-child` | tmp/tmp-nav-main.php:L31 | `p-nav__button--recruit` 系へ rename |
| `c-nav__buton--children` | `parent-child` | tmp/tmp-nav-main.php:L18, tmp/tmp-nav-main.php:L19, tmp/tmp-nav-main.php:L20, tmp/tmp-nav-main.php:L21, tmp/tmp-nav-main.php:L22 | typo 修正込みで `p-nav__button--children` 系へ rename |
| `c-nav__close-button` | `same-element` | tmp/tmp-nav-main.php:L32 | `p-nav__item--close` へ吸収 |

### 置換順序

1. src/scss/project/_header.scss に `p-header__logo`, `p-toolbar__button`, `p-toolbar__line`, `p-nav__button`, `p-nav__button--children`, `p-nav__button--recruit`, `p-nav__item--close` の受け皿を確定する
2. `same-element` の `c-header__logo`, `c-nav__close-button` を先に吸収し、対応 PHP から `c-*` を落とせる状態にする
3. `single` / `parent-child` の `c-header__logo-block*`, `c-toolbar__*`, `c-nav__button*` を rename で寄せる
4. src/scss/component/_header.scss に残る header 系 `c-*` がなくなったことを確認してから整理対象にする

### 同時修正が必要な SCSS/PHP の組

- `c-header__logo-block`, `c-header__logo-block--login`, `c-header__logo`
	- SCSS: src/scss/component/_header.scss + src/scss/project/_header.scss
	- PHP: header.php:L39, header.php:L40, header.php:L56, header.php:L58, header.php:L60, functions/admin_login.php:L51, functions/admin_login.php:L52
- `c-toolbar__button`, `c-toolbar__line`
	- SCSS: src/scss/component/_header.scss + src/scss/project/_header.scss
	- PHP: tmp/tmp-nav-main.php:L3
- `c-nav__button`, `c-nav__button--recruit`, `c-nav__buton--children`, `c-nav__close-button`
	- SCSS: src/scss/component/_header.scss + src/scss/project/_header.scss
	- PHP: tmp/tmp-nav-main.php:L10, tmp/tmp-nav-main.php:L18, tmp/tmp-nav-main.php:L25, tmp/tmp-nav-main.php:L31, tmp/tmp-nav-main.php:L32

## 2. Footer / Footernav

### SCSS 起点と受け皿

- source: src/scss/component/_footer.scss
- target: src/scss/project/_footer.scss
- PHP: footer.php

### 対象 class 群

- `c-footer__info`
- `c-footer__info--alone`
- `c-footer__links`
- `c-footer__links--item`
- `c-footer__fade--button`
- `c-footer__fade--icon`
- `c-footer--have-footernavi`
- `c-footernav__button`
- `c-footernav__button--icon`
- `c-footernav__button--icon-insta`
- `c-footernav__button--reserve`
- `c-footernav__button--reserve-inner`
- `c-footernav__button--reserve-icon`
- `c-footernav__button--tel`
- `c-footernav__button--tel-inner`
- `c-footernav__button--tel-icon`

### 利用箇所

| class | 関係 | 利用箇所 | 置換方針 |
|---|---|---|---|
| `c-footer--have-footernavi` | `same-element` | footer.php:L71 | `p-footer` 側へ吸収 |
| `c-footer__info` | `single` | footer.php:L65, footer.php:L73 | `p-footer__info` 系へ rename |
| `c-footer__info--alone` | `single` | footer.php:L65 | `p-footer__info--alone` 系へ rename |
| `c-footer__links` | `single` | footer.php:L77 | `p-footer__links` 系へ rename |
| `c-footer__links--item` | `parent-child` | footer.php:L79 | `p-footer__links--item` か `p-footer__links-item` 側へ整理 |
| `c-footer__fade--button` | `parent-child` | footer.php:L90 | `p-footer__fade-link` などへ rename |
| `c-footer__fade--icon` | `single` | footer.php:L91 | `p-footer__fade-icon` 系へ rename |
| `c-footernav__button` | `parent-child` | footer.php:L115, footer.php:L124, footer.php:L136, footer.php:L199 | `p-footernav__button` 系へ rename |
| `c-footernav__button--icon` | `single` | footer.php:L29, footer.php:L31, footer.php:L201 | `p-footernav__button-icon` 系へ rename |
| `c-footernav__button--icon-insta` | `single` | footer.php:L30 | `p-footernav__button-icon-insta` 系へ rename |
| `c-footernav__button--reserve` | `parent-child` | footer.php:L143, footer.php:L203 | `p-footernav__button--reserve` 側へ rename |
| `c-footernav__button--reserve-inner` | `single` | footer.php:L144, footer.php:L204 | `p-footernav__button--reserve-inner` 側へ rename |
| `c-footernav__button--reserve-icon` | `single` | footer.php:L32 | `p-footernav__button--reserve-icon` 側へ rename |
| `c-footernav__button--tel` | `single` | footer.php:L212 | `p-footernav__button--tel` 側へ rename |
| `c-footernav__button--tel-inner` | `single` | footer.php:L213 | `p-footernav__button--tel-inner` 側へ rename |
| `c-footernav__button--tel-icon` | `single` | footer.php:L33, footer.php:L214 | `p-footernav__button--tel-icon` 側へ rename |

### 置換順序

1. src/scss/project/_footer.scss 側で `p-footer__info*`, `p-footer__links*`, `p-footer__fade*`, `p-footernav__button*` の受け皿を確定する
2. `same-element` の `c-footer--have-footernavi` を `p-footer` 側へ吸収する
3. `c-footer__info*`, `c-footer__links*`, `c-footer__fade*` を footer 本体から rename する
4. `c-footernav__button*` 一式を icon class 文字列を含めてまとめて rename する
5. src/scss/component/_footer.scss に残る footer 系 `c-*` がなくなった時点で整理対象にする

### 同時修正が必要な SCSS/PHP の組

- `c-footer--have-footernavi`, `c-footer__info`, `c-footer__info--alone`, `c-footer__links`, `c-footer__links--item`, `c-footer__fade--button`, `c-footer__fade--icon`
	- SCSS: src/scss/component/_footer.scss + src/scss/project/_footer.scss
	- PHP: footer.php:L65, footer.php:L71, footer.php:L73, footer.php:L77, footer.php:L79, footer.php:L90, footer.php:L91
- `c-footernav__button`, `c-footernav__button--icon`, `c-footernav__button--icon-insta`, `c-footernav__button--reserve`, `c-footernav__button--reserve-inner`, `c-footernav__button--reserve-icon`, `c-footernav__button--tel`, `c-footernav__button--tel-inner`, `c-footernav__button--tel-icon`
	- SCSS: src/scss/component/_footer.scss + src/scss/project/_footer.scss
	- PHP: footer.php:L29, footer.php:L30, footer.php:L31, footer.php:L32, footer.php:L33, footer.php:L115, footer.php:L124, footer.php:L136, footer.php:L143, footer.php:L144, footer.php:L199, footer.php:L201, footer.php:L203, footer.php:L204, footer.php:L212, footer.php:L213, footer.php:L214

## 3. Post / Post Feed

### SCSS 起点と受け皿

- source: src/scss/component/_post.scss, src/scss/component/_post-feed.scss
- target: src/scss/project/_post.scss
- PHP: sidebar-latest.php, tmp/single-blog.php, tmp/archive-post.php, tmp/content/feed-post-grid.php, tmp/content/feed-post-dl.php

### 対象 class 群

- `c-post__archive--thumbnail`
- `c-post__archive--date`
- `c-post__widget--item`
- `c-post__widget--item-latest`
- `c-post__widget--thumbnail`
- `c-post__widget--info`
- `c-post-feed__dl`
- `c-post-feed__dl--row`

### 利用箇所

| class | 関係 | 利用箇所 | 置換方針 |
|---|---|---|---|
| `c-post__archive--thumbnail` | `single` | tmp/archive-post.php:L3 | `p-post__archive--thumbnail-link` などへ rename |
| `c-post__archive--date` | `same-element` | sidebar-latest.php:L45, sidebar-latest.php:L72, sidebar-latest.php:L101, tmp/archive-post.php:L33, tmp/content/feed-post-grid.php:L30, tmp/content/feed-post-grid.php:L44 | `p-sidebar__post--date`, `p-post__archive--date`, `p-latest-card__date` 側へ吸収 |
| `c-post__widget--item` | `same-element` | tmp/single-blog.php:L67 | `p-related__post--item` 側へ吸収 |
| `c-post__widget--item-latest` | `same-element` | sidebar-latest.php:L19 | `p-sidebar__post--item` 側へ吸収 |
| `c-post__widget--thumbnail` | `same-element` | sidebar-latest.php:L21, tmp/single-blog.php:L68 | `p-sidebar__post--thumbnail`, `p-related__post--thumbnail` 側へ吸収 |
| `c-post__widget--info` | `single` | sidebar-latest.php:L25, tmp/single-blog.php:L71 | post archive 側へ rename / 吸収 |
| `c-post-feed__dl` | `single` | tmp/content/feed-post-dl.php:L11 | `p-post-feed__dl` か `_post-archive.scss` 側の `p-*` 命名へ rename |
| `c-post-feed__dl--row` | `single` | tmp/content/feed-post-dl.php:L22 | 同上 |

### 置換順序

1. src/scss/project/_post.scss に archive / sidebar / related / feed の受け皿命名を先に確定する
2. `same-element` の `c-post__archive--date`, `c-post__widget--item*`, `c-post__widget--thumbnail` を各 feature 側 `p-*` に吸収する
3. `single` の `c-post__archive--thumbnail`, `c-post__widget--info`, `c-post-feed__dl*` を rename で移管する
4. archive / sidebar / related / feed の各テンプレートで `c-post*` が残っていないことを確認してから src/scss/component/_post.scss と src/scss/component/_post-feed.scss を整理対象にする

### 同時修正が必要な SCSS/PHP の組

- `c-post__archive--thumbnail`, `c-post__archive--date`
	- SCSS: src/scss/component/_post.scss + src/scss/project/_post.scss
	- PHP: tmp/archive-post.php:L3, tmp/archive-post.php:L33, tmp/content/feed-post-grid.php:L30, tmp/content/feed-post-grid.php:L44
- `c-post__widget--item`, `c-post__widget--item-latest`, `c-post__widget--thumbnail`, `c-post__widget--info`
	- SCSS: src/scss/component/_post.scss + src/scss/project/_post.scss
	- PHP: sidebar-latest.php:L19, sidebar-latest.php:L21, sidebar-latest.php:L25, sidebar-latest.php:L45, sidebar-latest.php:L72, sidebar-latest.php:L101, tmp/single-blog.php:L67, tmp/single-blog.php:L68, tmp/single-blog.php:L71
- `c-post-feed__dl`, `c-post-feed__dl--row`
	- SCSS: src/scss/component/_post-feed.scss + src/scss/project/_post.scss
	- PHP: tmp/content/feed-post-dl.php:L11, tmp/content/feed-post-dl.php:L22

## 4. Search

### SCSS 起点と受け皿

- source: src/scss/component/_search.scss
- target: 新規 `p-search*` の受け皿。第一候補は `project/_search.scss` とし、既存構成の都合で追加しにくい場合のみ `project` 配下の search 専用 partial を別名で作る
- PHP: searchform.php

### 対象 class 群

- `c-search`
- `c-search__form`
- `c-search__control`
- `c-search__submit`
- `c-search__icon`

### 利用箇所

| class | 関係 | 利用箇所 | 置換方針 |
|---|---|---|---|
| `c-search` | `single` | searchform.php:L1 | `p-search` へ rename |
| `c-search__form` | `single` | searchform.php:L2 | `p-search__form` へ rename |
| `c-search__control` | `single` | searchform.php:L3 | `p-search__control` へ rename |
| `c-search__submit` | `single` | searchform.php:L4 | `p-search__submit` へ rename |
| `c-search__icon` | `single` | searchform.php:L5 | `p-search__icon` へ rename |

### 置換順序

1. `p-search`, `p-search__form`, `p-search__control`, `p-search__submit`, `p-search__icon` の受け皿を先に作る
2. searchform.php:L1 から searchform.php:L5 までを一気に rename する
3. src/scss/component/_search.scss 側の `c-search*` を全廃できる状態にしてから整理対象にする

### 同時修正が必要な SCSS/PHP の組

- `c-search`, `c-search__form`, `c-search__control`, `c-search__submit`, `c-search__icon`
	- SCSS: src/scss/component/_search.scss + `project/_search.scss` 第一候補の search 受け皿 partial
	- PHP: searchform.php:L1, searchform.php:L2, searchform.php:L3, searchform.php:L4, searchform.php:L5

## 5. Post Titles / Widget Titles / Search Titles

### SCSS 起点と受け皿

- source: src/scss/component/_typ.scss
- target: src/scss/project/_typ.scss, src/scss/project/_post.scss
- PHP: tmp/archive-post.php, sidebar-latest.php, sidebar-blogs.php, tmp/single-blog.php, search.php

### 対象 class 群

- `c-ttl__post--archives`
- `c-ttl__post--widget`
- `c-ttl__post--single`
- `c-ttl__ptn-dgnl`
- `c-ttl__widget`
- `c-ttl__widget--bar`
- `c-ttl__widget--caption`
- `c-ttl__search`
- `c-ttl__search--caption`

### 利用箇所

| class | 関係 | 利用箇所 | 置換方針 |
|---|---|---|---|
| `c-ttl__post--archives` | `single` | tmp/archive-post.php:L9 | `p-ttl__post--archives` 側へ統合 |
| `c-ttl__post--widget` | `same-element` | sidebar-latest.php:L27, sidebar-latest.php:L54, sidebar-latest.php:L83, tmp/single-blog.php:L72 | `p-ttl__post--widget`, `p-ttl__post--widget-related` 側へ吸収 |
| `c-ttl__post--single` | `single` | tmp/single-blog.php:L11 | `p-ttl__post--single` 側へ統合 |
| `c-ttl__ptn-dgnl` | `single` | tmp/archive-post.php:L9, tmp/single-blog.php:L11 | post title 装飾として `p-*` 側へ rename |
| `c-ttl__widget` | `single` | sidebar-blogs.php:L4, sidebar-blogs.php:L24, sidebar-latest.php:L4, tmp/single-blog.php:L40 | `p-ttl__widget` 系へ rename |
| `c-ttl__widget--bar` | `single` | sidebar-blogs.php:L4, sidebar-blogs.php:L24, sidebar-latest.php:L4, tmp/single-blog.php:L40 | `p-ttl__widget--bar` 系へ rename |
| `c-ttl__widget--caption` | `single` | sidebar-blogs.php:L3, sidebar-blogs.php:L23, sidebar-latest.php:L3, tmp/single-blog.php:L39 | `p-ttl__widget--caption` 系へ rename |
| `c-ttl__search` | `single` | search.php:L6 | `p-ttl__search` へ rename |
| `c-ttl__search--caption` | `single` | search.php:L5 | `p-ttl__search--caption` へ rename |

### 置換順序

1. src/scss/project/_typ.scss と src/scss/project/_post.scss で post / widget / search title の受け皿を確定する
2. `same-element` の `c-ttl__post--widget` を `p-ttl__post--widget`, `p-ttl__post--widget-related` に吸収する
3. `single` の `c-ttl__post--archives`, `c-ttl__post--single`, `c-ttl__ptn-dgnl`, `c-ttl__widget*`, `c-ttl__search*` を rename で移管する
4. src/scss/component/_typ.scss から post/search/widget title 系を抜ける状態にする

### 同時修正が必要な SCSS/PHP の組

- `c-ttl__post--archives`, `c-ttl__ptn-dgnl`
	- SCSS: src/scss/component/_typ.scss + src/scss/project/_typ.scss
	- PHP: tmp/archive-post.php:L9
- `c-ttl__post--single`, `c-ttl__ptn-dgnl`
	- SCSS: src/scss/component/_typ.scss + src/scss/project/_typ.scss
	- PHP: tmp/single-blog.php:L11
- `c-ttl__post--widget`
	- SCSS: src/scss/component/_typ.scss + src/scss/project/_typ.scss + src/scss/project/_post.scss
	- PHP: sidebar-latest.php:L27, sidebar-latest.php:L54, sidebar-latest.php:L83, tmp/single-blog.php:L72
- `c-ttl__widget`, `c-ttl__widget--bar`, `c-ttl__widget--caption`
	- SCSS: src/scss/component/_typ.scss + src/scss/project/_typ.scss
	- PHP: sidebar-blogs.php:L3, sidebar-blogs.php:L4, sidebar-blogs.php:L23, sidebar-blogs.php:L24, sidebar-latest.php:L3, sidebar-latest.php:L4, tmp/single-blog.php:L39, tmp/single-blog.php:L40
- `c-ttl__search`, `c-ttl__search--caption`
	- SCSS: src/scss/component/_typ.scss + src/scss/project/_typ.scss
	- PHP: search.php:L5, search.php:L6

## 6. Global Typ / Generic Title / Form Title

### SCSS 起点と受け皿

- source: src/scss/component/_typ.scss
- target: src/scss/project/_typ.scss, src/scss/project/_form.scss
- PHP: 横断利用のため、この章ではテンプレート群を置換単位ごとに扱う

### 対象 class 群

- `c-ttl__large`
- `c-ttl__medium`
- `c-ttl__small`
- `c-ttl__xsmall`
- `c-ttl__bg-grd`
- `c-form__ttl`
- `c-typ`
- `c-typ__xs`
- `c-typ__s`
- `c-typ__m`
- `c-typ__l`

### 利用箇所

この領域は使用箇所が多いので、置換単位でまとめる。

#### `c-ttl__large`

- archive.php:L18
- index.php:L7
- 404.php:L13
- category.php:L23
- front-page.php:L10
- front-page.php:L24
- front-page.php:L141
- front-page.php:L212
- front-page.php:L281

#### `c-ttl__medium`

- front-page.php:L169

#### `c-ttl__small`

- front-page.php:L15
- front-page.php:L29
- front-page.php:L44
- tmp/page-campaign.php:L28
- tmp/page-campaign.php:L56
- tmp/page-company.php:L24
- tmp/page-company.php:L62
- tmp/content/menu-cut.php:L3
- tmp/content/salon-detail.php:L15
- tmp/page-privacy-policy.php:L5

#### `c-ttl__xsmall`

- page-form-contact-chk.php:L241
- tmp/page-sitemap.php:L8
- tmp/page-recruit-info.php:L13
- tmp/page-form-contact-thk.php:L8
- tmp/single-style.php:L23
- tmp/page-form-contact.php:L16

#### `c-ttl__bg-grd`

- functions/admin_login.php:L57

#### `c-form__ttl`

- tmp/page-form-contact.php:L56
- tmp/page-form-contact.php:L75
- tmp/page-form-contact.php:L83
- tmp/page-form-contact.php:L94
- tmp/page-form-contact.php:L106
- tmp/page-form-contact.php:L117
- tmp/page-form-contact.php:L146
- page-form-contact-chk.php:L276
- page-form-contact-chk.php:L289
- page-form-contact-chk.php:L300
- page-form-contact-chk.php:L311
- page-form-contact-chk.php:L325
- page-form-contact-chk.php:L345
- page-form-contact-chk.php:L362

#### `c-typ`

- 404.php:L25
- front-page.php:L16
- front-page.php:L30
- front-page.php:L46
- front-page.php:L67
- front-page.php:L143
- front-page.php:L283
- tmp/archive-none.php:L4
- tmp/page-salon-kitatoyama.php:L28
- tmp/page-recruit.php:L12
- tmp/page-recruit.php:L14
- tmp/page-campaign.php:L16
- tmp/page-campaign.php:L136
- tmp/page-company.php:L11
- tmp/page-qa.php:L11
- tmp/page-form-contact.php:L24
- tmp/page-form-contact-thk.php:L28
- tmp/page-form-contact-thk.php:L29
- tmp/page-form-contact-thk.php:L30
- tmp/page-menu.php:L11
- tmp/page-privacy-policy.php:L15
- tmp/page-privacy-policy.php:L18
- tmp/page-privacy-policy.php:L21
- tmp/page-privacy-policy.php:L24
- tmp/page-privacy-policy.php:L27
- tmp/page-privacy-policy.php:L31
- tmp/page-privacy-policy.php:L34
- tmp/page-privacy-policy.php:L41
- tmp/page-privacy-policy.php:L45
- tmp/page-privacy-policy.php:L48
- tmp/page-privacy-policy.php:L51

#### `c-typ__xs`

- front-page.php:L45
- tmp/content/menu-cut.php:L9

#### `c-typ__s`

- front-page.php:L47
- tmp/page-recruit.php:L13
- tmp/page-privacy-policy.php:L17
- tmp/page-privacy-policy.php:L20
- tmp/page-privacy-policy.php:L23
- tmp/page-privacy-policy.php:L26
- tmp/page-privacy-policy.php:L30
- tmp/page-privacy-policy.php:L33
- tmp/page-privacy-policy.php:L44
- tmp/page-privacy-policy.php:L47
- tmp/page-privacy-policy.php:L50

#### `c-typ__m`

- front-page.php:L48
- tmp/content/recruit-info-guiches.php:L52

#### `c-typ__l`

- front-page.php:L49

### 置換方針

- generic title 系は `p-ttl__*` へ寄せる
- form title は `p-form__ttl*` 側へ寄せる
- body copy 系は `p-typ*` へ寄せる
- ただし `p-front-*` のような既存 feature class と同一要素に置かれている箇所は、必要に応じて feature 側へ吸収するか `p-typ*` を併用するかを実装時に決める

### 置換順序

1. `p-ttl__large`, `p-ttl__medium`, `p-ttl__small`, `p-ttl__xsmall`, `p-ttl__bg-grd`, `p-typ`, `p-typ__xs`, `p-typ__s`, `p-typ__m`, `p-typ__l` の受け皿を src/scss/project/_typ.scss 側に先に確定する
2. form 専用の `c-form__ttl` は src/scss/project/_form.scss に寄せる
3. 影響範囲の狭い `c-ttl__bg-grd` と `c-form__ttl` を先に移管する
4. 次に `c-ttl__large` / `medium` / `small` / `xsmall` をテンプレート群ごとに rename する
5. 最後に `c-typ`, `c-typ__xs`, `c-typ__s`, `c-typ__m`, `c-typ__l` を横断置換する
6. すべての generic typ/title が抜けてから src/scss/component/_typ.scss の整理可否を判定する

### 同時修正が必要な SCSS/PHP の組

- `c-ttl__bg-grd`
	- SCSS: src/scss/component/_typ.scss + src/scss/project/_typ.scss
	- PHP: functions/admin_login.php:L57
- `c-form__ttl`
	- SCSS: src/scss/component/_typ.scss + src/scss/project/_form.scss
	- PHP: tmp/page-form-contact.php:L56, tmp/page-form-contact.php:L75, tmp/page-form-contact.php:L83, tmp/page-form-contact.php:L94, tmp/page-form-contact.php:L106, tmp/page-form-contact.php:L117, tmp/page-form-contact.php:L146, page-form-contact-chk.php:L276, page-form-contact-chk.php:L289, page-form-contact-chk.php:L300, page-form-contact-chk.php:L311, page-form-contact-chk.php:L325, page-form-contact-chk.php:L345, page-form-contact-chk.php:L362
- `c-ttl__large`, `c-ttl__medium`, `c-ttl__small`, `c-ttl__xsmall`
	- SCSS: src/scss/component/_typ.scss + src/scss/project/_typ.scss
	- PHP: archive.php:L18, index.php:L7, 404.php:L13, category.php:L23, front-page.php:L10, front-page.php:L15, front-page.php:L24, front-page.php:L29, front-page.php:L44, front-page.php:L141, front-page.php:L169, front-page.php:L212, front-page.php:L281, tmp/page-campaign.php:L28, tmp/page-campaign.php:L56, tmp/page-company.php:L24, tmp/page-company.php:L62, tmp/content/menu-cut.php:L3, tmp/content/salon-detail.php:L15, tmp/page-privacy-policy.php:L5, tmp/page-sitemap.php:L8, tmp/page-recruit-info.php:L13, tmp/page-form-contact-thk.php:L8, tmp/single-style.php:L23, tmp/page-form-contact.php:L16
- `c-typ`, `c-typ__xs`, `c-typ__s`, `c-typ__m`, `c-typ__l`
	- SCSS: src/scss/component/_typ.scss + src/scss/project/_typ.scss
	- PHP: 404.php:L25, front-page.php:L16, front-page.php:L30, front-page.php:L45, front-page.php:L46, front-page.php:L47, front-page.php:L48, front-page.php:L49, front-page.php:L67, front-page.php:L143, front-page.php:L283, tmp/archive-none.php:L4, tmp/page-salon-kitatoyama.php:L28, tmp/page-recruit.php:L12, tmp/page-recruit.php:L13, tmp/page-recruit.php:L14, tmp/page-campaign.php:L16, tmp/page-campaign.php:L136, tmp/page-company.php:L11, tmp/page-qa.php:L11, tmp/page-form-contact.php:L24, tmp/page-form-contact-thk.php:L28, tmp/page-form-contact-thk.php:L29, tmp/page-form-contact-thk.php:L30, tmp/page-menu.php:L11, tmp/page-privacy-policy.php:L15, tmp/page-privacy-policy.php:L17, tmp/page-privacy-policy.php:L18, tmp/page-privacy-policy.php:L20, tmp/page-privacy-policy.php:L21, tmp/page-privacy-policy.php:L23, tmp/page-privacy-policy.php:L24, tmp/page-privacy-policy.php:L26, tmp/page-privacy-policy.php:L27, tmp/page-privacy-policy.php:L30, tmp/page-privacy-policy.php:L31, tmp/page-privacy-policy.php:L33, tmp/page-privacy-policy.php:L34, tmp/page-privacy-policy.php:L41, tmp/page-privacy-policy.php:L44, tmp/page-privacy-policy.php:L45, tmp/page-privacy-policy.php:L47, tmp/page-privacy-policy.php:L48, tmp/page-privacy-policy.php:L50, tmp/page-privacy-policy.php:L51, tmp/content/menu-cut.php:L9, tmp/content/recruit-info-guiches.php:L52

## 実装前チェックルール

1. この台帳にある class を、実装前に再度 grep して最新化する
2. `same-element` は吸収統合、`parent-child` と `single` は rename 主体で処理する
3. 対象外 `c-*` を巻き込まない
4. PHP 置換と SCSS 統合は、台帳の class 単位で完了確認する
5. 各領域の「同時修正が必要な SCSS/PHP の組」を分割して実装しない
6. src/scss/style.scss の import 整理は、最後に不要 partial が確定してから行う

## 補足

この台帳は「実装用の初期台帳」として使うためのもの。別 AI に渡す場合も、このファイルを見れば今回の整理対象の PHP 利用箇所を追える状態にしてある。