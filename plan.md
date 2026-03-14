# Phase 1 作業計画（修正版 v6）

> 作成日: 2026-03-13 / 更新: 2026-03-14
> 対象: `wp-thm/` 配下の SCSS → Tailwind CSS 移行 Phase 1（基盤構築）
> 参照: `src/scss/tailwind-migration-plan.md` §1

---

## 前提

- **Tailwind v4**（PostCSS プラグイン方式: `@tailwindcss/postcss`）
- **ビルド順序**: Sass CLI → CSS 結合 → PostCSS（Tailwind + autoprefixer）→ 最終 CSS
- Sass コンパイルが先に走るため、`@layer` ディレクティブは CSS として PostCSS に渡る。Tailwind v4 はこれを正しく処理する
- **Phase の境界はファイル単位ではなく責務単位**。1 つのファイルの中でも、Phase 1 の責務（CSS 変数定義、`:root` ブロック）と Phase 3 の責務（グリッドクラス本体）が同居している場合がある。Phase 1 では Phase 1 の責務のみ変更し、他の責務には触れない

---

## 各ファイルの役割分担

| ファイル | 役割 |
|---|---|
| `src/scss/style.scss` | SCSS エントリポイント。Sass CLI がこのファイルをコンパイルして `css/style.css` を生成する |
| `src/scss/tailwind-base.css` | **新規**。Tailwind ディレクティブ専用の CSS ファイル。`@import "tailwindcss"` + `@config` の 2 行のみ。Sass を通さず、結合ステップで Sass 出力の先頭に挿入する |
| `src/scss/_tailwind-base-layer.scss` | **新規**。SCSS ファイル。`@use "global" as g;` で `g.get_vw()` や `g.$sm` 等の SCSS 関数・変数を使用する（同ファイルは `src/scss/` 直下に配置されるため、同階層の `global/` ディレクトリへは `../` 不要）。`@layer base` / `@layer components` を含むが、Sass は `@layer` を未知の at-rule としてそのまま出力するため問題ない。Sass コンパイル後の CSS が PostCSS (Tailwind) で処理される |
| `tailwind.config.js` | **新規**。Tailwind v4 の設定。`tailwind-base.css` の `@config` から参照される |
| `postcss.config.js` | PostCSS プラグイン設定。`@tailwindcss/postcss` を追加して Tailwind を処理する |
| `package.json` | ビルド / watch スクリプト。Sass → 結合 → PostCSS のパイプラインを定義する |
| `src/scss/layout/_grid.scss` | `:root` の gutter 系 CSS 変数（L114-165）+ グリッドクラス本体（L170-234）。Phase 1 では `:root` ブロックのみ `_tailwind-base-layer.scss` の `@layer base` に移動する。グリッドクラス本体は Phase 3 まで変更しない |

---

## 作業 A: 依存パッケージの更新とビルドパイプライン構築

### A-1. npm install

```
npm install -D tailwindcss @tailwindcss/postcss postcss-cli@latest autoprefixer@latest
```

現行の依存との整合:
- `postcss` (8.4.14) — そのまま使う。Tailwind v4 の `@tailwindcss/postcss` は PostCSS 8 に対応
- `postcss-cli` (7.1.1) → **最新版に更新**。v7 系は内部で PostCSS 7 を引くため、`@tailwindcss/postcss`（PostCSS 8 前提）と不整合を起こす。PostCSS 8 対応の v10 系以降が必要
- `autoprefixer` (9.8.6) → **最新版に更新**。v9 系は内部で PostCSS 7 を引くため同様に不整合を起こす。PostCSS 8 対応の v10 系以降が必要
- `sass` (1.32.8) — そのまま使う。SCSS コンパイルは Tailwind とは独立

### A-2. `postcss.config.js` の更新

```js
module.exports = {
  plugins: [
    require('@tailwindcss/postcss'),
    require('autoprefixer')()
  ]
}
```

### A-3. CSS エントリポイント戦略

**`style.scss` に `@import "tailwindcss"` を入れない。** Sass は `@import "tailwindcss"` を SCSS の `@import` として解釈し、ファイルが見つからずエラーになる。

代わりに **Sass 出力後の CSS に Tailwind ディレクティブを先頭結合する方式** をとる:

1. `src/scss/tailwind-base.css`（CSS ファイル。Sass を通さない）を新規作成:

```css
@import "tailwindcss";
@config "../../tailwind.config.js";
```

