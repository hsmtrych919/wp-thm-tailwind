# Phase 5 作業計画: ベンダー CSS の整理

> 作成日: 2026-03-17
> 前提: Phase 1〜4 完了済み。progress.md、tailwind-migration-plan.md §5、style.scss、全 vendor SCSS ファイル、global/_index.scss、foundation 変数ファイルの実コード確認に基づく。

---

## 1. Phase 5 の目的

vendor SCSS ファイル内に残っている `g.$clr1`, `g.$border-radius`, `g.$transition-base` 等の SCSS 変数参照を CSS 変数またはハードコード値に置換し、`foundation/_variables-color.scss` への依存を断ち切る。

**やること**:
- active vendor SCSS ファイル内の SCSS 変数参照（`g.$border-radius`, `g.$transition-base`, `g.$clrg*`, `g.$clr1`）を Phase 4 と同じ方針（ハードコードまたは CSS 変数）で置換する
- `g.rem()`, `g.get_zindex()` 等の SCSS 関数はそのまま維持する（SCSS 計算関数維持方針）
- `@media #{g.$sm}` 等のブレークポイント変数はそのまま維持する（ブレークポイント mixin 維持方針、Phase 4 §4.3 #3 で確定済み）
- active vendor ファイルを `@layer components` で囲む（Phase 4 と同じ方式）
- 置換完了後、`foundation/_variables-color.scss` の削除可否を判断する

**やらないこと**:
- vendor CSS のセレクタやロジックを変更しない（マスター計画書 §5.1「極力変更を加えず、最低限の差分のみ」）
- SCSS 計算関数（`g.rem()`, `g.get_vw()`）を書き換えない
- ブレークポイント変数（`g.$sm`, `g.$md` 等）を書き換えない
- `@include g.hover` を Tailwind バリアントに置き換えない
- PHP テンプレートを変更しない
- `_tab.scss`, `_table.scss`, `_toggle.scss`, `_blockquote.scss` を本計画の主対象として扱わない

---

## 2. 対象範囲と対象外

### 2.1 Active vendor（style.scss から読み込まれている）

| # | ファイル | style.scss の読み込み行 |
|---|---|---|
| 1 | `component/fontawesome-free-5.14.0/_index.scss` | L32: `@use "component/fontawesome-free-5.14.0";` |
| 2 | `component/micromodal/_micromodal.scss` | L33: `@use "component/micromodal/micromodal";` |
| 3 | `component/swiper/_swiper-bundle.scss` | L34: `@use "component/swiper/swiper-bundle";` |
| 4 | `component/scroll-hint/index.scss` | L36: `@use "component/scroll-hint";` |

### 2.2 Inactive vendor（style.scss から読み込まれていない）

| # | ファイル | 状態 |
|---|---|---|
| 5 | `component/wp-instagram-feed/_wp-instagram-feed.scss` | style.scss L35 でコメントアウト中 |
| 6 | `component/ultimate-member/_ultimate-member.scss` | style.scss に読み込み行自体が存在しない |

### 2.3 対象外

| ファイル | 理由 |
|---|---|
| `component/_tab.scss` | vendor ではない。style.scss でコメントアウト中。Phase 5 の主対象外 |
| `component/_table.scss` | vendor ではない。style.scss でコメントアウト中。Phase 5 の主対象外 |
| `component/_toggle.scss` | vendor ではない。style.scss でコメントアウト中。Phase 5 の主対象外 |
| `utility/_blockquote.scss` | vendor ではない。style.scss でコメントアウト中。Phase 5 の主対象外 |

---

## 3. Vendor ごとの現状と依存関係

### 3.1 FontAwesome 5.14.0

**ファイル**: `component/fontawesome-free-5.14.0/_index.scss`
**状態**: active
**`@use "../../global"` の有無**: なし
**SCSS 変数参照**: なし
**SCSS 関数参照**: なし
**CSS 変数参照**: なし

**内容**: `@font-face` 宣言 2 つ（regular 400, solid 900）のみ。全 feature module（`_core`, `_icons`, `_solid` 等）はコメントアウト済み。ファイル冒頭コメントに「フォームの疑似要素に限定」と記載。ローカル変数 `$fa-font-display`, `$fa-font-path` のみ使用（project 依存なし）。

