# SCSS → Tailwind CSS 移行計画書

> 作成日: 2026-02-26
> 対象: `wp-thm/src/scss/` 配下の全 SCSS 資産
> 参照: [事前調査レポート](./tailwind-migration-analysis.md) / [グリッド戦略ディスカッション](./grid-strategy-discussion.md)

---

## 0. 移行の大原則

| # | 原則 | 詳細 |
|---|---|---|
| 1 | **コンポーネントクラスはそのまま維持** | `.c-button`, `.p-header` 等のまとまったクラスは Tailwind ユーティリティに分解しない。`@layer components` でクラスごと定義する |
| 2 | **SCSS 計算関数は維持** | `g.rem()`, `g.get_vw()`, `g.get_lh()` 等は「px 値を入れてコンパイル後に rem/vw 換算される」人間に優しい仕組み。そのまま利用し続ける |
| 3 | **ユーティリティ系クラスは Tailwind に置換（値は現状維持）** | `mt__4--sm` → `sm:mt-4` 等、クラス名は Tailwind 記法に変更する。**ただし `mt-4` が意味する実際の値（32px）は現状の `$space_values` を維持する**。Tailwind デフォルトの spacing（`mt-4` = 16px）には合わせない。`tailwind.config.js` の `theme.spacing` で現状のスケールを再定義する |
| 4 | **グリッドは B案（Tailwind 完全移行）** | `.c-col__md--10` → `md:col-span-10` 等、PHPテンプレートも含めて Tailwind ネイティブに書き換え |
| 5 | **ベンダー CSS は最低限の差分** | FontAwesome, Swiper, Micromodal 等のライブラリ CSS は極力変更を加えず、SCSS 変数の入れ替え程度に留める |
| 6 | **CSS 変数は維持・活用** | `--gutter`, `--unit`, `--space` 等の vw ベース変数は `:root` に残し、Tailwind から `var()` で参照する |
| 7 | **クラス名変更ログを逐次記録** | クラス名が変更になったものは全て [_class-rename-log.md](./_class-rename-log.md) に逐一記録する。PHP テンプレート修正時のリファレンスとして使用する。各 Phase の作業中にリアルタイムで更新すること |

### 0.1 計画作成時の論点設定ルール（チャットエージェント向け注意）

**馬鹿な勝手な思い込みからくる計画の雑さを排除しろ**

- 何を問題にするかは、一般論ではなく実コードの影響で決めること。「Tailwind 移行だからこうすべき」「モダンだからこう直すべき」という抽象論を論点にしない
- 自分の好み・慣れ・思い込みを設計上の必須条件として扱わないこと。「普通はまとめる」「普通は分ける」「一箇所に寄せるべき」などの先入観だけで論点を立てない
- 「依存があるから危険」といった便利語で済ませない。どのファイルが、どの mixin / 変数 / placeholder / import を使い、それを変えるとどのセレクタや出力がどう壊れるのかまで具体化して書くこと
- 「整理」「統一」「集約」を目的化しない。まず現在の設計意図と使用実態を確認し、そのうえで本当に問題がある場合だけ論点化すること
- 初期の調査メモと後続の確定方針が食い違う場合は、後続の議論ログ・計画書本文・進捗記録を優先する。古い分析メモだけを根拠に現行方針を上書きしない
- ある実装パターンを見つけたとき、それを即「アンチパターン」「統一すべき対象」と決めつけないこと。複数箇所にあること、分散していること、SCSS 機能を使っていること自体は問題の根拠にならない
- 計画書で未確定事項を挙げるときは、「何を確認すれば確定できるか」までセットで書くこと。曖昧な懸念だけを列挙して終わらせない

---

## 1. 基盤構築

### 1.1 tailwind.config.js の作成

事前調査レポート §1 のマッピング表に基づき、以下を定義:

- `theme.screens` — カスタムブレークポイント (576/811/1025/1280/1366)
- `theme.colors` — ブランドカラー、グレースケール
- `theme.spacing` — **現状の `$space_values` の値をそのまま採用**（Tailwind デフォルトの spacing スケールは使わない。`mt-4` = 32px のように、現状の使い勝手を維持する）
- `theme.zIndex` — micromodal/header/footer/swiper/default
- `theme.fontFamily` — sans/serif/mono
- `theme.borderRadius` — デフォルト 6px
- `theme.extend.padding` — ガター用カスタム値（`gutter-1` 〜 `gutter-3` の倍率 + `gutter-row`）
- `theme.extend.gap` — カードグリッド列間隔用（`grid-gutter`）
- `theme.extend.maxWidth` — コンテナ用 (960/1152/1200/1260)

