# Phase 4 作業計画: コンポーネント・プロジェクト層の移行

> 作成日: 2026-03-16
> 前提: Phase 1〜3 完了済み。progress.md、tailwind-migration-plan.md §4、_archive/ 議論ログ、全対象 SCSS ファイルの実コード確認に基づく。

---

## 1. Phase 4 の目的

既存のコンポーネントクラス（`.c-*`, `.p-*`, `.l-*`）のまとまりとクラス名を維持したまま、`@layer components` に移行する。

**やること**:
- 各 SCSS ファイルの出力を `@layer components { ... }` で囲む
- SCSS 変数参照（`g.$clr1` 等）を CSS 変数参照（`var(--clr1)` 等）に置換する（計画書 §1.4 カテゴリ A に基づく）
- `grid.make-col` mixin の依存を解消し、`layout/_grid.scss` スタブを廃止可能にする
- `project/_style.scss` の `:root` ブロックを整理する（重複候補のみ）
- `project/_form.scss` が参照する `foundation/_variables-form.scss` の SCSS 変数を `.p-form` スコープ CSS 変数に切り替える

**やらないこと**:
- コンポーネントクラスを Tailwind ユーティリティに分解しない
- SCSS 計算関数（`g.rem`, `g.get_vw`, `g.get_lh`）を書き換えない
- ブレークポイント mixin（`@media #{g.$md}` 等）を `@screen md` に変換しない（変更不要: 確定済み §4.3 #3）
- `@extend %placeholder` を展開・`@apply` 化しない（変更不要: 確定済み §4.3 #4）
- `@include g.hover` を Tailwind バリアントに置き換えない（変更不要: 確定済み §4.3 #7）
- PHP テンプレートのクラス名を変更しない
- ベンダー CSS（FontAwesome, Swiper, Micromodal, scroll-hint, Ultimate Member, WP Instagram Feed）に手を入れない（Phase 5）

---

## 2. 対象範囲と対象外

### 2.1 Phase 4 対象ファイル

#### layout/（3 ファイル）

| ファイル | 主要クラス | 備考 |
|---|---|---|
| `layout/_content.scss` | `.l-main`, `.l-blog__*`, `.l-container`, `.l-split__*` | Phase 3 で除外、Phase 4 で対応 |
| `layout/_header.scss` | `.l-header`, `.l-nav`, `:root { --header-height }` | |
| `layout/_footer.scss` | `.l-footer`, `.l-footernav` | |

#### component/（12 ファイル — アクティブなもの）

| ファイル | 主要クラス | 備考 |
|---|---|---|
| `component/_button.scss` | `.c-button`, `.c-button__clr*`, `.c-button__tel--*` | `g.$clr1`, `g.$clr2`, `g.$border-radius` 参照多数 |
| `component/_header.scss` | `.c-header`, `.c-toolbar__*`, `.c-nav__*` | `g.$clr1`, `g.$transition` 参照 |
| `component/_footer.scss` | `.c-footer`, `.c-footernav__*`, `:root { --footernavi-height }` | |
| `component/_post.scss` | `.c-post__archive--*`, `.c-post__widget--*` | |
| `component/_post-feed.scss` | `.c-post-feed__*` | CSS 変数のみ使用、SCSS 変数参照なし |
| `component/_search.scss` | `.c-search`, `.c-search__submit` | `g.$black` 参照 |
| `component/_style.scss` | `.c-replace__*`, `.c-button-2columns__*`, `.c-likepost__*`, `.c-more__*`, `.c-categorylist__*`, `.c-thumbnail-list__*` | **`grid.make-col` 使用あり**。`g.$container-max-*`, `g.$clr3`, `g.$transition-base` 参照 |
| `component/_typ.scss` | `.c-ttl__*`, `.c-typ`, `.c-form__ttl` | `g.get_vw` 使用、CSS 変数参照中心 |
| `component/_google-map.scss` | `.c-gmap` | 依存少 |
| `component/_login.scss` | `body.login`, `#login` | WordPress 管理画面ログイン用 |
| `component/_pagenation.scss` | `.c-pagenation` | CSS 変数のみ |
| `component/_validation.scss` | `.js-error_submit`, `.js-error` | 依存少 |

#### project/（10 ファイル）

| ファイル | 主要クラス | 備考 |
|---|---|---|
| `project/_button.scss` | `.p-button__wrap`, `#anc_service`, `#anc_short` 等 | CSS 変数使用中心 |
| `project/_entrystep.scss` | `.p-entrystep`, `.p-entrystep__item--*` | **`g.gutter` mixin 使用**。`g.$border-radius` 参照 |
| `project/_footer.scss` | `.p-footer`, `.p-footernav`, `.p-footer-modal`, `.p-footer-fix__reserve` | `g.$clrg700`, `g.$transition-base` 参照 |
| `project/_form.scss` | `.p-form`, `.p-form__*`, `.p-submit__*` | **`g.gutter` mixin 使用**。**`f.$input-*` 参照（`_variables-form.scss`）**。最重要ファイル |
| `project/_header.scss` | `.p-header`, `.p-toolbar`, `.p-nav` | `g.$black`, `g.$grd1`, `g.$clrg400`, `g.$transition-base` 参照 |
| `project/_js-inview.scss` | `.js-fadeup`, `.js-fadeup__once`, `.js-fadeup__stagger--list li` | 依存なし |
| `project/_post.scss` | `.p-post__archive`, `.p-sidebar`, `.p-related`, `.p-latest-card`, `.p-archive__list` | **`g.gutter` mixin 使用**。`g.$clr1`, `g.$transition-base` 参照 |
| `project/_post-single.scss` | `.p-post-single`, `.p-author` | **`g.gutter` mixin 使用**。`g.$transition` 参照 |
| `project/_style.scss` | `.p-salon-info`, `.p-menu`, `.p-company-detail`, `.p-recruit__feature--*`, `.p-qa`, `.p-sitemap__list`, `.swiper-front`, `.swiper-style` | **`:root` でブランドカラー全再定義**。`g.$clrg600`, `g.$black`, `grid` import あり |
| `project/_typ.scss` | `.p-ttl__*` | `g.$link-color` 参照 |

### 2.2 Phase 4 対象外

| ファイル | 理由 |
|---|---|
| `layout/_grid.scss` | Phase 3 で `make-col` mixin スタブのみ残存。Phase 4 で依存解消後に削除可能になるが、削除自体は Phase 4 の最終ステップ |
| `component/fontawesome-free-5.14.0/` | ベンダー CSS（Phase 5） |
| `component/micromodal/` | ベンダー CSS（Phase 5） |
| `component/swiper/` | ベンダー CSS（Phase 5） |
| `component/scroll-hint/` | ベンダー CSS（Phase 5） |
| `component/ultimate-member/` | ベンダー CSS（Phase 5） |
| `component/wp-instagram-feed/` | style.scss でコメントアウト済み。Phase 5 で判断 |
| `component/_tab.scss` | style.scss でコメントアウト済み。現在無効 |
| `component/_table.scss` | style.scss でコメントアウト済み。現在無効 |
| `component/_toggle.scss` | style.scss でコメントアウト済み。現在無効 |
| `foundation/_variables-color.scss` | Phase 4 完了後に廃止判断。Phase 4 中は `global/_index.scss` 経由で参照されるため残す |
| `foundation/_variables-form.scss` | Phase 4 で参照を切る対象だが、ファイル削除は Phase 4 完了後に判断 |
| `foundation/_variables.scss` | 複数ファイルが `g.$grid-columns`, `g.$container-max-*`, `g.$border-radius`, `g.$transition-base` 等を参照。Phase 4 中は削除しない |
| `global/_gutter.scss` | Phase 4 対象ファイルが使用中。Phase 4 では触らない（後述） |

