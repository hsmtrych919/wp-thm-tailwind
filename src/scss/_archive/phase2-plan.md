# Phase 2 作業計画（確定版）

## 1. 現状認識

### Phase 1 完了状態

- Tailwind v4.2.1 + `@tailwindcss/postcss` がビルドパイプラインに組み込み済み
- `tailwind.config.js` に `theme.spacing` が現行 `$space_values` と同じスケールで定義済み（`mt-4` = 32px 維持）
- `theme.screens` にカスタムブレークポイント定義済み（sm:576, md:811, lg:1025, xl:1280, 2xl:1366）
- `theme.colors` にブランドカラー・グレースケールを CSS 変数参照で定義済み
- `:root` CSS 変数、gutter 系変数、リセット補足、`.p-form` CSS 変数は `@layer base` / `@layer components` で出力済み
- 既存コンポーネント CSS（`.c-button`, `.p-header`, `.l-row` 等）は `@layer` 外に出力され、最高優先度を維持

### Phase 2 の前提として確定していること

- **spacing スケール**: `mt-4` = 32px（Tailwind デフォルトの 16px ではない）。`tailwind.config.js` の `theme.spacing` に全値定義済み
- **Phase 境界**: Phase 2 = utility 層のみ。l-row, l-grid, c-col, c-gutter 等レイアウト層は Phase 3。コンポーネント層・ベンダー CSS は Phase 4/5
- **検証方針**: 件数比較ではなく、クラス単位・出力挙動単位で確認
- **クラス名変更ログ**: `_class-rename-log.md` に Phase 2 の全変換表を転記済み（ステータスは ⏳）。各 Step の完了時にステータスを ✅ に更新する

---

## 2. Phase 2 の確定スコープ

### 確実に対象（5 ファイル）

| ファイル | 根拠 | 概要 |
|---|---|---|
| `utility/_display.scss` | PHP で `display__none`, `display__block`, `display__inline-block` + レスポンシブが使用されている | 3 種 × 6 BP = 18 クラス（実コメントアウト分除外で有効 3 種） |
| `utility/_flex.scss` | PHP で `jc__start`, `jc__between`, `jc__between--md`, `ai__center` が使用されている | jc 5 種 + ai 5 種 + as 6 種 + order 6 個 × 6 BP。ただし **as/order は PHP 未使用** |
| `utility/_margin.scss` | PHP で `mt__1` 〜 `mt__10` + レスポンシブが多数使用されている | mt 0 固定 3 個 + mt 0 レスポンシブ 3 個 + `mt__N--BP` ループ（25 値 × 6 BP）+ mb__0 + mx__auto |
| `utility/_visibility.scss` | PHP で `hide__sm--down`, `hide__sm--up`, `hide__lg--up`, `hide__xs--down` が使用されている | hide up/down × 6 BP + invisible + print 系 4 個 |
| `utility/_font.scss` | PHP で `tac__sm`, `tac__lg`, `fz__14`, `fz__16`, `fw__500`, `clr__1` 〜 `clr__gray`, `tdu` が使用されている | text-align, font-weight, text-decoration, color, font-size の全カテゴリを Tailwind 記法へ置換。`strong` 要素ルールは SCSS 削除時に必要なら `@layer base` へ移動 |

### 条件付き対象（2 ファイル）

| ファイル | 条件 | 理由 |
|---|---|---|
| `utility/_padding.scss` | SCSS 削除は可能だが、PHP 側に影響なし | 有効クラスは `pt__0`, `pb__0`, `px__0`, `py__0` の 4 つのみ。**PHP で一切使用されていない**。他は全てコメントアウト済み。削除しても PHP 置換不要だが、削除タイミングは他と合わせるか判断が必要 |
| `utility/_responsive-embed.scss` | `embed__responsive` は PHP 未使用。`youtube` クラスは CSS 側で使用 | `youtube` クラスのスタイル定義（position:relative + padding-bottom:56.25% + overflow:hidden + 子 iframe の absolute 配置）は `_responsive-embed.scss` 内で完結している。SCSS 削除時にこの定義を `@layer components` へ移動すれば維持できる |

### 今回除外（Phase 2 から外すもの）

| 項目 | 理由 |
|---|---|
| `utility/_blockquote.scss` | `style.scss` でコメントアウト済み。Phase 2 の対象外 |
| `utility/_tables.scss` | `style.scss` でコメントアウト済み。Phase 2 の対象外 |
| `_font.scss` 内の `strong {}` 要素ルール（L181-194） | utility クラスではなく base ルール。`_font.scss` 削除時に `@layer base` へ移動する。独立した論点としては扱わない |
| l-row, l-grid, c-col, c-gutter 等 | Phase 3 |
| コンポーネント層（`.c-button` 等） | Phase 4 |
| ベンダー CSS | Phase 5 |

---

## 3. 実使用クラスの棚卸し

### 3.1 静的 class — ルートレベル PHP（本番テンプレート）