### 1.2 @layer base の構築

- `:root` に CSS 変数を定義（`--gutter`, `--unit`, `--space`, `--gutter-row` + ブレークポイント別）
- `:root` にカラー変数を定義（§1.4 カテゴリ A の全変数）
- `_reboot.scss` のカスタム部分を `@layer base` に移植（body ルール, a タグ, ::selection 等。body ルールの値は §1.4 カテゴリ B-body 参照）
- `destyle.css` を **削除**（Tailwind Preflight で代替）

### 1.3 SCSS 計算関数の維持

`global/_calc.scss` の全関数（`g.rem`, `g.get_vw`, `g.px-to-per`, `g.px-to-vw`, `g.get_lh`, `g.diff_lh`, `g.strip-unit`）を Tailwind 環境でも利用可能な状態で維持する。

### 1.4 SCSS 変数の変換カテゴリ（確定）

foundation 配下の SCSS 変数を性質ごとに分類し、変換先を決定した。
全変数の詳細は [_variable-inventory.md](./_variable-inventory.md) を参照。

#### カテゴリ A: `:root` CSS 変数に一括移行

**対象**: `_variables-color.scss` の全変数。サイト全体で広く参照されるブランドカラー・グレースケール。

| SCSS 変数 | CSS 変数 | 値 | 備考 |
|---|---|---|---|
| `$clr1` | `--clr1` | `#4FBA43` | |
| `$clr2` | `--clr2` | `#9BD22D` | |
| `$clr3` | `--clr3` | `#725907` | |
| `$clr4` | `--clr4` | `#B69941` | |
| `$clr5` | `--clr5` | `#f6f0dd` | |
| `$clr-prim-green` | `--clr-prim-green` | `#41c45d` | |
| `$black` | `--black` | `#222` | |
| `$clrg50` 〜 `$clrg900` | `--clrg50` 〜 `--clrg900` | ハードコード値 | `color.scale()` の計算結果を事前算出 |
| `$gray` | `--gray` | = `--clrg700` | |
| `$grd1` | `--grd1` | `linear-gradient(...)` | |
| `$grd2` | `--grd2` | `linear-gradient(...)` | |
| `$link-color` | `--link-color` | = `--clrg800` | |
| `$link-hover-color` | `--link-hover-color` | ハードコード値 | `color.scale()` の計算結果を事前算出 |

> `color.scale()` で定義されている `$clrg50`〜`$clrg900` と `$link-hover-color` は、
> ビルド済み CSS から計算結果のカラーコードを抽出してハードコードする。
> 事前調査レポート §1.4 に算出済みの値あり。

#### カテゴリ B-config: `tailwind.config.js` に統合

**対象**: Tailwind がユーティリティを生成する仕組みに自然に収まる変数。

| SCSS 変数 | tailwind.config.js | 備考 |
|---|---|---|
| `$grid-breakpoints-*` | `theme.screens` | §1.1 に記載済み |
| `$container-max-*` | `theme.extend.maxWidth` | §1.1 に記載済み |
| `$font-family-sans-serif/serif/mono` | `theme.fontFamily` | §1.1 に記載済み |
| `$border-radius` | `theme.borderRadius` | §1.1 に記載済み |
| `$transition-base` | `theme.transitionDuration` + CSS 変数 | §1.1 に記載済み |
| `$space_values` | `theme.spacing` | §1.1 に記載済み |
| `$space_values_with_clamp` | **廃止** | `_margin.scss` でもコメントアウト済み。削除 |
| `$layout_zindex` | `theme.zIndex` | §1.1 に記載済み |
| `$grid-columns` | 不要（Tailwind の `grid-cols-12` で対応） | |

#### カテゴリ B-body: `@layer base` の body ルールに直接記述

**対象**: `_reboot.scss` の body 要素でのみ使用される変数。1箇所のみの使用のため SCSS 変数・CSS 変数ともに不要。

```css
@layer base {
  body {
    background-color: #fff;              /* 旧 $body-bg */
    color: var(--black);                 /* 旧 $body-color → カテゴリ A の CSS 変数を参照 */
    font-family: theme('fontFamily.sans'); /* 旧 $font-family-base → B-config の値を参照 */
    font-size: 16px;                     /* 旧 $font-size-base */
    font-weight: normal;                 /* 旧 $font-weight-base */
    line-height: 1.5;                    /* 旧 $line-height-base */
  }
}
```