---

## 3. 事前確認結果

### 3.1 フォーム関連 SCSS 変数の扱い

**確認した内容**: `project/_form.scss` と `foundation/_variables-form.scss` の参照関係。

**事実**:
- `project/_form.scss` は `@use "../foundation/variables-form" as f;` で 19 個の SCSS 変数を参照
- 使用されている変数（アクティブコードのみ）:
  - `f.$input-padding-y`, `f.$input-padding-x` — `.p-form__control--input` 等の padding
  - `f.$input-transition` — `.p-form__control--input` 等の transition
  - `f.$input-btn-border-width` — `.p-form__control--input` 等の border-width
  - `f.$input-border-color` — `.p-form__control--input` 等の border-color
  - `f.$input-border-radius` — `.p-form` 本体、`.p-form__group strong` の border-radius
  - `f.$input-bg` — `.p-form__control--input` 等の background-color
  - `f.$input-color` — `.p-form__control--input` 等の color
  - `f.$input-line-height` — `.p-form__control--input` 等の line-height
  - `f.$custom-select-border-width` — `.p-form__control--select` 系の border-width
  - `f.$custom-select-border-color` — `.p-form__control--select` 系の border-color
  - `f.$custom-select-bg` — `.p-form__control--select` 系の background-color
  - `f.$custom-select-indicator-bgi` — `.p-form__control--select` 系の background-image
  - `f.$custom-select-indicator-repeat` — `.p-form__control--select` 系の background-repeat
  - `f.$custom-select-indicator-position` — `.p-form__control--select` 系の background-position
  - `f.$custom-select-bg-size` — `.p-form__control--select` 系の background-size
  - `f.$custom-select-color` — `.p-form__control--select` 系の color
  - `f.$custom-select-line-height` — `.p-form__control--select` 系の line-height
- **`_variables-form.scss` の変数同士が参照し合っている**:
  - `$custom-select-line-height: $input-line-height`
  - `$custom-select-color: $input-color`
  - `$custom-select-border-width: $input-btn-border-width`
  - `$custom-select-border-color: $input-border-color`

**確定方針との整合**:
- マスター計画書 §1.4 カテゴリ C: `.p-form` にスコープした CSS 変数として定義
- Phase 1 で `.p-form` 内に `--form-padding-x`, `--form-padding-y`, `--form-line-height`, `--form-bg`, `--form-color`, `--form-border-color`, `--form-border-width`, `--form-border-radius`, `--form-box-shadow`, `--form-transition`, `--form-select-bg`, `--form-select-bg-size`, `--form-select-indicator` の 13 変数が既に定義済み（`_tailwind-base-layer.scss` L376-393）
- **Phase 4 ではこの既定義の CSS 変数への参照切り替えが主作業**

**やること**:
- `project/_form.scss` 内の `f.$input-*` / `f.$custom-select-*` 参照を `var(--form-*)` に置換
- `f.$input-border-radius` → `var(--form-border-radius)` に置換（`.p-form` 本体と `.p-form__group strong` の 2 箇所）
- 置換後に `@use "../foundation/variables-form" as f;` を削除

**やらないこと**:
- `project/_form.scss` ファイル冒頭で定義されているローカル変数（`$input-border-focus`, `$input-box-shadow-focus`, `$active-indicator-color`, `$form-bgc`, `$form-bdc`, `$form__caution`, `$form__caution--icon`, `$form__required`, `$form__required-bgc`, `$form__group-bdc`, `$form__title-bdc`, `$form__submit-back-bgc`, `$form__submit-back-bgc--hover`, `$radio-padding`, `$select-padding`, `$dt-width`）は `project/_form.scss` 内でのみ使用されるローカル変数。これらは SCSS のファイルスコープ内で完結しており、CSS 変数化の対象外。そのまま維持する
- placeholder 色（`$input-color-placeholder`）は `_tailwind-base-layer.scss` の `@layer base` 内 `::placeholder` で `var(--clrg500)` として既に直接記述済み。`_variables-form.scss` の定義は使われていない
- `$cursor-disabled` は `_tailwind-base-layer.scss` の `@layer base` 内 `input[type="radio"]:disabled, input[type="checkbox"]:disabled` で `cursor: not-allowed` として既に直接記述済み

### 3.2 :root 宣言の扱い

**確認した内容**: Phase 4 対象ファイル内の `:root` 宣言 3 箇所。

#### A. `_tailwind-base-layer.scss` :root（サイト全体トークン — 既存）
- L13-36: カテゴリ A の 22 変数（`--clr1` 〜 `--link-hover-color`）
- L42-82: gutter 系 CSS 変数（`--unit`, `--space`, `--gutter`, `--gutter-row` × 6 BP）
- **Phase 1 で作成済み。Phase 4 では変更しない**

#### B. `project/_style.scss` :root（L62-86 — ブランドカラー再定義候補）

```scss
:root {
  --clr1: #{g.$clr1};
  --clr1-hover: #{color.scale(g.$clr1, $lightness: -20%)};
  --clr2: #{g.$clr2};
  // ... --clr5, --grd1, --grd2, --clrg50〜--clrg900, --black, --gray
  --bdrs: #{g.rem(50)};
  --bdrs-lg: #{g.rem(100)};
  --img-hover-opacity: 0.9;
}
```

**分類**:
- `--clr1` 〜 `--gray` の 22 変数: `_tailwind-base-layer.scss` の `:root` と**同じ値を同じ名前で再定義**している。SCSS コンパイル後の CSS 出力で `:root` ブロックが 2 つ並ぶ形になるが、同じ値なので実害はない。ただし **カテゴリ A の変数は `_tailwind-base-layer.scss` に一元化する方針が確定している**ため、Phase 4 でこの重複定義を削除する
- `--clr1-hover`: `color.scale(g.$clr1, $lightness: -20%)` の計算結果。`_tailwind-base-layer.scss` にはない。**`component/_style.scss` L357 (`.c-likepost__item` の hover) と `component/_header.scss` L165 (`.c-nav__button` の hover) で参照**。このプロジェクト固有のトークンとして `_tailwind-base-layer.scss` の `:root` に移動する
- `--bdrs`, `--bdrs-lg`: border-radius のデザイントークン。`project/_style.scss` 内の `.swiper-front__container` 等で使用。**`project/_style.scss` 内に残す（確定）。`_tailwind-base-layer.scss` には移動しない**
- `--img-hover-opacity`: `.c-likepost`, `.c-thumbnail-list`, `.c-post__archive`, `.p-sidebar`, `.p-related`, `.p-latest-card` 等で広く参照。サイト全体トークンとして `_tailwind-base-layer.scss` の `:root` に移動する

