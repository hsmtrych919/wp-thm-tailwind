# SCSS README

このファイルを見れば、現時点の SCSS と Tailwind の構成、役割分担、ビルド経路、archive の位置、どこが現役でどこが履歴かを把握できるようにまとめています。

最終更新: 2026-03-17

## 1. 現在の結論

このテーマは Tailwind に全面置換された構成ではありません。
現在は、Tailwind を既存テーマへ組み込んだハイブリッド構成です。

成り立ちは次の 3 層です。

1. Tailwind の入口
- `src/scss/tailwind-base.css`
- `tailwind.config.js`
- `postcss.config.js`

2. Sass の入口
- `src/scss/style.scss`
- `src/scss/_tailwind-base-layer.scss`

3. 既存テーマ SCSS の現役モジュール
- `src/scss/global/`
- `src/scss/layout/`
- `src/scss/component/`
- `src/scss/project/`

出力は最終的に 1 本です。

- 生成 CSS: `css/style.css`

ルートの `style.css` は WordPress テーマヘッダ用であり、ビルド成果物ではありません。

## 2. ディレクトリ全体像

現状の `src/scss/` は次の意味で分かれています。

### 現役
- `style.scss`
  - Sass 側のメインエントリーポイント
- `_tailwind-base-layer.scss`
  - Tailwind の `@layer base` / `@layer components` に載せる移行済み CSS をまとめる partial
- `tailwind-base.css`
  - Tailwind v4 の入口 CSS
- `global/`
  - 全体共通 API。変数定義、関数、mixin、breakpoint、shared token を公開（旧 `foundation/_variables.scss` を統合済み）
- `layout/`
  - レイアウト層の現役 SCSS
- `component/`
  - コンポーネント層の現役 SCSS と現役 vendor
- `project/`
  - ページや機能単位の現役 SCSS

### archive
- `_archive/`
  - 廃止済み foundation / utility / mixins と、移行時の議論・棚卸し資料
- `component/_archive/`
  - 現在ビルドに入れていない component 系 SCSS の退避先

### すでに消えたディレクトリ
- `foundation/`
  - `_variables.scss` を `global/` に統合後、削除済み
- `utility/`
  - archive 退避後に空になったため削除済み
- `mixins/`
  - archive 退避後に空になったため削除済み

## 3. どのファイルが build graph に入るか

判断基準は単純です。

`src/scss/style.scss` で `@use` されているものが、現行 build graph に入る SCSS です。

逆に、次のものは build graph 外です。

- `style.scss` で `@use` されていないファイル
- `_archive/` 配下
- `component/_archive/` 配下

例外はありません。まず `style.scss` を見れば、ビルドに入るかどうかが分かります。

## 4. 入口ファイルの役割

### `src/scss/style.scss`

Sass 側の本体です。

役割:
- `_tailwind-base-layer.scss` を読み込む
- `layout/`, `component/`, `project/` の現役 SCSS を束ねる
- active vendor の SCSS を読み込む

実務上の意味:
- ここに `@use` があるかで build graph 内外を判断する
- archive 済みファイルは、ここから参照しない状態で保持する

### `src/scss/tailwind-base.css`

Tailwind v4 の入口です。SCSS ではなく CSS です。

役割:
- `@import "tailwindcss" important;` で Tailwind を取り込む
- `@config "../tailwind.config.js";` で Tailwind 設定を参照する
- 必要な独自 utility を `@layer utilities` で追加する

### `src/scss/_tailwind-base-layer.scss`

Sass partial です。

役割:
- Tailwind 側へ移した base / components レイヤーの CSS を保持する
- reset 補足、CSS 変数、body 基本設定、共通コンポーネントをまとめる

補足:
- 先頭の `_` は Sass partial の命名です
- 実ファイル名として `_tailwind-base-layer.scss` が正式名称です

## 5. ビルドの流れ

ビルド定義は `package.json` と `tooling.config.cjs` にあります。

本番ビルドは次の順です。

1. `prd:scss`
- `src/scss/style.scss` を Sass コンパイルして `css/style.css` を生成

2. `prd:concat`
- `src/scss/tailwind-base.css` を `css/style.css` の先頭に連結

3. `prd:postcss`
- 連結後の `css/style.css` に Tailwind 展開と autoprefixer を適用

4. `prd:webpack`
- JS を production ビルド

要するに CSS 側は、Tailwind と SCSS を別々に出力しているのではなく、1 本の `css/style.css` に統合しています。

## 6. Tailwind と SCSS の共存ルール

このテーマは段階移行で作られているため、Tailwind と SCSS は役割分担して共存しています。

### Tailwind が担うもの
- utility 生成
- `theme.spacing`, `theme.screens`, `theme.zIndex`, `theme.fontFamily` などの設計値公開
- Preflight などの base レイヤー

### SCSS が担うもの
- 既存クラス構造の維持
- `@use` / `@forward` による分割管理
- `rem()`, `get_vw()`, `get_zindex()` などの関数
- `hover`, `gutter` などの mixin
- Tailwind に完全移行していない component / project 層の実装

