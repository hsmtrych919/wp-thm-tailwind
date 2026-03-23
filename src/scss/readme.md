# SCSS README

このファイルは、現時点の SCSS と Tailwind の構成・役割分担と、スタイリング時の判断指針をまとめたものです。
人間・AI の両方が、このテーマで新規スタイリングや既存修正を行う際の拠り所として使います。

最終更新: 2026-03-23

---

## 1. このドキュメントの目的と対象読者

### 目的

- SCSS と Tailwind がどう共存しているかを把握する
- 新しいクラスを書くとき「どこに・どう書くか」を迷わず判断できるようにする
- コンテナ、余白、レスポンシブ、タイポグラフィの基本パターンを示す

### 対象読者

- このテーマの SCSS / PHP テンプレートを編集する人間
- このテーマのスタイリングを指示される AI

### 前提

- Web サイトのデザインカンプが先行する。SCSS はそのデザインを実装する手段
- ユーティリティクラスだけで完結させるのではなく、デザインに応じて独自クラスを積極的に使う
- Tailwind ユーティリティは汎用的な部分（余白、表示制御、Flex/Grid 指定等）に活用し、デザイン固有の装飾は SCSS で独自クラスを定義する

---

## 2. アーキテクチャ概要

### 構成の結論

Tailwind に全面置換された構成ではない。
Tailwind を既存テーマへ組み込んだ **ハイブリッド構成** です。

出力は最終的に 1 本の `css/style.css` に統合される。ルートの `style.css` は WordPress テーマヘッダ用であり、ビルド成果物ではない。

### ディレクトリ構造

```
src/scss/
├── style.scss                 ← Sass エントリーポイント
├── _tailwind-base-layer.scss  ← Tailwind 移行済み CSS の受け皿（base + components レイヤー）
├── tailwind-base.css          ← Tailwind v4 エントリーポイント
├── global/                    ← 共通 API（変数, 関数, mixin）
│   ├── _index.scss            ← @forward による公開口
│   ├── _variables.scss        ← breakpoint, zIndex
│   ├── _breakpoints.scss      ← BP 関数・mixin
│   ├── _calc.scss             ← rem(), get_vw() 等の関数
│   ├── _font.scss             ← FontAwesome mixin
│   ├── _hover.scss            ← hover mixin
│   └── _media-queries.scss    ← $sm, $md 等のメディアクエリ変数
├── features/                  ← 全自作 SCSS（機能単位で管理）
│   ├── _layout.scss           ← container-width, split, blog レイアウト
│   ├── _header.scss
│   ├── _footer.scss
│   ├── _button.scss
│   ├── _typ.scss              ← タイポグラフィ（見出し・本文）
│   ├── _style.scss            ← ページ固有コンポーネント
│   ├── _post.scss
│   ├── _post-single.scss
│   ├── _form.scss
│   ├── _search.scss
│   ├── _login.scss
│   ├── _google-map.scss
│   └── _pagenation.scss
└── vendor/                    ← サードパーティ CSS
    ├── fontawesome-free-5.14.0/
    ├── micromodal/
    ├── swiper/
    └── scroll-hint/
```

---

## 3. Tailwind と SCSS の役割分担

### Tailwind が担うもの

- ユーティリティ生成（PHP テンプレートで `sm:mt-4`, `flex`, `w-full` 等を直接記述）
- `theme.spacing`, `theme.screens`, `theme.zIndex`, `theme.fontFamily` などの設計値公開
- Preflight（base リセット）
- `theme()` 関数による SCSS → Tailwind config の値参照

### SCSS が担うもの

- 独自クラスの定義（`@layer components` 内で BEM クラスを定義）
- `@use` / `@forward` による分割管理
- `rem()`, `get_vw()`, `get_lh()` 等の計算関数
- `hover` mixin
- コンポーネント内のレスポンシブ対応（`@media #{g.$md}` 等）

### breakpoints の二重定義について