**Phase 5 での扱い**: **変換不要**。SCSS 変数への依存がゼロなので、SCSS 変数置換の作業対象にならない。出力が `@font-face` のみでクラス定義がないため、`@layer components` 囲みも不要。

---

### 3.2 Micromodal

**ファイル**: `component/micromodal/_micromodal.scss`
**状態**: active
**`@use "../../global/" as g;`**: あり（L2）

**SCSS 変数・関数の参照一覧**:

| 行 | 参照 | 用途 | 置換方針 |
|---|---|---|---|
| L37 | `g.get_zindex(micromodal)` | z-index | 維持（SCSS 関数維持方針） |
| L50 | `g.rem(24)` | padding | 維持（SCSS 関数維持方針） |
| L52 | `g.$border-radius` | border-radius | `6px` ハードコード（Phase 4 確定方針） |
| L54 | `g.$sm` | メディアクエリ | 維持（ブレークポイント維持方針） |
| L61 | `g.rem(50)` | width | 維持（SCSS 関数維持方針） |
| L62 | `g.rem(8)` | margin | 維持（SCSS 関数維持方針） |
| L63 | `g.$transition-base` | transition | `all 0.2s ease-in-out` ハードコード（Phase 4 確定方針） |

**Phase 5 で置換が必要な箇所**: 2 箇所（`g.$border-radius`, `g.$transition-base`）
**維持する箇所**: 5 箇所（`g.rem()` × 3, `g.get_zindex()` × 1, `g.$sm` × 1）
**`@use "../../global/" as g;`**: 維持する必要あり。`g.rem()`, `g.get_zindex()`, `g.$sm` が残るため。

---

### 3.3 Swiper 8.3.2

**ファイル**: `component/swiper/_swiper-bundle.scss`
**状態**: active
**`@use "../../global/" as g;`**: あり（L13）

**SCSS 変数・関数の参照一覧**:

| 行 | 参照 | 用途 | 置換方針 |
|---|---|---|---|
| L206 | `g.$sm` | `:root` 内メディアクエリ（`--swiper-navigation-size` のレスポンシブ値） | 維持（ブレークポイント維持方針） |

**その他**: L22-24 で `:root { --swiper-theme-color }` を独自定義。L710 で `%swiper-btn-circle` placeholder 定義、L728 で `@extend`（同一ファイル内完結）。

**Phase 5 で置換が必要な箇所**: 0 箇所
**維持する箇所**: 1 箇所（`g.$sm`）
**`@use "../../global/" as g;`**: 維持する必要あり。`g.$sm` が残るため。

---

### 3.4 Scroll Hint

**ファイル**: `component/scroll-hint/index.scss`
**状態**: active
**`@use "../../global/" as g;`**: あり（L2）

**SCSS 変数・関数の参照一覧**:

| 行 | 参照 | 用途 | 置換方針 |
|---|---|---|---|
| L45 | `g.$border-radius` | border-radius | `6px` ハードコード（Phase 4 確定方針） |
| L59 | `g.$md` | メディアクエリ | 維持（ブレークポイント維持方針） |

**Phase 5 で置換が必要な箇所**: 1 箇所（`g.$border-radius`）
**維持する箇所**: 1 箇所（`g.$md`）
**`@use "../../global/" as g;`**: 維持する必要あり。`g.$md` が残るため。

---

### 3.5 Ultimate Member

**ファイル**: `component/ultimate-member/_ultimate-member.scss`
**状態**: inactive（style.scss に読み込み行が存在しない）
**`@use "../../global" as g;`**: あり（L2）

**SCSS 変数・関数の参照一覧**:

| 行 | 参照 | 用途 | 置換方針 |
|---|---|---|---|
| L17 | `g.$clrg600` | color | `var(--clrg600)` に置換 |
| L23 | `g.$clrg500` | background | `var(--clrg500)` に置換 |
| L28 | `g.$clrg600` | background | `var(--clrg600)` に置換 |