2. 結合コマンド:

```sh
cat src/scss/tailwind-base.css css/style.css > css/style.tmp.css && mv css/style.tmp.css css/style.css
```

Tailwind ディレクティブが CSS の先頭に来るため、PostCSS が `@import "tailwindcss"` を解決して Preflight + ユーティリティを注入し、その後に Sass 出力のスタイルが続く。

### A-4. `package.json` の scripts 更新

#### 本番ビルド（`npm run build`）

実行順序（`npm-run-all -s` で逐次実行）:

```
1. prd:scss     — Sass コンパイル（style.scss → css/style.css、compressed）
2. prd:concat   — tailwind-base.css + css/style.css を結合
3. prd:postcss  — PostCSS（Tailwind 展開 + autoprefixer）
4. prd:webpack  — JS バンドル（変更なし）
```

```json
{
  "prd:scss":    "sass --no-charset $npm_package_config_scssDir/style.scss:$npm_package_config_cssDir/style.css --style=compressed",
  "prd:concat":  "cat src/scss/tailwind-base.css css/style.css > css/style.tmp.css && mv css/style.tmp.css css/style.css",
  "prd:postcss": "postcss $npm_package_config_cssDir/style.css -o $npm_package_config_cssDir/style.css",
  "prd:webpack": "webpack --mode=production",
  "build":       "npm-run-all -s prd:scss prd:concat prd:postcss prd:webpack"
}
```

#### 開発 watch（`npm run start`）

実行順序:

```
並列起動（npm-run-all -p）:
  ├── watch:css       — SCSS 変更を検知 → css:build（Sass → 結合 → PostCSS）を逐次実行
  ├── watch:templates — PHP 変更を検知 → css:build（Sass → 結合 → PostCSS）を逐次実行（Tailwind の content rescan 用）
  ├── watch:webpack   — JS watch（変更なし）
  ├── watch:img       — 画像最適化 watch（変更なし）
  └── watch:server    — BrowserSync（css/style.css の変更を検知してブラウザリロード／スタイル注入のみ。CSS 生成の責務は持たない）
```

`watch:css` / `watch:templates` はどちらも `css:build` を実行する（3 ステップ逐次実行）:

```
1. scss:compile  — Sass コンパイル（style.scss → css/style.css）
2. css:concat    — tailwind-base.css + css/style.css を結合
3. css:postcss   — PostCSS（Tailwind 展開 + autoprefixer）→ css/style.css 上書き
```

> **`watch:templates` が `css:build`（Sass 込み）を実行する理由:**
> concat は Sass 出力（css/style.css）に tailwind-base.css を先頭結合する設計である。css/style.css は最終出力ファイルでもあるため、PostCSS 実行後は Tailwind 展開済みの CSS に上書きされている。もし concat → PostCSS のみを実行すると、すでに Tailwind 展開済みの css/style.css に再度 tailwind-base.css を結合することになり、Tailwind 出力が重複する。Sass から再実行すれば css/style.css が Sass のクリーンな出力に戻るため、重複は発生しない。

> **`watch:templates` が必要な理由:** Tailwind v4 は `content` で指定された PHP ファイルをスキャンしてユーティリティクラスを検出する。PHP テンプレートに新しい Tailwind クラスを追加した場合、SCSS は変わらないため `watch:css` は発火しない。`watch:templates` が PHP の変更を検知して `css:build` を再実行することで、PostCSS (Tailwind) が PHP を再スキャンし、新しいユーティリティが CSS に反映される。

```json
{
  "scss:compile":    "sass --no-charset $npm_package_config_scssDir/style.scss:$npm_package_config_cssDir/style.css --style=compressed",
  "css:concat":      "cat src/scss/tailwind-base.css css/style.css > css/style.tmp.css && mv css/style.tmp.css css/style.css",
  "css:postcss":     "postcss $npm_package_config_cssDir/style.css -o $npm_package_config_cssDir/style.css",
  "css:build":       "npm-run-all -s scss:compile css:concat css:postcss",
  "watch:css":       "watch 'npm run css:build' ./src/scss",
  "watch:templates": "onchange './**/*.php' -e 'node_modules/**' -- npm run css:build",
  "watch:webpack":   "webpack --mode=development",
  "watch:img":       "onchange './src/img' -e '**/*.DS_Store' -- npm run imagemin",
  "watch:server":    "browser-sync start -p $npm_package_config_bsProxy --port 3000 --listen localhost --no-ui --no-open -f 'css/style.css, js, **/*.php, **/*.html, !node_modules/**/*'",
  "start":           "npm-run-all -p watch:css watch:templates watch:webpack watch:img watch:server"
}
```