| ファミリー | 使用されているクラス | 主な使用ファイル |
|---|---|---|
| **margin** | `mt__2`, `mt__3`, `mt__4`, `mt__5`, `mt__6`, `mt__7`, `mt__8`, `mt__9`, `mt__1_5`, `mt__1_25`, `mt__2--md`, `mt__3--md`, `mt__4--md`, `mt__5--md`, `mt__8--lg`, `mt__10--md` | front-page.php, 404.php, page-form-contact-chk.php, sidebar-blogs.php |
| **display** | `display__none`, `display__none--sm`, `display__none--md`, `display__block--sm`, `display__block--xl` | footer.php, front-page.php |
| **flex** | `jc__start`, `jc__between--md`, `ai__center` | front-page.php, footer.php, header.php, page-form-contact-chk.php |
| **font/text** | `tac__sm`, `tac__lg`, `fz__14`, `fz__16`, `fw__500`, `tdu` | page-form-contact-chk.php |
| **color** | `clr__1`, `clr__2`, `clr__3`, `clr__4`, `clr__gray` | front-page.php, page-form-contact-chk.php |
| **visibility** | `hide__sm--down`, `hide__sm--up`, `hide__xs--down` | footer.php, front-page.php, page-form-contact-chk.php |

### 3.2 静的 class — tmp/ テンプレートパーツ（本番対象）

tmp/ は `get_template_part()` で読み込まれる本番テンプレートパーツ。置換対象に含める。

| ファミリー | 追加で使用されているクラス | 主な使用ファイル |
|---|---|---|
| **margin** | `mt__1`, `mt__075`(**※バグ**), `mt__1--md`, `mt__6--md`, `mt__8--md` | page-form-contact.php, page-company.php, page-recruit.php, salon-detail.php 等多数 |
| **display** | `display__inline-block`, `display__block--lg` | single-blog.php, page-company.php |
| **flex** | `jc__start--sm`, `jc__between` | archive-post.php, page-campaign.php |
| **font/text** | `tac`, `fz__14`, `fw__500` | page-form-contact.php, salon-detail.php, page-recruit.php |
| **color** | `clr__1`, `clr__3`, `clr__4`, `clr__gray`, `clr__alert`(**※バグ**) | 多数のテンプレート |
| **visibility** | `hide__sm--up`, `hide__lg--up`, `hide__xs--down` | page-campaign.php, tmp-nav-main.php, page-form-contact-thk.php, archive-none.php |

### 3.3 動的 class 構築パターン（3 箇所）

| ファイル | パターン | 安全性 |
|---|---|---|
| `footer.php` L199, L203 | `'display__none--sm ... ' . $class_name_reserve` | **安全**。`$class_name_reserve` は GTM トラッキング用クラス (`gtm-footer-reserve-{key}`)。utility 部分は静的文字列 |
| `tmp/page-campaign.php` L3 | `$notice_all = '<p class="fz__14 clr__gray mt__1">...'` | **安全**。PHP 変数に代入された静的文字列。置換スクリプトで検出可能 |
| `tmp/page-qa.php` L83 | `echo '<p class="...">' . wp_kses_post($qa['answer'])` | **安全**。utility クラス部分 (`clr__1`) は静的文字列 |

**結論: 動的 class 生成で utility クラス名が動的に組み立てられているケースはゼロ。** 全て静的文字列中に書かれており、一括置換スクリプトで対応可能。

### 3.4 発見されたバグ（Phase 2 で修正すべき）

| # | 内容 | 詳細 |
|---|---|---|
| 1 | **`mt__075`** — 存在しないクラス | `tmp/page-form-contact.php` L98, L110, L131, L133 で使用。CSS 出力は `.mt__0_75`（アンダースコア区切り）。PHP のクラス名にアンダースコアが欠落しており、CSS にマッチしていない。**現状死んだクラスで margin-top が効いていない** |
| 2 | **`clr__alert`** — 存在しないクラス | `page-form-contact-chk.php` L385 で使用。`_font.scss` にも他の SCSS にも定義なし。**現状効いていない** |

### 3.5 SCSS で定義されているが PHP で未使用のクラス

| クラス群 | ファイル | 判断 |
|---|---|---|
| `mb__0`, `mx__auto` | `_margin.scss` | PHP 未使用。削除して問題なし |
| `as__*` (6種 × 6BP), `order__*` (6個 × 6BP) | `_flex.scss` | PHP 未使用。削除して問題なし |
| `pt__0`, `pb__0`, `px__0`, `py__0` | `_padding.scss` | PHP 未使用。削除して問題なし |
| `invisible`, `visible-print__*`, `hidden-print` | `_visibility.scss` | PHP 未使用。削除して問題なし |
| `clr__red`, `clr__w`/`clr__white`, `clr__g50`〜`clr__g600`, `clr__g800`〜`clr__g900`, `clr__black` | `_font.scss` | PHP 未使用。**ただし投稿本文（the_content）で使われている可能性は排除できない**。WordPress のエディタから手入力されたクラスは PHP テンプレートの grep では検出不可 |
| `embed__responsive`, `embed__responsive-*` | `_responsive-embed.scss` | PHP テンプレートで未使用。`youtube` クラスは CSS 側で使用されているため `@layer components` へ移動して維持 |
| `display__inline-block` (レスポンシブ含む) | `_display.scss` | 1 箇所のみ（tmp/single-blog.php） |

---

## 4. 変換表（確定案）

### 4.1 margin 系（確定 ✅）

**規則**: `mt__N` → `mt-N` / `mt__N--BP` → `BP:mt-N`

spacing は `tailwind.config.js` の `theme.spacing` で全値定義済みのため、値は自動的に維持される。