breakpoints は SCSS（`global/_variables.scss`）と Tailwind（`tailwind.config.js` screens）の両方で定義しているが、用途が異なるため二重管理ではない。

| 記法 | 用途 |
|---|---|
| `@media #{g.$md}` | SCSS: 1 つのクラス内で BP ごとにスタイルを切り替える |
| `md:` | Tailwind: PHP テンプレートで独立したユーティリティを適用する |

値は揃えてある。`@layer components` 内で `@media` を使うレスポンシブ定義は Tailwind が公式に想定している使い方。

### theme() の使い方

SCSS 内で Tailwind の `theme()` 関数を参照する場合、Sass の interpolation で素通しさせる。

```scss
// Sass が解釈せず、後段の PostCSS/Tailwind が解決する
font-family: #{"theme(fontFamily.sans)"};
```

---

## 4. 新規クラス作成の判断フロー

### 基本方針

デザインカンプの実装に必要なクラスは、原則として `features/` 内の該当ファイルに `@layer components` で定義する。
Tailwind ユーティリティだけで済む汎用的なスタイリング（余白、表示制御、Flex 指定等）は PHP テンプレート側で直接記述する。

### 判断フロー

```
1. Tailwind ユーティリティだけで済むか？
   Yes → PHP テンプレートにユーティリティクラスを記述
   No ↓

2. 既存の features/ クラスで対応できるか？
   Yes → 既存クラスを使用
   No ↓

3. features/ 内の既存ファイルに追加するのが自然か？
   Yes → 該当ファイルに @layer components で追加
   No ↓

4. 新しい機能単位か？
   Yes → features/ に新規 partial を作成し、style.scss に @use を追加
```

### @layer components 内での定義ルール

- 独自クラスは必ず `@layer components { }` 内で定義する
- `@extend`、`@media #{g.$md}`、`@include g.hover` は `@layer` 内でそのまま動作する（Sass コンパイルが Tailwind 処理より先に実行されるため）
- `@screen md` への変換や Tailwind バリアントへの置き換えは不要

### vendor/ の扱い

- サードパーティ CSS はロジックやセレクタを変更しない
- FontAwesome, Swiper, Micromodal, scroll-hint が現役

---

## 5. 命名規則

### BEM 準拠

クラス命名は BEM（Block__Element--Modifier）で統一する。

```scss
.split__outer { }
.split__outer--reverse { }
.split__thumbnail--left { }
.split__content--right { }
```

### 状態クラス

JavaScript で動的に付与される状態クラスは BEM の対象外。

```scss
.is-active { }
.is-inview { }
.js-replace-pic { }
```

### 外部ライブラリの上書き

外部ライブラリのクラス名（`.swiper-*`, `.micromodal-*` 等）を上書きする場合は、ライブラリのクラス名をそのまま使う。

---

## 6. コンテナとレイアウト

### 基本コンテナパターン

ページ内のコンテンツブロックには以下の構成を基本とする。

```html
<div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
  <div class="w-full md:w-10/12">
    <!-- コンテンツ -->
  </div>
</div>
```

| クラス | 役割 |
|---|---|
| `container-width` | BP 別の max-width を設定（SCSS 定義） |
| `mx-auto` | 中央寄せ（Tailwind） |
| `flex flex-wrap` | 子要素の横並び制御（Tailwind） |
| `justify-center` / `justify-start` | 子要素の配置、基本はセンター揃え（Tailwind、デザインに応じて選択） |
| `px-gutter-row xl:px-0` | 左右ガター。xl 以上で 0 に（Tailwind カスタム） |
| `w-full md:w-10/12` | 子要素の横幅。デザインに応じて md:w-11/12, md:w-full 等に変更 |

### container-width の BP 別 max-width

| BP | min-width | max-width |
|---|---|---|
| xs | 0 | 100%（制限なし） |
| sm | 576px | 540px |
| md | 811px | 960px |
| lg | 1025px | 1152px |
| xl | 1280px | 1200px |
| 2xl | 1366px | 1260px |

