# 移行進捗

## 現在: Phase 3（グリッド・レイアウト層の移行）— 完了 → Phase 4 へ進む

---

## Phase 1: 基盤構築

### 状態: 完了（2026-03-14）

### 実施した作業

- [x] A: 依存パッケージ更新 + ビルドパイプライン構築
  - tailwindcss v4.2.1, @tailwindcss/postcss v4.2.1 を devDependencies に追加
  - postcss-cli を 7.1.1 → 11.0.1 に更新（PostCSS 8 対応）
  - autoprefixer を 9.8.6 → 10.4.27 に更新（PostCSS 8 対応、dependencies → devDependencies に移動）
  - postcss.config.js に `@tailwindcss/postcss` プラグインを追加
  - package.json scripts を更新:
    - `css:concat`, `css:postcss`, `css:build` を新規追加
    - `watch:scss` → `watch:css` に置き換え（css:build を実行）
    - `watch:templates` を新規追加（PHP 変更検知 → css:build）
    - `build` を `prd:scss → prd:concat → prd:postcss → prd:webpack` の逐次実行に変更
    - `start` を `watch:css watch:templates watch:webpack watch:img watch:server` の並列実行に変更
- [x] B: tailwind.config.js 作成
  - screens, container, colors, spacing, zIndex, fontFamily, borderRadius, transitionDuration, extend.maxWidth, extend.padding (gutter), extend.gap を定義
  - content: `./**/*.php` のみ（JS は除外）
- [x] C: `_tailwind-base-layer.scss` 作成
  - C-1: `:root` CSS 変数（カテゴリ A: 22 変数）
  - C-2: gutter 系 CSS 変数（layout/_grid.scss L114-165 から移動）
  - C-3: リセット CSS 補足（destyle + _reboot の差分を @layer base で補足）
  - C-4: destyle / _reboot の廃止（style.scss からコメントアウト）
  - C-5: `.p-form` スコープ CSS 変数（@layer components で 13 変数定義）
- [x] D: SCSS 計算関数の維持確認
  - g.rem(), g.get_vw() 等の出力値が変わっていないことを確認（rem: 716件, vw: 31件）
- [x] `src/scss/tailwind-base.css` 作成（@import "tailwindcss" + @config）
- [x] `src/scss/style.scss` 更新（destyle/reboot コメントアウト、_tailwind-base-layer 追加）
- [x] `src/scss/layout/_grid.scss` から `:root` ブロック削除（グリッドクラス本体は維持）

### 検証結果

| # | 条件 | 結果 |
|---|---|---|
| 1 | ビルドが通る | PASS — CSS pipeline (prd:scss → prd:concat → prd:postcss) 成功 |
| 2 | Tailwind Preflight が出力に含まれている | PASS — `box-sizing: border-box` 9件 |
| 3 | `:root` カラー CSS 変数が出力されている | PASS — --clr1, --clrg500, --link-hover-color 確認 |
| 4 | gutter 系 CSS 変数が出力されている | PASS — --gutter 6件, --gutter-row 6件, @media breakpoint あり |
| 5 | リセット補足コードが出力されている | PASS — ::selection, a text-decoration, ul list-style, ::placeholder 確認 |
| 6 | `.p-form` スコープ CSS 変数が出力されている | PASS — --form-padding-x, --form-border-color, --form-select-indicator 確認 |
| 7 | 既存コンポーネント CSS が出力されている | PASS — .c-button 67件, .p-header 6件, .l-row 7件, .c-col--12 2件 |
| 8 | SCSS 計算関数の出力値が変わっていない | PASS — rem/vw 値が出力に含まれている |

### plan.md との差異

- `tailwind-base.css` の `@config` パス: plan.md では `../../tailwind.config.js` だが、実際は `../tailwind.config.js` に変更。理由: concat 後の CSS は `css/style.css` として PostCSS に処理されるため、`css/` ディレクトリからの相対パスで解決される。`css/` → `../tailwind.config.js` が正しいパス

### 注意事項

