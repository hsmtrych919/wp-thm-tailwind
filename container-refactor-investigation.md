# Container リファクタリング 事前調査結果

実装前に全件把握するための調査。実装時の A-1, B-1 調査結果と照合し、一致しなければ即停止する。

---

## Phase A 対象: `.l-container` 系（全 11 箇所 / PHP 10 ファイル）

### SCSS 定義: `src/scss/layout/_content.scss`

| 行 | 内容 | 備考 |
|---|---|---|
| 46 | `// l-container` | コメント |
| 49 | `@mixin l-container--padding-top {` | コメントアウトされた mixin。参照箇所ゼロ → 削除対象 |
| 61 | `@mixin l-container--padding-bottom {` | 同上 → 削除対象 |
| 71 | `.l-container {` | クラス定義本体 |
| 73 | `// @include l-container--padding-top;` | コメントアウト済み |
| 74 | `// @include l-container--padding-bottom;` | コメントアウト済み |
| 84 | `&__blog {` | `.l-container__blog` バリエーション |
| 98 | `&__search {` | `.l-container__search` バリエーション |

### PHP テンプレート（全 11 箇所 / 10 ファイル）

| ファイル | 行 | コード | 置換先 |
|---|---|---|---|
| `category.php` | 29 | `<div class="l-container">` | `l-container-py` |
| `404.php` | 22 | `<div class="l-container">` | `l-container-py` |
| `archive.php` | 25 | `<div class="l-container">` | `l-container-py` |
| `page-form-contact-chk.php` | 247 | `<div class="l-container">` | `l-container-py` |
| `index.php` | 13 | `<div class="l-container">` | `l-container-py` |
| `search.php` | 3 | `<article class="l-container__search">` | `l-container-py--search` |
| `search.php` | 8 | `<div class="l-container">` | `l-container-py` |
| `front-page.php` | 60 | `<div class="l-container">` | `l-container-py` |
| `tmp/page-privacy-policy.php` | 12 | `<div class="l-container">` | `l-container-py` |
| `tmp/page-form-contact.php` | 22 | `<div class="l-container">` | `l-container-py` |
| `tmp/single-blog.php` | 1 | `<div class="l-container__blog">` | `l-container-py--blog` |
| `tmp/page-form-contact-thk.php` | 14 | `<div class="l-container">` | `l-container-py` |

### JS: 参照なし

`src/js/` 全ファイルに `l-container` への参照ゼロ。

### CSS 出力確認用: `css/style.css`

| 行 | セレクタ |
|---|---|
| 1608 | `.l-container` |
| 1613 | `.l-container`（@media 内） |
| 1618 | `.l-container__blog` |
| 1623 | `.l-container__blog`（@media 内） |
| 1628 | `.l-container__blog`（@media 内） |
| 1633 | `.l-container__search` |
| 1637 | `.l-container__search`（@media 内） |

---

## Phase B 対象: `.container`（横幅制御クラス）

### SCSS/CSS 定義: `src/scss/tailwind-base.css`

| 行 | 内容 |
|---|---|
| 14 | `@source not inline("container");` |
| 17 | `.container {` |
| 18 | `margin-inline: auto !important;` |
| 19 | `width: 100% !important;` |
| 20-34 | 5 段階の BP 別 max-width（540/960/1152/1200/1260px） |

### PHP テンプレート（全 68 箇所 / 25 ファイル）