**やること**: Step 4-2 で具体化

#### C. `layout/_header.scss` :root（L8-20 — ヘッダー専用トークン）

```scss
:root {
  --header-height: #{g.rem(50)};
  @media #{g.$md} { --header-height: #{g.rem(56)}; }
  @media #{g.$lg} { --header-height: #{g.rem(80)}; }
}
```

- **ヘッダー高さ専用のトークン**。`layout/_header.scss`, `layout/_content.scss`, `project/_header.scss`, `project/_button.scss` で参照
- コンポーネントスコープのトークンではなく、サイト全体のレイアウトトークン
- **`_tailwind-base-layer.scss` の `:root` に移動し、`layout/_header.scss` からは削除する**

#### D. `component/_footer.scss` :root（L10-12 — フッターナビ専用トークン）

```scss
:root {
  --footernavi-height: #{g.rem(55)};
}
```

- **フッターナビ高さ専用のトークン**。`component/_footer.scss` 内でのみ参照（`.c-footer--have-footernavi`, `%footernav__button`, `%footernav__button__right`）
- **統合は不要**。このファイル内で完結しており、Phase 4 で `@layer components` に入れる際にそのまま維持する

### 3.3 layout/grid 参照の実態

**確認した内容**: `layout/_grid.scss` を `@use` しているファイルと、`grid.make-col` の使用箇所。

**事実**:
- `component/_style.scss` L5: `@use "../layout/grid" as grid;`
  - L178: `@include grid.make-col(7);` — `%replace__content` placeholder 内、`@media #{g.$sm}` で幅 `percentage(7/12)` = 58.333% を出力
  - L181: `@include grid.make-col(6);` — 同 placeholder 内、`@media #{g.$lg}` で幅 `percentage(6/12)` = 50% を出力
  - **このファイルは `grid.make-col` を実使用している**
- `project/_style.scss` L4: `@use "../layout/grid" as grid;`
  - **ファイル内のアクティブコード（コメント外）で `grid.` を参照する箇所はゼロ**
  - **未使用 import**

**`grid.make-col` mixin の出力**:
```scss
@mixin make-col($size, $columns: g.$grid-columns) {
  width: percentage(math.div($size, $columns));
}
```
- `make-col(7)` → `width: 58.3333333333%`
- `make-col(6)` → `width: 50%`

**影響を受けるセレクタ**（`%replace__content` を `@extend` しているクラス）:
- `.c-replace__content--left` (L219)
- `.c-replace__content--right` (L239)

これらは「写真だけ飛び出す」レイアウトの内容エリア幅を制御。sm で 7/12、lg で 6/12 の幅を設定している。

**置換方法**: `@include grid.make-col(7)` → `width: percentage(math.div(7, 12))` （`sass:math` は既に `component/_style.scss` で `@use` 済み）。つまり SCSS の `percentage()` + `math.div()` で直接記述すれば、`grid.make-col` mixin への依存を解消できる。`g.$grid-columns`（= 12）はハードコードで問題ない（12 カラムグリッドは変更される想定がない）。

### 3.4 foundation 変数ファイルの現状

#### `foundation/_variables-color.scss`

**参照経路**: `global/_index.scss` → `@forward "../foundation/_variables-color"` → 全ファイルが `@use "../global" as g;` で `g.$clr1` 等としてアクセス。

**Phase 4 対象ファイルからの直接参照**（grep 結果に基づく）:
- `g.$clr1`: component/_button.scss（12 箇所）, component/_header.scss（3 箇所）, project/_form.scss（1 箇所）, project/_post.scss（1 箇所）, project/_style.scss（:root で全変数、+ L64 の `color.scale`）
- `g.$clr2`: component/_button.scss（4 箇所）
- `g.$clr3`: component/_style.scss（1 箇所）
- `g.$border-radius`: component/_button.scss（1 箇所）, component/_footer.scss（1 箇所）, project/_entrystep.scss（1 箇所）, project/_form.scss（2 箇所）
- `g.$transition-base`: component/_header.scss（1 箇所）, component/_footer.scss（1 箇所）, component/_style.scss（3 箇所）, project/_header.scss（3 箇所）, project/_post.scss（1 箇所）, project/_footer.scss（2 箇所）
- `g.$transition`: component/_header.scss（3 箇所）, project/_form.scss（1 箇所）, project/_post.scss（1 箇所）, project/_post-single.scss（1 箇所）
- `g.$black`: component/_search.scss（1 箇所）, component/_table.scss（2 箇所）, project/_header.scss（1 箇所）, project/_style.scss（2 箇所）
- `g.$clrg200`: component/_header.scss（1 箇所 — ローカル変数定義の mixin 引数で使用）
- `g.$clrg400`: project/_header.scss（1 箇所 — `rgba()` 内）
- `g.$clrg600`: project/_style.scss（5 箇所 — `border-top: 1px dashed g.$clrg600`）
- `g.$clrg700`: project/_footer.scss（1 箇所 — `color: g.$clrg700`）
- `g.$grd1`: project/_header.scss（1 箇所 — `background-image: g.$grd1`）
- `g.$link-color`: project/_typ.scss（1 箇所 — `color: g.$link-color`）
- `g.$container-max-md`, `g.$container-max-lg`, `g.$container-max-xl`, `g.$container-max-xxl`: component/_style.scss（4 箇所 — `%replace__pic` の `max-width` / `width` 計算）

**Phase 4 での扱い**:
- `g.$clr1`, `g.$clr2`, `g.$clr3`, `g.$clr4`, `g.$clr5`, `g.$black`, `g.$gray`, `g.$clrg*`, `g.$grd1`, `g.$grd2`, `g.$link-color` → `var(--clr1)` 等の CSS 変数に置換する
- **ただし SCSS の `rgba()` や算術式で使っている箇所はそのままでは置換できない**:
  - `rgba(g.$clr1, 0.5)` → `rgba(#4FBA43, 0.5)` にハードコード化（SCSS コンパイル時に色値が必要）
  - `rgba(g.$black, 0.2)` → `rgba(#222, 0.2)` にハードコード化
  - `rgba(g.$black, .1)` → `rgba(#222, .1)` にハードコード化
  - `rgba(g.$clrg200, .5)` → `rgba(#e8e8e8, .5)` にハードコード化
  - `rgba(g.$clrg400, .5)` → `rgba(#b0b0b0, .5)` にハードコード化
  - `rgba(g.$black, 0.8)` → `rgba(#222, 0.8)` にハードコード化