定義箇所: `features/_layout.scss`

### container-py

セクション間の縦方向パディングには `container-py` を使う。

```html
<div class="container-py">
  <!-- セクション内容 -->
</div>
```

- 基本: 32px → sm: 44px
- `--blog`: ブログ用（上下パディングが異なる）
- `--search`: 検索結果用（上パディングのみ）

定義箇所: `features/_layout.scss`

### split レイアウト

特定のデザインパターン専用のレイアウトクラス。汎用的な 2 カラム配置として使うものではない。

**AI はこのクラスを利用禁止。** 用途の判断が難しいため、人間が判断して使用する。

定義箇所: `features/_layout.scss`

---

## 7. タイポグラフィ

### 基本方針

このテーマのタイポグラフィクラスは **汎用的な基礎クラス** として提供している。
実際の Web サイト制作ではデザインカンプが先行するため、以下の使い分けが前提となる。

- **本文・説明文など汎用的な文章** → 下記の `typ` 系クラスを使う
- **デザイン要素を含む見出し・タイトル** → デザインに応じて独自クラスを `features/` に定義する
- **汎用見出しのサイズ指定だけで済む場合** → `ttl__*` クラスを使う

つまり、タイトルはユーティリティ的に使う部分とデザイン固有で独自クラスを作る部分の判断が臨機応変に求められる。

### 本文クラス（typ 系）

レスポンシブ対応済み。font-size と line-height が BP ごとに最適化されている。

| クラス | 用途 |
|---|---|
| `.typ` | 標準本文（最も使用頻度が高い） |
| `.typ__xs` | 注釈、補足 |
| `.typ__s` | やや大きめの本文 |
| `.typ__m` | 中見出し下の文章等 |
| `.typ__l` | リード文 |
| `.typ__marker` | マーカー装飾 |

### 汎用見出しクラス（ttl 系）

サイズとウェイトのみを定義した汎用見出し。デザイン装飾は含まない。

| クラス | 用途 |
|---|---|
| `.ttl__xsmall` | 最小見出し |
| `.ttl__small` | 小見出し |
| `.ttl__medium` | 中見出し |
| `.ttl__large` | 大見出し |

これらは `@extend` 用の placeholder（`%ttl__large` 等）も用意されている。独自見出しクラスの中でサイズだけ借りたい場合に使える。

### デザイン装飾付き見出しの例

以下は `_typ.scss` に定義済みのデザイン固有見出し。新規デザインではこれらを参考に独自クラスを作る。

| クラス | 装飾内容 |
|---|---|
| `.ttl__rhombus` | 背景色 + 菱形 SVG 装飾付き |
| `.ttl__horizontal` | グラデーション横線付き |
| `.ttl__underbar` | 中央下線付き |
| `.ttl__bg-grd` | グラデーション背景上の白文字 |
| `.ttl__ptn-dgnl` | 斜線パターン下線付き |

### テキストカラー

テーマカラーを適用するユーティリティクラス。SCSS で定義しているクラスではなく、Tailwind が `tailwind.config.js` の `theme.colors` から生成するユーティリティ。

- `text-clr1`, `text-clr2` 等 → CSS 変数 `--clr1`, `--clr2` を参照

色の追加・修正は `tailwind.config.js` の `theme.colors` で行う。CSS 変数の実値は `_tailwind-base-layer.scss` の `:root` で定義。

---

## 8. 余白の考え方

### 単位の使い分け

| 単位 | 用途 | 例 |
|---|---|---|
| `rem` | 文字サイズ、margin、padding 等の基本寸法 | `g.rem(32)` → `2rem` |
| `vw` + CSS 変数 | 横方向余白、レスポンシブな基準値 | `--gutter`, `--gutter-row` |
| `px` | border や一部の固定サイズなど厳密固定したい寸法 | `border: 1px solid` |

