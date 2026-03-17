# SCSS → Tailwind 移行 精査報告書

> 作成日: 2026-03-17
> 対象: Phase 1〜5 完了後の実装状態 vs 当初計画（tailwind-migration-plan.md）

---

## 概要

Phase 1〜5 の移行作業は概ね計画どおりに完了している。
ビルドは通り、PHP テンプレートの旧クラスも全て置換済み。
ただし、計画の改訂過程で「意図的に残した」ものと「見落とし・未処理」のものが混在しており、以下に整理する。

---

## A. 計画で「廃止」と明記されたが、まだ残っているもの

### A-1. `global/_gutter.scss`（旧 `global/_gutter.scss`）

**計画書 §3.4 / §5.5**: `global/_gutter.scss` は廃止対象として明記。

**現状**: まだ現役。以下の 6 箇所が `@include g.gutter` を使用中。

| ファイル | 行 |
|---|---|
| `project/_form.scss` | L51 |
| `project/_post.scss` | L124 |
| `project/_post.scss` | L193 |
| `project/_post-single.scss` | L12 |
| `project/_post-single.scss` | L116 |
| `project/_entrystep.scss` | L22 |

**原因**: Phase 3 で PHP テンプレート上の `.c-gutter` クラスは Tailwind ユーティリティ（`px-gutter-row xl:px-0`）に置換したが、SCSS コンポーネント内部の `@include g.gutter` は Phase 4 でも手をつけなかった。これらはコンポーネント定義の内部にあり、PHP のクラス名置換では対処できない箇所。

**影響**: ビルドには影響なし。mixin が出力する CSS（`padding: 0 var(--gutter-row); @media md { padding: 0; }`）は意図どおり動作している。ただし計画上は廃止対象であり、Tailwind の `px-gutter-row md:px-0` への置換で mixin を不要にできる。

**対応案**: 各コンポーネント内の `@include g.gutter` を、直接 `padding: 0 var(--gutter-row)` + `@media #{g.$md} { padding: 0; }` に展開するか、このまま mixin 維持とするか判断が必要。

---

### A-2. `global/_variables.scss`（旧 `foundation/_variables.scss`）の未使用変数

**計画書 §1.4 / §5.5**: `foundation/_variables.scss` は「全変数が config / @layer base / SCSS 関数に分散」として廃止対象。ただし `get_zindex()` と `$layout_zindex` は維持。

**現状**: ファイルは `global/_variables.scss` に移動して現役。以下の変数が**定義されているが、active SCSS から参照されていない（デッドコード）**。

| 変数 | 行 | 状況 |
|---|---|---|
| `$font-family-sans-serif` | L79 | tailwind.config.js + _tailwind-base-layer.scss body に移行済み。SCSS 参照ゼロ |
| `$font-family-serif` | L80 | tailwind.config.js に移行済み。SCSS 参照ゼロ |
| `$font-family-monospace` | L81 | tailwind.config.js に移行済み。SCSS 参照ゼロ |
| `$font-family-base` | L82 | `$font-family-sans-serif` のエイリアス。SCSS 参照ゼロ |
| `$font-size-base` | L84 | `@layer base` body に `16px` ハードコード済み。SCSS 参照ゼロ |
| `$font-weight-base` | L85 | 同上 `normal`。SCSS 参照ゼロ |
| `$line-height-base` | L86 | 同上 `1.5`。SCSS 参照ゼロ |
| `$border-radius` | L87 | Phase 4/5 で全参照箇所を `6px` ハードコードに置換済み。SCSS 参照ゼロ |
| `$grid-columns` | L52 | Phase 3 で不要化。SCSS 参照ゼロ |
| `$container-max-sm` 〜 `$container-max-xxl` | L37-41 | tailwind.config.js に移行済み。`$container-max-widths` map 構築のためだけに存在 |
| `$container-max-widths` | L43-49 | SCSS 参照ゼロ |

**まだ参照がある（削除不可）変数**:

| 変数 | 参照元 | 用途 |
|---|---|---|
| `$grid-breakpoints-sm` 〜 `$grid-breakpoints-xxl` | `$grid-breakpoints` map 構築 | 間接的に `_media-queries.scss` で使用 |
| `$grid-breakpoints` (map) | `_breakpoints.scss`, `_media-queries.scss` | `g.$sm` 等のメディアクエリ変数の算出に必須 |
| `$transition-base` | `_transition.scss` L3 | `$transition` 変数に代入（後述 A-3） |
| `$layout_zindex` (map) | `get_zindex()` | 3 箇所で参照中（header, footer, micromodal） |
| `get_zindex()` (関数) | component/project/layout | 3 箇所で参照中 |
| `$space_values` (map) | — | active SCSS からの直接参照ゼロ。ただし tailwind.config.js と同期の基準値 |

**対応案**: デッドコード（上の表の「SCSS 参照ゼロ」行）は安全に削除可能。

---

### A-3. `global/_transition.scss` のデッドコード

**現状**: `_transition.scss` は `$transition: v.$transition-base` を定義しているが、この `g.$transition` を参照している active SCSS ファイルはゼロ。`_archive/foundation/_reboot.scss` で 2 箇所使われているが、archive なので build graph 外。

**影響**: ビルドに影響なし。ただしファイル自体がデッドコード。

**対応案**:
- `_transition.scss` を削除し、`global/_index.scss` から `@forward "_transition"` を除去
- または `$transition-base`（_variables.scss L88）と合わせて削除

---

## B. 計画からの乖離（意図的な変更 or 見落とし）

### B-1. body の `font-family` がハードコード（計画と不一致）

**計画書 §1.4 カテゴリ B-body**:
```css
font-family: theme('fontFamily.sans');
```

**実装**（`_tailwind-base-layer.scss` L121-124）:
```scss
font-family:
  avenir, "Noto Sans JP", "游ゴシック体", yugothic, ...;
```

**影響**: 機能的には同一（tailwind.config.js に同じフォントスタックが定義されている）。ただし値の変更時に `tailwind.config.js` と `_tailwind-base-layer.scss` の 2 箇所を更新する必要がある。

**判断**: `theme()` 関数は SCSS ファイル内では Sass コンパイル時に解決できない（PostCSS/Tailwind 処理は後段）。SCSS partial である `_tailwind-base-layer.scss` 内で `theme()` を使うには concat 後の CSS に書く必要があり、現在のパイプライン構成では困難。**これは計画側の見落としであり、ハードコードが現実的に正しい判断**。

---

### B-2. `--form-focus-color` の欠落

**計画書 §1.4 カテゴリ C**:
```css
.p-form {
  --form-focus-color: var(--clr1);
  ...
}
```

**実装**（`_tailwind-base-layer.scss` L391-409）: `.p-form` の CSS 変数に `--form-focus-color` が**存在しない**。

**確認**: `--form-focus-color` は active SCSS 全体で参照ゼロ。`project/_form.scss` でフォーカス色は `var(--clr1)` を直接参照している可能性がある。

**影響**: 実害なし（参照がないため）。計画書に書いたが実装時に不要と判断して省略した、もしくは見落とした。

---

### B-3. `--bdrs` / `--bdrs-lg` が `@layer` 外に存在

**現状**: `project/_style.scss` L59-62 に `:root` ブロックが `@layer components` の**外側**に定義されている。

```scss
:root {
  --bdrs: #{g.rem(50)};
  --bdrs-lg: #{g.rem(100)};
}

@layer components {
  // ... 以降のスタイル
```

**参照箇所**: 同ファイル L488, L491 の `border-radius: var(--bdrs)` / `var(--bdrs-lg)` で使用。

**影響**: 動作上の問題はない（`:root` は `@layer` 外でも正常に動作し、むしろ優先度的に安全）。ただし Phase 4 の progress.md に「`:root` は `@layer` 外、`@keyframes` は `@layer` 外に配置」とあり、これは意図的な設計判断。