- 既存コンポーネント（.c-button, .p-header 等）は `@layer` の外側に出力される。これは Tailwind の layer 順序の外側であり、最高の優先度を持つため既存の見た目に影響しない
- Tailwind Preflight により `img { display: block }` が適用される。インラインで画像を使用している箇所があれば個別対応が必要（Phase 4/5 で確認）
- webpack は CSS パイプラインとは独立しており、Phase 1 の CSS 変更の影響を受けない

### 次に進める状態か

**Yes** — Phase 2（ユーティリティ層の移行）に着手可能

---

## Phase 2: ユーティリティ層の移行

### 状態: 完了（2026-03-15）

### 実施した作業

- [x] `tailwind.config.js` の `theme.extend.fontSize` に fz12〜fz36 のカスタムトークンを追加
- [x] PHP クラス名置換（margin 系）: `mt__N` → `mt-N`, `mt__N--BP` → `BP:mt-N`, バグ修正 `mt__075` → `mt-0.75`
- [x] PHP クラス名置換（display + visibility 系）: `display__none` → `hidden`, `hide__BP--up/down` → Tailwind バリアント
- [x] PHP クラス名置換（flex 系）: `jc__VALUE` → `justify-VALUE`, `ai__VALUE` → `items-VALUE`
- [x] PHP クラス名置換（font 系）: `fz__N` → `text-fzN 2xl:text-fzN+1`, `tac` → `text-center`, `fw__500` → `font-medium`, `tdu` → `underline`, `clr__N` → `text-clrN` 等
- [x] `youtube` クラス定義を `_responsive-embed.scss` から `@layer components` へ移動
- [x] `strong {}` ルールを `_font.scss` から `@layer base` へ移動
- [x] utility SCSS ファイル削除（_display, _flex, _margin, _padding, _visibility, _responsive-embed, _font）+ style.scss 更新
- [x] `_class-rename-log.md` を各 Step 完了時に更新

### 計画書

- `phase2-plan.md` — Phase 2 の詳細計画・変換表・検証計画
- `src/scss/_class-rename-log.md` — 旧→新クラス名の全変換表（Phase 2 セクションのステータスは全て ✅）

### 次に進める状態か

**Yes** — Phase 3（グリッド・レイアウト層の移行）に着手可能。計画は別途作成する。

---

## Phase 3: グリッド・レイアウト層の移行

### 状態: 完了（2026-03-15）

### 実施した作業

- [x] Step 3-1: `tailwind.config.js` の確認（gutter padding, gap, container — 全て定義済み）
- [x] Step 3-2: PHP クラス名置換（l-row 系）: `l-row--container` → `container mx-auto flex flex-wrap justify-center`, `l-row` → `flex flex-wrap justify-center`
  - l-row 系は行単位で判定。justify-start がある行では justify-center を省略、md:justify-between がある行では justify-center を維持
  - c-replace__flex-start / c-replace__flex-end を持つ行は justify-center を含めて置換（コンポーネントが sm+ で上書き）
- [x] Step 3-3: PHP クラス名置換（c-col 系）: `c-col--12` → `w-full`, `c-col__BP--N` → `BP:w-N/12`
- [x] Step 3-4: PHP クラス名置換（l-grid / c-grid 系）: `l-grid` → `grid gap-x-grid-gutter`, `c-grid--N` → `grid-cols-N`, `c-grid__BP--N` → `BP:grid-cols-N`
- [x] Step 3-5: PHP クラス名置換（c-gutter 系）: `c-gutter__row` → `px-gutter-row xl:px-0`, `c-gutter__post` → `md:px-gutter-row xl:px-0`, 方向指定 gutter も置換
- [x] Step 3-6: SCSS ファイル更新 + 削除
  - `style.scss` から `@use "layout/_grid"` と `@use "component/_gutter"` をコメントアウト
  - `component/_gutter.scss` を削除
  - `layout/_grid.scss` は `make-col` mixin のみ残存（`component/_style.scss` と `project/_style.scss` が `@use` で参照しているため、Phase 4 で解消予定）