- `g.$border-radius` → `6px` ハードコード化（確定）。`g.$border-radius` は単なる `6px` の定数。CSS 変数化しない
- `g.$transition-base` → `all 0.2s ease-in-out` ハードコード化（確定）。CSS 変数化しない
- `g.$transition` → `all 0.2s ease-in-out` ハードコード化（確定）。実体は `g.$transition-base` と同一（`_variables.scss` L88）
- `g.$container-max-md`, `g.$container-max-lg`, `g.$container-max-xl`, `g.$container-max-xxl` → `960px`, `1152px`, `1200px`, `1260px` ハードコード化（`component/_style.scss` の `%replace__pic` 内 `calc()` で使用。CSS 変数では `calc()` 内で動作するが、現行の SCSS 値を直接記述する方が簡明）

**Phase 4 では `foundation/_variables-color.scss` ファイル自体は削除しない**。`global/_index.scss` から `@forward` されており、Phase 4 対象外のベンダー CSS ファイルも `g.$clr1` 等を参照しているため。Phase 5 でベンダー CSS の変数置換を完了した後に削除を判断する。

#### `foundation/_variables-form.scss`

Phase 4 で `project/_form.scss` からの参照を切る（§3.1 参照）。参照を切った後も、他のファイルからの import がないことを確認してから削除する。

**参照確認結果**: `_variables-form.scss` を `@use` しているのは `project/_form.scss` のみ（`@use "../foundation/variables-form" as f;`）。Phase 4 で参照切り替え後、ファイル削除可能。

#### `foundation/_variables.scss`

**Phase 4 対象ファイルから参照される変数**:
- `g.$grid-columns`（= 12）: `layout/_grid.scss` の `make-col` mixin のデフォルト引数。Phase 4 で make-col 依存解消後は不要
- `g.$container-max-*`: `component/_style.scss` の 4 箇所。Phase 4 でハードコード化
- `g.$border-radius`（= 6px）: 5 箇所
- `g.$transition-base`, `g.$transition`（= `all 0.2s ease-in-out`）: 14 箇所
- `g.$font-family-sans-serif`, `g.$font-family-serif`: `component/_typ.scss` の `%font-min` placeholder（`font-family: "Times New Roman", ...` でハードコード済み。`g.$font-family-serif` は使われていない）
- `g.$space_values`, `$layout_zindex`, `get_zindex()`: 間接的に global 経由で参照

**Phase 4 では `foundation/_variables.scss` ファイル自体は削除しない**。`get_zindex()` 関数と `$layout_zindex` は SCSS 関数維持方針（計画書 §1.3）に基づき維持する。`g.$border-radius` は `6px` に、`g.$transition-base` / `g.$transition` は `all 0.2s ease-in-out` に Phase 4 対象箇所ではハードコード化する。全参照の切り替えが完了するまでファイルは残す。

### 3.5 global/_gutter.scss の扱い

**確認した内容**: `global/_gutter.scss` の `gutter` / `gutter_row` mixin を使用しているファイル。

**使用箇所**（Phase 4 対象ファイル内）:
- `project/_entrystep.scss` L20: `@include g.gutter;`
- `project/_form.scss` L51: `@include g.gutter;`（`.p-form-caution` 内）
- `project/_post.scss` L123: `@include g.gutter;`（`.p-sidebar` 内）
- `project/_post.scss` L192: `@include g.gutter;`（`.p-related` 内）
- `project/_post-single.scss` L11: `@include g.gutter;`（`.p-post-single` 内）
- `project/_post-single.scss` L115: `@include g.gutter;`（`.p-author` 内）

**mixin の出力内容**:
```scss
@mixin gutter {
  padding: 0 var(--gutter-row);
  @media #{g.$md} { padding: 0; }
}
@mixin gutter_row {
  padding: 0 var(--gutter-row);
  @media #{g.$xl} { padding: 0; }
}
```

**判断**: Phase 4 では `global/_gutter.scss` に手を入れない。mixin は CSS 変数 `var(--gutter-row)` を使用しており、`@layer components` 内でもそのまま動作する。マスター計画書 §5.5 で廃止候補と記載されているが、Phase 3 の progress.md で「維持確認済み」と記録されている。Phase 4 の対象外とし、Phase 5 で改めて判断する。

---

## 4. 過去議論との整合

### 4.1 初期分析メモと確定方針のズレ

| 項目 | 初期分析メモ（`tailwind-migration-analysis.md`）の記述 | 確定方針（マスター計画書 + 議論ログ） | Phase 4 での扱い |
|---|---|---|---|
| ブレークポイント mixin | §3: `@screen md` への変換を提案 | §4.3 #3: 変更不要（SCSS コンパイルが先に走る） | **変更しない** |
| `@extend` | §3: 展開 or `@apply` を提案 | §4.3 #4: 変更不要（同一ファイル内完結、SCSS コンパイルが先） | **変更しない** |
| hover mixin | §3: Tailwind `hover:` バリアントへの置換を提案 | §4.3 #7: 変更不要（`_hover.scss` そのまま残す） | **変更しない** |
| `color.scale()` | §3: ハードコード vs `color-mix()` を未議論と記載 | 計画書 §4.3 #5: 全 33 箇所ハードコード済み（2026-03-13 完了） | **完了済み。追加作業なし** |
| フォーム変数 | §6: foundation の変数を一括で `:root` CSS 変数に移行と記載 | §1.4 カテゴリ C: `.p-form` スコープ CSS 変数（`:root` には置かない） | **カテゴリ C 方針に従う** |
| グリッド移行 | `grid-strategy-discussion.md`: C+案（内部再定義）を推奨 | 最終的に B案（Tailwind 完全移行）で Phase 3 完了済み | **Phase 3 で確定済み。Phase 4 は残存 make-col 依存解消のみ** |

### 4.2 _handoff-log.md で「未議論」とされた項目の現状

| 項目 | _handoff-log.md での状態 | 現在の状態 |
|---|---|---|
| §3.5 レイアウトパターン（split / float） | ⚠️ AI が方針を決めた疑い | **確定**: split は flexbox のまま維持。float は使用コンテンツごと削除済み（計画書 §3.5） |
| destyle.css 削除の妥当性 | ⚠️ 未議論 | **完了**: Phase 1 で destyle.css → Tailwind Preflight に移行済み。差分は `_reset-diff-inventory.md` で棚卸し、`@layer base` で補足済み |
| §4.3 コンポーネント移行の個別手法 | ⚠️ 未議論 | **確定**: ブレークポイント mixin・@extend・hover mixin は全て変更不要（2026-03-13 実コード確認済み）。color.scale() はハードコード済み |

---

## 5. 現状コードの参照関係と影響整理

### 5.1 ファイル間参照マップ（Phase 4 対象ファイルのみ）

```
global/_index.scss
  ├── @forward foundation/_variables       ← g.$border-radius, g.$transition-base, g.$container-max-* 等
  ├── @forward foundation/_variables-color  ← g.$clr1, g.$clrg*, g.$black 等
  ├── @forward global/_breakpoints          ← g.$sm, g.$md 等
  ├── @forward global/_calc                 ← g.rem(), g.get_vw() 等
  ├── @forward global/_font                 ← g.webfont-lato()
  ├── @forward global/_gutter               ← g.gutter, g.gutter_row
  ├── @forward global/_hover                ← g.hover
  ├── @forward global/_transition           ← g.$transition
  └── @forward global/_unicode              ← g.unicode()

project/_form.scss
  ├── @use "../global" as g
  └── @use "../foundation/variables-form" as f  ← f.$input-*, f.$custom-select-*

component/_style.scss
  ├── @use "../global" as g
  └── @use "../layout/grid" as grid  ← grid.make-col()

project/_style.scss
  ├── @use "../global" as g
  ├── @use "../layout/grid" as grid  ← 未使用 import
  ├── @use "button" as button        ← button.button__width mixin
  └── @use "../component/typ" as typ ← 未使用（確認必要）
```