> **`$body-bg`**: `_tables.scss` でも 2 箇所使用されているが、テーブル背景が body と連動する
> 設計意図ではないため `#fff` をハードコードする。

#### カテゴリ C: コンポーネントスコープの CSS 変数

**対象**: `_variables-form.scss` の全変数。`project/_form.scss` でほぼ独占的に使用され、変数同士が参照し合う（例: `$custom-select-border-color: $input-border-color`）。

**方針**: `.p-form` にスコープした CSS 変数として定義。`:root` には置かない。

```css
@layer components {
  .p-form {
    --form-padding-x: 0.625rem;
    --form-padding-y: 0.625rem;
    --form-line-height: 1.25;
    --form-bg: #fff;
    --form-color: var(--clrg700);
    --form-border-color: var(--clrg500);
    --form-border-width: 1px;
    --form-border-radius: 6px;
    --form-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    --form-focus-color: var(--clr1);
    --form-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
  }
}
```

> **利点**: input と select が同じトークンを参照するため、共通値の関連性が維持される。
> `:root` を汚さず、フォームの仕様変更が `.p-form` 内で完結する。
>
> **`$input-color-placeholder`**: `_reboot.scss` の placeholder スタイルで使用。
> `@layer base` で直接 `var(--clrg500)` を参照する（フォームスコープに入れない）。
>
> **`$cursor-disabled`**: `_reboot.scss` で 1 箇所のみ使用。`cursor: not-allowed` をハードコード。

> 廃止するファイル: `foundation/_variables-color.scss`, `_variables-form.scss`, `_variables.scss` → §5.5 に一括記載
>
> **注意**: `_variables.scss` 内の `get_zindex()` 関数と `$layout_zindex` マップは
> SCSS 関数維持方針（§1.3）により、`tailwind.config.js` の `theme.zIndex` と併用する形で維持する。

---

## 2. ユーティリティ層の移行

### 2.1 対象ファイル（判定 A: Tailwind utility で直接代替）

| ファイル | 生成クラス数 | Tailwind 代替 |
|---|---|---|
| `utility/_display.scss` | 18 | `hidden`, `block`, `inline-block` + `sm:` 等 |
| `utility/_flex.scss` | ~108 | `justify-*`, `items-*`, `self-*`, `order-*` + responsive |
| `utility/_font.scss` | 18 | `text-center/left/right`, `font-*`, `text-*` + responsive |
| `utility/_margin.scss` | ~150 | `mt-*` + responsive |
| `utility/_padding.scss` | — | `pt-0`, `pb-0` 等（コメントアウト済み） |
| `utility/_visibility.scss` | 12 | `hidden`, `sm:hidden` 等 |
| `utility/_responsive-embed.scss` | — | `aspect-video`, `aspect-square` |

### 2.2 作業内容

1. PHP テンプレートのクラス名を Tailwind に一括置換（対応表に基づくスクリプト）
2. **変更したクラス名を全て [_class-rename-log.md](./_class-rename-log.md) に記録**
3. 上記 SCSS ファイルを削除
4. 全ページで表示確認

### 2.3 クラス名変換表（未定 — 別途議論）

> [!NOTE]
> ユーティリティクラスの具体的な変換表（旧クラス名 → Tailwind クラス名）は Phase 2 開始前に確定する。
> 例: `.mt__4--sm` → `sm:mt-4` or `sm:mt-[32px]`？ spacing スケールとの整合性を要議論。

---

## 3. グリッド・レイアウト層の移行

### 3.1 結論: Flex と CSS Grid の使い分け

| 用途 | 方式 | Tailwind クラス構成 |
|---|---|---|
| **ページレイアウト・カラム分割** | **Flex** | `container mx-auto flex flex-wrap justify-center` + `w-{n}/12` |
| **リピートグリッド（カード等）** | **CSS Grid** | `grid grid-cols-{n}` + gap（§ガター議論で決定） |
| **コンポーネント内部** | **FLOCSS 維持** | `.c-button`, `.p-header` 等（§4） |

**廃止するクラスと代替:**