| 旧クラス | 新 Tailwind クラス | 実値 | 根拠 |
|---|---|---|---|
| `mt__0` | `mt-0` | 0px | spacing.0 = '0px' |
| `mt__1` | `mt-1` | 8px → 0.5rem | spacing.1 = '8px' |
| `mt__1_5` | `mt-1.5` | 12px → 0.75rem | spacing.1.5 = '12px' |
| `mt__1_25` | `mt-1.25` | 10px → 0.625rem | spacing.1.25 = '10px' |
| `mt__2` | `mt-2` | 16px → 1rem | spacing.2 = '16px' |
| `mt__3` | `mt-3` | 24px → 1.5rem | spacing.3 = '24px' |
| `mt__4` | `mt-4` | 32px → 2rem | spacing.4 = '32px' |
| `mt__5` | `mt-5` | 40px → 2.5rem | spacing.5 = '40px' |
| `mt__6` | `mt-6` | 48px → 3rem | spacing.6 = '48px' |
| `mt__7` | `mt-7` | 56px → 3.5rem | spacing.7 = '56px' |
| `mt__8` | `mt-8` | 64px → 4rem | spacing.8 = '64px' |
| `mt__9` | `mt-9` | 72px → 4.5rem | spacing.9 = '72px' |
| `mt__10` | `mt-10` | 80px → 5rem | spacing.10 = '80px' |
| `mt__0--sm` | `sm:mt-0` | 0px @576px+ | |
| `mt__0--md` | `md:mt-0` | 0px @811px+ | |
| `mt__0--lg` | `lg:mt-0` | 0px @1025px+ | |
| `mt__N--BP` | `BP:mt-N` | 同上規則 | |
| `mb__0` | `mb-0` | 0px | PHP 未使用（定義のみ） |
| `mx__auto` | `mx-auto` | auto | PHP 未使用（定義のみ） |

**注意**: `mt__0_75` は spacing キー `0.75` に対応。SCSS の `$space_values` キー `0_75` と `tailwind.config.js` の `0.75` の対応が取れている。

**バグ修正**: `mt__075` → `mt-0.75`（PHP 側のクラス名を修正。現状は CSS にマッチしていない死んだクラス）

### 4.2 display 系（確定 ✅）

**規則**: `display__VALUE` → Tailwind display utility / `display__VALUE--BP` → `BP:VALUE`

| 旧クラス | 新 Tailwind クラス |
|---|---|
| `display__none` | `hidden` |
| `display__none--sm` | `sm:hidden` |
| `display__none--md` | `md:hidden` |
| `display__none--xl` | `xl:hidden` |
| `display__block` | `block` |
| `display__block--sm` | `sm:block` |
| `display__block--lg` | `lg:block` |
| `display__block--xl` | `xl:block` |
| `display__inline-block` | `inline-block` |
| `display__inline-block--BP` | `BP:inline-block` |

**`!important` について**: SCSS では全て `!important` 付き。Tailwind v4 のユーティリティはデフォルトで `!important` なし。既存コンポーネントが `@layer` 外にあるため優先度は Tailwind ユーティリティより高い。`!important` が必要な場面があれば `!hidden` 等の `!` プレフィックスを個別に使うか、`@import "tailwindcss" important;` で全ユーティリティに一括付与する。**実装時に優先度衝突が起きるか個別確認が必要**。

### 4.3 flex 系（確定 ✅）

**規則**: `XX__VALUE` → Tailwind flex utility / `XX__VALUE--BP` → `BP:VALUE`

| 旧クラス | 新 Tailwind クラス |
|---|---|
| `jc__start` | `justify-start` |
| `jc__end` | `justify-end` |
| `jc__center` | `justify-center` |
| `jc__between` | `justify-between` |
| `jc__around` | `justify-around` |
| `jc__start--sm` | `sm:justify-start` |
| `jc__between--md` | `md:justify-between` |
| `ai__start` | `items-start` |
| `ai__end` | `items-end` |
| `ai__center` | `items-center` |
| `ai__baseline` | `items-baseline` |
| `ai__stretch` | `items-stretch` |
| `ai__center--BP` | `BP:items-center` |

### 4.4 visibility / hide 系（確定 ✅）

**hide の仕組み**:
- `hide__BP--up` = `media-breakpoint-up(BP)` = `@media (min-width: BPpx)` で `display: none`
- `hide__BP--down` = `media-breakpoint-down(BP)` = `@media (max-width: nextBP - 1px)` で `display: none`

**変換規則**:
- `--up` → そのまま `BP:hidden`（min-width ベース）
- `--down` → `max-{nextBP}:hidden`（Tailwind v4 の `max-*` バリアント）

| 旧クラス | 出力 media query | 新 Tailwind クラス |
|---|---|---|
| `hide__xs--up` | なし（常時） | `hidden` |
| `hide__sm--up` | `min-width: 576px` | `sm:hidden` |
| `hide__md--up` | `min-width: 811px` | `md:hidden` |
| `hide__lg--up` | `min-width: 1025px` | `lg:hidden` |
| `hide__xl--up` | `min-width: 1280px` | `xl:hidden` |
| `hide__xxl--up` | `min-width: 1366px` | `2xl:hidden` |
| `hide__xs--down` | `max-width: 575px` | `max-sm:hidden` |
| `hide__sm--down` | `max-width: 810px` | `max-md:hidden` |
| `hide__md--down` | `max-width: 1024px` | `max-lg:hidden` |
| `hide__lg--down` | `max-width: 1279px` | `max-xl:hidden` |
| `hide__xl--down` | `max-width: 1365px` | `max-2xl:hidden` |
| `hide__xxl--down` | なし（常時） | `hidden` |
| `invisible` | — | `invisible` | Tailwind に同名あり |