**Phase 5 での扱い**: style.scss から読み込まれていないため、このファイルの SCSS 変数は CSS 出力に影響しない。ビルドグラフ外のためビルドテストでは正しさを保証できない。Phase 5 vendor 本体ステップの対象外とし、§10.1 に置換内容を記録して将来 active 化時に対応する。

---

### 3.6 WP Instagram Feed

**ファイル**: `component/wp-instagram-feed/_wp-instagram-feed.scss`
**状態**: inactive（style.scss L35 でコメントアウト中）
**`@use "../../global" as g;`**: あり（L3）
**`@use "sass:color";`**: あり（L2）

**SCSS 変数・関数の参照一覧**（アクティブコードのみ）:

| 行 | 参照 | 用途 | 置換方針 |
|---|---|---|---|
| L352 | `g.$border-radius` | border-radius | `6px` ハードコード |
| L411 | `g.$border-radius` | border-radius | `6px` ハードコード |
| L514 | `g.$sm` | メディアクエリ | 維持 |
| L532 | `g.rem(20)` | margin-top | 維持 |
| L535 | `g.rem(14)` | padding | 維持 |
| L536 | `g.$border-radius` | border-radius | `6px` ハードコード |
| L537 | `g.$clr1` | background | `var(--clr1)` に置換 |
| L540 | `g.rem(15)` | font-size | 維持 |
| L547 | `@include g.hover` | hover mixin | 維持 |
| L549 | `#47a73c` | background（`color.scale` → ハードコード済み） | 対応済み |
| L553 | `g.$md` | メディアクエリ | 維持 |
| L554-556 | `g.rem()` × 3 | padding, font-size | 維持 |

**Phase 5 での扱い**: style.scss でコメントアウト中のため CSS 出力に影響しない。ビルドグラフ外のためビルドテストでは正しさを保証できない。Phase 5 vendor 本体ステップの対象外とし、§10.1 に置換内容を記録して将来 active 化時に対応する。

---

## 4. foundation 変数ファイルの扱い

### 4.1 `foundation/_variables-color.scss`

**現在の参照元**:
- `global/_index.scss` L7: `@forward "../foundation/_variables-color";` → 全ファイルが `g.$clr1` 等でアクセス可能

**Phase 5 完了後に残る参照**:

(A) **build graph 内**（style.scss から直接・間接に読み込まれるファイル）:
- Active vendor ファイル: Micromodal, Swiper, Scroll Hint は `g.$clr*` / `g.$clrg*` を**使用していない**（実コード確認済み）
- Non-vendor ファイル（Phase 4 対象）: アクティブコードでの `g.$clr*` 参照はゼロ（コメント内の `/* was ... */` 記述のみ残存。インターポレーション `#{}` は使用していない — grep 確認済み）

(B) **build graph 外**（style.scss でコメントアウトまたは未記載のファイル）:
- `foundation/_reboot.scss`: `g.$body-bg`(L91), `g.$body-color`(L92), `g.$link-color`(L196), `g.$link-hover-color`(L200) — 4 箇所
- `utility/_tables.scss`: `g.$body-bg`(L13,L81,L170), `g.$black`(L15,L16) — 5 箇所
- `component/_table.scss`: `g.$black`(L78,L83) — 2 箇所
- `component/_toggle.scss`: `g.$clr1`(L27) — 1 箇所
- `component/ultimate-member/_ultimate-member.scss`: `g.$clrg500`(L23), `g.$clrg600`(L17,L28) — 3 箇所
- `component/wp-instagram-feed/_wp-instagram-feed.scss`: `g.$clr1`(L537) — 1 箇所

**結論**: build graph 内のファイルに `_variables-color.scss` 由来の変数へのアクティブコード参照はゼロ。`@forward` を削除してもビルドは通る。ただし build graph 外の 6 ファイルにアクティブコード参照が計 16 箇所残っており、これらのファイルを将来 active 化する際には先に SCSS 変数を CSS 変数 / ハードコード値に置換する必要がある。