| 廃止 | 代替 | 備考 |
|---|---|---|
| `.l-row` | `flex flex-wrap justify-center` | ユーティリティ直書き |
| `.l-row--container` | `container mx-auto flex flex-wrap justify-center` | 同上。`container` は `tailwind.config.js` で max-width をカスタム |
| `.c-col--12` | `w-full` | Flex 子要素の幅指定 |
| `.c-col--11` | `w-11/12` | 同上 |
| `.c-col__md--8` | `md:w-8/12` | レスポンシブ幅 |
| `.c-col__lg--10` | `lg:w-10/12` | 同上。`w-{n}/12` は Tailwind に 1〜11 まで全て組み込み |
| `.l-grid` | `grid` | CSS Grid。全4箇所、すべて `ul` 要素 |
| `.c-grid--1` | `grid-cols-1` | CSS Grid の列数指定 |
| `.c-grid__lg--3` | `lg:grid-cols-3` | レスポンシブ列数 |

### 3.2 議論の経緯（参考）

> **なぜ CSS Grid 一本化ではなく Flex と Grid を使い分けるか？**
>
> 当初は `.l-row` を CSS Grid (`grid grid-cols-12 + col-span-{n}`) で置き換える方針だったが、
> 以下の問題が判明して方針を変更した:
>
> 1. **CSS Grid は中央寄せできない** — `grid-template-columns: repeat(12, 1fr)` では
>    12列が常に 100% を埋めるため、`justify-content: center` が効かない。
>    `col-span-8` の要素はカラム 1〜8 に固定され、「余白を左右均等配分」ができない。
>    Flex の `justify-content: center` でのみ実現可能。
>
> 2. **日本のデザイン慣習** — カラム分割でシングルカラムでも中央揃えをデフォルトにする設計方針。
>    現状の `.l-row` に `justify-content: center` が入っていたのはこのため。
>
> 3. **Tailwind に `w-{n}/12` がある** — `w-7/12`, `w-10/12` 等の 12分割幅ユーティリティが
>    Tailwind 組み込みで全て揃っているため、Flex + `w-{n}/12` で 12カラムグリッドの挙動を完全再現可能。
>
> 4. **`.l-grid` は CSS Grid に適合** — `.l-grid` は元々 `justify-content: flex-start`（左寄せ）で、
>    使用箇所は**全4箇所すべて `ul` 要素のカードグリッド**。中央寄せ不要なので CSS Grid で問題なし。
>    さらに CSS Grid 化でネガティブマージンハックが不要になる。

### 3.3 テンプレートの変換例

```html
<!-- ■ ページレイアウト: 現状 -->
<div class="l-row--container c-gutter__row">
  <div class="c-col--12 c-col__sm--8 c-col__md--10">
    コンテンツ
  </div>
</div>

<!-- ■ ページレイアウト: 移行後（Flex） -->
<div class="container mx-auto flex flex-wrap justify-center （ガター別議論）">
  <div class="w-full sm:w-8/12 md:w-10/12">
    コンテンツ
  </div>
</div>

<!-- ■ カードグリッド: 現状 -->
<ul class="l-grid c-grid--1 c-grid__lg--3 c-likepost__list">
  <li>カード1</li>
  <li>カード2</li>
  <li>カード3</li>
</ul>

<!-- ■ カードグリッド: 移行後（CSS Grid） -->
<ul class="grid grid-cols-1 lg:grid-cols-3 （gap別議論） c-likepost__list">
  <li>カード1</li>
  <li>カード2</li>
  <li>カード3</li>
</ul>
```

### 3.4 c-gutter（ガター）の移行方針（確定）

#### 方針: 全て Tailwind カスタムユーティリティに移行

`@layer components` でクラスを維持するのではなく、`theme.extend.padding` / `theme.extend.gap` にカスタム値を定義して Tailwind ユーティリティとして使う。PHP テンプレートのクラス名を書き換える。

#### tailwind.config.js のカスタム値

```js
theme: {
  extend: {
    padding: {
      // コンテナ両サイド余白 + 方向指定ガター
      'gutter-1':   'calc(var(--gutter) * 1)',
      'gutter-1.5': 'calc(var(--gutter) * 1.5)',
      'gutter-2':   'calc(var(--gutter) * 2)',
      'gutter-3':   'calc(var(--gutter) * 3)',
      'gutter-row': 'var(--gutter-row)',
    },
    gap: {
      // カードグリッド列間隔（l-grid 後継）
      'grid-gutter': 'calc(var(--gutter) * 2)',
    }
  }
}
```