- [x] Step 3-7: `global/_gutter.scss` が維持されていることを確認
- [x] `_class-rename-log.md` を更新（Phase 3 全変換表、全ステータス ✅）

### 計画書

- `phase3-plan.md` — Phase 3 の詳細計画・変換表・検証計画

### 検証結果

| # | 条件 | 結果 |
|---|---|---|
| 1 | ビルドが通る | PASS — CSS pipeline (prd:scss → prd:concat → prd:postcss) 成功 |
| 2 | 旧クラス (l-row, c-col, l-grid, c-grid, c-gutter) が PHP から消えている | PASS — grep 結果ゼロ（HTML コメント 2 件のみ残存） |
| 3 | 旧クラスが CSS 出力から消えている | PASS — css/style.css に旧クラス定義なし |
| 4 | 新 Tailwind utility が CSS に生成されている | PASS — flex-wrap, justify-center, w-full, grid-cols-*, gap, gutter-row 全て確認 |
| 5 | justify 系が正しく処理されている | PASS — justify-start 行に justify-center 混在なし、md:justify-between 行に justify-center 維持、c-replace__flex-* 行に justify-center 含む |
| 6 | global/_gutter.scss が維持されている | PASS — ファイル存在、global/_index.scss から参照あり |

### 代表テンプレート照合

| テンプレート | 確認ポイント | 結果 |
|---|---|---|
| front-page.php L41 | `container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0` | 一致 |
| front-page.php L42 | `w-full md:w-10/12 xl:w-9/12 flex flex-wrap justify-center` | 一致 |
| front-page.php L165 | `container mx-auto flex flex-wrap justify-start px-gutter-row xl:px-0` | 一致 |
| front-page.php L97 | `grid gap-x-grid-gutter grid-cols-1 sm:grid-cols-1 lg:grid-cols-3` | 一致 |
| footer.php L64 | `container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0 md:justify-between` | 一致 |
| page-form-contact.php L28 | `w-full md:w-10/12 lg:w-9/12 xl:w-8/12` | 一致 |
| page-recruit.php L16 | `w-full sm:w-4/12 md:w-4/12 lg:w-3/12 sm:pl-gutter-2 md:pl-gutter-3` | 一致 |

### 注意事項

- `layout/_grid.scss` は完全削除ではなく `make-col` mixin のみ残したスタブに変更。`component/_style.scss` と `project/_style.scss` が `@use "../layout/grid"` で参照しているため。Phase 4 のコンポーネント層移行で解消する
- `c-col` の `flex: 0 0 auto; min-height: 1px` は明示的に追加していない（未確定事項 #1 の選択肢 B）。`w-N/12` のみで旧挙動が再現されると判断

### 計画どおりに進まなかった点と対応

- PR レビューで `container` の二重定義が発生した。初回実装では `tailwind.config.js` の `container.screens` と `tailwind-base.css` 側の追加定義が競合し、生成後の `css/style.css` に `.container` が 2 回出力された
- 解決: `tailwind.config.js` から v3 compat の `container.screens` 定義を外し、`src/scss/tailwind-base.css` で `@source not inline("container")` + `@layer utilities` により `.container` を単一定義に変更した。これで計画書どおりの BP→max-width 対応を維持したまま、レビュー指摘を解消した
- 修正方針の判断でも一度ズレがあった。レビューを通すだけなら別名 utility に逃がす案もあり得たが、`phase3-plan.md` の `l-row--container -> container mx-auto flex flex-wrap justify-center` という置換方針とずれるため不採用とした
- 解決: 実装判断を計画書ベースに戻し、「`container` の名前は維持したまま、生成結果を単一定義にする」ことを修正条件として再整理した。Phase 3 はレビュー通過より計画準拠を優先する方針で完了させた
- テンプレート移行中に `tmp/content/salon-detail.php` で HTML 崩れも発生した。見出しの閉じタグ構造が崩れ、さらに外部リンクの `rel` 属性にスマートクォートが混入していた
- 解決: 見出し部分を正しいタグ階層に修正し、`rel="noopener noreferrer"` を ASCII クォートへ修正した。最終 PR では container 問題とあわせてこのテンプレート不整合も解消済み
- 進捗管理ファイルの更新も実装完了より後ろ倒しになった。Phase 3 本体の置換は進んでいたが、PR 修正が落ち着くまで `CLAUDE.md` / `progress.md` / `_class-rename-log.md` の完了記録が揃っていなかった
- 解決: 最終修正コミットで Phase 3 完了状態、検証結果、変換表をまとめて反映し、作業内容とドキュメントの状態を一致させた