**Phase 5 での扱い**: 削除判定は **build graph 内に限定**して行う（Option A）。手順:
1. Step 5-4 で build graph 内の全 `.scss` ファイルにアクティブコード参照がないことを grep で確認
2. `global/_index.scss` の `@forward` をコメントアウトし、`npm run build` で最終確認
3. ビルド成功なら `_variables-color.scss` を削除
4. build graph 外に残る 6 ファイル × 16 箇所の参照は §10 に残件として記録し、各ファイルの active 化時に対応する

### 4.2 `foundation/_variables.scss`

**現在の参照元**（`@use` している global ファイル）:
- `global/_breakpoints.scss` L2: `v.$grid-breakpoints` を多数の関数・mixin のデフォルト引数で使用
- `global/_media-queries.scss` L2: `v.$grid-breakpoints` を `$sm`, `$md` 等の定義で使用
- `global/_transition.scss` L1: `v.$transition-base` を `$transition` の定義で使用
- `global/_gutter.scss` L1: `@use` しているが `v.$` を一度も使用していない（未使用 import → §10.3 に残件記録）

**Phase 5 で置換される変数**（active vendor のみ）:
- `$border-radius`（= `6px`）: Micromodal L52, Scroll Hint L45 の 2 箇所 → `6px` ハードコード化
- `$transition-base`（= `all 0.2s ease-in-out`）: Micromodal L63 の 1 箇所 → ハードコード化
- ※ inactive vendor（WP Instagram Feed）にも `g.$border-radius` 参照が 3 箇所あるが、Phase 5 実行ステップの対象外（§10.1 に記録）

**Phase 5 完了後もまだ必要な変数**:
- `$grid-breakpoints` map + 個別 BP 変数: `global/_breakpoints.scss` と `global/_media-queries.scss` が使用。vendor/non-vendor の全ファイルが `g.$sm`, `g.$md` 経由で間接依存。**削除不可**
- `$grid-columns`（= 12）: Phase 4 で直接参照は解消済みだが、`$grid-breakpoints` map の一部として `_breakpoints.scss` が参照する構造の中にある
- `$container-max-*`: Phase 4 でハードコード化済み。直接参照するアクティブコードなし
- `$space_values`, `$space_values_with_clamp`: `global/_calc.scss` 経由で `g.rem()` 等が使用
- `$layout_zindex` + `get_zindex()`: Micromodal が `g.get_zindex(micromodal)` で使用中
- `$font-family-*`: `global/_font.scss` 経由で使用
- `$font-size-base`, `$line-height-base`: `global/_calc.scss` 内の `rem()` 関数の基準値

**結論**: `_variables.scss` は Phase 5 完了後も削除できない。`$grid-breakpoints`, `$space_values`, `$layout_zindex`, `get_zindex()`, `$font-*` 等がプロジェクト全体のインフラとして使われ続ける。

**Phase 5 での扱い**: 削除しない。`$border-radius`, `$transition-base` の参照を Phase 5 対象 vendor ファイルから切っても、上記のインフラ変数が残るためファイル自体は維持。

---

## 5. 実行ステップ

### Step 5-0: 移行前 CSS バックアップ

**目標**: 移行前の CSS 出力を保存し、移行後との比較基準にする
**実施内容**:
1. `npm run build` を実行してビルドが通ることを確認
2. `css/style.css` を `css/style.css.bak` としてコピー

**確認方法**: `css/style.css.bak` が存在すること
**完了条件**: バックアップファイルが作成され、ビルドが成功している

### Step 5-1: Scroll Hint の SCSS 変数置換

**目標**: `component/scroll-hint/index.scss` から `g.$border-radius` への参照を除去する
**実施内容**:
- L45: `border-radius: g.$border-radius;` → `border-radius: 6px;`

**確認方法**: ビルド成功。`css/style.css` で `.scroll-hint-icon` の `border-radius` が `6px` であること
**完了条件**: `component/scroll-hint/index.scss` 内に `g.$border-radius` の参照がない

### Step 5-2: Micromodal の SCSS 変数置換

**目標**: `component/micromodal/_micromodal.scss` から `g.$border-radius`, `g.$transition-base` への参照を除去する
**実施内容**:
- L52: `border-radius: g.$border-radius;` → `border-radius: 6px;`
- L63: `transition: g.$transition-base;` → `transition: all 0.2s ease-in-out;`