> **padding の倍率値について**: `gutter-1` 〜 `gutter-3` の数字は CSS 変数 `--gutter` への倍率。
> `--gutter` 自体が vw ベースで BP ごとに変化するため、倍率の切り替え + ベース値の変化の両方が効く。
>
> **gap が `gutter × 2` である理由**: 旧方式はネガティブマージン + 各カラムのパディング（`var(--gutter)` × 両側）で
> カラム間隔 = `var(--gutter) * 2` だった。CSS Grid gap に変換する際、同じ間隔を維持するため `× 2`。
>
> **gap-y を使わない理由**: 縦方向の余白はカード内容（画像高さ、テキスト行数、line-height）で見え方が変わるため、
> 個別のマージンで制御する方が柔軟。`gap-x` のみ使用。

#### クラス変換表

| 旧クラス | 新 Tailwind | 備考 |
|---|---|---|
| `.c-gutter` | `px-gutter-row md:px-0` | コンテナ両サイド余白。md で解除 |
| `.c-gutter__row` | `px-gutter-row xl:px-0` | コンテナ両サイド余白。xl で解除 |
| `.c-gutter__post` | `md:px-gutter-row xl:px-0` | md で開始、xl で解除 |
| `.c-gutter__sm--left` | `sm:pl-gutter-2 md:pl-gutter-3` | 方向指定 + 倍率切り替え |
| `.c-gutter__sm--left-half` | `sm:pl-gutter-1 md:pl-gutter-1.5` | 同上（half = 半分の倍率） |
| `.c-gutter__md--left` | `md:pl-gutter-3` | md 以上のみ |
| `.c-gutter__md--left-half` | `md:pl-gutter-1.5` | 同上 |
| `.c-gutter__sm--right` | `sm:pr-gutter-2 md:pr-gutter-3` | 右方向（left と対称） |
| `.c-gutter__sm--right-half` | `sm:pr-gutter-1 md:pr-gutter-1.5` | 同上 |
| `.c-gutter__md--right` | `md:pr-gutter-3` | 同上 |
| `.c-gutter__md--right-half` | `md:pr-gutter-1.5` | 同上 |

#### CSS Grid（l-grid 後継）の gap

`.l-grid` のネガティブマージン + パディング方式は CSS Grid `gap-x` に置き換え:

```html
<!-- 旧 -->
<ul class="l-grid c-grid--1 c-grid__lg--3">

<!-- 新 -->
<ul class="grid grid-cols-1 lg:grid-cols-3 gap-x-grid-gutter">
```

#### 命名規約

- **語順**: Tailwind 規約の `{property}-{value}` 順に統一（例: `pl-gutter-2`、`gap-x-grid-gutter`）
- **padding のキー名**: `gutter-N`（N = 倍率）、`gutter-row`（CSS変数名由来）
- **gap のキー名**: `grid-gutter`（用途由来。padding の gutter 系と名前空間が重複しない）

> 廃止するファイル: `component/_gutter.scss`, `global/_gutter.scss` → §5.5 に一括記載

### 3.5 レイアウトパターン

| パターン | 対象ファイル | 方針 |
|---|---|---|
| split (2カラム) | `layout/_content.scss` | ✅ **flexbox のまま維持**。`@layer components` にそのまま移行 |
| split reverse | 同上 | 同上 |
| float 回り込み | 同上 | ✅ **削除完了**。`.l-float__*` クラス、clearfix mixin、`_reboot.scss` の `.clearfix` を全て削除。`front-page.php` の HTML も削除済み |

> [!NOTE]
> 当初の計画では split を CSS Grid に「再定義」、float を CSS Grid で「完全置換（モダン化）」と記載されていた。
> これは AI が「Tailwind 移行 = モダン化」と解釈して勝手に方針判断したもの。
> split は flexbox で問題なく動作し、`@layer components` 内でもそのまま使える。
> float は使っていたコンテンツ自体を削除する判断となったため、CSS Grid への置換ではなく単純に削除で完了。


---

## 4. コンポーネント・プロジェクト層の移行

### 4.1 方針

**既存のクラス名とクラスのまとまりをそのまま維持**して `@layer components` に定義し直す。
Tailwind ユーティリティに分解しない。SCSS 計算関数 (`g.rem`, `g.get_vw`) もそのまま利用。

### 4.2 対象ファイル

| 層 | ファイル数 | 主要ファイル |
|---|---|---|
| component/ | 16 自作 | `_button`, `_header`, `_footer`, `_post`, `_tab`, `_toggle`, `_typ` 等 |
| project/ | 10 | `_header`, `_footer`, `_form`, `_post`, `_style`, `_typ` 等 |
| layout/ | 4 | `_content`, `_header`, `_footer`（`_grid` は Phase 3 で対応済み）|

### 4.3 作業内容