**Tailwind v4 `max-*` バリアントの互換性**: Tailwind v4 は `max-sm:hidden` を `@media not (width >= 576px)` として出力する。これは `@media (width < 576px)` と同義で、旧 SCSS の `@media (max-width: 575px)` とはサブピクセルレベルで異なるが、実用上の差異はない（viewport 幅は整数）。

**print 系**: `visible-print__block`, `visible-print__inline`, `visible-print__inline-block`, `hidden-print` は PHP 未使用だが、SCSS を削除すれば CSS からも消える。問題なし。

### 4.5 font / text-align 系（確定 ✅）

#### text-align（確定 ✅）

**注意**: text-align 系は **element infix**（`__sm`）を使っている。modifier infix（`--sm`）ではない。旧クラスを Tailwind 記法へ置換する。

| 旧クラス | 新 Tailwind クラス |
|---|---|
| `tac` | `text-center` |
| `tac__sm` | `sm:text-center` |
| `tac__md` | `md:text-center` |
| `tac__lg` | `lg:text-center` |
| `tal` | `text-left` |
| `tal__sm` | `sm:text-left` |
| `tar` | `text-right` |
| `tar__sm` | `sm:text-right` |

#### font-weight（確定 ✅）

| 旧クラス | 新 Tailwind クラス |
|---|---|
| `fwl` / `fw__300` | `font-light` |
| `fw__400` | `font-normal` |
| `fw__500` | `font-medium` |
| `fwb` / `fw__600` | `font-semibold` |

#### text-decoration（確定 ✅）

| 旧クラス | 新 Tailwind クラス |
|---|---|
| `tdu` | `underline` |

#### color（確定 ✅）

`tailwind.config.js` の `theme.colors` で CSS 変数参照が定義済み。

| 旧クラス | 新 Tailwind クラス | 根拠 |
|---|---|---|
| `clr__1` | `text-clr1` | colors.clr1 = 'var(--clr1)' |
| `clr__2` | `text-clr2` | colors.clr2 |
| `clr__3` | `text-clr3` | colors.clr3 |
| `clr__4` | `text-clr4` | colors.clr4 |
| `clr__black` | `text-black` | colors.black = '#222' |
| `clr__w` / `clr__white` | `text-white` | colors.white = '#fff' |
| `clr__gray` / `clr__g700` | `text-gray-700` | colors.gray.700 |
| `clr__g50` | `text-gray-50` | colors.gray.50 |
| `clr__g100` | `text-gray-100` | colors.gray.100 |
| `clr__g200` | `text-gray-200` | colors.gray.200 |
| `clr__g300` | `text-gray-300` | colors.gray.300 |
| `clr__g400` | `text-gray-400` | colors.gray.400 |
| `clr__g500` | `text-gray-500` | colors.gray.500 |
| `clr__g600` | `text-gray-600` | colors.gray.600 |
| `clr__g800` | `text-gray-800` | colors.gray.800 |
| `clr__g900` | `text-gray-900` | colors.gray.900 |
| `clr__red` | `text-[#f00]` | colors に red が未定義。**arbitrary value** が必要。または `theme.colors` に `red: '#f00'` を追加する |

**`clr__red` の扱い**: PHP では未使用だが、投稿本文 (`the_content`) で使われている可能性を排除できない。`tailwind.config.js` に `red: '#f00'` を追加するか、arbitrary value `text-[#f00]` にするかは実装時に判断。

#### font-size / fz__ 系（確定 ✅）

既存の `fz__` クラスを Tailwind 記法へ置換する。旧 SCSS の 1600px 以上での +1px サイズアップは、既存の最大ブレークポイント `2xl`（1366px）を暫定のサイズアップ地点として使い、基本サイズ + `2xl:` 上書きクラスの 2 クラスで再現する。

**`tailwind.config.js` の `theme.extend.fontSize` に追加するトークン（fz12 〜 fz36）**:

| トークン | 値 | トークン | 値 | トークン | 値 |
|---|---|---|---|---|---|
| `fz12` | `0.75rem` | `fz21` | `1.3125rem` | `fz30` | `1.875rem` |
| `fz13` | `0.8125rem` | `fz22` | `1.375rem` | `fz31` | `1.9375rem` |
| `fz14` | `0.875rem` | `fz23` | `1.4375rem` | `fz32` | `2rem` |
| `fz15` | `0.9375rem` | `fz24` | `1.5rem` | `fz33` | `2.0625rem` |
| `fz16` | `1rem` | `fz25` | `1.5625rem` | `fz34` | `2.125rem` |
| `fz17` | `1.0625rem` | `fz26` | `1.625rem` | `fz35` | `2.1875rem` |
| `fz18` | `1.125rem` | `fz27` | `1.6875rem` | `fz36` | `2.25rem` |
| `fz19` | `1.1875rem` | `fz28` | `1.75rem` | | |
| `fz20` | `1.25rem` | `fz29` | `1.8125rem` | | |

`theme.extend.fontSize` を使うことで、Tailwind デフォルトの font-size トークン（`text-xs`, `text-sm` 等）を維持したまま追加する。

**変換表**:

| 旧クラス | 新 Tailwind クラス（2 クラス置換） |
|---|---|
| `fz__12` | `text-fz12 2xl:text-fz13` |
| `fz__14` | `text-fz14 2xl:text-fz15` |
| `fz__15` | `text-fz15 2xl:text-fz16` |
| `fz__16` | `text-fz16 2xl:text-fz17` |
| `fz__18` | `text-fz18 2xl:text-fz19` |
| `fz__20` | `text-fz20 2xl:text-fz21` |

### 4.6 responsive-embed / youtube（確定 ✅）

| 旧クラス | 使用状況 | 対応方針 |
|---|---|---|
| `embed__responsive` + 派生 | PHP テンプレート未使用 | SCSS 削除で CSS からも消える。問題なし |
| `youtube` | CSS 側で使用（position:relative + padding-bottom:56.25% + overflow:hidden + 子 iframe の absolute 配置） | `_responsive-embed.scss` 削除時に `youtube` クラスの定義を `@layer components` に移動して維持する。クラス名・スタイル内容ともに変更しない |

---

## 5. リスクと先回り対策

### 5.1 fz__ 系のサイズアップ地点の変更

**リスク**: 旧 SCSS では 1600px 以上で font-size が +1px される微調整があった。`tailwind.config.js` の `theme.screens` に 1600px がないため、既存の最大ブレークポイント `2xl`（1366px）を暫定のサイズアップ地点として使う。発火地点が 1600px → 1366px に前倒しになる。
**対策**: +1px（0.0625rem）の差異は微小であり、1366px 時点で適用されても視覚的な問題はない。将来 `3xl: 1600px` を追加して本来の地点に戻すことも可能だが、Phase 2 では 2xl で十分。

### 5.2 hide__ 系の max-width ブレークポイント

**リスク**: `hide__BP--down` は `max-width` ベース。Tailwind v4 は `max-*` バリアントをサポートするが、旧 SCSS の `max-width: Npx` と Tailwind v4 の `@media not (width >= Npx)` にサブピクセルの差異がある。
**対策**: 実用上の差異はない（viewport は整数ピクセル）。ただし、実装後にブレークポイント境界（575px, 576px 等）での表示テストを行う。

### 5.3 文字列連結された class 属性

**リスク**: `footer.php` L199/L203 で `echo '<a class="display__none--sm ... ' . $class_name_reserve . '"'` のように文字列連結が使われている。単純な sed/awk 置換スクリプトでは連結部分を壊す可能性。
**対策**: 動的部分（`$class_name_reserve`）は GTM トラッキングクラスで utility クラスではないことを確認済み。置換対象は静的文字列部分のみ。**置換スクリプトは class 属性全体ではなく、個別クラス名単位で置換する設計にする**。

### 5.4 tmp/ 配下の扱い

**結論**: tmp/ は `get_template_part()` で読み込まれる本番テンプレートパーツ。**全ファイルが Phase 2 の置換対象**。
- `tmp/content/` 配下も含む
- `tmp/google-tag-manager/` は utility クラスを使用していないため実質対象外

### 5.5 SCSS 削除タイミング

**リスク**: SCSS を先に削除すると、PHP 側のクラス名置換漏れがあった場合にスタイルが壊れる。
**対策**: SCSS の削除は PHP 置換 + 検証の後に行う。以下の手順を厳守:
1. PHP のクラス名を置換
2. ビルドして新旧両方の CSS が出力されることを確認（旧 SCSS がまだ残っているので旧クラスの CSS も出る）
3. 旧クラスが PHP から完全に消えたことを grep で確認
4. SCSS ファイルを削除
5. 再ビルドして新クラスの CSS だけが出ることを確認

### 5.6 一括置換で Phase 3 領域まで触ってしまうリスク

**リスク**: `jc__start` 等の flex 系クラスは Phase 3 の対象ファイル（`l-row` と同じ行）にも出現する。一括置換すると Phase 3 領域のクラスも変更される。
**対策**: Phase 2 ではクラス名の置換のみを行い、HTML 構造は変更しない。`jc__start` → `justify-start` は Phase 2 の scope。同じ行にある `l-row--container` はそのまま残す。`l-row` → Tailwind flex ユーティリティへの置換は Phase 3。

### 5.7 `!important` の扱い

**リスク**: 旧 SCSS ユーティリティは全て `!important` 付き。Tailwind v4 のデフォルトは `!important` なし。既存コンポーネントクラスが `@layer` 外にあるため優先度問題が起きる可能性。
**対策**: Tailwind v4 では CSS に `@import "tailwindcss" important;` と書くことで、全ユーティリティに `!important` を付与できる（important flag）。実装時に優先度問題が発生するか確認し、必要なら有効化する。

### 5.8 `strong` 要素ルール

`_font.scss` 内の `strong {}` は base ルール。`_font.scss` 削除時に `@layer base` へ移動する。実装時に処理すれば十分で、事前の方針決定は不要。

### 5.9 投稿本文・管理画面入力由来の旧 utility クラス

**リスク**: WordPress エディタから手入力された旧 utility クラス（`clr__*`, `mt__*`, `fz__*`, `tac`, `fw__*` 等）は PHP テンプレートの grep では検出できない。投稿本文（`the_content`）やカスタムフィールド等、管理画面からの入力に含まれる旧クラスは Tailwind のコンテンツ検出対象外となり、CSS が生成されない。
**対策**: `wp_posts` テーブルの `post_content` を旧 utility クラスのパターンで検索するか、ビルド済みサイトの HTML ソースで確認する。該当クラスがあれば DB 側のクラス名を新しい Tailwind クラスに置換するか、CSS 側で Tailwind v4 の手段（`@source inline(…)` 等）で明示的に検出対象へ追加する。Tailwind v4 の JS config では `safelist` は使えない。**Phase 2 の SCSS 削除前に確認必須**。

