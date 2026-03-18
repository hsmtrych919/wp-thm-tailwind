# Tailwind / SCSS Architecture

作成日: 2026-03-17

## 概要

このテーマは、完全に Tailwind だけへ置き換わった構成ではありません。

現在は次の 2 系統が共存しています。

1. Tailwind 系
- `src/scss/tailwind-base.css`
- `tailwind.config.js`
- `postcss.config.js`

2. 既存 SCSS 系
- `src/scss/style.scss`
- `src/scss/_tailwind-base-layer.scss`
- `src/scss/global/`
- `src/scss/foundation/_variables.scss`
- `src/scss/layout/`
- `src/scss/component/`
- `src/scss/project/`

出力先は最終的に 1 つです。

- 生成 CSS: `css/style.css`


## それぞれのファイルの役割

### `src/scss/style.scss`

SCSS 側のメインエントリーポイントです。

役割:
- `layout/`, `component/`, `project/` の現役 SCSS を `@use` で束ねる
- 先頭で `@use "_tailwind-base-layer";` を読み込み、Tailwind 移行済みの base/components レイヤーを SCSS 出力に含める

ポイント:
- ここで `@use` されているものが、現行 build graph に入る SCSS です
- コメントアウトされているものはビルドに入りません

### `src/scss/_tailwind-base-layer.scss`

Sass partial です。

役割:
- `@layer base` / `@layer components` に乗せる、移行済みの CSS をまとめる
- Phase 1〜4 で旧 SCSS から移した CSS 変数、reset 補足、base ルール、共通 component 定義を置く

なぜ `_` 付きか:
- Sass の partial 命名です
- `_tailwind-base-layer.scss` というファイル名でも、`style.scss` からは `@use "_tailwind-base-layer";` と読めます
- ここでの正式な実ファイル名は `_tailwind-base-layer.scss` です

### `src/scss/tailwind-base.css`

Tailwind v4 の CSS エントリーポイントです。

役割:
- `@import "tailwindcss" important;` で Tailwind を読み込む
- `@config "../tailwind.config.js";` で設定ファイルを参照する
- `@layer utilities` で独自 `container` ユーティリティを定義する

これは SCSS ではなく CSS ファイルです。

### `tailwind.config.js`

Tailwind の設計値を定義します。

主な役割:
- `content` で PHP テンプレートをスキャン対象にする
- `screens`, `spacing`, `zIndex`, `fontFamily`, `colors`, `borderRadius` などを既存テーマの値に合わせる

重要点:
- Tailwind の値はゼロから設計し直しているのではなく、旧 SCSS トークンに寄せてあります
- たとえば breakpoints や spacing は `foundation/_variables.scss` の値をベースにしています

### `postcss.config.js`

PostCSS 実行時のプラグイン設定です。

役割:
- `@tailwindcss/postcss` で Tailwind を展開する
- `autoprefixer` でベンダープレフィックスを付与する

### `src/scss/global/`

SCSS から広く使う共通 API 層です。

中心は `src/scss/global/_index.scss` です。

役割:
- `foundation/_variables.scss` を `@forward` する
- breakpoints, calc, font, gutter, hover, transition, unicode, media-queries を `@forward` する

つまり `@use "../global" as g;` と書くと、各 SCSS ファイルは `g.rem()`, `g.$sm`, `g.get_vw()` などを使えます。

### `src/scss/foundation/_variables.scss`

旧 SCSS 基盤トークンの残存本体です。

役割:
- breakpoints
- container max-width
- font family
- spacing map
- z-index map
- `get_zindex()` 関数

Phase 5 完了後も、これはまだ現役です。
なぜなら global 経由で多数の現役 SCSS が参照しているからです。

### `src/scss/foundation/_variables-color.scss`

これは Phase 5 で削除済みです。

役割だったもの:
- 旧色トークン (`$clr1`, `$clrg500`, `$body-bg` など)

現在は:
- build graph 内の参照をなくしたうえで削除済み
- 色トークンの役割は主に CSS 変数と Tailwind config に移っています