1. 各 SCSS ファイルを `@layer components` ブロック内に移行
2. SCSS 変数参照（`$clr1` 等）→ CSS 変数参照（`var(--clr1)`）に変換（§1.4 カテゴリ A に基づく）
3. ブレークポイント mixin → ✅ **変更不要**（`@media #{g.$md}` も `@include media-breakpoint-up(md)` も SCSS コンパイル時に `@media (min-width: ...)` に展開される。`@layer components` 内でそのまま動作するため `@screen md` への変換は不要）
4. `@extend %placeholder` → ✅ **変更不要**（SCSS コンパイルが Tailwind 処理より先に実行されるため `@layer` 内でもそのまま動作。全使用箇所が同一ファイル内で完結していることを実コードで確認済み）
5. `color.scale()` → ✅ **完了**（全33箇所をビルド済みカラーコードでハードコード済み。`_variables-color.scss` の変数定義は §1.4 で CSS 変数に移行予定）
6. JS 状態クラス（`.js-active`, `.js-open` 等）に基づくスタイルはそのまま維持
7. `@include g.hover` mixin → ✅ **変更不要**（`_hover.scss` をそのまま残す。`@layer base` の `a:hover` は直接記述で確定 — [_reset-diff-inventory.md](./_reset-diff-inventory.md) L426-431 参照）

> [!IMPORTANT]
> **#3, #4, #7 が「変更不要」となった経緯:**
>
> 当初の計画では「`@screen md` への変換」「`@extend` の展開」「`hover mixin` の Tailwind バリアント化」が作業項目として記載されていた。
> これは AI が「Tailwind に移行するなら全て Tailwind 式に書き換えるべき」という一般論で計画を書いたため。
> **実コードを確認せずにリスクとして記載していた。**
>
> 実際には **SCSS コンパイルは Tailwind の処理より先に実行される。**
> そのため `@media #{g.$md}`, `@extend`, `@include g.hover` などの SCSS 機能は、
> `@layer components` / `@layer base` の中でもそのまま動作する。
> 実コード確認（2026-03-13）で全使用箇所が問題なく動作することを検証済み。
>
> また、`@layer components` 内で `@media` クエリを使ったクラス定義は
> **Tailwind が公式に想定している使い方**であり、独自ルールではない。
> WordPress テーマやデザイン重視のサイトでは、
> コンポーネントクラスの中でレスポンシブ対応する（HTML 側で `sm:` `md:` 等のバリアントを使わない）パターンが一般的。

---

## 5. ベンダー CSS の整理

### 5.1 方針: 極力変更を加えず、最低限の差分のみ

| ベンダー | 方針 | 作業 |
|---|---|---|
| FontAwesome 5.14.0 | そのまま維持 | SCSS 変数 → CSS 変数に置換のみ |
| Swiper 8.3.2 | そのまま維持 | SCSS 変数 → CSS 変数に置換のみ |
| Micromodal | そのまま維持 | SCSS 変数 → CSS 変数に置換のみ |
| scroll-hint | そのまま維持 | SCSS 変数 → CSS 変数に置換のみ |
| Ultimate Member | そのまま維持 | `$clrg500` 等 → CSS 変数に置換のみ |
| WP Instagram Feed | そのまま維持 | `$clr1` 等 → CSS 変数に置換のみ |

### 5.5 廃止・統合決定ファイル一覧

各セクションで個別に決定した廃止ファイルを一覧化。

#### foundation 変数ファイル（§1.4 で決定）

| ファイル | 理由 |
|---|---|
| `foundation/_variables-color.scss` | 全変数が `:root` CSS 変数に移行 |
| `foundation/_variables-form.scss` | 全変数が `.p-form` スコープ CSS 変数に移行 |
| `foundation/_variables.scss` | 全変数が config / `@layer base` / SCSS 関数に分散（`get_zindex()` は維持） |

#### ガター関連（§3.4 で決定）

| ファイル | 理由 |
|---|---|
| `component/_gutter.scss` | 全クラスが Tailwind カスタムユーティリティに移行 |
| `global/_gutter.scss` | gutter / gutter_row mixin が不要に |

#### mixin・ユーティリティ（本議論 2026-02-27 で決定）