### 次に進める状態か

**Yes** — Phase 4（コンポーネント・プロジェクト層の移行）に着手可能

---

## Phase 4: コンポーネント・プロジェクト層の移行

### 状態: 完了（2026-03-16）

### 実施した作業

- [x] Step 4-0: `_tailwind-base-layer.scss` の `:root` に `--clr1-hover: #3f9536` と `--img-hover-opacity: 0.9` を追加
- [x] Step 4-1: `project/_style.scss` の `:root` から重複 22 色変数 + `--clr1-hover` + `--img-hover-opacity` を削除。`--bdrs`, `--bdrs-lg` のみ残存。`@use "../layout/grid"` 未使用 import を削除
- [x] Step 4-2: `project/_style.scss` の SCSS 変数置換（`g.$clrg600` → `var(--clrg600)`, `rgba(g.$black, 0.8)` → `rgba(#222, 0.8)`）
- [x] Step 4-3: `component/_style.scss` の `grid.make-col` 依存解消（`percentage(math.div(7, 12))` / `percentage(math.div(6, 12))` に直接記述）。`@use "../layout/grid"` を削除。`g.$container-max-*` → ハードコード化
- [x] Step 4-4: `component/_style.scss` の残り SCSS 変数置換（`g.$clr3` → `var(--clr3)`, `g.$transition-base` → `all 0.2s ease-in-out`）
- [x] Step 4-5: `project/_form.scss` の `f.$input-*` / `f.$custom-select-*` → `var(--form-*)` CSS 変数に切り替え。`@use "../foundation/variables-form"` を削除
- [x] Step 4-6: `component/_button.scss` の SCSS 変数置換（`g.$clr1` / `g.$clr2` → `var(--clr1)` / `var(--clr2)`, `g.$border-radius` → `6px`）。mixin 定義側デフォルト引数と呼び出し側引数の両方を置換
- [x] Step 4-7: 残り全ファイルの SCSS 変数置換（`g.$transition-base` / `g.$transition` → `all 0.2s ease-in-out`, `g.$border-radius` → `6px`, 色変数 → CSS 変数, `rgba()` 内 → ハードコード化）
- [x] Step 4-8: 全 Phase 4 対象ファイル（25 ファイル）を `@layer components { ... }` で囲み。`:root` は `@layer` 外、`@keyframes` は `@layer` 外に配置
- [x] Step 4-9: `layout/_header.scss` の `:root { --header-height }` を `_tailwind-base-layer.scss` の `@layer base` に移動
- [x] Step 4-10: `layout/_grid.scss` スタブを削除
- [x] Step 4-11: `foundation/_variables-form.scss` を削除

### 計画書

- `phase4-plan.md` — Phase 4 の詳細計画

### 検証結果

| # | 条件 | 結果 |
|---|---|---|
| 1 | `npm run css:build` 成功 | PASS |
| 2 | `npm run build` 成功 | PASS |
| 3 | `@layer components` が CSS 出力に含まれる | PASS — 26 ブロック |
| 4 | `.c-button` の `border-radius` が `6px` | PASS |
| 5 | `.c-replace__content--left` の sm 時 `width` が `58.3333333333%`、lg 時 `50%` | PASS |
| 6 | `.p-form__control--input` の `padding`, `border`, `border-radius` が `var(--form-*)` | PASS |
| 7 | `--header-height` が `_tailwind-base-layer.scss` のみで定義 | PASS |
| 8 | `--clr1-hover`, `--img-hover-opacity` が `:root` に出力 | PASS |
| 9 | `.l-container` の `padding-top` が `2rem` | PASS |
| 10 | `layout/_grid.scss` 削除後にビルドエラーなし | PASS |
| 11 | `foundation/_variables-form.scss` 削除後にビルドエラーなし | PASS |