### 5.2 `@layer components` への移行で影響が出る可能性のある箇所

**影響なし（確認済み）**:
- `@media #{g.$md}` 等のブレークポイント: SCSS コンパイルで展開済みのため `@layer` 内で動作
- `@extend %placeholder`: 同一ファイル内完結のため `@layer` 内で動作
- `@include g.hover`: SCSS コンパイルで展開済みのため `@layer` 内で動作
- `@include g.gutter` / `g.gutter_row`: CSS 変数 `var(--gutter-row)` を出力するだけのため `@layer` 内で動作
- `g.rem()`, `g.get_vw()`: SCSS コンパイル時に数値に展開されるため `@layer` 内で動作

**注意点**:
- `@layer components` 内のスタイルは、`@layer` 外のスタイルより詳細度が低くなる。Phase 4 では全 component/project/layout ファイルを `@layer components` に入れるため、相対的な優先順位は維持される。ただし、`@layer` 外に残るベンダー CSS（Phase 5 対象）との優先順位が変わる可能性がある → Phase 4 完了後の検証で確認

---

## 6. ファイル群ごとの移行方針

### 分類

| 分類 | 説明 | 対象ファイル |
|---|---|---|
| **A: ほぼそのまま `@layer components` に入れられる** | SCSS 変数参照が CSS 変数のみ、または参照なし | `component/_post-feed.scss`, `component/_google-map.scss`, `component/_pagenation.scss`, `component/_validation.scss`, `project/_button.scss`, `project/_js-inview.scss` |
| **B: SCSS 変数参照の置換が必要** | `g.$clr1` → `var(--clr1)` 等の変換が必要 | `component/_button.scss`, `component/_header.scss`, `component/_footer.scss`, `component/_post.scss`, `component/_search.scss`, `component/_typ.scss`, `component/_login.scss`, `layout/_content.scss`, `layout/_header.scss`, `layout/_footer.scss`, `project/_entrystep.scss`, `project/_footer.scss`, `project/_header.scss`, `project/_post.scss`, `project/_post-single.scss`, `project/_typ.scss` |
| **C: トークン整理や mixin 依存確認が必要** | `:root` ブロック整理、`grid.make-col` 解消、`f.$input-*` 切り替え | `component/_style.scss`（make-col + container-max）, `project/_style.scss`（:root 再定義 + grid 未使用 import）, `project/_form.scss`（f.$input-* 切り替え） |
| **D: ベンダー依存または Phase 5 領域** | Phase 4 では触らない | `component/fontawesome-free-5.14.0/`, `component/micromodal/`, `component/swiper/`, `component/scroll-hint/`, `component/ultimate-member/`, `component/wp-instagram-feed/` |

---

## 7. 実行ステップ

### Step 4-0: 事前準備

**対象ファイル**: `_tailwind-base-layer.scss`
**やること**: `project/_style.scss` から移動するサイト全体トークンを `:root` に追加
**やらないこと**: 既存の `:root` 定義の構造を変更しない。`--bdrs`, `--bdrs-lg` は `project/_style.scss` に残すため移動しない

1. `_tailwind-base-layer.scss` の `:root`（C-1 セクション）に以下を追加:
   - `--clr1-hover: #3c9536;`（`color.scale(#4FBA43, $lightness: -20%)` の計算結果）
   - `--img-hover-opacity: 0.9;`
2. ビルド確認

**確認方法**: `css/style.css` で `--clr1-hover`, `--img-hover-opacity` が `:root` に出力されていること
**完了条件**: ビルド成功、CSS 出力に 2 変数が含まれる

> **注**: `--clr1-hover` の値は `color.scale(#4FBA43, $lightness: -20%)` の計算結果を事前に算出する必要がある。ビルド済み CSS の `project/_style.scss` 出力から抽出する。

### Step 4-1: project/_style.scss の :root 整理 + grid 未使用 import 削除

**対象ファイル**: `project/_style.scss`
**やること**:
- L62-86 の `:root` ブロックから、`_tailwind-base-layer.scss` と重複する 22 変数（`--clr1` 〜 `--gray`）を削除
- `--clr1-hover` は Step 4-0 で `_tailwind-base-layer.scss` に移動済みなので削除
- `--img-hover-opacity` は Step 4-0 で `_tailwind-base-layer.scss` に移動済みなので削除
- **`--bdrs`, `--bdrs-lg` は `project/_style.scss` 内に残す（確定）**。`:root` ブロックは空にならず、この 2 変数を含む形で維持する
- L4 の `@use "../layout/grid" as grid;` を削除（未使用 import）
- L64 の `color.scale(g.$clr1, $lightness: -20%)` は Step 4-0 で CSS 変数化済みのため不要

**やらないこと**: `.test`, `.p-salon-info`, `.p-menu`, `.p-company-detail` 等のクラス定義には触らない。`--bdrs`, `--bdrs-lg` を移動・削除しない

**確認方法**: ビルド成功。`css/style.css` で `.p-salon-info`, `.p-menu` 等のクラスが以前と同じ出力であること。`:root` の重複定義（22 色変数 + `--clr1-hover` + `--img-hover-opacity`）が消え、`--bdrs`, `--bdrs-lg` のみ残っていること
**完了条件**: ビルド成功。`project/_style.scss` の `:root` に `--bdrs`, `--bdrs-lg` のみ残存。`grid` import が消えている

### Step 4-2: project/_style.scss の SCSS 変数→ CSS 変数置換

**対象ファイル**: `project/_style.scss`
**やること**:
- `g.$clrg600` → `var(--clrg600)` に置換（L269, L284, L298, L307 の `border-top: 1px dashed g.$clrg600`）
- `rgba(g.$black, 0.8)` → `rgba(#222, 0.8)` にハードコード化（L346, L351）
- `g.$border-radius` → `6px` にハードコード化（L529 の `.p-entrystep` の参照は不要 — ここでは _style.scss）

**やらないこと**: SCSS 計算関数やブレークポイント mixin はそのまま。CSS 変数（`var(--clr1)` 等）で既に記述されている箇所は変更しない

**確認方法**: ビルド成功。`.p-company-detail__dl dt` の `border-top` が `1px dashed` + 正しい色値で出力されていること
**完了条件**: `project/_style.scss` 内に `g.$clrg` / `g.$black` の直接参照がなくなっている

### Step 4-3: component/_style.scss の grid.make-col 依存解消