---

## 6. 推奨実行手順

### Step 2-1: fz__ 系の Tailwind 化準備

**変更するもの**: `tailwind.config.js`
**触らないもの**: PHP ファイル、SCSS ファイル

1. `tailwind.config.js` の `theme.extend.fontSize` にカスタムトークン（`fz12` 〜 `fz36`）を追加する。基本サイズ用（`fz12`, `fz14`, `fz15`, `fz16`, `fz18`, `fz20`）と 2xl サイズアップ用（`fz13`, `fz15`, `fz16`, `fz17`, `fz19`, `fz21`）の両方を含む。将来の拡張に備え `fz22` 〜 `fz36` も定義する
2. `extend.fontSize` を使い、Tailwind デフォルトの font-size トークン（`text-xs`, `text-sm` 等）を消さないようにする
3. `npm run build` して問題なくビルドできることを確認
4. **この時点では PHP は変更しない**

### Step 2-2: PHP のクラス名置換（margin）

**変更するもの**: 全 PHP ファイル（ルート + tmp/）
**触らないもの**: SCSS ファイル

1. `mt__075` → `mt-0.75` に修正（バグ修正。4 箇所）
2. `mt__N_M` → `mt-N.M` パターンで置換（例: `mt__1_5` → `mt-1.5`, `mt__1_25` → `mt-1.25`）
3. `mt__N--BP` → `BP:mt-N` パターンで置換（例: `mt__4--md` → `md:mt-4`）
4. `mt__N` → `mt-N` パターンで置換（例: `mt__4` → `mt-4`）
5. `mb__0` → `mb-0`, `mx__auto` → `mx-auto`（PHP 未使用だが念のため）
6. `npm run build` して Tailwind が新クラスの CSS を生成することを確認
7. `_class-rename-log.md` の margin 系のステータスを ✅ に更新する

**置換順序が重要**: レスポンシブ付き（`mt__4--md`）を先に、基本形（`mt__4`）を後に置換する。逆にすると `mt__4--md` が `mt-4--md` に中途半端に変換される。

### Step 2-3: PHP のクラス名置換（display + visibility）

**変更するもの**: 全 PHP ファイル
**触らないもの**: SCSS ファイル

1. `display__none--BP` → `BP:hidden` で置換
2. `display__block--BP` → `BP:block` で置換
3. `display__inline-block--BP` → `BP:inline-block` で置換
4. `display__none` → `hidden` で置換
5. `display__block` → `block` で置換
6. `display__inline-block` → `inline-block` で置換
7. `hide__BP--up` → `BP:hidden` で置換（xs の場合は `hidden`）
8. `hide__BP--down` → `max-{nextBP}:hidden` で置換
9. ビルド確認
10. `_class-rename-log.md` の display 系・visibility / hide 系のステータスを ✅ に更新する

**置換順序が重要**: レスポンシブ付きを先、基本形を後。

### Step 2-4: PHP のクラス名置換（flex）

**変更するもの**: 全 PHP ファイル
**触らないもの**: SCSS ファイル

1. `jc__VALUE--BP` → `BP:justify-VALUE` で置換
2. `jc__VALUE` → `justify-VALUE` で置換
3. `ai__VALUE--BP` → `BP:items-VALUE` で置換
4. `ai__VALUE` → `items-VALUE` で置換
5. ビルド確認
6. `_class-rename-log.md` の flex 系のステータスを ✅ に更新する

### Step 2-5: PHP のクラス名置換（font: font-size, text-align, font-weight, text-decoration, color）

**変更するもの**: 全 PHP ファイル
**触らないもの**: SCSS ファイル

1. `fz__12` → `text-fz12 2xl:text-fz13`, `fz__14` → `text-fz14 2xl:text-fz15`, `fz__15` → `text-fz15 2xl:text-fz16`, `fz__16` → `text-fz16 2xl:text-fz17`, `fz__18` → `text-fz18 2xl:text-fz19`, `fz__20` → `text-fz20 2xl:text-fz21` で置換（各 1 クラス → 2 クラスへの展開）
2. `tac__BP` → `BP:text-center` で置換（**注意: element infix `__` を使っている**）
3. `tal__BP` → `BP:text-left` で置換
4. `tar__BP` → `BP:text-right` で置換
5. `tac` → `text-center`（BP なし版）
6. `tal` → `text-left`
7. `tar` → `text-right`
8. `fw__300` / `fwl` → `font-light`
9. `fw__400` → `font-normal`
10. `fw__500` → `font-medium`
11. `fw__600` / `fwb` → `font-semibold`
12. `tdu` → `underline`
13. `clr__1` → `text-clr1`, `clr__2` → `text-clr2`, ..., `clr__gray` → `text-gray-700`
14. `clr__alert` → 定義がないバグ。**赤色テキスト用と推測**。`text-[#f00]` か `text-red` を追加するか、ユーザーに確認
15. ビルド確認
16. `_class-rename-log.md` の font-size 系・text-align 系・font-weight 系・text-decoration 系・color 系のステータスを ✅ に更新する