**確認方法**: ビルド成功。`css/style.css` で `.c-micromodal__container` の `border-radius` が `6px`、`.c-micromodal__close` の `transition` が `all 0.2s ease-in-out` であること
**完了条件**: `component/micromodal/_micromodal.scss` 内に `g.$border-radius` と `g.$transition-base` の参照がない

### Step 5-3: Active vendor ファイルの @layer components 囲み

**目標**: active vendor 4 ファイルを `@layer components` で囲む
**実施内容**:
- **FontAwesome**: `@font-face` は `@layer` の外に置く。コメントアウト済み module は無視。結果として `@layer components` で囲むべきクラス出力がないため、**@layer 囲みは不要**
- **Micromodal**: `@use` 文の後に `@layer components {` を追加、ファイル末尾に `}` を追加。`@keyframes mmfadeIn`, `@keyframes mmfadeOut` は `@layer` の外に出す（Phase 4 確定方針）
- **Swiper**: `@use` 文の後に `@layer components {` を追加、ファイル末尾に `}` を追加。`:root { --swiper-* }` は `@layer` の外に出す。`@font-face` は `@layer` の外に出す。`%swiper-btn-circle` placeholder は `@extend` と同じ `@layer` 内に置く
- **Scroll Hint**: `@use` 文の後に `@layer components {` を追加、ファイル末尾に `}` を追加。`@keyframes scroll-hint-appear` は `@layer` の外に出す

**確認方法**: ビルド成功。`css/style.css` で vendor クラス（`.c-micromodal__overlay`, `.swiper`, `.scroll-hint-icon`）が `@layer components` 内に出力されていること
**完了条件**: active vendor 3 ファイル（Micromodal, Swiper, Scroll Hint）のクラス出力が `@layer components` 内に含まれている。FontAwesome は `@font-face` のみで `@layer` 囲み不要

### Step 5-4: foundation/_variables-color.scss の削除

**目標**: build graph 内に `_variables-color.scss` 由来の変数参照が残っていないことを確認し、ファイルを削除する

**削除判定の対象範囲**: **build graph 内のファイルのみ**（style.scss から直接・間接に読み込まれるファイル）。build graph 外のファイルに残る参照は §10 に残件記録し、Phase 5 では対応しない。

**実施内容**:

1. **build graph 内の確認**: `src/scss/` 配下の全 `.scss` ファイルで `g.$clr`, `g.$clrg`, `g.$black`, `g.$gray`, `g.$grd`, `g.$link-`, `g.$body-` を grep する
2. **検出結果を build graph 内外に仕分ける**: style.scss の `@use` 行（コメントアウトされていない行）から辿れるファイルを build graph 内とする
3. **build graph 内の結果を分類する**:
   - (a) SCSS コメント内（`//` or `/* */`）でインターポレーション `#{}` を使っていない → 安全
   - (b) SCSS コメント内だがインターポレーション `#{g.$clr1}` 等を使っている → ビルドエラーになるため置換が必要
   - (c) アクティブコードで参照 → 削除不可
4. **build graph 内で (b)(c) がゼロであることを確認する**
   - 2026-03-17 時点の実コード確認結果: build graph 内の (b) = 0 箇所、(c) = 0 箇所（§4.1 参照）
   - 実装時に Step 5-1〜5-3 の変更後に改めて grep し、上記が依然成り立つことを確認すること
5. **`global/_index.scss` L7 の `@forward "../foundation/_variables-color";` をコメントアウト**し、`npm run build` を実行
6. **ビルド成功を確認**したら `foundation/_variables-color.scss` を削除
7. **build graph 外の残存参照を §10 の記録と照合**し、漏れがないことを確認