### gutter 系 CSS 変数

横方向の余白はレスポンシブに変化する CSS 変数で管理する。

| 変数 | 役割 |
|---|---|
| `--unit` | 最小単位（1vw 換算） |
| `--space` | 汎用スペース |
| `--gutter` | カラム間余白 |
| `--gutter-row` | コンテナ左右余白 |

各変数は BP ごとにビューポート基準で再計算される。定義箇所: `_tailwind-base-layer.scss`

### spacing スケールの注意

Tailwind のスペーシングは **テーマ独自のスケール** を使っている。Tailwind デフォルトとは異なる。

基準は `1: '0.5rem'`（8px）で、**8 の倍数** を基本単位とする。

```
1  = 0.5rem  =  8px（基準）
2  = 1rem    = 16px
3  = 1.5rem  = 24px
4  = 2rem    = 32px
5  = 2.5rem  = 40px
6  = 3rem    = 48px
...
```

```
mt-4 = 2rem = 32px（このテーマ）
mt-4 = 1rem = 16px（Tailwind デフォルト）
```

全スケールは `tailwind.config.js` の `theme.spacing` を参照。

### Tailwind カスタムパディング

gutter 系の余白は Tailwind カスタムユーティリティとして利用可能。

| クラス | 値 |
|---|---|
| `px-gutter-row` | `var(--gutter-row)` |
| `p*-gutter-1` | `calc(var(--gutter) * 1)` |
| `p*-gutter-1.5` | `calc(var(--gutter) * 1.5)` |
| `p*-gutter-2` | `calc(var(--gutter) * 2)` |
| `p*-gutter-3` | `calc(var(--gutter) * 3)` |
| `gap-grid-gutter` | `calc(var(--gutter) * 2)` |

---

## 9. レスポンシブ対応

### 基本方針

モバイルファースト。小さい画面のスタイルをベースとし、`min-width` で大きい画面を上書きする。

### breakpoints 一覧

| 名前 | min-width | SCSS 変数 | Tailwind prefix |
|---|---|---|---|
| sm | 576px | `g.$sm` | `sm:` |
| md | 811px | `g.$md` | `md:` |
| lg | 1025px | `g.$lg` | `lg:` |
| xl | 1280px | `g.$xl` | `xl:` |
| 2xl | 1366px | `g.$xxl` | `2xl:` |

### SCSS での使い方

```scss
@use "../global" as g;

.example {
  font-size: g.rem(16);

  @media #{g.$md} {
    font-size: g.rem(18);
  }

  @media #{g.$lg} {
    font-size: g.rem(20);
  }
}
```

### max-width 系メディアクエリ

下方向の BP も用意されている。

| 変数 | 値 |
|---|---|
| `g.$maxSm` | `max-width: 575px` |
| `g.$maxMd` | `max-width: 810px` |
| `g.$maxLg` | `max-width: 1024px` |

Tailwind 側では `max-md:` 等で利用可能。

### 禁止事項

- **独自の BP 値を作成しない。** 必ず定義済みの BP を使う
- **不要に細かい BP 分割をしない。** 1px 差のような意味のない段階は避ける

---

## 10. ビルドパイプライン

### ビルド順序

1. `prd:scss` — `src/scss/style.scss` を Sass コンパイル → `css/style.css`
2. `prd:concat` — `src/scss/tailwind-base.css` を `css/style.css` の先頭に連結
3. `prd:postcss` — Tailwind 展開 + autoprefixer（`theme()` もここで解決）
4. `prd:webpack` — JS を production ビルド

### エントリーポイント

| ファイル | 役割 |
|---|---|
| `src/scss/style.scss` | Sass 側の本体。`@use` で features/ と vendor/ を束ねる |
| `src/scss/tailwind-base.css` | Tailwind v4 の入口。`@import "tailwindcss"` |
| `src/scss/_tailwind-base-layer.scss` | Tailwind 移行済みの CSS 変数・リセット補足・form 変数 |

