# SCSS → Tailwind CSS 移行 事前調査レポート

> 生成日: 2026-02-25
> 対象: `wp-thm/src/scss/` 配下の全 SCSS 資産
> 方針: 現状と同じことができるように Tailwind の流儀で再構築する（SCSS をそのまま載せ替えるのではない）

---

## 目次

1. [変数 → tailwind.config.js マッピング表](#1-変数--tailwindconfigjs-マッピング表)
2. [SCSS 計算関数 → CSS calc() 変換表](#2-scss-計算関数--css-calc-変換表)
3. [Mixin → Tailwind 代替方法の一覧表](#3-mixin--tailwind-代替方法の一覧表)
4. [CSS 変数 (:root) の calc() 変換済み完成形](#4-css-変数-root-の-calc-変換済み完成形)
5. [グリッドシステム → Tailwind grid/gap 再現の対応表](#5-グリッドシステム--tailwind-gridgap-再現の対応表)
6. [ファイルごとの移行カテゴリ分類表](#6-ファイルごとの移行カテゴリ分類表)
7. [ベンダー CSS の扱い方針](#7-ベンダー-css-の扱い方針)
8. [動的クラス生成（@each/@for）の Tailwind 移行パターン](#8-動的クラス生成eachfor-の-tailwind-移行パターン)
9. [計算関数の使用箇所リスト](#9-計算関数の使用箇所リスト)
10. [移行時の注意点・リスク](#10-移行時の注意点リスク)

---

## 1. 変数 → tailwind.config.js マッピング表

### 1.1 ブレークポイント → `theme.screens`

| SCSS 変数              | 値     | tailwind.config.js      |
| ---------------------- | ------ | ----------------------- |
| $grid-breakpoints(sm)  | 576px  | `screens.sm: '576px'`   |
| $grid-breakpoints(md)  | 811px  | `screens.md: '811px'`   |
| $grid-breakpoints(lg)  | 1025px | `screens.lg: '1025px'`  |
| $grid-breakpoints(xl)  | 1280px | `screens.xl: '1280px'`  |
| $grid-breakpoints(xxl) | 1366px | `screens.2xl: '1366px'` |

> **注意**: Bootstrap 標準 (768, 992, 1200) と異なるカスタム値。Tailwind デフォルト (640, 768, 1024, 1280, 1536) とも異なる。完全に再定義が必要。

### 1.2 コンテナ最大幅

| SCSS 変数          | 値     | 移行先                                  |
| ------------------ | ------ | --------------------------------------- |
| $container-max-sm  | 540px  | CSS変数 or `@layer base` コンテナルール |
| $container-max-md  | 960px  | 同上                                    |
| $container-max-lg  | 1152px | 同上                                    |
| $container-max-xl  | 1200px | 同上                                    |
| $container-max-xxl | 1260px | 同上                                    |

### 1.3 スペーシング → `theme.spacing`

`$space_values` マップ（25エントリ）:

| キー | px値  | tailwind.config.js キー   |
| ---- | ----- | ------------------------- |
| 0_5  | 4px   | `spacing['0.5']: '4px'`   |
| 0_75 | 6px   | `spacing['0.75']: '6px'`  |
| 1    | 8px   | `spacing['1']: '8px'`     |
| 1_25 | 10px  | `spacing['1.25']: '10px'` |
| 1_5  | 12px  | `spacing['1.5']: '12px'`  |
| 2    | 16px  | `spacing['2']: '16px'`    |
| 2_25 | 18px  | `spacing['2.25']: '18px'` |
| 2_5  | 20px  | `spacing['2.5']: '20px'`  |
| 3    | 24px  | `spacing['3']: '24px'`    |
| 3_5  | 28px  | `spacing['3.5']: '28px'`  |
| 4    | 32px  | `spacing['4']: '32px'`    |
| 4_5  | 36px  | `spacing['4.5']: '36px'`  |
| 5    | 40px  | `spacing['5']: '40px'`    |
| 6    | 48px  | `spacing['6']: '48px'`    |
| 7    | 56px  | `spacing['7']: '56px'`    |
| 8    | 64px  | `spacing['8']: '64px'`    |
| 9    | 72px  | `spacing['9']: '72px'`    |
| 10   | 80px  | `spacing['10']: '80px'`   |
| 12   | 96px  | `spacing['12']: '96px'`   |
| 13   | 104px | `spacing['13']: '104px'`  |
| 14   | 112px | `spacing['14']: '112px'`  |
| 15   | 120px | `spacing['15']: '120px'`  |
| 16   | 128px | `spacing['16']: '128px'`  |
| 18   | 144px | `spacing['18']: '144px'`  |
| 20   | 160px | `spacing['20']: '160px'`  |

`$space_values_with_clamp`（23エントリ）→ CSS変数 `--space-*` として `:root` に配置し、`theme.spacing` から `var()` で参照。

### 1.4 カラー → `theme.colors`

| SCSS 変数       | 値         | tailwind.config.js                 |
| --------------- | ---------- | ---------------------------------- |
| $clr1           | #4FBA43    | `colors.brand.green: '#4FBA43'`    |
| $clr2           | #9BD22D    | `colors.brand.lime: '#9BD22D'`     |
| $clr3           | #725907    | `colors.brand.darkGold: '#725907'` |
| $clr4           | #B69941    | `colors.brand.gold: '#B69941'`     |
| $clr5           | #f6f0dd    | `colors.brand.cream: '#f6f0dd'`    |
| $clr-prim-green | #41c45d    | `colors.primary: '#41c45d'`        |
| $black          | #222       | `colors.black: '#222'`             |
| $clrg50         | #fbfbfb    | `colors.gray.50: '#fbfbfb'`        |
| $clrg100        | #f5f5f5    | `colors.gray.100: '#f5f5f5'`       |
| $clrg200        | #e8e8e8    | `colors.gray.200: '#e8e8e8'`       |
| $clrg300        | #d5d5d5    | `colors.gray.300: '#d5d5d5'`       |
| $clrg400        | #b0b0b0    | `colors.gray.400: '#b0b0b0'`       |
| $clrg500        | #959595    | `colors.gray.500: '#959595'`       |
| $clrg600        | #858585    | `colors.gray.600: '#858585'`       |
| $clrg700        | #767676    | `colors.gray.700: '#767676'`       |
| $clrg800        | #5b5b5b    | `colors.gray.800: '#5b5b5b'`       |
| $clrg900        | #3f3f3f    | `colors.gray.900: '#3f3f3f'`       |
| $gray           | = $clrg700 | `colors.gray.DEFAULT: '#767676'`   |

### 1.5 グラデーション → CSS変数

| SCSS 変数 | 値                                        | 移行先                  |
| --------- | ----------------------------------------- | ----------------------- |
| $grd1     | linear-gradient(120deg, #9BD22D, #4FBA43) | `:root { --grd1: ... }` |
| $grd2     | linear-gradient(90deg, #FDD88A, #9E8004)  | `:root { --grd2: ... }` |

### 1.6 z-index → `theme.zIndex`

`$layout_zindex` マップ:

| キー       | 値   | tailwind.config.js      |
| ---------- | ---- | ----------------------- |
| micromodal | 2000 | `zIndex.modal: '2000'`  |
| header     | 1000 | `zIndex.header: '1000'` |
| footer     | 500  | `zIndex.footer: '500'`  |
| swiper     | 100  | `zIndex.swiper: '100'`  |
| default    | 1    | `zIndex.base: '1'`      |

### 1.7 フォント → `theme.fontFamily`

| SCSS 変数               | tailwind.config.js                                                 |
| ----------------------- | ------------------------------------------------------------------ |
| $font-family-sans-serif | `fontFamily.sans: ['avenir', '"Noto Sans JP"', '游ゴシック', ...]` |
| $font-family-serif      | `fontFamily.serif: ['"Times New Roman"', '游明朝', ...]`           |
| $font-family-monospace  | `fontFamily.mono: ['menlo', 'monaco', ...]`                        |

### 1.8 その他

| SCSS 変数        | 値                   | tailwind.config.js                              |
| ---------------- | -------------------- | ----------------------------------------------- |
| $border-radius   | 6px                  | `borderRadius.DEFAULT: '6px'`                   |
| $transition-base | all 0.2s ease-in-out | `transitionDuration.DEFAULT: '200ms'` + CSS変数 |
| $grid-columns    | 12                   | (Tailwind grid-cols-12 標準で対応)              |

### 1.9 フォーム変数

`foundation/_variables-form.scss` の変数群（$input-padding-x/y, $input-border-color 等）は `@layer base` でフォーム要素にスタイルとして適用。CSS変数（`var(--clrg*)`) を参照しているため、`:root` のカラー変数定義が前提。

### 1.10 destyle.css + reboot.scss の扱い

| 現状                            | Tailwind移行後                                                 |
| ------------------------------- | -------------------------------------------------------------- |
| destyle.css v2.0.2              | **削除** — Tailwind Preflight（modern-normalize ベース）で代替 |
| \_reboot.scss（Bootstrap 由来） | **削除** — カスタム部分のみ `@layer base` に移植               |

`@layer base` に移植する内容:

- body のフォント・色・背景設定
- font-smoothing（-webkit-font-smoothing, -moz-osx-font-smoothing）
- a タグのトランジション・色設定
- touch-action: manipulation
- ::selection カラー
- input placeholder スタイル

---

## 2. SCSS 計算関数 → CSS calc() 変換表

### 2.1 関数定義一覧（global/\_calc.scss）

| 関数名       | 引数            | デフォルト値 | 用途                             |
| ------------ | --------------- | ------------ | -------------------------------- |
| `strip-unit` | $num            | —            | 数値から単位を除去               |
| `get_lh`     | $fz, $lh        | —            | line-height 算出（単位なし比率） |
| `diff_lh`    | $size, $fz, $lh | —            | line-height 余白補正値           |
| `get_vw`     | $size, $view    | 390          | px → vw 変換                     |
| `px-to-per`  | $px, $view      | 390          | px → % 変換                      |
| `px-to-vw`   | $px, $view      | 390          | px → vw 変換（get_vwと同等）     |
| `rem`        | $size           | —            | px → rem 変換                    |

その他: `unicode($str)` — Unicode 文字出力（\_unicode.scss）、`get_zindex($key)` — z-index マップ参照（\_variables.scss）

### 2.2 変換表

| SCSS 記法                     | Tailwind移行後の扱い（**方針変更**）                 | 備考                                                                   |
| ----------------------------- | ----------------------------------------------------- | ---------------------------------------------------------------------- |
| `g.get_vw(16, 960)`           | **そのまま維持**（`g.get_vw(16, 960)` を使い続ける）  | 人間が px 値（16）で直感的に書ける恩恵を最優先し、SCSS 関数を残す      |
| `g.rem(24)`                   | **そのまま維持**（`g.rem(24)` を使い続ける）          | `0.5rem` 等を人間が計算するのは手間のため、px 入力できる仕組みを維持   |
| `g.px-to-per(100, 960)`       | **そのまま維持**                                      | 同上                                                                   |
| `g.px-to-vw(100, 390)`        | **そのまま維持**                                      | 同上                                                                   |
| `g.get_lh(16, 28)`            | **そのまま維持**                                      | 同上                                                                   |
| `g.diff_lh($size, $fz, $lh)`  | **そのまま維持**                                      | 同上                                                                   |
| `g.strip-unit($val)`          | **そのまま維持**                                      | 他の関数が依存しているため維持                                         |
| `g.get_zindex('header')`      | **そのまま維持** ＋ `theme.zIndex` を併用             | 既存コンポーネント内では維持。Tailwind ユーティリティも設定して併用    |

### 2.3 計算関数の特記事項

ユーザーフィードバックに基づく重要方針: **「静的な rem や calc() への強制変換は行わない」**。

Tailwind の Utility クラス（`mt-6` や `text-xl`）に分解するアプローチは「既存のクラス名（`.hghg` 等）を維持して Tailwind 流儀に載せる」という目的に反する。また、人間が直接 `1.5rem` に計算して書く、あるいは冗長な `calc(24 / 16 * 1rem)` を書かされるのも開発体験を下げる。
よって、**`g.rem()` や `g.get_vw()` などの「px 値を入れて単位換算させる仕組み」は SCSS の関数としてそのまま Tailwind 環境に持ち越す**ことが最適解となる。

---

## 3. Mixin → Tailwind 代替方法の一覧表

### 3.1 代替判定凡例

- **(A)** Tailwind 標準機能で代替
- **(B)** CSS calc() に変換
- **(C)** @layer に CSS で書く
- **(D)** 削除可能（古い or 不要）

### 3.2 一覧

| Mixin 名                                   | 定義ファイル                  | @content | 判定      | Tailwind 代替方法                                                                          |
| ------------------------------------------ | ----------------------------- | -------- | --------- | ------------------------------------------------------------------------------------------ |
| `media-breakpoint-up($bp)`                 | global/\_breakpoints.scss:63  | Yes      | **(A)**   | `sm:`, `md:`, `lg:`, `xl:`, `2xl:` レスポンシブプレフィックス                              |
| `media-breakpoint-down($bp)`               | global/\_breakpoints.scss:76  | Yes      | **(A)**   | `max-sm:`, `max-md:` 等                                                                    |
| `media-breakpoint-between($lower, $upper)` | global/\_breakpoints.scss:97  | Yes      | **(A)**   | `sm:max-md:` 等の組み合わせ                                                                |
| `media-breakpoint-only($bp)`               | global/\_breakpoints.scss:126 | Yes      | **(A)**   | 同上の組み合わせ                                                                           |
| `hover`                                    | global/\_hover.scss:3         | Yes      | **(A)** ※ | `hover:` プレフィックス。ただし `@media(hover:hover) and (pointer:fine)` 条件は別途CSS必要 |
| `gutter`                                   | global/\_gutter.scss:4        | No       | **(A)**   | `px-[var(--gutter)]` arbitrary value                                                       |
| `gutter_row`                               | global/\_gutter.scss:12       | No       | **(A)**   | `py-[var(--gutter-row)]` arbitrary value                                                   |
| `fa-icon`                                  | global/\_font.scss:5          | No       | **(C)**   | `@layer components` に FontAwesome 疑似要素ルールとして記述                                |
| `fa5-far`                                  | global/\_font.scss:14         | No       | **(C)**   | 同上                                                                                       |
| `webfont-lato`                             | global/\_font.scss:22         | No       | **(A)**   | `font-['Lato']` + `theme.fontFamily` に追加                                                |
| `z-index($key)`                            | mixins/\_zindex.scss:4        | No       | **(A)**   | `z-header`, `z-modal` 等の theme.zIndex カスタムユーティリティ                             |
| `clearfix`                                 | mixins/\_clearfix.scss:1      | No       | **(D)**   | Flexbox/Grid 前提で不要                                                                    |
| `placeholder`                              | mixins/\_placeholder.scss:3   | Yes      | **(D)**   | `placeholder:` プレフィックス（ベンダープレフィックス不要）                                |
| `style__flex`                              | mixins/\_dl.scss:5            | No       | **(C)**   | `@layer components` に dl/dt/dd レイアウトとして記述                                       |
| `style__menu`                              | mixins/\_dl.scss:84           | No       | **(C)**   | 同上                                                                                       |
| `table-row-variant`                        | mixins/\_table-row.scss:4     | No       | **(C)**   | `@layer components` にテーブル行バリアントとして記述                                       |

### 3.3 代替判定サマリー

| 判定                 | 個数 | 内訳                                                          |
| -------------------- | ---- | ------------------------------------------------------------- |
| **(A)** Tailwind標準 | 7    | breakpoint系4, hover, gutter系, webfont-lato, z-index         |
| **(B)** CSS calc()   | 0    | （mixin自体はcalcではなく、関数がcalc対象）                   |
| **(C)** @layer CSS   | 5    | fa-icon, fa5-far, style**flex, style**menu, table-row-variant |
| **(D)** 削除可能     | 2    | clearfix, placeholder                                         |

### 3.4 メディアクエリ文字列変数

| 変数      | 値                    | 用途                  |
| --------- | --------------------- | --------------------- |
| `$sm`     | `(min-width: 576px)`  | media-breakpoint-up   |
| `$md`     | `(min-width: 811px)`  | 同上                  |
| `$lg`     | `(min-width: 1025px)` | 同上                  |
| `$xl`     | `(min-width: 1280px)` | 同上                  |
| `$xxl`    | `(min-width: 1366px)` | 同上                  |
| `$maxSm`  | `(max-width: 575px)`  | media-breakpoint-down |
| `$maxMd`  | `(max-width: 810px)`  | 同上                  |
| `$maxLg`  | `(max-width: 1024px)` | 同上                  |
| `$retina` | Retina 判定クエリ     | 高DPI対応             |

---

## 4. CSS 変数 (:root) の calc() 変換済み完成形

### 4.1 layout/\_grid.scss の :root ブロック

> **方針**: SCSS 計算関数 `g.get_vw()` をそのまま維持するため、以下は参考用の「現状の値」一覧。
> SCSS のまま維持する場合はこのブロックの書き換え自体が不要。
> CSS に直接書く場合の calc() 式も併記する。

```scss
/* === 現状の SCSS（そのまま維持） === */
:root {
  --unit: #{g.get_vw(1, 390)};        /* calc(1 / 390 * 100vw) */
  --space: #{g.get_vw(8, 390)};       /* calc(8 / 390 * 100vw) */
  --gutter: #{g.get_vw(6, 390)};      /* calc(6 / 390 * 100vw) */
  --gutter-row: #{g.get_vw(16, 390)}; /* calc(16 / 390 * 100vw) */
}
@media #{g.$sm} { /* min 576px */
  :root {
    --unit: #{g.get_vw(1, 768)};        /* calc(1 / 768 * 100vw) */
    --space: #{g.get_vw(8, 768)};       /* calc(8 / 768 * 100vw) */
    --gutter: #{g.get_vw(12, 768)};     /* calc(12 / 768 * 100vw) */
    --gutter-row: #{g.get_vw(42, 768)}; /* calc(42 / 768 * 100vw) */
  }
}
@media #{g.$md} { /* min 811px */
  :root {
    --unit: #{g.get_vw(1, 960)};        /* calc(1 / 960 * 100vw) */
    --space: #{g.get_vw(8, 960)};       /* calc(8 / 960 * 100vw) */
    --gutter: #{g.get_vw(16, 960)};     /* calc(16 / 960 * 100vw) */
    --gutter-row: #{g.get_vw(32, 960)}; /* calc(32 / 960 * 100vw) */
  }
}
@media #{g.$lg} { /* min 1025px */
  :root {
    --unit: #{g.get_vw(1, 1025)};        /* calc(1 / 1025 * 100vw) */
    --space: #{g.get_vw(8, 1025)};       /* calc(8 / 1025 * 100vw) */
    --gutter: #{g.get_vw(16, 1025)};     /* calc(16 / 1025 * 100vw) */
    --gutter-row: #{g.get_vw(32, 1025)}; /* calc(32 / 1025 * 100vw) */
  }
}
@media #{g.$xl} { /* min 1280px */
  :root {
    --unit: #{g.get_vw(1, 1280)};        /* calc(1 / 1280 * 100vw) */
    --space: #{g.get_vw(8, 1280)};       /* calc(8 / 1280 * 100vw) */
    --gutter: #{g.get_vw(16, 1280)};     /* calc(16 / 1280 * 100vw) */
    --gutter-row: #{g.get_vw(32, 1280)}; /* calc(32 / 1280 * 100vw) */
  }
}
@media #{g.$xxl} { /* min 1366px */
  :root {
    --unit: #{g.get_vw(1, 1366)};        /* calc(1 / 1366 * 100vw) */
    --space: #{g.get_vw(8, 1366)};       /* calc(8 / 1366 * 100vw) */
    --gutter: #{g.get_vw(16, 1366)};     /* calc(16 / 1366 * 100vw) */
    --gutter-row: #{g.get_vw(32, 1366)}; /* calc(32 / 1366 * 100vw) */
  }
}
```

### 4.2 layout/\_header.scss の :root ブロック

```scss
/* SCSS 関数 g.rem() を維持 */
:root {
  --header-height: #{g.rem(56)}; /* = 3.5rem */
}
@media #{g.$md} {
  :root {
    --header-height: #{g.rem(72)}; /* = 4.5rem */
  }
}
```

### 4.3 project/\_style.scss の :root ブロック（カラー・装飾）

```css
:root {
  --clr1: #4fba43;
  --clr2: #9bd22d;
  --clr3: #725907;
  --clr4: #b69941;
  --clr5: #f6f0dd;
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
  --black: #222;
  --gray: #767676;
  --grd1: linear-gradient(120deg, #9bd22d, #4fba43);
  --grd2: linear-gradient(90deg, #fdd88a, #9e8004);
  --grd-recruit: linear-gradient(120deg, #9bd22d, #4fba43);
  --bdrs: 6px;
  --img-hover-opacity: 0.7;
}
```

---

## 5. グリッドシステム → Tailwind grid/gap 再現の対応表

### 5.1 グリッドパターン一覧

#### パターン1: 12カラム Flex グリッド

| 現状 (SCSS)          | 実現していること                  | Tailwind 代替                                         |
| -------------------- | --------------------------------- | ----------------------------------------------------- |
| `.l-row`             | Flexコンテナ + 負マージンでガター | `grid grid-cols-12 gap-[calc(var(--gutter)*2)]`       |
| `.c-col--{n}` (1-12) | カラム幅 n/12                     | `col-span-{n}`                                        |
| `.c-col--{n}--{bp}`  | レスポンシブカラム幅              | `{bp}:col-span-{n}`                                   |
| `.c-col--offset-{n}` | カラムオフセット                  | `col-start-{n+1}`                                     |
| `.l-row--container`  | コンテナ max-width                | `max-w-[960px] md:max-w-[960px] lg:max-w-[1152px]` 等 |

生成クラス合計: **84クラス**（12カラム × 7ブレークポイント相当）

#### パターン2: ブロックグリッド（N等分）

| 現状 (SCSS)          | 実現していること          | Tailwind 代替                      |
| -------------------- | ------------------------- | ---------------------------------- |
| `.l-grid`            | Flexコンテナ + 負マージン | `grid gap-[calc(var(--gutter)*2)]` |
| `.c-grid--{n}` (2-6) | N等分レイアウト           | `grid-cols-{n}`                    |
| `.c-grid--{n}--{bp}` | レスポンシブN等分         | `{bp}:grid-cols-{n}`               |

> **ガター距離の注意**: 旧方式は負マージン + 各カラムのパディングでガターを実現。両側パディングの合計 = `var(--gutter) * 2`。CSS Grid の gap に置き換える場合、gap 値は `calc(var(--gutter) * 2)` とする。

#### パターン3: コンテナ

| ブレークポイント | max-width | Tailwind             |
| ---------------- | --------- | -------------------- |
| sm (576px〜)     | 100%      | `max-w-full`         |
| md (811px〜)     | 960px     | `md:max-w-[960px]`   |
| lg (1025px〜)    | 1152px    | `lg:max-w-[1152px]`  |
| xl (1280px〜)    | 1200px    | `xl:max-w-[1200px]`  |
| xxl (1366px〜)   | 1260px    | `2xl:max-w-[1260px]` |

### 5.2 レイアウトパターン（layout/\_content.scss）

| パターン        | 現状                        | Tailwind 代替                                    |
| --------------- | --------------------------- | ------------------------------------------------ |
| split (2カラム) | flex + width %              | `grid grid-cols-[45%_55%]` or `grid grid-cols-2` |
| split reverse   | flex-direction: row-reverse | `flex-row-reverse` or `grid` + order             |
| float 回り込み  | clearfix + float            | **CSS Grid で完全置換**（モダン化）              |

### 5.3 @extend / %placeholder セレクタ

| Placeholder           | 使用箇所               | 移行方法                               |
| --------------------- | ---------------------- | -------------------------------------- |
| `%grid-column`        | 各カラムクラス         | `@layer components` の通常クラスに展開 |
| `%l-grid__col`        | ブロックグリッド子要素 | 同上                                   |
| `%l-split__thumbnail` | split レイアウト       | 同上                                   |
| `%l-split__content`   | split レイアウト       | 同上                                   |
| `%l-float__thumbnail` | float レイアウト       | 同上（float自体を廃止）                |

> Tailwind には `@extend` 相当がない。全ての placeholder は通常のクラスとして `@layer components` に展開するか、Tailwind utility の組み合わせに分解する。

### 5.4 推奨グリッド移行戦略: 既存クラス名維持 + Tailwind 内部再定義

> [!IMPORTANT]
> **既存の `.l-row`, `.c-col--*`, `.l-grid`, `.c-grid--*` クラス名はそのまま維持する。**
> 内部実装だけを `@layer components` で Tailwind の CSS Grid + gap に置き換える。
> PHP テンプレートの書き換えは不要。

#### なぜこの戦略か

| 選択肢 | テンプレート変更 | 技術的メリット | 判定 |
|---|---|---|---|
| A: 旧 SCSS をそのまま維持 | 不要 | なし（負のマージンパターン維持） | ✖︎ |
| B: Tailwind ユーティリティに全置換 | PHP テンプレート全ファイル | 最も Tailwind ネイティブ | △ |
| **C: 既存クラス名維持 + 内部再定義** | **不要** | **CSS Grid gap に移行、将来 B にも移行可** | **✔︎ 推奨** |

#### 実装例

```css
@layer components {
  /* === 12カラムグリッド === */
  .l-row {
    @apply grid grid-cols-12;
    gap: calc(var(--gutter) * 2);
  }
  .l-row--container {
    @apply mx-auto;
    @apply max-w-full md:max-w-[960px] lg:max-w-[1152px] xl:max-w-[1200px] 2xl:max-w-[1260px];
  }

  /* カラム幅 (1〜12) */
  .c-col--1  { @apply col-span-1; }
  .c-col--2  { @apply col-span-2; }
  /* ... */
  .c-col--12 { @apply col-span-12; }

  /* レスポンシブ: .c-col__md--6 等 */
  @screen md {
    .c-col__md--6 { @apply col-span-6; }
    /* ... */
  }

  /* === ブロックグリッド === */
  .l-grid {
    @apply grid;
    gap: calc(var(--gutter) * 2);
  }
  .c-grid--2 { @apply grid-cols-2; }
  .c-grid--3 { @apply grid-cols-3; }
  /* ... */
}
```

#### ガター距離の検証が必要

負のマージン + パディング → CSS Grid `gap` への変換で、ガターの計算が変わる:
- 旧: 各カラムに `padding-left/right: var(--gutter)` → カラム間の実際の間隔 = `var(--gutter) * 2`
- 新: `gap: calc(var(--gutter) * 2)` → 同じ間隔

見た目は変わらないはずだが、ピクセル単位でのデザイン検証が必要。

#### 将来の置き換え

デザインリニューアル等の機会に、テンプレート側で `.l-row` → `grid grid-cols-12 gap-gutter` に段階的に置き換え可能。ロックインなし。

---

## 6. ファイルごとの移行カテゴリ分類表

### 凡例

- **(A)** Tailwind utility で代替
- **(B)** @layer components に移行
- **(C)** Tailwind plugin 化
- **(D)** CSS 維持（ベンダー系等）

### 6.1 foundation/ (5ファイル)

| ファイル               | 判定     | 備考                                                    |
| ---------------------- | -------- | ------------------------------------------------------- |
| \_variables.scss       | →        | tailwind.config.js + CSS変数に分散                      |
| \_variables-color.scss | →        | tailwind.config.js `theme.colors`                       |
| \_variables-form.scss  | →        | `@layer base` のフォームルール                          |
| \_reboot.scss          | →        | カスタム部分のみ `@layer base` に移植、残りは Preflight |
| destyle.css            | **削除** | Tailwind Preflight で代替                               |

### 6.2 global/ (9ファイル)

| ファイル             | 判定     | 備考                                                         |
| -------------------- | -------- | ------------------------------------------------------------ |
| \_index.scss         | **削除** | @forward 構成。Tailwind では不要                             |
| \_breakpoints.scss   | **(A)**  | Tailwind レスポンシブプレフィックスで代替                    |
| \_calc.scss          | **維持** | SCSS 計算関数 (g.rem, g.get_vw 等) をそのまま維持。Tailwind 環境でも利用可 |
| \_font.scss          | **(C)**  | FA mixin は `@layer components`、webfont は theme            |
| \_gutter.scss        | **(A)**  | CSS変数 + Tailwind arbitrary value                           |
| \_hover.scss         | **(A)**  | `hover:` プレフィックス + @layer base で @media(hover:hover) |
| \_media-queries.scss | **(A)**  | Tailwind screens で代替                                      |
| \_transition.scss    | →        | tailwind.config.js + CSS変数                                 |
| \_unicode.scss       | **(C)**  | `@layer components` の FA ルールで使用                       |

### 6.3 mixins/ (5ファイル)

| ファイル           | 判定    | 備考                                            |
| ------------------ | ------- | ----------------------------------------------- |
| \_clearfix.scss    | **(D)** | 削除可能                                        |
| \_dl.scss          | **(C)** | `@layer components` に移行                      |
| \_placeholder.scss | **(D)** | 削除可能（`placeholder:` プレフィックスで代替） |
| \_table-row.scss   | **(C)** | `@layer components` に移行                      |
| \_zindex.scss      | **(A)** | theme.zIndex で代替                             |

### 6.4 layout/ (4ファイル)

| ファイル       | 判定        | 備考                                                           |
| -------------- | ----------- | -------------------------------------------------------------- |
| \_grid.scss    | **(B)** | :root CSS変数は `@layer base`、グリッドは `@layer components` で既存クラス名維持 + Tailwind @apply で内部再定義（§5.4） |
| \_content.scss | **(B)**     | split/float レイアウトは `@layer components`                   |
| \_header.scss  | **(B)**     | `@layer components` + CSS変数                                  |
| \_footer.scss  | **(B)**     | `@layer components`                                            |

### 6.5 component/ (16自作ファイル)

| ファイル          | 判定    | 備考                                                    |
| ----------------- | ------- | ------------------------------------------------------- |
| \_button.scss     | **(B)** | 多数のカラーバリエーション。@layer components + CSS変数 |
| \_footer.scss     | **(B)** | 複合コンポーネント                                      |
| \_google-map.scss | **(A)** | ほぼ utility 化可能                                     |
| \_gutter.scss     | **(B)** | CSS変数ベースのガターシステム                           |
| \_header.scss     | **(B)** | ナビ・ツールバー                                        |
| \_login.scss      | **(B)** | WP login カスタム                                       |
| \_pagenation.scss | **(B)** | ページネーション                                        |
| \_post-feed.scss  | **(B)** | 投稿一覧 DL 型                                          |
| \_post.scss       | **(B)** | 投稿カード・ウィジェット                                |
| \_search.scss     | **(B)** | 検索フォーム                                            |
| \_style.scss      | **(B)** | 複合コンポーネント（replace, likepost, more 等）        |
| \_tab.scss        | **(B)** | タブ UI                                                 |
| \_table.scss      | **(B)** | テーブル + スクロールシャドウ                           |
| \_toggle.scss     | **(B)** | アコーディオン                                          |
| \_typ.scss        | **(B)** | タイポグラフィシステム（タイトル各種、本文）            |
| \_validation.scss | **(B)** | バリデーションエラー                                    |

### 6.6 utility/ (9ファイル)

| ファイル                | 判定    | 備考                                           |
| ----------------------- | ------- | ---------------------------------------------- |
| \_blockquote.scss       | **(B)** | FA アイコン疑似要素依存                        |
| \_display.scss          | **(A)** | `hidden`, `block`, `inline-block` + responsive |
| \_flex.scss             | **(A)** | `justify-*`, `items-*`, `self-*`, `order-*`    |
| \_font.scss             | **(A)** | `text-center/left/right`, `font-*`, `text-*`   |
| \_margin.scss           | **(A)** | `mt-*` + responsive                            |
| \_padding.scss          | **(A)** | `pt-0`, `pb-0` 等（コメントアウト済み）        |
| \_responsive-embed.scss | **(A)** | `aspect-video`, `aspect-square` 等             |
| \_tables.scss           | **(D)** | Bootstrap 由来、ほぼそのまま維持               |
| \_visibility.scss       | **(A)** | `hidden`, `sm:hidden`, `md:block` 等           |

### 6.7 project/ (10ファイル)

| ファイル           | 判定    | 備考                                    |
| ------------------ | ------- | --------------------------------------- |
| \_button.scss      | **(B)** | ボタン幅 + アンカーリンク               |
| \_entrystep.scss   | **(B)** | フォームステップ表示                    |
| \_footer.scss      | **(B)** | フッター全体                            |
| \_form.scss        | **(B)** | フォーム全体                            |
| \_header.scss      | **(B)** | ヘッダー全体                            |
| \_js-inview.scss   | **(A)** | `opacity-0` のみ                        |
| \_post-single.scss | **(B)** | 記事詳細                                |
| \_post.scss        | **(B)** | 投稿一覧・サイドバー・関連記事          |
| \_style.scss       | **(B)** | トップページ固有（salon-info, menu 等） |
| \_typ.scss         | **(B)** | プロジェクト固有タイトルスタイル        |

### 6.8 ベンダー (6ファイル)

| ファイル                                   | 判定    | 備考                                 |
| ------------------------------------------ | ------- | ------------------------------------ |
| fontawesome-free-5.14.0/\_index.scss       | **(D)** | @font-face 定義。維持                |
| swiper/\_swiper-bundle.scss                | **(D)** | npm swiper CSS 使用 + カスタム上書き |
| micromodal/\_micromodal.scss               | **(D)** | 薄い自作スタイル。最低限の差分で維持 |
| scroll-hint/index.scss                     | **(D)** | ほぼオリジナル                       |
| ultimate-member/\_ultimate-member.scss     | **(D)** | WP プラグイン上書き                  |
| wp-instagram-feed/\_wp-instagram-feed.scss | **(D)** | WP プラグイン上書き                  |

### 6.9 サマリー

| 判定                                     | ファイル数 |
| ---------------------------------------- | ---------- |
| **(A)** Tailwind utility で代替          | 11         |
| **(B)** @layer components に移行         | 28         |
| **(C)** Tailwind plugin / @layer         | 3          |
| **(D)** CSS 維持・削除可能               | 9          |
| **→** tailwind.config.js / CSS変数に分散 | 5          |

---

## 7. ベンダー CSS の扱い方針

| ベンダー               | 現状ファイル                       | 方針                      | 詳細                                                                                                                                                           |
| ---------------------- | ---------------------------------- | ------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **FontAwesome 5.14.0** | component/fontawesome-free-5.14.0/ | **(D) CSS維持**           | 極力変更を加えず、最低限の差分で維持する（SCSS変数の入れ替え程度）。将来的に FA6 や Heroicons への移行を別途検討                                                |
| **Swiper 8.3.2**       | component/swiper/                  | **(D) CSS維持**           | 極力変更を加えず、最低限の差分で維持する。                                                                                                                     |
| **Micromodal**         | component/micromodal/              | **(D) CSS維持**           | 薄い自作スタイル。極力変更を加えず、最低限の差分（SCSS変数の入れ替えなど）で維持する。                                                                         |
| **scroll-hint**        | component/scroll-hint/             | **(D) CSS維持**           | 極力変更を加えず、最低限の差分で維持する。                                                                                                                     |
| **Ultimate Member**    | component/ultimate-member/         | **(D) CSS維持**           | WP プラグインの上書きスタイル。極力変更を加えず、最低限の差分（$clrg500等 → CSS変数）で維持する。                                                                |
| **WP Instagram Feed**  | component/wp-instagram-feed/       | **(D) CSS維持**           | WP プラグインの上書きスタイル。極力変更を加えず、最低限の差分（$clr1等 → CSS変数）で維持する。                                                                 |

---

## 8. 動的クラス生成（@each/@for）の Tailwind 移行パターン

### 8.1 現状の動的生成パターン

| ファイル                  | ループ                           | 対象                 | 生成クラス例                                                     | 推定クラス数 |
| ------------------------- | -------------------------------- | -------------------- | ---------------------------------------------------------------- | ------------ |
| utility/\_display.scss    | `@each $bp in $grid-breakpoints` | 6 BP                 | `.display__none`, `.display__none--sm`, `.display__block--md`    | 18           |
| utility/\_flex.scss       | `@each $bp` + `@for 1-6`         | 6 BP                 | `.jc__start`, `.jc__end--sm`, `.ai__center--md`, `.order__3--lg` | 約108        |
| utility/\_font.scss       | `@each $bp`                      | 6 BP (element infix) | `.tac`, `.tac__sm`, `.tal__md`                                   | 18           |
| utility/\_margin.scss     | `@each $bp` × `@each $space`     | 6 BP × 25 space      | `.mt__2`, `.mt__4--sm`, `.mt__8--md`                             | 約150        |
| utility/\_visibility.scss | `@each $bp`                      | 6 BP                 | `.hide__sm--up`, `.hide__md--down`                               | 12           |
| layout/\_grid.scss        | `@each $bp` × `@for 1-12`        | 6 BP × 12 cols       | `.c-col--6`, `.c-col--4--md`                                     | 84           |

**合計**: 約390クラスが動的に生成されている

### 8.2 Tailwind 移行パターン

| 現状クラス            | Tailwind 代替               | 備考                 |
| --------------------- | --------------------------- | -------------------- |
| `.display__none`      | `hidden`                    |                      |
| `.display__none--sm`  | `sm:hidden`                 |                      |
| `.display__block--md` | `md:block`                  |                      |
| `.jc__center`         | `justify-center`            |                      |
| `.jc__between--sm`    | `sm:justify-between`        |                      |
| `.ai__center`         | `items-center`              |                      |
| `.order__2--md`       | `md:order-2`                |                      |
| `.tac`                | `text-center`               |                      |
| `.tac__sm`            | `sm:text-center`            |                      |
| `.mt__2`              | `mt-[16px]` or `mt-2`       | spacing マップに依存 |
| `.mt__4--sm`          | `sm:mt-[32px]` or `sm:mt-4` |                      |
| `.hide__sm--up`       | `sm:hidden`                 |                      |
| `.hide__md--down`     | `max-md:hidden`             |                      |
| `.c-col--6`           | `col-span-6`                | grid-cols-12 前提    |
| `.c-col--4--md`       | `md:col-span-4`             |                      |

### 8.3 移行方針

動的生成クラスは全て Tailwind 標準ユーティリティ + レスポンシブプレフィックスで**完全に代替可能**。
Tailwind plugin 化は不要。HTML テンプレート側のクラス名を Tailwind のクラス名に書き換える。

> **注意**: `$infix` の形式が BEM修飾子型（`--sm`）と BEM要素型（`__sm`）の2種があるが、Tailwind は `sm:` プレフィックス型に統一される。HTML テンプレートの一括置換が必要。

---

## 9. 計算関数の使用箇所リスト

### 9.1 g.rem() — 約300箇所以上（最多）

全 component/, project/, utility/ ファイルで使用。

**移行方針（重要）**:
コンポーネントクラス内（例: `.hghg`, `.c-button`）で記述されている `font-size: g.rem(20)` などは、Tailwind のユーティリティクラス（`text-xl` 等）に分解したり、`1.25rem` の固定値や `calc()` に変換したり**しない**。
既存のクラスのまとまりを崩さず、「人間が px 数値でデザインカンプ通りに入力し、コンパイルで rem 換算される」システムを保護するため、**SCSS 関数 `g.rem()` をそのまま Tailwind 環境でも維持する**。

### 9.2 g.get_vw() — layout/\_grid.scss + component/\_typ.scss 等

| ファイル             | 行         | SCSS                           | → calc()                             |
| -------------------- | ---------- | ------------------------------ | ------------------------------------ |
| layout/\_grid.scss   | :root      | `g.get_vw(1, 390)`             | `calc(1 / 390 * 100vw)`              |
| layout/\_grid.scss   | :root      | `g.get_vw(24, 390)`            | `calc(24 / 390 * 100vw)`             |
| layout/\_grid.scss   | :root      | `g.get_vw(16, 390)`            | `calc(16 / 390 * 100vw)`             |
| layout/\_grid.scss   | :root(sm)  | `g.get_vw(1, 576)`             | `calc(1 / 576 * 100vw)`              |
| layout/\_grid.scss   | :root(md)  | `g.get_vw(20, 960)`            | `calc(20 / 960 * 100vw)`             |
| layout/\_grid.scss   | :root(lg)  | `g.get_vw(20, 1152)`           | `calc(20 / 1152 * 100vw)`            |
| layout/\_grid.scss   | :root(xl)  | `g.get_vw(20, 1200)`           | `calc(20 / 1200 * 100vw)`            |
| layout/\_grid.scss   | :root(xxl) | `g.get_vw(20, 1260)`           | `calc(20 / 1260 * 100vw)`            |
| component/\_typ.scss | ~409       | `min(g.get_vw(16), g.rem(17))` | `min(calc(16/390*100vw), 1.0625rem)` |
| component/\_typ.scss | ~423       | `min(g.get_vw(13), g.rem(14))` | `min(calc(13/390*100vw), 0.875rem)`  |

### 9.3 g.px-to-per() — 1箇所

| ファイル                | 行  | SCSS                    | → calc()                 |
| ----------------------- | --- | ----------------------- | ------------------------ |
| component/\_footer.scss | 28  | `g.px-to-per(310, 576)` | `calc(310 / 576 * 100%)` |

### 9.4 color.scale() — 約25箇所

| ファイル                | 用途                       |
| ----------------------- | -------------------------- |
| component/\_button.scss | ボタン hover/active カラー |
| component/\_header.scss | ナビ hover カラー          |
| component/\_post.scss   | リンク hover カラー        |
| project/\_footer.scss   | フッター hover カラー      |
| project/\_form.scss     | フォーム hover カラー      |
| project/\_style.scss    | 各種 hover カラー          |
| project/\_typ.scss      | テキスト hover カラー      |
| utility/\_font.scss     | カラーユーティリティ       |

> `color.scale()` は Sass 固有の色計算。移行方法:
>
> - ハードコードされたカラー値に変換（ビルド時の計算結果を取得）
> - または CSS `color-mix()` を使用（ブラウザ対応を確認の上）

### 9.5 math.div() — 数箇所

| ファイル                        | SCSS                          | → calc()                                      |
| ------------------------------- | ----------------------------- | --------------------------------------------- |
| utility/\_responsive-embed.scss | `percentage(math.div(9, 16))` | `calc(9 / 16 * 100%)` or `aspect-ratio: 16/9` |
| project/\_footer.scss           | `math.div(64%, 3)`            | `calc(64% / 3)`                               |

### 9.6 SCSS 変数による計算式

| ファイル              | SCSS                            | → calc()                |
| --------------------- | ------------------------------- | ----------------------- |
| component/\_post.scss | `100% - $separate-thumbnail-xs` | `calc(100% - 45%)` 等   |
| project/\_form.scss   | `100% - $dt-width`              | `calc(100% - 200px)` 等 |

---

## 10. 移行時の注意点・リスク

### 10.1 高リスク項目

| #   | 項目                              | リスク                                                                                          | 対策                                                                                             |
| --- | --------------------------------- | ----------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------ |
| 1   | **ブレークポイントのカスタム値**  | Bootstrap/Tailwind 標準と異なる値（576/811/1025/1280/1366）。theme.screens の完全再定義が必須   | tailwind.config.js で最初に screens を定義し、全チームで共有                                     |
| 2   | **CSS 変数の vw ベース動的計算**  | --gutter, --unit 等がビューポート幅で変化。Tailwind の固定値 spacing と根本的に異なるアプローチ | CSS変数は `@layer base` の :root に維持し、Tailwind arbitrary value `gap-[var(--gutter)]` で参照 |
| 4   | **color.scale() の25箇所**        | CSS に直接の等価機能がない                                                                      | ビルド済み CSS から計算結果のカラーコードを抽出してハードコード                                  |
| 5   | **@extend / %placeholder の多用** | Tailwind に @extend 相当がない                                                                  | 全て `@layer components` の通常クラスに展開 or `@apply` に変換                                   |

### 10.2 中リスク項目

| #   | 項目                                                      | リスク                                                                  | 対策                                                                                          |
| --- | --------------------------------------------------------- | ----------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- |
| 6   | **hover mixin の @media(hover:hover) and (pointer:fine)** | Tailwind `hover:` はこの条件を含まない                                  | `@layer base` でグローバルに `@media(hover:hover)` ルールを追加、またはカスタムバリアント作成 |
| 7   | **BEM 命名パターンの混在**                                | c-, p-, l- プレフィックスと Tailwind utility の共存期間が必要           | 段階的移行: まず utility 層→次に layout 層→最後に component/project 層                        |
| 8   | **JS 状態クラスとの連携**                                 | `.js-active`, `.js-open`, `.js-scroll` 等が JS から操作される           | これらのクラスに基づくスタイルは `@layer components` に維持。HTML のクラス名は変更しない      |
| 9   | **$space_values_with_clamp**                              | 23パターンの clamp() 値。Tailwind spacing に直接マップしにくい          | CSS変数 `--space-*` として定義し、arbitrary value で参照                                      |
| 10  | **FontAwesome 疑似要素パターン**                          | mixin で ::before/::after に FA アイコンを挿入。Tailwind で直接表現不可 | `@layer components` に維持。将来的に FA6 や SVG アイコンへの移行を検討                        |

### 10.3 低リスク項目

| #   | 項目                                      | 備考                                                                                       |
| --- | ----------------------------------------- | ------------------------------------------------------------------------------------------ |
| 11  | **destyle.css の削除**                    | Tailwind Preflight でほぼカバー。一部アグレッシブなリセット（appearance:none）は確認が必要 |
| 12  | **clearfix mixin の削除**                 | Flexbox/Grid 前提で不要                                                                    |
| 13  | **utility 層の動的クラス（約390クラス）** | Tailwind 標準 utility で完全代替可能。HTML テンプレートのクラス名一括置換                  |
| 14  | **ベンダー CSS の SCSS 変数依存**         | 数箇所の変数参照を CSS変数に置き換えるだけで維持可能                                       |

### 10.4 推奨移行順序

```
Phase 1: 基盤構築
  ├── tailwind.config.js 作成（screens, colors, spacing, zIndex, fontFamily, borderRadius）
  ├── @layer base に :root CSS変数を定義（--gutter, --unit, --space, カラー等）
  └── @layer base に reboot カスタム部分を移植

Phase 2: ユーティリティ層の移行
  ├── utility/ の (A) 判定ファイル → HTML テンプレートのクラス名を Tailwind に置換
  └── 動的生成クラスの対応表に基づき一括置換

Phase 3: レイアウト層の移行
  ├── グリッド: 既存クラス名 (.l-row, .c-col 等) を維持し、@layer components で @apply grid/gap に再定義（§5.4）
  ├── コンテナ → max-w-* を @apply で再定義
  ├── split / float → CSS Grid
  └── ガター距離のピクセル検証

Phase 4: コンポーネント・プロジェクト層の移行
  ├── @layer components に BEM コンポーネントを移行（そのままSCSSクラスを維持）
  ├── SCSS 計算関数 (`g.rem`, `g.get_vw`) はそのまま維持・利用
  ├── color.scale() のハードコード変換
  └── @extend → @apply or 通常クラス展開

Phase 5: ベンダー CSS の整理
  ├── SCSS 変数依存を CSS変数に置換
  └── 不要なベンダーファイルの削除
```

### 10.5 HTML テンプレート側の影響

WordPress テーマの PHP テンプレートファイル（`*.php`）内のクラス名を書き換える必要がある。
影響範囲:

- utility クラス（`.display__none--sm` → `sm:hidden` 等）: テンプレート全体
- layout クラス（`.l-row`, `.c-col--*`）: テンプレート全体
- component/project クラス（`.c-button`, `.p-header` 等）: `@layer components` で定義すればテンプレート変更不要

> **重要**: クラス名の一括置換には、対応表（旧クラス名 → 新 Tailwind クラス名）を事前に作成し、PHP テンプレート全体に適用するスクリプトを用意すること。

---

## 付録: @forward 構成図

```
style.scss（エントリポイント）
  ├── @use "foundation/variables"
  ├── @use "foundation/variables-color"
  ├── @use "foundation/reboot"
  ├── @use "global" as g
  │     ├── @forward "../foundation/_variables"
  │     ├── @forward "../foundation/_variables-color"
  │     ├── @forward "_breakpoints"
  │     ├── @forward "_calc"
  │     ├── @forward "_font"
  │     ├── @forward "_gutter"
  │     ├── @forward "_hover"
  │     ├── @forward "_transition"
  │     ├── @forward "_unicode"
  │     └── @forward "_media-queries"
  ├── layout/*
  ├── component/*
  ├── utility/*
  └── project/*
```

## 付録: クラス命名パターン

| プレフィックス | 層        | 用途           | 例                                       |
| -------------- | --------- | -------------- | ---------------------------------------- |
| `c-`           | component | 再利用可能 UI  | `.c-button`, `.c-nav`, `.c-tab`          |
| `p-`           | project   | ページ固有     | `.p-header`, `.p-form`, `.p-post-single` |
| `l-`           | layout    | レイアウト     | `.l-row`, `.l-grid`, `.l-main`           |
| (なし)         | utility   | ユーティリティ | `.display__*`, `.mt__*`, `.tac`          |
| `js-`          | 全層      | JS 状態        | `.js-active`, `.js-open`, `.js-scroll`   |
| `is-`          | component | 状態           | `.is-active`, `.is-open`                 |