**対象ファイル**: `component/_style.scss`
**やること**:
- L178: `@include grid.make-col(7);` → `width: percentage(math.div(7, 12));` に置換
- L181: `@include grid.make-col(6);` → `width: percentage(math.div(6, 12));` に置換
- L5: `@use "../layout/grid" as grid;` を削除
- `g.$container-max-md` → `960px`, `g.$container-max-lg` → `1152px`, `g.$container-max-xl` → `1200px`, `g.$container-max-xxl` → `1260px` にハードコード化（L192, L197, L200, L204）

**やらないこと**: `%replace__content` / `%replace__pic` のレイアウト構造は変更しない

**確認方法**:
- ビルド成功
- `css/style.css` で `.c-replace__content--left` / `.c-replace__content--right` の sm 時 `width` が `58.3333333333%`、lg 時 `width` が `50%` であること
- `css/style.css` で `.c-replace__pic--right` / `.c-replace__pic--left` の `max-width` 計算値が移行前と同一であること

**完了条件**: `component/_style.scss` から `grid` import が消えている。出力 CSS の `.c-replace__*` セレクタの `width` / `max-width` 値が移行前後で一致

### Step 4-4: component/_style.scss の残り SCSS 変数置換

**対象ファイル**: `component/_style.scss`
**やること**:
- `g.$clr3` → `var(--clr3)` に置換（L529）
- `g.$transition-base` → `all 0.2s ease-in-out` にハードコード化（L374, L405, L572）

**確認方法**: ビルド成功
**完了条件**: `component/_style.scss` 内に `g.$clr` / `g.$transition` の直接参照がなくなっている

### Step 4-5: project/_form.scss の f.$input-* 参照切り替え

**対象ファイル**: `project/_form.scss`
**やること**:
- `f.$input-padding-y` → `var(--form-padding-y)` に置換
- `f.$input-padding-x` → `var(--form-padding-x)` に置換
- `f.$input-transition` → `var(--form-transition)` に置換
- `f.$input-btn-border-width` → `var(--form-border-width)` に置換
- `f.$input-border-color` → `var(--form-border-color)` に置換
- `f.$input-border-radius` → `var(--form-border-radius)` に置換
- `f.$input-bg` → `var(--form-bg)` に置換
- `f.$input-color` → `var(--form-color)` に置換
- `f.$input-line-height` → `var(--form-line-height)` に置換
- `f.$custom-select-border-width` → `var(--form-border-width)` に置換
- `f.$custom-select-border-color` → `var(--form-border-color)` に置換
- `f.$custom-select-bg` → `var(--form-select-bg)` に置換
- `f.$custom-select-indicator-bgi` → `var(--form-select-indicator)` に置換
- `f.$custom-select-indicator-repeat` → `no-repeat` にハードコード化（Phase 1 で CSS 変数化していない。値は `no-repeat` 固定）
- `f.$custom-select-indicator-position` → `right 6px center` にハードコード化（同上）
- `f.$custom-select-bg-size` → `var(--form-select-bg-size)` に置換
- `f.$custom-select-color` → `var(--form-color)` に置換（`$custom-select-color: $input-color` なので同じ値）
- `f.$custom-select-line-height` → `var(--form-line-height)` に置換（`$custom-select-line-height: $input-line-height` なので同じ値）
- `@use "../foundation/variables-form" as f;` を削除
- `rgba(g.$clr1, 0.5)` → `rgba(#4FBA43, 0.5)` にハードコード化（L10）
- `g.$border-radius` → `6px` にハードコード化（L529）
- `g.$transition` → `all 0.2s ease-in-out` にハードコード化（L527）

**やらないこと**:
- ファイル冒頭のローカル SCSS 変数（`$input-border-focus`, `$active-indicator-color` 等）はファイルスコープで完結しているため変更しない
- FontAwesome mixin（`g.fa5-far`, `g.unicode`）はそのまま維持
- `.p-form` の `@layer components` 囲みはこのステップでは行わない（Step 4-8 で一括）

**確認方法**:
- ビルド成功
- `css/style.css` で `.p-form__control--input` の `padding`, `border`, `border-radius`, `background-color`, `color`, `line-height` 値が移行前と同一であること
- `css/style.css` で `.p-form__control--select` の `background-image`（SVG data URI）、`background-position`、`border` 値が移行前と同一であること

**完了条件**: `project/_form.scss` 内に `f.$` 参照がゼロ。`@use "../foundation/variables-form"` が削除されている

### Step 4-6: component/_button.scss の SCSS 変数置換

**対象ファイル**: `component/_button.scss`
**やること**:
- `g.$border-radius` → `6px` にハードコード化（L13）
- `g.$clr1` の mixin **定義側**デフォルト値を `var(--clr1)` に置換。対象箇所:
  - `button__color` 定義 — L22: `$bgc: g.$clr1` → `$bgc: var(--clr1)`
  - `button__color--border` 定義 — L41: `$clr: g.$clr1` → `$clr: var(--clr1)`, L43: `$bdc: g.$clr1` → `$bdc: var(--clr1)`, L45: `$bgc-hover: g.$clr1` → `$bgc-hover: var(--clr1)`, L46: `$bdc-hover: g.$clr1` → `$bdc-hover: var(--clr1)`
  - ただし **SCSS mixin のデフォルト引数に `var()` を使う場合、引用符なしで記述する**。SCSS は `var(--clr1)` を未評価のまま CSS に出力する
- `g.$clr1` の mixin **呼び出し側**引数: `g.$clr1` を明示的に渡している箇所も `var(--clr1)` に置換する。対象箇所:
  - L162: `$bgc: g.$clr1` → `$bgc: var(--clr1)`
  - L189, L201: `$clr: g.$clr1` → `$clr: var(--clr1)`
  - L191, L203: `$bdc: g.$clr1` → `$bdc: var(--clr1)`
  - L193: `$bgc-hover: g.$clr1` → `$bgc-hover: var(--clr1)`
  - L194, L206: `$bdc-hover: g.$clr1` → `$bdc-hover: var(--clr1)`
- `g.$clr2` の mixin 呼び出し側も同様に `var(--clr2)` に置換する。対象箇所:
  - L225: `$clr: g.$clr2` → `$clr: var(--clr2)`
  - L227: `$bdc: g.$clr2` → `$bdc: var(--clr2)`
  - L229: `$bgc-hover: g.$clr2` → `$bgc-hover: var(--clr2)`
  - L230: `$bdc-hover: g.$clr2` → `$bdc-hover: var(--clr2)`
- 呼び出し側で既にハードコード hex 値が渡されている箇所はそのまま

**やらないこと**: mixin 構造を変更しない。`@keyframes` 定義はそのまま

**確認方法**: ビルド成功。`.c-button__clr1` の `background-color` が `#4fba43` であること
**完了条件**: `component/_button.scss` 内に `g.$clr` / `g.$border-radius` の直接参照がなくなっている

### Step 4-7: 残りの component/ project/ layout/ ファイルの SCSS 変数置換

**対象ファイル**: Step 4-1〜4-6 で未処理の全ファイル