### `css/style.css`

最終成果物です。

中身の流れは次の順です。

1. `src/scss/tailwind-base.css` を展開した Tailwind 由来の CSS
2. `src/scss/style.scss` を Sass コンパイルした既存 SCSS / 移行済みレイヤー CSS
3. その合成結果に PostCSS をかけたもの

### ルートの `style.css`

これは WordPress テーマ認識用です。

役割:
- `Theme Name` などのテーマメタ情報を持つ

重要点:
- `css/style.css` の中身をここに出しているわけではない
- だから root の `style.css` を見ても SCSS/Tailwind の出力は見えません

## ビルドの流れ

`package.json` の `build` は次の順です。

1. `prd:scss`
- `scripts/scss-compile.cjs`
- `src/scss/style.scss` を Sass コンパイルして `css/style.css` を作る

2. `prd:concat`
- `scripts/css-concat.cjs`
- `src/scss/tailwind-base.css` の内容を `css/style.css` の先頭へ連結する

3. `prd:postcss`
- `scripts/css-postcss.cjs`
- Tailwind 展開 + autoprefixer を `css/style.css` に対して実行する

4. `prd:webpack`
- JS を本番ビルドする

設定パスは `tooling.config.cjs` にまとまっています。

## なぜ Tailwind と SCSS が共存しているのか

理由は移行方式です。

このテーマは一括置換ではなく、Phase 単位で既存 SCSS を段階的に Tailwind 側へ移しました。

その結果:
- すでに Tailwind / `@layer` 側へ移したものがある
- まだ SCSS モジュールとして残しているものがある
- でも最終出力は 1 本の `css/style.css` に統合している

つまり「Tailwind が別サイトとして動いている」のではなく、同じテーマの CSS パイプラインの一部として Tailwind を組み込んでいます。

## `global/` と `foundation/` はどこで使われるか

### `global/`

現役の layout / component / project / vendor SCSS から広く使われています。

例:
- `src/scss/_tailwind-base-layer.scss`
- `src/scss/layout/*.scss`
- `src/scss/component/*.scss`
- `src/scss/project/*.scss`
- active vendor (`micromodal`, `swiper`, `scroll-hint`)

### `foundation/`

現役なのは `foundation/_variables.scss` です。

これは直接いろいろなファイルから参照されるというより、`global/_index.scss` から `@forward` され、各 SCSS が `global` 経由で使います。

流れ:

`foundation/_variables.scss`
→ `global/_index.scss` が `@forward`
→ 各 SCSS が `@use "../global" as g;`
→ `g.$sm`, `g.rem()`, `g.get_zindex()` などを使う

## 今の整理後の状態

### 現役でビルドに入る主な SCSS
- `src/scss/style.scss`
- `src/scss/_tailwind-base-layer.scss`
- `src/scss/global/`
- `src/scss/foundation/_variables.scss`
- `src/scss/layout/`
- `src/scss/component/` の現役ファイルと active vendor
- `src/scss/project/`

### archive に退避済み
- `src/scss/_archive/foundation/`
- `src/scss/_archive/utility/`
- `src/scss/_archive/mixins/`
- `src/scss/component/_archive/`
- `src/scss/_archive/` の議論・棚卸し・計画補助 md

### 空になって削除対象になったもの
- `src/scss/utility/`
- `src/scss/mixins/`

## 迷いやすいポイント

### 1. root `style.css` と `css/style.css` は別物
- root `style.css`: WordPress テーマ定義
- `css/style.css`: 実際に生成される CSS

### 2. `_tailwind-base-layer.scss` は Tailwind 本体ではない
- Tailwind の出力先ではなく、Tailwind 移行済みの CSS を `@layer` で抱える SCSS partial

### 3. `tailwind-base.css` は utility 追加と Tailwind 展開の入口
- SCSS ではなく CSS ファイル
- Tailwind v4 の入口として使っている

### 4. `foundation/_variables.scss` はまだ現役
- Phase 5 完了後でも消えていないのは、まだ breakpoints や spacing の基盤だから