### 代表セレクタ照合

| セレクタ | 確認ポイント | 結果 |
|---|---|---|
| `.c-button` | `border-radius: 6px`, `padding` に `rem` 値 | 一致 |
| `.c-button__clr1` | `background-color` | `var(--clr1)` で出力 |
| `.c-replace__content--left` | sm: `width: 58.3333333333%`, lg: `width: 50%` | 一致 |
| `.p-form__control--input` | `padding: var(--form-padding-y) var(--form-padding-x)`, `border`, `border-radius` | 一致 |
| `.l-container` | `padding-top: 2rem`, `padding-bottom: 2rem` | 一致 |
| `--header-height` | `:root` に `3.125rem` / `3.5rem` / `5rem` | 一致 |

### 計画どおりに進まなかった点と対応

- `--clr1-hover` の値: 計画書では `#3c9536` と記載していたが、実際の `color.scale(#4FBA43, $lightness: -20%)` 出力は `#3f9536`。移行前 CSS の値を確認し `#3f9536` を採用した

### Phase 5 へ送る残件

- ベンダー CSS（FontAwesome, Swiper, Micromodal, scroll-hint, Ultimate Member, WP Instagram Feed）の SCSS 変数→ CSS 変数置換
- `foundation/_variables-color.scss` の削除（ベンダー CSS が `g.$clr1` 等を参照中）
- `foundation/_variables.scss` の削除（`get_zindex()` 等の参照が残る）
- `global/_gutter.scss` の廃止判断（マスター計画書 §5.5）
- `style.scss` でコメントアウト済みファイルの整理（`_tab.scss`, `_table.scss`, `_toggle.scss`, `_blockquote.scss`）
- `@layer components` 内スタイルとベンダー CSS（`@layer` 外）の優先順位問題の確認

### 次に進める状態か

**Yes** — Phase 5（ベンダー CSS の整理）に着手可能

---

## Phase 5: ベンダー CSS の整理

### 状態: 完了（2026-03-17）

### 実施した作業

- [x] Step 5-0: 移行前 CSS バックアップ更新（`css/style.css.bak`）
- [x] Step 5-1: Scroll Hint の SCSS 変数置換（`g.$border-radius` → `6px`）
- [x] Step 5-2: Micromodal の SCSS 変数置換（`g.$border-radius` → `6px`, `g.$transition-base` → `all 0.2s ease-in-out`）
- [x] Step 5-3: Active vendor ファイルの `@layer components` 囲み
  - Micromodal: `@layer components` で囲み。`@keyframes mmfadeIn/mmfadeOut` は `@layer` 外に配置
  - Swiper: `@layer components` で囲み。`:root` ブロック 3 箇所と `@font-face`、`@keyframes swiper-preloader-spin` は `@layer` 外に配置。`%swiper-btn-circle` と `@extend` は同一 `@layer` 内
  - Scroll Hint: `@layer components` で囲み。`@keyframes scroll-hint-appear` は `@layer` 外に配置
  - FontAwesome: `@font-face` のみでクラス出力なし → `@layer` 囲み不要（変更なし）
- [x] Step 5-4: `foundation/_variables-color.scss` の削除
  - `global/_index.scss` の `@forward` をコメントアウト → ビルド成功を確認 → ファイル削除
  - build graph 内に `_variables-color.scss` 由来のアクティブコード参照ゼロ（コメント内のプレーンテキスト記述のみ）

### 計画書

- `phase5-plan.md` — Phase 5 の詳細計画

### 検証結果