**build graph 外で参照が残るファイル（2026-03-17 時点で確認済み、§10.1〜10.2 に記録）**:
- `foundation/_reboot.scss`: `g.$body-bg`(L91), `g.$body-color`(L92), `g.$link-color`(L196), `g.$link-hover-color`(L200)
- `utility/_tables.scss`: `g.$body-bg`(L13,L81,L170), `g.$black`(L15,L16)
- `component/_table.scss`: `g.$black`(L78,L83)
- `component/_toggle.scss`: `g.$clr1`(L27)
- `component/ultimate-member/_ultimate-member.scss`: `g.$clrg500`(L23), `g.$clrg600`(L17,L28)
- `component/wp-instagram-feed/_wp-instagram-feed.scss`: `g.$clr1`(L537)

これらのファイルは style.scss でコメントアウトまたは未記載であり、`@forward` 削除後もビルドに影響しない。ただし、いずれかを将来 active 化する場合は、先に SCSS 変数を CSS 変数 / ハードコード値に置換する必要がある。

**確認方法**: build graph 内の grep 結果で (b)(c) = 0 + `npm run build` 成功
**完了条件**: `foundation/_variables-color.scss` が削除され、`global/_index.scss` から `@forward` が除去され、ビルドエラーなし。build graph 外の残存参照 6 ファイル 16 箇所が §10 に記録されていること

---

## 6. 検証手順

### 6.1 代表セレクタの出力照合

移行前の `css/style.css.bak` と移行後の `css/style.css` を比較する。

| 代表セレクタ | 確認する宣言 | 期待 |
|---|---|---|
| `.c-micromodal__container` | `border-radius` | `6px` |
| `.c-micromodal__container` | `padding` | `1.5rem`（`g.rem(24)` の出力） |
| `.c-micromodal__close` | `transition` | `all 0.2s ease-in-out` |
| `.c-micromodal__overlay` | `z-index` | `2000`（`get_zindex(micromodal)` の出力） |
| `.scroll-hint-icon` | `border-radius` | `6px` |
| `.scroll-hint-icon-wrap` | md 時 `display` | `none` |
| `.swiper` | `z-index` | `1` |
| `.swiper-button-prev, .swiper-button-next` | sm 時 `--swiper-navigation-size` | `28px` |

### 6.2 @layer components の出力確認

```bash
# @layer components ブロック数が Phase 4 完了時より増えていること
grep -c '@layer components' css/style.css

# vendor クラスが @layer 内にあること
grep -A 2 'c-micromodal' css/style.css | head -10
grep -A 2 'scroll-hint-icon' css/style.css | head -10
```

### 6.3 @font-face が @layer 外にあること

```bash
# FontAwesome と Swiper の @font-face が @layer の外にあること（目視確認）
grep -B 2 '@font-face' css/style.css | head -10
```

### 6.4 @keyframes が @layer 外にあること

```bash
grep -B 2 '@keyframes' css/style.css | head -10
```

### 6.5 Phase 4 完了時のセレクタが壊れていないこと

Phase 4 検証で確認した代表セレクタのうち、vendor と隣接する可能性があるものを再確認:

| セレクタ | 確認ポイント |
|---|---|
| `.c-button__clr1` | `background-color` が `var(--clr1)` |
| `.p-form__control--input` | `padding`, `border` |
| `.l-container` | `padding-top: 2rem` |

---

## 7. リスク

| # | リスク | 影響度 | 対策 |
|---|---|---|---|
| 1 | `@layer components` に vendor CSS を入れることで、Phase 4 コンポーネントとの優先順位が変わる | 低 | 同じ `@layer components` 内なので、ソース順序で優先される。style.scss の読み込み順（vendor → component → project）が CSS 出力順を決めるため、Phase 4 コンポーネントが後に来る。問題なし |
| 2 | `foundation/_variables-color.scss` 削除時にコメント内参照でエラー | 極低 | 2026-03-17 時点の grep で、build graph 内のコメントに色変数のインターポレーション `#{g.$clr*}` は 0 件。`/* was ... */` 形式のコメントはすべてプレーンテキスト。Step 5-4 でビルドテストし最終確認 |
| 3 | Swiper の `%swiper-btn-circle` が `@layer` 内で正しく `@extend` されるか | 極低 | 同一ファイル内の placeholder。Phase 4 で同パターン（`@extend` + `@layer`）が動作確認済み |
| 4 | inactive vendor ファイルが将来 active 化された際に SCSS 変数参照が残っている | 低 | §10.1 に置換内容を記録済み。active 化時に対応する |