| ファイル | 使用状況 | 理由 |
|---|---|---|
| `utility/_blockquote.scss` | `style.scss` で `@use` のみ | 他ファイルへの影響なし。FA mixin 使用あり（※補足） |
| `mixins/_table-row.scss` | 使用箇所ゼロ | 死にコード |
| `mixins/_zindex.scss` | 使用箇所ゼロ | 関数版 `get_zindex()` のみ使用されており mixin 版は不要 |
| `mixins/_placeholder.scss` | `_search.scss` で 1 箇所 | ✅ **削除済み**。`_search.scss` を `::placeholder` 直書きに変更済み |
| `mixins/_clearfix.scss` | `layout/_content.scss` で 2 箇所 | ✅ **削除済み**。float 廃止に伴い不要。§3.5 参照 |

> **blockquote 補足**: 中で FontAwesome mixin（`m.fa-icon`）と `g.unicode(f10d)` を使用。
> blockquote スタイル自体を別の場所で残す場合は要考慮。
>
> **placeholder 補足**: `_search.scss` が `@layer components` に移行する際に
> `::placeholder { color: var(--clrg500); }` を直接記述すれば mixin 不要。

---

## 6. HTML テンプレート（PHP）の一括置換

### 6.1 対象

WordPress テーマの PHP テンプレートファイル（`*.php`）全体。

### 6.2 置換の種類

| 種類 | 対象クラス | 置換方法 | 優先度 |
|---|---|---|---|
| ユーティリティ | `.display__*`, `.mt__*`, `.tac` 等 | 変換表に基づく一括置換スクリプト | Phase 2 |
| グリッドラッパー | `.l-row`, `.l-row--container`, `.l-grid` | Tailwind ユーティリティに置換（§3.1） | Phase 3 |
| グリッド子要素 | `.c-col__*`, `.c-grid__*` | パターン置換（§3.3） | Phase 3 |
| ガター | `.c-gutter`, `.c-gutter__row`, `.c-gutter__post`, `.c-gutter__*--left/right` | カスタムユーティリティに置換（§3.4） | Phase 3 |
| コンポーネント | `.c-button`, `.p-header` 等 | **変更不要** | — |
| JS 状態クラス | `.js-active`, `.is-open` 等 | **変更不要** | — |

### 6.3 変換テーブルと変更ログ

> [!IMPORTANT]
> **spacing スケールは決定済み**: `theme.spacing` に現状の `$space_values` をそのまま設定するため、`mt__4` → `mt-4`（= 32px のまま）となる。Tailwind デフォルトの spacing には合わせない。

以下の命名規則は未確定（別途議論）:
- グリッド: `c-col__md--10` → `md:w-10/12` 等は §3.1 で確定
- ガター: `theme.extend.padding` + `theme.extend.gap` のカスタムユーティリティで全て対応（§3.4 で確定）

> [!NOTE]
> **変更ログ**: クラス名の変更は全て [_class-rename-log.md](./_class-rename-log.md) に逐一記録する。
> このファイルは PHP テンプレートの一括置換スクリプトの入力データとしても使用する。
> 各 Phase の作業中にリアルタイムで更新し、旧クラス名 → 新クラス名の完全な対応表として機能させる。

---

## 7. 実行順序とスケジュール

```
Phase 1: 基盤構築（§1）
  ├── tailwind.config.js 作成
  ├── @layer base に :root CSS 変数 + reboot 移植
  ├── SCSS 計算関数の維持確認
  └── Tailwind ビルドパイプラインの構築・動作確認
       ↓
Phase 2: ユーティリティ層の移行（§2）
  ├── 変換テーブルの確定（命名規則の議論）
  ├── PHP テンプレートのユーティリティクラス一括置換
  ├── _class-rename-log.md に変更を記録
  ├── SCSS ユーティリティファイルの削除
  └── 全ページ表示確認
       ↓
Phase 3: グリッド・レイアウト層の移行（§3）
  ├── PHP テンプレートの l-row/l-grid ラッパーを Tailwind ユーティリティに置換
  ├── PHP テンプレートの c-col/c-grid 子要素クラスを置換（~80箇所）
  ├── PHP テンプレートの c-gutter クラスをカスタムユーティリティに置換（~55箇所）
  ├── _class-rename-log.md に変更を記録
  ├── 旧 SCSS ファイルの削除（layout/_grid, component/_gutter, global/_gutter）
  └── ガター距離のピクセル検証 + 全ページ表示確認
       ↓
Phase 4: コンポーネント・プロジェクト層の移行（§4）
  ├── @layer components に BEM コンポーネントを移行（クラス名維持）
  ├── SCSS 変数 → CSS 変数の置換
  ├── ブレークポイント mixin → 変更不要（確定済み）
  ├── color.scale() → ハードコード変換（完了済み）
  ├── @extend → 変更不要（確定済み）
  └── 全ページ表示確認
       ↓
Phase 5: ベンダー CSS の整理（§5）
  ├── SCSS 変数依存を CSS 変数に置換
  ├── 不要ファイルの削除
  └── 最終確認
```

