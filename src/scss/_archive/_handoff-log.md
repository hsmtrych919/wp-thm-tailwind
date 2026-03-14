# SCSS → Tailwind CSS 移行: 議論ログ＆引き継ぎドキュメント

> 作成日: 2026-02-27
> 目的: 新しいチャットに読み込ませて、前提条件をそのまま引き継いで議論を再開するためのファイル

---

## 必読ファイル（全て `wp-thm/src/scss/` 配下）

| ファイル | 内容 |
|---|---|
| `tailwind-migration-plan.md` | マスター計画書。全ての確定事項を反映 |
| `tailwind-migration-analysis.md` | 事前調査レポート（SCSS 全量調査） |
| `grid-strategy-discussion.md` | グリッド戦略のディスカッション結果 |
| `_variable-inventory.md` | SCSS 変数 71 個の棚卸しログ |
| `_class-rename-log.md` | クラス名変更ログ（テンプレート） |

---

## 第1チャットで確定した方針（計画書 §0 に反映済み）

1. **コンポーネントクラスはそのまま維持** — `.c-button` 等は Tailwind 分解せず `@layer components` で定義
2. **SCSS 計算関数は維持** — `g.rem()`, `g.get_vw()` 等はそのまま利用
3. **ユーティリティは Tailwind 置換（値は現状維持）** — `mt-4` = 32px のまま。Tailwind デフォルトに合わせない
4. **グリッドは B案（Tailwind 完全移行）** — PHP テンプレートも Tailwind ネイティブに
5. **ベンダー CSS は最低限の差分** — SCSS 変数→CSS変数の入れ替え程度
6. **CSS 変数は維持・活用** — `--gutter`, `--unit`, `--space` 等は `:root` に残す
7. **クラス名変更ログを逐次記録** — `_class-rename-log.md` に全て記録

### グリッド戦略（確定）

- **ページレイアウト（.l-row 系）→ Flex**: `flex flex-wrap justify-center` + `w-{n}/12`
  - CSS Grid の `grid-cols-12` は `justify-content: center` が効かない（12列が100%埋めるため）
  - 日本のデザインでは中央揃えが基本
- **カードグリッド（.l-grid 系）→ CSS Grid**: `grid grid-cols-{n}` + gap
  - 全4箇所、全て `ul` 要素。中央寄せ不要

---

## 第2チャット（本チャット）で確定した方針

### ガター移行（計画書 §3.4 に反映済み）

全て Tailwind カスタムユーティリティに移行。`@layer components` で維持ではない。

- `theme.extend.padding`: `gutter-1` 〜 `gutter-3`（倍率）+ `gutter-row`
- `theme.extend.gap`: `grid-gutter` = `calc(var(--gutter) * 2)`（旧ネガティブマージン方式と同じ間隔維持）
- 命名語順: Tailwind 規約の `{property}-{value}` 順（`pl-gutter-2`, `gap-x-grid-gutter`）
- gap-y は使わない（縦は個別マージン制御）
- 変換表: 全11クラス → 計画書 §3.4 に記載

### SCSS 変数の変換カテゴリ（計画書 §1.4 + `_variable-inventory.md` に反映済み）

foundation 3ファイル全71変数を仕分け:

| カテゴリ | 数 | 変換先 |
|---|---|---|
| A（ブランドカラー） | 22 | `:root` CSS 変数 |
| B-config | 20 | `tailwind.config.js` |
| B-body | 6 | `@layer base` body ルールに直書き（変数化不要） |
| C（フォーム） | 19 | `.p-form` スコープ CSS 変数 |
| ハードコード | 2 | placeholder色 → `var(--clrg500)` 直接、cursor → `not-allowed` |
| 廃止 | 1 | `$space_values_with_clamp` |

**重要な議論ルール**:
- 全部まとめて CSS 変数のような一括思考はしない。性質で仕分ける
- インライン展開はダメ（連動させたいプロパティの関連性が消える）
- カテゴリ C: `.p-form` にスコープすることで `:root` を汚さず、input/select の共通値を CSS 変数で関連付け

### 廃止・統合ファイル（計画書 §5.5 に一覧で反映済み）

**確定:**

| ファイル | 判断 |
|---|---|
| `foundation/_variables-*.scss` ×3 | 廃止確定（§1.4 で決定） |
| `component/_gutter.scss`, `global/_gutter.scss` | 廃止確定（§3.4 で決定） |
| `utility/_blockquote.scss` | 廃止確定（使用箇所なし。FA mixin 依存あり補足付き） |
| `mixins/_table-row.scss` | 廃止確定（死にコード） |
| `mixins/_zindex.scss` | 廃止確定（関数版 `get_zindex()` のみ使用中、mixin 版不要） |
| `mixins/_placeholder.scss` | 廃止確定（移行時に `::placeholder` 直書きに変更） |

**保留:**

| ファイル | 状態 |
|---|---|
| `mixins/_clearfix.scss` | ⚠️ §3.5 レイアウトパターンの議論結果に依存 |

---

## ⚠️ 未議論のまま計画書に記載されている項目

**前チャットの AI が先走って書いた、方針を勝手に決めた疑いのある項目。議論が必要。**

### 1. §3.5 レイアウトパターン

```
| split (2カラム)  | @layer components で CSS Grid に再定義 |
| split reverse   | 同上 |
| float 回り込み   | CSS Grid で完全置換（モダン化） |
```

- split を CSS Grid に「再定義」するか未議論（Flex のまま移すだけかもしれない）
- float を CSS Grid に「完全置換」するか未議論（float のまま維持する選択肢もある）
- **「モダン化」という方針判断を AI が勝手にしている**
- clearfix 廃止はこの判断に依存

### 2. §1.2 `destyle.css` の削除

- destyle.css → Tailwind Preflight 代替の妥当性が未議論
- リセット挙動の差分を確認していない

### 3. §4.3 コンポーネント移行の個別手法

| 項目 | 状態 |
|---|---|
| ブレークポイント mixin → `@screen md` 変換 | ⚠️ 手法が未議論 |
| `@extend %placeholder` → 展開 or `@apply` | ⚠️ どちらか未議論 |
| `color.scale()` → ハードコード | ⚠️ ハードコード vs `color-mix()` 未議論 |

### 4. §8 リスク管理の具体手法

| リスク | 状態 |
|---|---|
| `@extend / %placeholder` の展開方法 | ⚠️ 未議論 |
| hover mixin `@media(hover:hover)` の扱い | ⚠️ 未議論 |

---

## 議論のルール

- **計画のための計画の議論**段階。コード実行を急がない
- 確定したらまとめて計画書に反映。未確定事項は書き込まない
- **用意しているクラスは、もしも・念の為の場合もあるので、基本的にはキープ**
- **AI が勝手に方針を決めない。「モダン化」等の価値判断はユーザーと議論して決定する**
- 結論を急がず質問に正確に回答する

---

## 次に議論すべき項目（優先順）

1. §3.5 レイアウトパターン（split / float の扱い）
2. destyle.css 削除の妥当性
3. §4.3 コンポーネント移行の個別手法（@extend, @screen, color.scale）
4. hover mixin の扱い
5. clearfix 廃止（§3.5 の結果次第）