> **旧 `watch:scss`** は `css:build` → `watch:css` に置き換え。旧スクリプトは削除する。
> **BrowserSync** の責務はブラウザリロード／スタイル注入のみ。`css/style.css` のファイル変更を検知してリロードする。CSS 生成は `watch:css` と `watch:templates` が担当する。

---

## 作業 B: `tailwind.config.js` の作成

`wp-thm/tailwind.config.js` を新規作成。

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
  ],

  theme: {
    // --- screens ---
    // 出典: _variables.scss L15-19
    screens: {
      sm: '576px',
      md: '811px',
      lg: '1025px',
      xl: '1280px',
      '2xl': '1366px',
    },

    // --- container ---
    // 出典: _variables.scss L37-41 ($container-max-*)
    // theme.container は extend.maxWidth とは独立した設定。
    // .l-row--container → container mx-auto への置き換え（Phase 3）で使用する。
    container: {
      center: true,
      screens: {
        sm: '540px',
        md: '960px',
        lg: '1152px',
        xl: '1200px',
        '2xl': '1260px',
      },
    },

    // --- colors ---
    // 出典: _variables-color.scss → CSS 変数参照
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      white: '#fff',
      black: '#222',
      clr1: 'var(--clr1)',
      clr2: 'var(--clr2)',
      clr3: 'var(--clr3)',
      clr4: 'var(--clr4)',
      clr5: 'var(--clr5)',
      'clr-prim-green': 'var(--clr-prim-green)',
      gray: {
        50:  'var(--clrg50)',
        100: 'var(--clrg100)',
        200: 'var(--clrg200)',
        300: 'var(--clrg300)',
        400: 'var(--clrg400)',
        500: 'var(--clrg500)',
        600: 'var(--clrg600)',
        700: 'var(--clrg700)',
        800: 'var(--clrg800)',
        900: 'var(--clrg900)',
      },
      link: 'var(--link-color)',
      'link-hover': 'var(--link-hover-color)',
    },

    // --- spacing ---
    // 出典: _variables.scss L90-116 ($space_values)
    // mt-4 = 32px を維持。Tailwind デフォルト (mt-4 = 16px) には合わせない
    spacing: {
      0: '0px',
      px: '1px',
      0.5: '4px',
      0.75: '6px',
      1: '8px',
      1.25: '10px',
      1.5: '12px',
      2: '16px',
      2.25: '18px',
      2.5: '20px',
      3: '24px',
      3.5: '28px',
      4: '32px',
      4.5: '36px',
      5: '40px',
      6: '48px',
      7: '56px',
      8: '64px',
      9: '72px',
      10: '80px',
      12: '96px',
      13: '104px',
      14: '112px',
      15: '120px',
      16: '128px',
      18: '144px',
      20: '160px',
    },

    // --- zIndex ---
    // 出典: _variables.scss L147-153 ($layout_zindex)
    zIndex: {
      auto: 'auto',
      default: '1',
      swiper: '100',
      footer: '500',
      header: '1000',
      micromodal: '2000',
    },

    // --- fontFamily ---
    // 出典: _variables.scss L79-82
    fontFamily: {
      sans: [
        'avenir', '"Noto Sans JP"', '"游ゴシック体"', 'yugothic',
        '"游ゴシック Medium"', '"Yu Gothic Medium"',
        '"ヒラギノ角ゴ ProN W3"', '"Hiragino Kaku Gothic ProN"',
        '"メイリオ"', 'meiryo', 'sans-serif',
      ],
      serif: [
        '"Times New Roman"', '"游明朝体"', 'yumincho', '"游明朝"',
        '"Yu Mincho"', '"ヒラギノ明朝 ProN W3"', '"Hiragino Mincho ProN"',
        '"HGS明朝E"', '"ＭＳ Ｐ明朝"', '"MS PMincho"', 'serif',
      ],
      mono: [
        'menlo', 'monaco', 'consolas', '"Liberation Mono"',
        '"Courier New"', 'monospace',
      ],
    },

    // --- borderRadius ---
    // 出典: _variables.scss L87
    borderRadius: {
      DEFAULT: '6px',
    },

    // --- transitionDuration ---
    // 出典: _variables.scss L88 ($transition-base の duration 部分)
    transitionDuration: {
      DEFAULT: '200ms',
    },

    extend: {
      // --- maxWidth ---
      // 出典: _variables.scss L37-41 ($container-max-*)
      maxWidth: {
        'container-sm': '540px',
        'container-md': '960px',
        'container-lg': '1152px',
        'container-xl': '1200px',
        'container-xxl': '1260px',
      },

      // --- padding (gutter系) ---
      // 出典: 計画書 §3.4
      padding: {
        'gutter-1': 'calc(var(--gutter) * 1)',
        'gutter-1.5': 'calc(var(--gutter) * 1.5)',
        'gutter-2': 'calc(var(--gutter) * 2)',
        'gutter-3': 'calc(var(--gutter) * 3)',
        'gutter-row': 'var(--gutter-row)',
      },

      // --- gap (grid用) ---
      // 出典: 計画書 §3.4
      gap: {
        'grid-gutter': 'calc(var(--gutter) * 2)',
      },
    },
  },
}
```

---

## 作業 C: `@layer base` の構築 + `:root` CSS 変数 + gutter CSS 変数 + フォーム CSS 変数

新規ファイル `src/scss/_tailwind-base-layer.scss` を作成し、`style.scss` から `@use` する。

**このファイルは SCSS ファイルであり、`@use "global" as g;` で `g.get_vw()`、`g.$sm` 等の SCSS 関数・変数を使用する。**（`src/scss/` 直下に配置されるため、同階層の `global/` ディレクトリへは `../` 不要。`../global` を使うのは `layout/` や `component/` 等のサブディレクトリ内ファイル） `@layer base` / `@layer components` はSass にとって未知の at-rule であり、そのまま CSS に出力される。Sass コンパイル後の CSS を PostCSS (Tailwind) が処理し、`@layer` の順序制御が適用される。

### C-1. `:root` CSS 変数（カテゴリ A: 22 変数）

出典: `_variable-inventory.md` カテゴリ A + `_variables-color.scss`

```css
@layer base {
  :root {
    --clr1: #4FBA43;
    --clr2: #9BD22D;
    --clr3: #725907;
    --clr4: #B69941;
    --clr5: #f6f0dd;
    --clr-prim-green: #41c45d;
    --grd1: linear-gradient(120deg, #9BD22D, #4FBA43);
    --grd2: linear-gradient(90deg, #FDD88A, #9E8004);
    --black: #222;
    --clrg50: #fbfbfb;
    --clrg100: #f5f5f5;
    --clrg200: #e8e8e8;
    --clrg300: #d5d5d5;
    --clrg400: #b0b0b0;
    --clrg500: #959595;
    --clrg600: #858585;
    --clrg700: #767676;
    --clrg800: #5b5b5b;
    --clrg900: #3f3f3f;
    --gray: var(--clrg700);
    --link-color: var(--clrg800);
    --link-hover-color: #747474;
  }
}
```

> **`--link-hover-color: #747474` の算出根拠:**
> `$link-hover-color: color.scale($link-color, $lightness: 15%)`
> `$link-color` = `$clrg800` = `color.scale(#222, $lightness: 26%)` = `#5b5b5b`
> `color.scale(#5b5b5b, $lightness: 15%)` = `rgb(116, 116, 116)` = `#747474`
> Sass CLI で `npx sass` を実行して算出・確認済み。

### C-2. gutter 系 CSS 変数（`--unit`, `--space`, `--gutter`, `--gutter-row`）

**定義元: `src/scss/layout/_grid.scss` L114-165 の `:root` ブロック。**

計画書 §1.2 に基づき、この `:root` ブロックを Phase 1 で `@layer base` に移動する。

現在の `layout/_grid.scss` L114-165:

```scss
:root {
  --unit: #{g.get_vw(1, 390)};
  --space: #{g.get_vw(8, 390)};
  --gutter: #{g.get_vw(6, 390)};
  --gutter-row: #{g.get_vw(16, 390)};
  @media #{g.$sm} { ... }
  @media #{g.$md} { ... }
  @media #{g.$lg} { ... }
  @media #{g.$xl} { ... }
  @media #{g.$xxl} { ... }
}
```

**Phase 1 での変更内容:**

1. `layout/_grid.scss` から `:root` ブロック（L114-165）を削除する
2. 同じ `:root` 定義を `_tailwind-base-layer.scss` の `@layer base` 内に移動する
3. `_tailwind-base-layer.scss` は SCSS ファイルであり `@use "global" as g;` を持つため、`g.get_vw()` や `g.$sm` 等をそのまま使用できる。値のハードコードは不要

**`_tailwind-base-layer.scss` 内での記述イメージ:**

```scss
@use "global" as g;

@layer base {
  :root {
    --unit: #{g.get_vw(1, 390)};
    --space: #{g.get_vw(8, 390)};
    --gutter: #{g.get_vw(6, 390)};
    --gutter-row: #{g.get_vw(16, 390)};

    @media #{g.$sm} {
      --unit: #{g.get_vw(1, 768)};
      --space: #{g.get_vw(8, 768)};
      --gutter: #{g.get_vw(12, 768)};
      --gutter-row: #{g.get_vw(42, 768)};
    }
    // ... md, lg, xl, xxl も同様に移動
  }
}
```

Sass は `@layer base` を未知の at-rule としてそのまま出力し、内部の `g.get_vw()` は計算済み vw 値に展開される。PostCSS (Tailwind) が `@layer` の順序制御を行う。

**Phase 1 で変更するのは `:root` ブロックのみ。** `layout/_grid.scss` の L170 以降にあるグリッドクラス本体（`.l-row`, `.l-grid`, `.c-col*`, `.c-grid*`）は Phase 3 まで一切変更しない。

### C-3. リセット CSS の移行

出典: `_reset-diff-inventory.md` の「補足が必要なスタイル一覧」（L370-661）

`@layer base` ブロック内に以下のセクションを記述:

- Box Model（`*::before, *::after` の font-smoothing）
- Document（`html` の text-size-adjust、`body` のフォント・色・背景）
- Headings（`h1`-`h6` の line-height: inherit）
- Lists（`dt` の font-weight、`ul` の list-style-position）
- Links（`a` の text-decoration: none、color、transition、hover、`:not([href])` 系）
- Text-level Semantics（`abbr`、`address`、`code/kbd/samp`、`pre`）
- Images（`embed/object/iframe` の vertical-align、`a img` の transition）
- Forms（`button/input/optgroup/textarea` の appearance、cursor、label、fieldset、legend、textarea、select、`::placeholder`、`input:-webkit-autofill`）
- Tables（`caption`、`td/th` の vertical-align/text-align）
- Grouping/Misc（`hr`、`[contenteditable]`）
- Custom（`::selection`、touch-action、`[role="button"]`、`[tabindex="-1"]:focus`、`.clearfix::after`）

### C-4. `destyle.css` と `_reboot.scss` の廃止

- `style.scss` から `@use "foundation/_destyle"` を削除（コメントアウト）
- `style.scss` から `@use "foundation/_reboot"` を削除（コメントアウト）
- ファイル自体は削除せず残しておく（ロールバック用。Phase 5 完了後に削除）

### C-5. カテゴリ C: `.p-form` スコープ CSS 変数（Phase 1 で 19 変数を定義し切る）

出典: `_variable-inventory.md` カテゴリ C（19 変数）+ `_variables-form.scss` + `project/_form.scss` の実参照

`@layer components` で定義:

```css
@layer components {
  .p-form {
    /* --- input 系（10 変数） --- */
    --form-padding-x: 0.625rem;
    --form-padding-y: 0.625rem;
    --form-line-height: 1.25;
    --form-bg: #fff;
    --form-color: var(--clrg700);
    --form-border-color: var(--clrg500);
    --form-border-width: 1px;
    --form-border-radius: 6px;
    --form-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    --form-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;

    /* --- select 固有（3 変数） --- */
    --form-select-bg: #fff;
    --form-select-bg-size: 12px;
    --form-select-indicator: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
  }
}
```

**変数数の内訳（計 19 変数の完全マッピング）:**

| # | SCSS 変数 | CSS 変数 | 備考 |
|---|---|---|---|
| 1 | `$input-padding-x` | `--form-padding-x` | |
| 2 | `$input-padding-y` | `--form-padding-y` | |
| 3 | `$input-line-height` | `--form-line-height` | |
| 4 | `$input-bg` | `--form-bg` | |
| 5 | `$input-color` | `--form-color` | |
| 6 | `$input-border-color` | `--form-border-color` | |
| 7 | `$input-btn-border-width` | `--form-border-width` | |
| 8 | `$input-box-shadow` | `--form-box-shadow` | |
| 9 | `$input-border-radius` | `--form-border-radius` | |
| 10 | `$input-transition` | `--form-transition` | |
| 11 | `$custom-select-line-height` | = `--form-line-height` | `$input-line-height` と同値。統合 |
| 12 | `$custom-select-color` | = `--form-color` | `$input-color` と同値。統合 |
| 13 | `$custom-select-bg` | `--form-select-bg` | |
| 14 | `$custom-select-bg-size` | `--form-select-bg-size` | |
| 15 | `$custom-select-indicator-bgi` | `--form-select-indicator` | |
| 16 | `$custom-select-indicator-repeat` | （CSS 変数化しない） | 固定値 `no-repeat`。select ルール内に直書き |
| 17 | `$custom-select-indicator-position` | （CSS 変数化しない） | 固定値 `right 6px center`。select ルール内に直書き |
| 18 | `$custom-select-border-width` | = `--form-border-width` | `$input-btn-border-width` と同値。統合 |
| 19 | `$custom-select-border-color` | = `--form-border-color` | `$input-border-color` と同値。統合 |

> CSS 変数の実体数は 13 個（統合 4 個 + 直書き 2 個 = 6 個が別変数不要）。
> 19 変数すべての行き先が確定している。

**`project/_form.scss` の実参照による自己点検:**

`_form.scss` で `f.` プレフィックスで参照されている変数を全行検索した結果:

| 参照箇所 | SCSS 変数 | 上表の # | 状態 |
|---|---|---|---|
| L299 | `f.$input-padding-y`, `f.$input-padding-x` | #1, #2 | 定義済み |
| L300 | `f.$input-transition` | #10 | 定義済み |
| L301 | `f.$input-btn-border-width`, `f.$input-border-color` | #7, #6 | 定義済み |
| L302, L38, L142, L353 | `f.$input-border-radius` | #9 | 定義済み |
| L303, L317, L370 | `f.$input-bg` | #4 | 定義済み |
| L305, L319, L371 | `f.$input-color` | #5 | 定義済み |
| L306 | `f.$input-line-height` | #3 | 定義済み |
| L352 | `f.$custom-select-border-width`, `f.$custom-select-border-color` | #18, #19 | 統合で対応 |
| L354 | `f.$custom-select-bg` | #13 | 定義済み |
| L355 | `f.$custom-select-indicator-bgi` | #15 | 定義済み |
| L356 | `f.$custom-select-indicator-repeat` | #16 | 直書きで対応 |
| L357 | `f.$custom-select-indicator-position` | #17 | 直書きで対応 |
| L358 | `f.$custom-select-bg-size` | #14 | 定義済み |
| L359 | `f.$custom-select-color` | #12 | 統合で対応 |
| L360 | `f.$custom-select-line-height` | #11 | 統合で対応 |

`_reboot.scss` で参照されている変数（`@layer base` で直接対応）:

| 参照箇所 | SCSS 変数 | 対応 |
|---|---|---|
| L351 | `f.$cursor-disabled` | `cursor: not-allowed` ハードコード（C-3 に含まれる） |
| L430, L435, L440 | `f.$input-color-placeholder` | `::placeholder { color: var(--clrg500); }` 直接記述（C-3 に含まれる） |

**結論: `_variables-form.scss` の全 21 変数（`$input-box-shadow` 含む）+ `_reboot.scss` で使用される 2 変数、計 23 変数すべての行き先が確定。定義漏れなし。**

---

## 作業 D: SCSS 計算関数の維持確認

- `global/_calc.scss` の全関数（`rem`, `get_vw`, `px-to-per`, `px-to-vw`, `get_lh`, `diff_lh`, `strip-unit`）が Tailwind 導入後もそのまま動作することを確認
- `_variables.scss` の `get_zindex()` 関数が引き続き動作することを確認
- **確認方法**: `npm run build` 実行 → エラーなし + 既存コンポーネントの CSS 出力が変わっていないこと（完了条件 #8 で具体的に検証）

---

## ファイル一覧

### 新規作成するファイル

| ファイル | 内容 |
|---|---|
| `wp-thm/tailwind.config.js` | 作業 B の全定義（theme.container 含む） |
| `wp-thm/src/scss/tailwind-base.css` | `@import "tailwindcss"` + `@config` のみ（2 行）。Sass を通さない CSS ファイル |
| `wp-thm/src/scss/_tailwind-base-layer.scss` | 作業 C 全体（`:root` CSS 変数 + gutter CSS 変数 + `@layer base` リセット + `.p-form` CSS 変数）。SCSS ファイルとして `@use "global" as g;` で SCSS 関数を使用（`src/scss/` 直下のため `../` 不要） |

### 変更するファイル

| ファイル | 変更内容 |
|---|---|
| `wp-thm/postcss.config.js` | `@tailwindcss/postcss` プラグインを追加 |
| `wp-thm/package.json` | devDependencies: `tailwindcss` `@tailwindcss/postcss` 追加、`postcss-cli` `autoprefixer` を PostCSS 8 対応版に更新。scripts: `css:concat`, `css:postcss`, `css:build` 新規追加、`watch:scss` → `watch:css` に置換、`watch:templates` 追加、`build` の実行順序変更 |
| `wp-thm/src/scss/style.scss` | `@use "foundation/_destyle"` と `@use "foundation/_reboot"` をコメントアウト。`@use "_tailwind-base-layer"` を追加 |
| `wp-thm/src/scss/layout/_grid.scss` | `:root` ブロック（L114-165）を削除。グリッドクラス本体（L170-234: `.l-row`, `.l-grid`, `.c-col*`, `.c-grid*`）は変更しない |

### 変更しないファイル

| ファイル | 理由 |
|---|---|
| `wp-thm/webpack.config.js` | JS バンドルのみ。CSS パイプラインに関係なし |
| `wp-thm/src/scss/foundation/_variables.scss` | Phase 1 では SCSS 変数を維持。config に値をコピーするだけ |
| `wp-thm/src/scss/foundation/_variables-color.scss` | 同上。CSS 変数への移行は `_tailwind-base-layer.scss` 側で行い、元ファイルは残す |
| `wp-thm/src/scss/foundation/_variables-form.scss` | 同上。CSS 変数化は `_tailwind-base-layer.scss` 側 |
| `wp-thm/src/scss/foundation/_destyle.css` | ファイル削除はしない。`style.scss` からの `@use` を外すだけ |
| `wp-thm/src/scss/foundation/_reboot.scss` | 同上 |
| `wp-thm/src/scss/global/_calc.scss` | 維持確認のみ。変更なし |
| `wp-thm/src/scss/global/_index.scss` | 変更なし。既存の `@forward` 構成を維持 |
| `wp-thm/src/scss/global/_gutter.scss` | Phase 3 で廃止。Phase 1 では触らない |
| `wp-thm/src/scss/layout/_grid.scss` のグリッドクラス部分 | `.l-row`, `.l-grid`, `.c-col*`, `.c-grid*` は Phase 3 で対応。Phase 1 では `:root` ブロックのみ移動 |
| `wp-thm/src/scss/component/` 全体 | Phase 4 で対応 |
| `wp-thm/src/scss/project/` 全体 | Phase 4 で対応 |
| `wp-thm/src/scss/utility/` 全体 | Phase 2 で対応 |
| `wp-thm/*.php` | Phase 2/3 で対応 |

---

## 完了条件

### #1. ビルドが通る

- `npm run build` がエラーなく完了する
- `npm run start` で watch が起動し、SCSS ファイルを保存すると `css/style.css` が Tailwind 展開済みで再生成される

### #2. Tailwind Preflight が出力に含まれている

確認方法: `css/style.css` 内に以下のセレクタが存在すること

```
*, ::before, ::after { box-sizing: border-box; ... }
```

Preflight の特徴的なルール `box-sizing: border-box` がユニバーサルセレクタで出力されていれば Preflight が展開されている。

### #3. `:root` カラー CSS 変数が出力されている

確認方法: `css/style.css` 内に以下の変数が存在すること

```
:root {
  --clr1: #4FBA43;
  --clrg500: #959595;
  --link-hover-color: #747474;
}
```

最低でも `--clr1`（ブランドカラー先頭）、`--clrg500`（グレースケール中間）、`--link-hover-color`（算出値）の 3 つで確認。

### #4. gutter 系 CSS 変数が出力されている

確認方法: `css/style.css` 内に以下が存在すること

```
:root {
  --gutter: ...vw;
  --gutter-row: ...vw;
}
```

ブレークポイントごとの `@media (min-width: 576px) { :root { --gutter: ...` も存在すること。

### #5. リセット補足コードが出力されている

確認方法: `css/style.css` 内に以下のセレクタが存在すること

- `::selection { background: #afe14a; }` — カスタム選択色
- `a { text-decoration: none; ... }` — リンクリセット
- `ul { list-style-position: inside; }` — リストスタイル
- `::placeholder { color: var(--clrg500); }` — placeholder カスタム色

### #6. `.p-form` スコープ CSS 変数が出力されている

確認方法: `css/style.css` 内に以下が存在すること

```
.p-form {
  --form-padding-x: 0.625rem;
  --form-border-color: var(--clrg500);
  --form-select-indicator: url("data:image/svg+xml,...");
}
```

最低でも `--form-padding-x`（input 系）、`--form-border-color`（共通系）、`--form-select-indicator`（select 固有）の 3 つで確認。

### #7. 既存コンポーネント CSS が出力されている

確認方法: `css/style.css` 内に以下のセレクタが存在すること

- `.c-button` — component 層
- `.p-header` — project 層
- `.l-row` — layout 層（Phase 3 まで維持）
- `.c-col--12` — グリッドカラム（Phase 3 まで維持）

### #8. SCSS 計算関数の出力値が変わっていない

確認方法: Phase 1 実行前に `css/style.css` のスナップショットを取得し、Phase 1 完了後の出力と diff を取る。以下の観点で差分を確認:

- `g.rem()` の出力（例: `1rem`, `2rem` 等）が変わっていないこと
- `g.get_vw()` の出力（例: `...vw` 値）が変わっていないこと
- Tailwind Preflight / `@layer base` / `.p-form` CSS 変数の追加は想定内の差分として許容
- それ以外の既存セレクタの値変更がないこと

---

## v5 → v6 の変更点

- `postcss-cli` と `autoprefixer` を PostCSS 8 対応版に更新する前提に修正。現行の `postcss-cli` (7.1.1) と `autoprefixer` (9.8.6) は lockfile 上で PostCSS 7 系を引いており、`@tailwindcss/postcss`（PostCSS 8 前提）と不整合を起こすため「そのまま使う」は不正確だった
- `npm install` コマンドに `postcss-cli@latest autoprefixer@latest` を追加
- ファイル一覧の `package.json` 変更内容説明に `postcss-cli` `autoprefixer` の更新を明記

## v4 → v5 の変更点

- `tailwind.config.js` の `content` から `./src/**/*.js` を削除し、PHP のみ（`./**/*.php`）に変更。この WordPress テーマの JS はフロントの挙動制御が中心で、Tailwind ユーティリティ文字列を動的生成していないため、content に含める根拠がない

## v3 → v4 の変更点

- `watch:templates` を `css:build`（Sass → concat → PostCSS のフルパイプライン）に変更。v3 では `css:tailwind`（concat → PostCSS のみ）を実行していたが、css/style.css は PostCSS 実行後に Tailwind 展開済みの最終出力に上書きされるため、concat のみでは tailwind-base.css が重複結合される問題があった。Sass から再実行すれば css/style.css がクリーンな Sass 出力に戻るため重複しない
- `css:tailwind` スクリプトを削除。中間ファイル方式（Sass 出力を final と別に持つ）を採用しない限り、concat → PostCSS のみの部分実行は安全でないため
- `@use` パスを修正: `@use "../global" as g;` → `@use "global" as g;`。`_tailwind-base-layer.scss` は `src/scss/` 直下に配置されるため、同階層の `global/` ディレクトリへは `../` 不要。`../global` を使うのは `layout/`、`component/` 等のサブディレクトリ内ファイル（1 階層上に戻る必要があるため）
- パスの根拠を各記述箇所に明記

## v2 → v3 の変更点

- `watch:templates` を追加: PHP 変更検知 → CSS 再生成
- `theme.container` を `tailwind.config.js` に追加: `center: true` + カスタム `screens`（sm:540, md:960, lg:1152, xl:1200, 2xl:1260）。`extend.maxWidth` とは独立した設定
- `_tailwind-base-layer.scss` の記述を統一: SCSS ファイルとして SCSS 関数を使用する方式に確定。ハードコード方式への言及と「代替案」の記述を削除
- C-2 に `_tailwind-base-layer.scss` 内での gutter 記述イメージ（SCSS コード）を追加
- ファイル役割分担の `_tailwind-base-layer.scss` 説明を更新: SCSS ファイルとしての性質を正確に記述