**置換順序が重要**: `fz__` はそのまま置換で衝突なし。`tac__sm` を先に、`tac` を後に。`clr__gray` を先に、`clr__g` 系を後に。

### Step 2-6: padding の処理

PHP で未使用のため、SCSS 削除のみ（Step 2-8 で一括）。

### Step 2-7: responsive-embed の処理

1. `_responsive-embed.scss` の `youtube` クラス定義を `_tailwind-base-layer.scss` の `@layer components` に移動する
2. `embed__responsive` 系は PHP 未使用のため移動不要
3. `npm run build` して `youtube` クラスの CSS が出力されることを確認

### Step 2-8: SCSS ファイルの削除 + style.scss の更新

**Step 2-2〜2-5 の PHP 置換と検証が全て完了した後に実行する**

1. `style.scss` から以下の `@use` をコメントアウトまたは削除:
   - `@use "utility/_display"`
   - `@use "utility/_flex"`
   - `@use "utility/_margin"`
   - `@use "utility/_padding"`
   - `@use "utility/_visibility"`
   - `@use "utility/_responsive-embed"`
2. `_font.scss` の全カテゴリ（font-size, text-align, font-weight, text-decoration, color）が Tailwind 置換済みのため `@use` を削除:
   - `strong {}` ルールは `_tailwind-base-layer.scss` の `@layer base` に移動してから削除する
3. `npm run build` で最終確認
4. 旧クラス名が CSS 出力から消え、新 Tailwind クラスのみが出力されることを確認

### Step 2-9: 廃止

> `_class-rename-log.md` の更新は各 Step（2-2〜2-5）の完了条件に組み込み済み。まとめて後から記録する方式は廃止した。

---

## 7. 検証計画

### 7.1 旧 utility class が PHP から消えたこと

```bash
# wp-thm ルートで実行。各ファミリーの旧クラス名パターンで grep（結果ゼロであること）
grep -rn 'mt__[0-9]' --include='*.php' .
grep -rn 'display__' --include='*.php' .
grep -rn -e 'jc__' -e 'ai__' -e 'as__' -e 'order__' --include='*.php' .
grep -rn -e 'fz__' --include='*.php' .
grep -rn -e 'tac__' -e 'tal__' -e 'tar__' --include='*.php' .
grep -rn -e '"tac"' -e "'tac'" -e ' tac ' --include='*.php' .
grep -rn -e '"tal"' -e "'tal'" -e ' tal ' --include='*.php' .
grep -rn -e '"tar"' -e "'tar'" -e ' tar ' --include='*.php' .
grep -rn -e 'fw__' -e '"fwl"' -e "'fwl'" -e '"fwb"' -e "'fwb'" --include='*.php' .
grep -rn 'clr__' --include='*.php' .
grep -rn -e 'hide__' --include='*.php' .
grep -rn -e '"tdu"' -e "'tdu'" -e ' tdu ' --include='*.php' .
```

**注意**: macOS 標準の grep は `\b`（単語境界）を認識しない。短いクラス名（`tac`, `tal`, `tar`, `tdu`, `fwl`, `fwb`）はクォートや前後スペースで文脈を絞る。`__` 付きパターン（`mt__`, `fz__` 等）は部分一致で十分。

### 7.2 新 Tailwind class が意図通り入ったこと

代表テンプレートで新クラスが正しく挿入されていることを目視確認:

| テンプレート | 確認ポイント |
|---|---|
| `front-page.php` L6 | `mt-9 md:mt-10`（旧: `mt__9 mt__10--md`） |
| `footer.php` L165 | `max-md:hidden`（旧: `hide__sm--down`） |
| `footer.php` L199 | `sm:hidden`（旧: `display__none--sm`）+ 動的クラスが壊れていないこと |
| `front-page.php` L15 | `text-clr2 mt-2`（旧: `clr__2 mt__2`） |
| `page-form-contact-chk.php` L385 | `sm:text-center`（旧: `tac__sm`） |
| `tmp/page-form-contact.php` L98 | `mt-0.75 md:mt-1`（旧: `mt__075 mt__1--md` → バグ修正含む） |

### 7.3 style.css に必要な utility が生成されること

`npm run build` 後の `css/style.css` に以下が含まれることを確認:

```bash
# wp-thm ルートで実行。Tailwind が新クラスの CSS を生成していること
grep -c -e 'justify-start' -e 'justify-between' -e 'justify-center' css/style.css  # > 0
grep -c -e 'items-center' -e 'items-start' css/style.css  # > 0
grep -c -e 'text-center' -e 'text-left' css/style.css  # > 0
grep -c 'text-fz14' css/style.css  # > 0（text-fz14 の utility が生成されていること）
grep -c 'text-fz15' css/style.css  # > 0（2xl:text-fz15 に対応する utility が生成されていること）
grep -c '\.mt-4' css/style.css  # > 0
grep -c '\.hidden' css/style.css  # > 0
```

### 7.4 spacing 値が維持されていること

代表クラスの実値を CSS 出力で照合:

| Tailwind クラス | 期待値 | 確認方法 |
|---|---|---|
| `mt-4` | `margin-top: 32px` or `2rem` | `css/style.css` で `.mt-4` を検索 |
| `mt-1.5` | `margin-top: 12px` or `0.75rem` | 同上 |
| `mt-0.75` | `margin-top: 6px` or `0.375rem` | 同上 |

### 7.5 display / visibility / alignment のレスポンシブ挙動が維持されていること

ブラウザ確認（BrowserSync + viewport リサイズ）:

| 確認ポイント | テンプレート | 操作 |
|---|---|---|
| `max-md:hidden` が 810px 以下で非表示 | footer.php L165 「はこちら」テキスト | viewport を 811px ↔ 810px で切り替え |
| `sm:hidden` が 576px 以上で非表示 | footer.php L199 予約ボタン | viewport を 575px ↔ 576px で切り替え |
| `sm:block` が 576px 以上で表示 | footer.php L203 予約ボタン（desktop版） | 同上 |
| `md:justify-between` が 811px 以上で有効 | footer.php L64 | viewport を 811px 以上にして要素間スペースを確認 |
| `lg:text-center` が 1025px 以上で中央寄せ | 404.php L25 | viewport を 1025px 以上にして確認 |
| `text-fz14 2xl:text-fz15` で通常幅では基本サイズ、2xl 幅ではサイズアップに切り替わること | `fz__14` 使用箇所 | ブラウザの viewport を 2xl（1366px）の前後で切り替え、DevTools の computed font-size が変わることを確認 |

### 7.6 動的 class 箇所の確認方法

| 箇所 | 確認方法 |
|---|---|
| `footer.php` L199/L203 | ブラウザで該当ページのフッターを表示し、DevTools で `<a>` 要素の class 属性を確認。`sm:hidden` + `gtm-footer-reserve-*` が共存していること |
| `tmp/page-campaign.php` L3 | ブラウザで該当ページを表示し、`$notice_all` が展開された箇所の class を確認 |

---

## 8. Go / No-Go 判定

### Go（このまま進められる項目）

- margin, display, visibility, flex, font-size, text-align, font-weight, text-decoration, color の置換
- PHP 側の置換は全て静的文字列。一括置換スクリプトで対応可能
- tmp/ は本番テンプレートパーツとして全ファイル対象
- spacing スケールは `tailwind.config.js` で確定済み

### 実装前に決定が必要な未解決事項（2 件）

| # | 事項 | 選択肢 | 推奨 |
|---|---|---|---|
| 1 | **`clr__alert` の修正方針** | 定義を追加する（赤色？）/ 別のクラスに置換する / 放置する | ユーザーに**デザイン意図を確認**。フォームのエラーメッセージ用と推測されるが、色が不明 |
| 2 | **`clr__red` を config に追加するか** | `theme.colors` に `red: '#f00'` を追加する / 不要（PHP 未使用） | 投稿本文での使用可能性を**確認してから判断** |

### 追加で確認すべきこと（実装着手前）

1. **投稿本文・管理画面入力由来の旧 utility クラス**: `wp_posts.post_content` に `clr__*`, `mt__*`, `fz__*`, `tac`, `fw__*` 等の旧 utility クラスが使われているか。DB 検索またはビルド済みサイトの HTML ソースで確認する。該当があれば DB 側のクラス名を新しい Tailwind クラスに置換するか、CSS 側で Tailwind v4 の手段（`@source inline(…)` 等）で明示的に検出対象へ追加する
2. **`!important` の優先度問題**: Tailwind v4 デフォルトでは `!important` が付かない。旧 SCSS では全 utility に `!important` がついていた。コンポーネントクラスとの優先度衝突が発生するかを、実装時に代表的なテンプレートで確認する。問題があれば `@import "tailwindcss" important;` で有効化する
3. **Tailwind v4 の `max-*` バリアントが custom screens で正しく動作するか**: `max-md:hidden` が `@media not (width >= 811px)` を正しく出力するか、実装開始前にテスト用クラスを PHP に入れてビルドして確認する

---

## 計画書（tailwind-migration-plan.md）の §2 との差分

計画書の §2 には以下の不足・不正確があった:

| 項目 | 計画書の記述 | 現物確認の結果 |
|---|---|---|
| `_font.scss` のクラス数 | 「18」 | 実際は text-align 3種×6BP + font-weight 4個 + text-decoration 1個 + color 18個 + font-size 6個 + `strong` 要素ルール = **約 50 クラス + 1 要素ルール**。18 は大幅に過少 |
| `_padding.scss` | 「`pt-0`, `pb-0` 等」 | `pt__0`, `pb__0`, `px__0`, `py__0` の 4 個のみで**全て PHP 未使用**。他は全てコメントアウト |
| `_responsive-embed.scss` | 「`aspect-video`, `aspect-square`」 | `embed__responsive` 系と `youtube` クラスが定義されている。`aspect-*` は存在しない。`embed__responsive` は PHP 未使用。`youtube` クラスは CSS 側で使用されており、SCSS 削除時に `@layer components` へ移動して維持する |
| `_visibility.scss` のクラス数 | 「12」 | `invisible` 1 + hide up/down 12 + print 系 4 = **17 クラス**。PHP で使用されているのは hide 系のみ |
| fz__ の 1600px 条件 | 言及なし | 全 fz__ クラスが `@media (min-width: 1600px)` で +1px バンプを持つ。本計画では既存の最大 BP `2xl`（1366px）を暫定のサイズアップ地点とし、`theme.extend.fontSize` にカスタムトークンを定義して 2 クラス置換で再現する |
| `strong` 要素ルール | 言及なし | `_font.scss` に含まれる。utility ではなく base ルール。`_font.scss` 削除時に `@layer base` へ移動 |
| クラス名変換表 | 「未定 — 別途議論」 | 本計画で全カテゴリ確定 |