| ファイル | 行 | パターン概要 |
|---|---|---|
| `front-page.php` | 8, 22, 41, 95, 133, 165, 190, 209, 216, 248, 267 | 11 箇所 |
| `page-form-contact-chk.php` | 236, 248, 257, 260 | 4 箇所 |
| `archive.php` | 16 | 1 箇所 |
| `category.php` | 21 | 1 箇所 |
| `index.php` | 5 | 1 箇所 |
| `404.php` | 11, 23, 41 | 3 箇所 |
| `search.php` | 12 | 1 箇所 |
| `footer.php` | 64, 72 | 2 箇所 |
| `functions/admin_login.php` | 74 | 1 箇所 |
| `tmp/page-qa.php` | 3, 18 | 2 箇所 |
| `tmp/page-menu.php` | 3 | 1 箇所 |
| `tmp/page-salon-kitatoyama.php` | 4, 18, 24 | 3 箇所 |
| `tmp/page-form-contact-thk.php` | 3, 15, 26, 34 | 4 箇所 |
| `tmp/tmp-post.php` | 1, 14 | 2 箇所 |
| `tmp/single-blog.php` | 2 | 1 箇所 |
| `tmp/content/sitemap.php` | 1 | 1 箇所 |
| `tmp/page-recruit-info.php` | 8, 20 | 2 箇所 |
| `tmp/content/menu-cut.php` | 1, 7 | 2 箇所 |
| `tmp/page-company.php` | 3, 18, 22, 27, 60, 65 | 6 箇所 |
| `tmp/page-sitemap.php` | 3 | 1 箇所 |
| `tmp/page-privacy-policy.php` | 3, 13 | 2 箇所 |
| `tmp/page-recruit.php` | 4, 10, 26 | 3 箇所 |
| `tmp/content/salon-detail.php` | 12, 20, 160 | 3 箇所 |
| `tmp/content/container-feed-post.php` | 3, 7, 16 | 3 箇所 |
| `tmp/single-staff.php` | 27, 35 | 2 箇所 |
| `tmp/page-campaign.php` | 8, 23 | 2 箇所 |
| `tmp/page-form-contact.php` | 11, 23, 27, 37, 41, 165 | 6 箇所 |
| `tmp/single-style.php` | 18, 31 | 2 箇所 |

### JS: `.container` クラスへの直接参照なし

| ファイル | 行 | 内容 | 判定 |
|---|---|---|---|
| `src/js/__memo.js` | 57 | `document.querySelector('.main-container')` | `.main-container` であり `.container` ではない。対象外 |
| `src/js/swiper.js` | 複数行 | `container: '.swiper-style__container'` 等 | Swiper の config パラメータ名。`.container` CSS クラスへの参照ではない。対象外 |

### CSS 出力確認用: `css/style.css`

| 行 | 内容 |
|---|---|
| 1231-1235 | `.container` クラス定義 |

注意: CSS 出力には `.c-micromodal__container`（L1866）、`.p-latest-card__container`（L5551）、`.swiper-style__container`（L6372）、`.swiper-front__container`（L6397）も存在するが、これらは別クラスであり Phase B の対象外。

---

## Phase C 対象: `max-w-container-*`

### 定義: `tailwind.config.js` L166-172

```js
maxWidth: {
  'container-sm': '540px',
  'container-md': '960px',
  'container-lg': '1152px',
  'container-xl': '1200px',
  'container-xxl': '1260px',
},
```

### PHP: 使用箇所ゼロ

### JS: 使用箇所ゼロ

### SCSS: 使用箇所ゼロ

### sample.html: 14 箇所（git 除外ファイル。運用確認用）

### CSS 出力: `css/style.css`

| 行 | 内容 |
|---|---|
| 385 | `.max-w-container-lg { max-width: 1152px !important; }` |
| 388 | `.max-w-container-md { max-width: 960px !important; }` |
| 391 | `.max-w-container-xl { max-width: 1200px !important; }` |

`container-sm` と `container-xxl` は content（PHP）に使用箇所がないため Tailwind が生成していない。sample.html は content 対象外（`.php` のみスキャン）。

---

## 集計

| Phase | 対象クラス | PHP 箇所数 | PHP ファイル数 | JS 参照 | SCSS 定義ファイル |
|---|---|---|---|---|---|
| A | `.l-container` 系 | 11 | 10 | なし | `layout/_content.scss` |
| B | `.container` | 68 | 25 | なし | `tailwind-base.css` |
| C | `max-w-container-*` | 0 | 0 | なし | `tailwind.config.js` |