**問題点**: 他の CSS 変数は全て `_tailwind-base-layer.scss` の `@layer base` 内 `:root` に集約されているが、`--bdrs` / `--bdrs-lg` だけが `project/_style.scss` に残っている。一貫性の問題。

**対応案**: `--bdrs` / `--bdrs-lg` を `_tailwind-base-layer.scss` の `:root`（L13-38 内）に移動し、`project/_style.scss` の `:root` ブロックを削除。

---

### B-4. Ultimate Member / WP Instagram Feed の vendor 移行未実施

**計画書 §5.1**: 6 つの vendor 全てについて「SCSS 変数 → CSS 変数に置換のみ」と記載。

| Vendor | 状態 |
|---|---|
| FontAwesome | ✅ 完了 |
| Swiper | ✅ 完了 |
| Micromodal | ✅ 完了 |
| scroll-hint | ✅ 完了 |
| Ultimate Member | ❌ 未実施（`component/_archive/` に退避） |
| WP Instagram Feed | ❌ 未実施（`component/_archive/` に退避） |

**現状**: Ultimate Member と WP Instagram Feed は `style.scss` から参照されておらず build graph 外。archive ファイル内に `g.$clrg500`, `g.$clrg600`, `g.$clr1`, `g.$border-radius` 等の旧 SCSS 変数参照が残存。

**影響**: 現時点では影響なし。ただし将来これらを active 化する場合は、先に SCSS 変数 → CSS 変数の置換が必要。progress.md の「build graph 外の残件」セクションに記録済み。

**判断**: これは意図的な除外。build graph に入っていないファイルを修正する必要はなく、active 化時に対応すればよい。

---

## C. 値の二重管理（設計上の技術的負債）

計画の性質上、SCSS と Tailwind の共存期間には値の二重定義が発生する。以下は現時点の二重管理一覧。

### C-1. 削除可能な二重管理（SCSS 側がデッドコード）

| 値 | SCSS 定義 | Tailwind 定義 | SCSS 参照 |
|---|---|---|---|
| font-family-sans | `_variables.scss` L79 | `tailwind.config.js` L104-109 | ゼロ |
| font-family-serif | `_variables.scss` L80 | `tailwind.config.js` L110 | ゼロ |
| font-family-mono | `_variables.scss` L81 | `tailwind.config.js` L111 | ゼロ |
| border-radius | `_variables.scss` L87 | `tailwind.config.js` L123-125 | ゼロ |
| $container-max-* | `_variables.scss` L37-49 | `tailwind.config.js` L157-162 | ゼロ |
| $grid-columns | `_variables.scss` L52 | Tailwind 組み込み | ゼロ |

→ SCSS 側を削除すれば解消。

### C-2. 削除不可の二重管理（SCSS 側に参照あり）

| 値 | SCSS 定義 | Tailwind 定義 | SCSS 参照 |
|---|---|---|---|
| breakpoints | `_variables.scss` L15-28 | `tailwind.config.js` L10-16 | `_media-queries.scss`, `_breakpoints.scss` で必須 |
| spacing | `_variables.scss` L90-116 | `tailwind.config.js` L60-88 | active 参照ゼロだが同期の基準 |
| zIndex | `_variables.scss` L119-125 | `tailwind.config.js` L92-99 | `get_zindex()` で 3 箇所使用 |
| transition-base | `_variables.scss` L88 | `tailwind.config.js` L129-131 | `_transition.scss` L3（ただしその先の参照ゼロ） |

**breakpoints** が二重管理になるのは構造上避けられない。SCSS 側で `@media #{g.$md}` を使い続ける限り、`$grid-breakpoints` map は必要。Tailwind のユーティリティクラス（`md:` 等）は `tailwind.config.js` の `screens` から生成される。両者を同期させる責任はメンテナーにある。

**spacing** は active SCSS からの直接参照はゼロだが、tailwind.config.js の値の根拠として `_variables.scss` のマップが存在している。削除可能だが、参照元を失うリスクがある。

---