### 共存の実態
- Tailwind だけで完結しているわけではない
- SCSS だけでも完結していない
- 既存テーマの class 設計を維持しながら、Tailwind を基盤へ差し込んでいる状態

## 7. `global/` の役割

### `global/_variables.scss`

旧 `foundation/_variables.scss` を `global/` に統合したもの。

保持しているもの:
- breakpoint 値
- container max-width
- font family
- spacing map
- z-index map
- `get_zindex()`

### `global/_index.scss`

共通 API の公開口です。

各ファイルが `@use "../global" as g;` と書けるのは、ここで `@forward` しているためです。

公開している主なもの:
- `_variables`
- `_breakpoints`
- `_calc`
- `_font`
- `_gutter`
- `_hover`
- `_transition`
- `_unicode`
- `_media-queries`

### `global/_hover.scss`

現役です。

理由:
- component / project の現役ファイルから多数 `@include g.hover` で参照されている
- Tailwind の hover variant へ全面置換したわけではない

### `global/_gutter.scss`

これも現役です。

理由:
- `project/_form.scss`, `project/_post.scss`, `project/_post-single.scss`, `project/_entrystep.scss` などで `@include g.gutter` が使われている
- 旧議論の一部に廃止予定の記録があるが、現コードではまだ使っている

## 8. spacing / 単位の考え方

現行コードでは、値の性質ごとに使い分けています。

### rem
- 文字サイズ、margin、padding など基本寸法
- `global/_calc.scss` の関数経由で利用する箇所が多い

### vw と CSS 変数
- 横方向余白や responsive な基準値で利用
- `--unit`, `--gutter`, `--gutter-row` のような CSS 変数として使う

### px
- 厳密固定したい寸法
- border や一部の固定サイズで使用

### spacing トークン
- SCSS 側では `global/_variables.scss` の `$space_values`
- Tailwind 側では `tailwind.config.js` の `theme.spacing`

この 2 つは別管理ではなく、同じテーマスケールを両側で共有する前提です。

## 9. 現在の vendor の扱い

`component/` には、通常コンポーネントと vendor SCSS が混在しています。

### 現役 vendor
- `fontawesome-free-5.14.0/`
- `micromodal/`
- `swiper/`
- `scroll-hint/`

### 現在は build graph 外だが保管しているもの
- `component/_archive/ultimate-member/`
- `component/_archive/wp-instagram-feed/`

現時点では、この配置を現状として扱います。

意味:
- どちらも今は `style.scss` から読んでいない
- ただし削除ではなく、参照復帰や再検討ができる形で保持している

## 10. archive の意味

archive は単なるゴミ箱ではありません。

意味は 2 つです。

1. build graph から外したが、履歴や差分確認のために残している
2. 移行設計・議論・棚卸しの経緯を残している

### `_archive/` にあるもの
- foundation の旧ファイル
- utility の旧ファイル
- mixins の旧ファイル
- 旧計画補助資料、議論ログ、棚卸しログ

### `component/_archive/` にあるもの
- 現在未使用の component 系 SCSS
- 現在 build graph 外の vendor 残置物

## 11. 今このファイルだけ見れば押さえるべきこと

判断の要点は次のとおりです。

1. CSS の実出力先は `css/style.css`。ルート `style.css` ではない。
2. build graph の入口は `src/scss/style.scss`。
3. Tailwind の入口は `src/scss/tailwind-base.css`。
4. `_tailwind-base-layer.scss` は Tailwind 移行済み CSS の受け皿であって、Tailwind 本体ではない。
5. `global/` が共通 API と変数定義の両方を担う（旧 `foundation/` は廃止済み）。
6. `utility/` と `mixins/` は現役ディレクトリとしては消えており、内容は archive 側へ移っている。
7. `component/_archive/` は現在ビルドに入っていない SCSS の保管場所。
8. SCSS と Tailwind は並存ではあるが、出力は最終的に 1 本へ統合される。

## 12. 現在の参照順序

全体の流れを文字で書くと次です。

`tailwind-base.css`
→ Tailwind を読み込む
→ `style.scss` が Sass を束ねる
→ `style.scss` の中で `_tailwind-base-layer.scss` と各 SCSS モジュールを読む
→ `css/style.css` に統合
→ PostCSS で Tailwind 展開と prefix 付与

共通 API の流れは次です。

`global/_variables.scss`
→ `global/_index.scss` が `@forward`
→ 各 SCSS が `@use "../global" as g;`
→ `g.$md`, `g.rem()`, `g.gutter`, `g.hover`, `g.get_zindex()` を使う

## 13. 参照の優先順位

現状把握で見る順番はこれで十分です。

1. `src/scss/readme.md`
2. `src/scss/style.scss`
3. `src/scss/tailwind-base.css`
4. `src/scss/_tailwind-base-layer.scss`
5. `src/scss/global/_index.scss`
6. `src/scss/global/_variables.scss`

この順に見れば、ほぼ全体像を見失いません。