### build graph の判断基準

`style.scss` で `@use` されているファイルがビルド対象。それ以外はビルドに入らない。

---

## 11. 共通 API（global/）

各 SCSS ファイルは以下の 1 行で共通 API を読み込む。

```scss
@use "../global" as g;
```

`global/_index.scss` が `@forward` で公開している。

### 関数

| 関数 | 用途 | 例 |
|---|---|---|
| `g.rem($px)` | px → rem 変換（base 16px） | `g.rem(32)` → `2rem` |
| `g.get_vw($size, $view)` | px → vw 変換 | `g.get_vw(16, 390)` |
| `g.get_lh($fz, $lh)` | line-height 算出 | `g.get_lh(16, 28)` |
| `g.get_zindex($key)` | z-index map から値取得 | `g.get_zindex(header)` → `1000` |
| `g.unicode($str)` | FontAwesome コードポイント用 | `g.unicode("f105")` |

### mixin

| mixin | 用途 | 例 |
|---|---|---|
| `g.hover` | hover + focus（pointer: fine 対応） | `@include g.hover { opacity: 0.8; }` |
| `g.fa5-far($code)` | FontAwesome アイコン出力 | `@include g.fa5-far("f105");` |

### メディアクエリ変数

`g.$sm`, `g.$md`, `g.$lg`, `g.$xl`, `g.$xxl`（min-width）
`g.$maxSm`, `g.$maxMd`, `g.$maxLg`（max-width）

---

## 12. デザイントークン

### CSS 変数（:root）

定義箇所: `_tailwind-base-layer.scss`

**カラー:**
`--clr1` 〜 `--clr5`, `--clr-prim-green`, `--grd1`, `--grd2`, `--black`, `--clrg50` 〜 `--clrg900`, `--gray`, `--link-color`, `--link-hover-color`

**gutter 系:**
`--unit`, `--space`, `--gutter`, `--gutter-row`（BP ごとにレスポンシブ）

**レイアウト:**
`--header-height`（BP ごとにレスポンシブ: 50px → 56px → 80px）

**その他:**
`--bdrs`（border-radius）, `--bdrs-lg`, `--img-hover-opacity`

### tailwind.config.js

| キー | 内容 |
|---|---|
| `theme.screens` | BP 定義（sm 〜 2xl） |
| `theme.spacing` | 独自スペーシングスケール（mt-4 = 32px） |
| `theme.colors` | CSS 変数を参照するカラーパレット |
| `theme.zIndex` | z-index マップ |
| `theme.fontFamily` | sans / serif / mono |
| `theme.borderRadius` | 6px |
| `theme.transitionDuration` | 200ms |
| `extend.fontSize` | fz12 〜 fz36（カスタムサイズ） |
| `extend.padding` | gutter 系カスタムパディング |
| `extend.gap` | grid-gutter |

### Tailwind content スキャン

```js
content: ['./**/*.php']
```

PHP テンプレートのみがスキャン対象。HTML ファイルはスキャンされない。

---

## 13. リセット CSS の前提

### 構成

リセットは Tailwind Preflight + `_tailwind-base-layer.scss` の `@layer base` 内の補足で構成される。

### 補足している主な項目

- `*::before, *::after` の font-smoothing
- `body` の position, background-color, color, font-family, font-size
- `h1` 〜 `h6` の font-weight: normal, line-height: inherit
- `a` の text-decoration: none, transition, color（CSS 変数参照）
- form 要素の appearance: none, outline: 0
- `button` / `select` の text-transform: none
- `::placeholder` のカラー
- `::selection` のハイライト色
- touch-action: manipulation（タッチ系要素）
- `strong` 要素のカラーとレスポンシブ font-size

### form 用 CSS 変数

`.form` スコープ内に `--form-*` 変数を定義（`@layer components` 内）。
input 系 10 変数 + select 固有 3 変数。

定義箇所: `_tailwind-base-layer.scss`