---

## 8. 確定済み事項

Phase 4 で確定した方針を Phase 5 でもそのまま適用する。

| # | 事項 | 方針 |
|---|---|---|
| 1 | `g.$border-radius` | `6px` ハードコード |
| 2 | `g.$transition-base` / `g.$transition` | `all 0.2s ease-in-out` ハードコード |
| 3 | `@keyframes` の配置 | `@layer components` の外 |
| 4 | ブレークポイント変数 `g.$sm`, `g.$md` 等 | 維持（変換しない） |
| 5 | SCSS 関数 `g.rem()`, `g.get_zindex()` | 維持（変換しない） |
| 6 | `@include g.hover` | 維持（変換しない） |

---

## 9. 未確定事項

| # | 事項 | 確定に必要な確認 |
|---|---|---|
| — | （現時点で未確定事項なし） | `_variables-color.scss` の削除は build graph 内限定判定で実施する方針が確定（§4.1, Step 5-4）。build graph 外の残存参照は §10 に残件記録済み |

---

## 10. 主対象外の残件

以下は Phase 5 vendor 本体ステップの対象外だが、`_variables-color.scss` 削除後に影響を受けるファイルを含む。各ファイルを将来 active 化する際は、先に色変数参照を CSS 変数 / ハードコード値に置換すること。

### 10.1 build graph 外の `_variables-color.scss` 参照（6 ファイル 16 箇所）

Phase 5 Step 5-4 で `_variables-color.scss` を削除した後、以下のファイルは `@forward` が消えた状態になる。style.scss でコメントアウト / 未記載のためビルドに影響しないが、active 化すると `g.$clr*` 等が解決できずビルドエラーになる。

| ファイル | style.scss での状態 | `_variables-color.scss` 由来の参照（実コード確認済み） |
|---|---|---|
| `foundation/_reboot.scss` | L15 コメントアウト | L91: `g.$body-bg`, L92: `g.$body-color`, L196: `g.$link-color`, L200: `g.$link-hover-color` |
| `utility/_tables.scss` | L81 コメントアウト | L13: `g.$body-bg`, L15: `g.$black`, L16: `g.$black`, L81: `g.$body-bg`, L170: `g.$body-bg` |
| `component/_table.scss` | L49 コメントアウト | L78: `g.$black`, L83: `g.$black` |
| `component/_toggle.scss` | L50 コメントアウト | L27: `g.$clr1` |
| `component/ultimate-member/_ultimate-member.scss` | 読み込み行なし | L17: `g.$clrg600`, L23: `g.$clrg500`, L28: `g.$clrg600` |
| `component/wp-instagram-feed/_wp-instagram-feed.scss` | L35 コメントアウト | L537: `g.$clr1` |

> **注**: WP Instagram Feed にはさらに `g.$border-radius`(L352/411/536) の参照もあるが、これは `_variables.scss` 由来であり `_variables-color.scss` 削除とは無関係。

### 10.2 コメントアウト済み非 vendor ファイル（色変数以外の残件）

| ファイル | 状態 | 備考 |
|---|---|---|
| `component/_tab.scss` | style.scss でコメントアウト中 | 削除するか復活させるかはユーザー判断。Phase 5 vendor 計画とは独立。色変数参照なし |
| `utility/_blockquote.scss` | style.scss でコメントアウト中 | FontAwesome mixin 使用あり。色変数参照なし |

### 10.3 非 vendor 残件

| 項目 | 状態 | 備考 |
|---|---|---|
| `global/_gutter.scss` 未使用 import 削除 | 未着手 | L1 `@use "../foundation/_variables" as v;` が未使用（`v.$` 参照ゼロ）。vendor CSS とは無関係のため Phase 5 vendor 本体ステップから除外 |
| `global/_gutter.scss` mixin 本体の廃止判断 | 未着手 | マスター計画書 §5.5 で廃止候補。Phase 4 対象ファイルが使用中のため Phase 5 の範囲外 |