---

## 8. リスク管理

| # | リスク | 影響度 | 対策 |
|---|---|---|---|
| 1 | ブレークポイントのカスタム値 | ✅ **問題なし** | SCSS コンパイルで `@media (min-width: ...)` に展開されるため `@layer` 内でそのまま動作。`theme.screens` は Tailwind ユーティリティ（`sm:`, `md:` 等）を使う場合にのみ必要 |
| 2 | vw ベース CSS 変数ガター | 高 | `:root` に維持 + `theme.extend.padding`/`gap` でカスタムユーティリティ化（§3.4 で確定） |
| 3 | `color.scale()` の 25 箇所 | ✅ **完了** | 全 33 箇所を Sass コンパイル済みカラーコードでハードコードに置換済み |
| 4 | `@extend / %placeholder` | ✅ **問題なし** | SCSS コンパイルが先に実行されるため `@layer` 内でもそのまま動作。全使用箇所（約35箇所）が同一ファイル内で完結していることを実コード確認済み |
| 5 | hover mixin の `@media(hover:hover)` | ✅ **問題なし** | `_hover.scss` をそのまま残す。SCSS コンパイル時に処理されるため変更不要。39箇所が SCSS 固有の書き方（`color.scale()`, 子要素操作等）を含み Tailwind バリアントへの置き換え不適 |
| 6 | JS 状態クラスとの連携 | 低 | `@layer components` でそのまま維持。クラス名変更なし |
| 7 | destyle.css → Preflight の差分 | ✅ **調査完了** | [_reset-diff-inventory.md](./_reset-diff-inventory.md) に全差分を棚卸し済み。`@layer base` 補足コードも作成済み |

---

## 付録: 決定済み方針の根拠ドキュメント

| 方針 | 根拠 | 参照 |
|---|---|---|
| SCSS 計算関数の維持 | 人間が px でデザインカンプ通りに入力できる仕組みを保護 | 本会話 2026-02-25 |
| コンポーネントクラスの維持 | クラスのまとまりを崩さず `@layer components` で定義 | 本会話 2026-02-25 |
| グリッド B案 | ~80箇所の機械的置換、AI一括処理でリスク低、Tailwind 学習にも有効 | [grid-strategy-discussion.md](./grid-strategy-discussion.md) |
| ガター Tailwind ユーティリティ化 | `theme.extend.padding`/`gap` でカスタムユーティリティ定義。全 c-gutter クラスを廃止しテンプレート側で Tailwind 記法に置換 | 本会話 2026-02-26 |
| SCSS 変数の変換カテゴリ | カテゴリ A（ブランドカラー）→ CSS 変数、B-config → tailwind.config.js、B-body → @layer base 直接記述、C（フォーム）→ コンポーネントスコープ CSS 変数 | 本会話 2026-02-27 |
| mixin・ユーティリティ廃止 | blockquote, table-row, zindex, placeholder の 4 ファイル廃止（§5.5 に一覧） | 本会話 2026-02-27 |
| ベンダー CSS 最低限差分 | ライブラリ CSS に手を入れるリスクを回避 | 本会話 2026-02-25 |
| リセットCSS差分調査 | destyle.css + _reboot.scss vs Tailwind Preflight の全差分を棚卸し。`@layer base` 補足コード作成済み | [_reset-diff-inventory.md](./_reset-diff-inventory.md) 2026-03-08 |
| @extend / %placeholder | 実コード確認で全使用箇所が同一ファイル内完結。SCSS コンパイルが先に走るため `@layer` 内でもそのまま動作。変更不要 | 2026-03-13 |
| hover mixin | `_hover.scss` をそのまま残す。SCSS コンパイル時に処理されるため変更不要 | 2026-03-13 |
| ブレークポイント mixin | `@media #{g.$md}` 等はそのまま残す。SCSS コンパイルで展開されるため `@layer` 内で動作。`@screen md` への変換は不要。`@layer components` 内での `@media` 使用は Tailwind 公式に想定された使い方 | 2026-03-13 |
| `color.scale()` ハードコード化 | 全 33 箇所を Sass コンパイル済みの hex 値で置換。`_variables-color.scss` の定義は §1.4 で CSS 変数に移行予定 | 2026-03-13 |