| ファイル | 置換内容 |
|---|---|
| `component/_header.scss` | `g.$clr1` → `var(--clr1)`, `rgba(g.$clrg200, .5)` → `rgba(#e8e8e8, .5)`, `g.$transition-base` → ハードコード, `g.$transition` → ハードコード |
| `component/_footer.scss` | `g.$transition-base` → ハードコード, `g.$border-radius` → `6px` |
| `component/_post.scss` | 変換済み（`color.scale` は既にハードコード化済み） — 確認のみ |
| `component/_search.scss` | `rgba(g.$black, 0.2)` → `rgba(#222, 0.2)` |
| `component/_login.scss` | 依存確認（`g.hover` のみ、CSS 変数使用済み）— 置換不要の可能性 |
| `component/_typ.scss` | `color.scale` 済み。`var(--clr1)` 等の CSS 変数使用済み — 確認のみ |
| `layout/_content.scss` | SCSS 変数参照なし（`g.rem()` と CSS 変数のみ）— 確認のみ |
| `layout/_header.scss` | `:root` ブロックは Step 4-0 との連携で処理済み。残りのクラスは SCSS 変数参照なし — 確認のみ |
| `layout/_footer.scss` | `g.get_zindex(footer)` → そのまま維持（SCSS 関数維持方針） |
| `project/_entrystep.scss` | `g.$border-radius` → `6px` |
| `project/_footer.scss` | `g.$clrg700` → `var(--clrg700)`, `g.$transition-base` → ハードコード |
| `project/_header.scss` | `rgba(g.$black, .1)` → `rgba(#222, .1)`, `g.$grd1` → `var(--grd1)`, `rgba(g.$clrg400, .5)` → `rgba(#b0b0b0, .5)`, `g.$transition-base` → ハードコード |
| `project/_post.scss` | `g.$clr1` → `var(--clr1)`, `g.$transition-base` → ハードコード, `g.$transition` → ハードコード |
| `project/_post-single.scss` | `g.$transition` → ハードコード |
| `project/_typ.scss` | `g.$link-color` → `var(--link-color)` |

**確認方法**: 各ファイルのビルド成功。代表セレクタの出力確認
**完了条件**: 全 Phase 4 対象ファイルで `g.$clr*`, `g.$clrg*`, `g.$black`, `g.$gray`, `g.$grd*`, `g.$link-*`, `g.$border-radius`, `g.$transition-base`, `g.$transition`, `g.$container-max-*` の直接参照がゼロ（`g.rem()`, `g.get_vw()`, `g.get_zindex()`, ブレークポイント変数 `g.$sm` 等、CSS 変数 `var(--*)` は残る）

### Step 4-8: @layer components 囲み

**対象ファイル**: Phase 4 対象の全 component/ project/ layout/ ファイル
**やること**: 各ファイルの出力クラスを `@layer components { ... }` で囲む

**方法**:
- 各 SCSS ファイルの `@charset`, `@use` 文の後、クラス定義の開始前に `@layer components {` を追加
- ファイル末尾に `}` を追加
- `@mixin` 定義はファイル内で使用される場合 `@layer` の**外側**に置く（mixin 自体は出力を持たないため）
- `@keyframes` 定義は `@layer` の**外側**に置く（確定）
- `%placeholder` 定義は `@extend` で展開されるクラスと同じ `@layer` 内に置く
- `:root` 定義（`layout/_header.scss`, `component/_footer.scss`, `project/_style.scss`）は `@layer components` の**外側**に置く（`:root` はグローバルスコープ。`project/_style.scss` の `:root` には `--bdrs`, `--bdrs-lg` が残る）

**確認方法**: ビルド成功。`css/style.css` で `@layer components` ブロックが出力されていること
**完了条件**: 全 Phase 4 対象ファイルの出力が `@layer components` 内に含まれている

> **注意**: `@layer components` は `_tailwind-base-layer.scss` で既に使用されている（`.p-form` CSS 変数 + `.youtube`）。各ファイルで個別に `@layer components { }` を書くと、CSS 出力で複数の `@layer components` ブロックが出現するが、CSS 仕様上これは正常動作。同じ `@layer` 名のブロックは連結される。

### Step 4-9: layout/_header.scss の :root を _tailwind-base-layer.scss に移動

**対象ファイル**: `layout/_header.scss`, `_tailwind-base-layer.scss`
**やること**:
- `layout/_header.scss` L8-20 の `:root { --header-height: ... }` を `_tailwind-base-layer.scss` の `@layer base` 内 `:root`（C-2 セクションの後）に移動
- `layout/_header.scss` から `:root` ブロックを削除

**やらないこと**: `--header-height` の値やブレークポイントは変更しない

**確認方法**: ビルド成功。`css/style.css` で `--header-height` が `:root` に出力されていること。`.l-header` の `height: var(--header-height)` が正常に機能すること
**完了条件**: `--header-height` が `_tailwind-base-layer.scss` のみで定義されている

### Step 4-10: layout/_grid.scss スタブの削除

**前提**: Step 4-3 で `component/_style.scss` の `grid.make-col` 依存解消済み、Step 4-1 で `project/_style.scss` の `grid` 未使用 import 削除済み。

**対象ファイル**: `layout/_grid.scss`, `style.scss`
**やること**:
- `layout/_grid.scss` を削除
- `style.scss` L22 の `// @use "layout/_grid";` コメント行を削除（任意）

**確認方法**: ビルド成功。`grid.make-col` に依存するセレクタ（`.c-replace__content--left`, `.c-replace__content--right`）の `width` 値が変わっていないこと
**完了条件**: `layout/_grid.scss` が存在しない。ビルドエラーなし

### Step 4-11: foundation/_variables-form.scss の削除

**前提**: Step 4-5 で `project/_form.scss` の `f.$input-*` 参照切り替え済み。

**対象ファイル**: `foundation/_variables-form.scss`
**やること**:
- `foundation/_variables-form.scss` を削除
- `global/_index.scss` を確認し、`_variables-form.scss` への `@forward` がないことを確認（実際にはない — `global/_index.scss` は `_variables` と `_variables-color` のみ `@forward`）

**確認方法**: ビルド成功
**完了条件**: `foundation/_variables-form.scss` が存在しない。ビルドエラーなし

---

## 8. 検証手順

### 8.1 代表セレクタの出力照合

移行前の `css/style.css` を `css/style.css.bak` として保存し、移行後と比較する。

| 代表セレクタ | 確認する宣言 | 期待 |
|---|---|---|
| `.c-button` | `border-radius`, `padding`, `font-size` | 値が同一 |
| `.c-button__clr1` | `background-color`, `color` | `#4fba43`, `#fff` |
| `.c-button__clr1:hover` | `background-color` | `#47a73c` |
| `.c-replace__content--left` | sm 時 `width` | `58.3333333333%` |
| `.c-replace__content--left` | lg 時 `width` | `50%` |
| `.c-replace__pic--right` | sm 時 `max-width` | `calc((960px - var(--gutter-row) * 2) / 12 * 5)` |
| `.p-form__control--input` | `padding`, `border`, `border-radius`, `background-color`, `color`, `line-height` | `_variables-form.scss` の値と同一 |
| `.p-form__control--select` | `background-image`, `background-position`, `background-size` | SVG data URI + `right 6px center` + `12px` |
| `.p-header__row` | `height` | `var(--header-height)` |
| `.p-header__row.js-scroll` | `box-shadow` | `3px 3px 6px rgba(34, 34, 34, .1)`（`g.$black` = `#222`） |
| `.l-container` | `padding-top`, `padding-bottom` | `g.rem(32)` = `2rem` |
| `.l-split__outer` | sm 時 `display: flex` | 変わらず |
| `.p-footer` | `margin-top` | `g.rem(56)` = `3.5rem` |
| `.p-salon-info__dl dt` | sm 時 `width`, `padding` | 変わらず |
| `.c-categorylist__button` | `color` | `var(--clr3)` |