| # | 条件 | 結果 |
|---|---|---|
| 1 | `npm run build` 成功 | PASS |
| 2 | `.c-micromodal__container` の `border-radius` が `6px` | PASS |
| 3 | `.c-micromodal__container` の `padding` が `1.5rem`（`g.rem(24)` 出力） | PASS |
| 4 | `.c-micromodal__close` の `transition` が `all .2s ease-in-out` | PASS |
| 5 | `.c-micromodal__overlay` の `z-index` が `2000` | PASS |
| 6 | `.scroll-hint-icon` の `border-radius` が `6px` | PASS |
| 7 | `.scroll-hint-icon-wrap` の md 時 `display: none` | PASS |
| 8 | `--swiper-navigation-size` sm 時 `28px` | PASS |
| 9 | `@layer components` ブロック数: 34（Phase 4 の 26 から増加） | PASS |
| 10 | `@font-face` が `@layer` 外 | PASS |
| 11 | `@keyframes` が `@layer` 外 | PASS |
| 12 | Phase 4 セレクタ維持（`.c-button__clr1`, `.p-form__control--input`, `.l-container`） | PASS |
| 13 | `_variables-color.scss` 削除後のビルド成功 | PASS |

### build graph 外の残件（Phase 5 では対応しない）

`_variables-color.scss` 削除後、以下の build graph 外ファイルに SCSS 変数参照が残存。style.scss でコメントアウト / 未記載のためビルドに影響しないが、active 化時には先に置換が必要。

| ファイル | 参照数 | 参照内容 |
|---|---|---|
| `foundation/_reboot.scss` | 4 | `g.$body-bg`, `g.$body-color`, `g.$link-color`, `g.$link-hover-color` |
| `utility/_tables.scss` | 5 | `g.$body-bg` × 3, `g.$black` × 2 |
| `component/_table.scss` | 2 | `g.$black` × 2 |
| `component/_toggle.scss` | 1 | `g.$clr1` |
| `component/ultimate-member/_ultimate-member.scss` | 3 | `g.$clrg500`, `g.$clrg600` × 2 |
| `component/wp-instagram-feed/_wp-instagram-feed.scss` | 1 | `g.$clr1` |

その他の残件:
- `global/_gutter.scss` L1 の未使用 `@use` は Phase 5 vendor 本体ステップから除外（計画書 §10.3）
- `_tab.scss`, `_table.scss`, `_toggle.scss`, `_blockquote.scss` は Phase 5 の主対象外（計画書 §2.3）

### 次に進める状態か

Phase 5 完了。次の Phase は計画書に未定義。

---

## 2026-03-17 SCSS ファイル整理

### 状態: 実施済み

### 実施内容

- `src/scss/_archive/` 配下に用途別ディレクトリを追加
  - `foundation/`
  - `utility/`
  - `mixins/`
- ルートの移行補助資料を `src/scss/_archive/` へ移動
  - `_class-rename-log.md`
  - `_reset-diff-inventory.md`
  - `_variable-inventory.md`
  - `tailwind-migration-plan.md`
- Phase 1 完了済みの旧 foundation 資産を `src/scss/_archive/foundation/` へ移動
  - `_destyle.css`
  - `_reboot.scss`
- 未使用 utility 9 ファイルを `src/scss/_archive/utility/` へ移動
- 未使用 mixins 3 ファイルを `src/scss/_archive/mixins/` へ移動
- `component/` 内で現在未使用の非 vendor 3 ファイルを `src/scss/component/_archive/` へ移動
  - `_tab.scss`
  - `_table.scss`
  - `_toggle.scss`

### 退避しなかったもの

- inactive vendor は元位置に維持
  - `component/ultimate-member/`
  - `component/wp-instagram-feed/`
- 理由: Phase 5 完了時点で build graph 外の vendor 残件として記録されており、将来 active 化時の対応対象だから

### 補足

- `src/scss/style.scss` 内のコメントは archive 先が分かる表記に更新

## 2026-03-17 SCSS 現況整理

- `src/scss/foundation/_variables.scss` から未使用の `$space_values_with_clamp` を削除
- `src/scss/readme.md` を全面更新し、Tailwind との共存、build graph、現役ディレクトリ、archive、vendor の現配置まで 1 ファイルで追える現況ドキュメントに差し替え