## D. ファイル整理の残件

### D-1. `global/_index.scss` のコメント更新

```scss
// @forward "../foundation/_variables-color";  // Phase 5: 削除済み
```

このコメントのパスが旧パスのまま。実害はないがメンテナンス上更新すべき。

### D-2. `global/_gutter.scss` L1 の未使用 `@use`

progress.md に「Phase 5 vendor 本体ステップから除外（計画書 §10.3）」と記録済み。`@use "_variables" as v;` が未使用のまま残存。ファイル自体の廃止判断（A-1）に含めて処理すべき。

### D-3. `style.scss` のコメント整理

`style.scss` 内に archive 先を示すコメントがあるが、一部は Phase 移行時のコメントアウトのまま残っている。archive 構成が固まった現時点で、不要なコメントアウト行を整理できる。

---

## E. 精査結果サマリ

| # | 分類 | 内容 | 優先度 | 影響 |
|---|---|---|---|---|
| A-1 | 未完了 | `global/_gutter.scss` 廃止未達 — 6 箇所の mixin 使用が残存 | 低 | 動作に問題なし。計画との不一致 |
| A-2 | デッドコード | `global/_variables.scss` に未使用変数 11 個 | 低 | 削除可能。ビルドに影響なし |
| A-3 | デッドコード | `global/_transition.scss` 全体がデッドコード | 低 | 削除可能 |
| B-1 | 計画乖離（許容） | body font-family のハードコード | — | パイプライン制約上、計画側が誤り |
| B-2 | 欠落 | `--form-focus-color` 未定義 | 低 | 参照箇所ゼロのため実害なし |
| B-3 | 一貫性 | `--bdrs` / `--bdrs-lg` が base layer 外 | 低 | 動作に問題なし。集約すべき |
| B-4 | 意図的除外 | UM / WP Instagram Feed の vendor 移行 | — | build graph 外。active 化時に対応 |
| C-1 | 二重管理 | 6 種の値が SCSS と config に重複定義（SCSS 側参照ゼロ） | 低 | SCSS 側削除で解消 |
| C-2 | 二重管理 | breakpoints / spacing / zIndex が SCSS と config に重複 | — | SCSS 参照が残っており削除不可 |
| D-1 | コメント | `global/_index.scss` の旧パスコメント | 最低 | 実害なし |
| D-2 | 未使用import | `global/_gutter.scss` の `@use` | 最低 | A-1 と一括対応 |
| D-3 | コメント | `style.scss` のコメントアウト行整理 | 最低 | 実害なし |

---

## F. 対応方針の提案（未着手・判断待ち）

上記の残件は全て「動作に問題がないが、計画との不一致 or デッドコード」に分類される。
対応するかどうか、するならどこまでやるかはユーザーの判断に委ねる。

大きく 3 段階に分けられる:

### F-1. デッドコード削除のみ（最小対応）

- `_variables.scss` から未使用変数を削除
- `_transition.scss` を削除
- ビルド確認

### F-2. F-1 + CSS 変数の集約

- `--bdrs` / `--bdrs-lg` を `_tailwind-base-layer.scss` の `:root` に移動
- `--form-focus-color` を `.p-form` に追加するか、不要として正式に除外するか決定

### F-3. F-2 + gutter mixin の廃止

- 6 箇所の `@include g.gutter` を直接記述に展開
- `global/_gutter.scss` を削除
- `global/_index.scss` から `@forward "_gutter"` を除去

---

## G. PHP テンプレートの検証結果

旧クラス名の残存を全 PHP ファイルで検索した結果:

| 旧クラスパターン | 残存数 |
|---|---|
| `mt__` | 0 |
| `display__` | 0 |
| `l-row` | 0（HTML コメント内に 1 件のみ） |
| `c-col` | 0 |
| `l-grid` | 0 |
| `c-grid` | 0 |
| `c-gutter` | 0 |
| `hide__` | 0 |
| `fz__` | 0 |

Phase 2・3 の PHP 置換は完了している。