### 8.2 rem / vw 値の維持確認

`g.rem()` / `g.get_vw()` の出力は Phase 4 で変更されないため、以下の代表クラスで `rem` / `vw` 値が含まれていることを確認:

| セレクタ | 宣言 | 期待値の例 |
|---|---|---|
| `.c-button` | `padding` | `g.rem(20)` = `1.25rem` |
| `.c-typ` | `font-size` | `min(g.get_vw(16), g.rem(17))` → `min(...)` |
| `.l-blog__main` | md 時 `padding-right` | `g.rem(56)` = `3.5rem` |
| `.p-entrystep__icon` | md 時 `left` | `g.rem(12)` = `0.75rem` |

### 8.3 色値の維持確認

SCSS 変数 → CSS 変数に置換した箇所で、CSS 出力の色値が変わっていないことを確認:

| 移行前の参照 | 移行後の参照 | CSS 出力値 |
|---|---|---|
| `g.$clr1` | `var(--clr1)` | — （CSS 変数として出力） |
| `g.$clrg600` | `var(--clrg600)` | — （CSS 変数として出力） |
| `rgba(g.$black, .1)` | `rgba(#222, .1)` | `rgba(34, 34, 34, .1)` |
| `rgba(g.$clrg200, .5)` | `rgba(#e8e8e8, .5)` | `rgba(232, 232, 232, .5)` |
| `g.$grd1` | `var(--grd1)` | — （CSS 変数として出力） |

### 8.4 hover / media query / box-shadow の維持確認

| セレクタ | 確認ポイント |
|---|---|
| `.c-button__clr1:hover` | `@media(hover:hover)` ラッパーあり、`background-color` 値維持 |
| `.p-nav__item--recruit:hover` | `opacity: 0.85` |
| `.c-footer__fade--button:hover` | `background-color: #f3f9d7` |
| `.c-nav__button` | lg 時の `display: flex`, `font-size` |
| `.p-submit__button` | `box-shadow` 値 |

### 8.5 @layer components の出力確認

```bash
# @layer components ブロックが出力されていること
grep -c '@layer components' css/style.css

# 代表クラスが @layer 内にあること（目視で確認）
grep -A 2 '@layer components' css/style.css | head -20
```

### 8.6 レビューで止まりそうな点（事前洗い出し）

1. **`@keyframes` の配置**: `@layer components` の外に出す方針で確定済み。`component/_button.scss` の `pulse`, `shake`, `swing`, `flare-anime`, `iconAnimation` が対象。Step 4-8 で対応
2. **ベンダー CSS との優先順位**: `@layer components` 内のスタイルが、`@layer` 外のベンダー CSS（FontAwesome, Swiper 等）より低詳細度になる。Phase 4 完了後にスタイル崩れがないかブラウザ確認が必要
3. **`:root` 宣言が `@layer` の外にある場合の出力順**: `layout/_header.scss` の `:root` を `@layer components` の外に置くと、CSS 出力での位置が変わる可能性。`_tailwind-base-layer.scss` に移動することで解消

---

## 9. リスクと確定済み事項

### 9.1 リスク

| # | リスク | 影響度 | 対策 |
|---|---|---|---|
| 1 | `@layer components` 内スタイルの詳細度がベンダー CSS より低くなる | 中 | Phase 4 完了後にブラウザ確認。問題があれば Phase 5 でベンダー CSS も `@layer` に入れる |
| 2 | `rgba()` 内の SCSS 変数ハードコード時に色値を間違える | 低 | ビルド前後の CSS diff で色値を検証 |
| 3 | `f.$input-*` → `var(--form-*)` 置換で CSS 変数名を間違える | 中 | `_tailwind-base-layer.scss` の定義と照合。ビルド後にフォーム画面を目視確認 |
| 4 | `@keyframes` を `@layer` の外に出した際に参照が切れる | 低 | `@keyframes` は名前ベースの参照のため `@layer` の内外に関係なく動作する。ただしブラウザ確認 |
| 5 | `grid.make-col` を `percentage(math.div(...))` に書き換えた際に精度差が出る | 極低 | 同じ SCSS 関数を使用するため出力値は完全一致 |

### 9.2 確定済み事項（2026-03-16 ユーザー判断で確定）

| # | 事項 | 確定方針 |
|---|---|---|
| 1 | `g.$border-radius`（= `6px`）の扱い | `6px` ハードコード。CSS 変数化しない |
| 2 | `g.$transition-base` / `g.$transition`（= `all 0.2s ease-in-out`）の扱い | `all 0.2s ease-in-out` ハードコード。CSS 変数化しない |
| 3 | `@keyframes` の配置 | `@layer components` の外に置く |
| 4 | `--bdrs`, `--bdrs-lg` の扱い | `project/_style.scss` 内に残す。`_tailwind-base-layer.scss` には移動しない |

---

## 10. Phase 5 へ送る項目

| 項目 | 理由 |
|---|---|
| ベンダー CSS の SCSS 変数→ CSS 変数置換 | `component/fontawesome-free-5.14.0/`, `component/micromodal/`, `component/swiper/`, `component/scroll-hint/`, `component/ultimate-member/`, `component/wp-instagram-feed/` の 6 ディレクトリ |
| `foundation/_variables-color.scss` の削除 | ベンダー CSS が `g.$clr1` 等を参照しているため、Phase 5 でベンダー CSS の変数置換完了後に削除 |
| `foundation/_variables.scss` の削除 | `get_zindex()` 等の参照がベンダー CSS にも残る可能性。Phase 4 対象箇所では `g.$border-radius` → `6px`、`g.$transition-base` → `all 0.2s ease-in-out` にハードコード化済みだが、ベンダー CSS の参照が残るため Phase 5 で全参照を確認後に判断 |
| `global/_gutter.scss` の廃止判断 | マスター計画書 §5.5 で廃止候補。Phase 4 対象ファイルが使用中のため Phase 4 では触らない。Phase 5 で Tailwind ユーティリティへの置換を検討 |
| style.scss でコメントアウト済みファイルの整理 | `_tab.scss`, `_table.scss`, `_toggle.scss`, `_blockquote.scss` — 削除するか復活させるか判断 |
| `@layer components` 内スタイルとベンダー CSS の優先順位問題 | Phase 4 完了後にブラウザ確認し、Phase 5 でベンダー CSS も `@layer` に入れる必要があるか判断 |
