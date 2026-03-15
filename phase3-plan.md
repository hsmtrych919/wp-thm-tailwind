# Phase 3 作業計画（確定版）

## 1. Phase 3 の目的

layout/_grid.scss と component/_gutter.scss を廃止し、PHP テンプレートのクラス指定を Tailwind 記法に置換する。

対象は以下の 3 カテゴリ:
- **l-row / c-col 系**（Flex ベースのページレイアウト・カラム分割）
- **l-grid / c-grid 系**（CSS Grid ベースのカードグリッド）
- **c-gutter 系**（コンテナ余白・方向指定ガター）

layout/_content.scss の l-split / l-container / l-blog / l-main は Phase 3 の主対象に含めない（後述）。

---

## 2. 調査結果サマリ

### 2.1 調査した SCSS ファイル

| ファイル | 内容 |
|---|---|
| `layout/_grid.scss` | l-row, l-row--container, c-col 系（make-grid-columns）, l-grid, c-grid 系（flex-grid-layout）の全定義 |
| `layout/_content.scss` | l-main, l-container, l-blog, l-split 系の全定義 |
| `component/_gutter.scss` | c-gutter 系の全定義 |
| `global/_gutter.scss` | gutter / gutter_row 共有 mixin（component/_gutter.scss および project/ 配下の複数ファイルが使用） |
| `style.scss` | @use の構成確認 |

### 2.2 調査した PHP ファイル

ルートレベル: front-page.php, footer.php, header.php, 404.php, index.php, archive.php, category.php, search.php, page-form-contact-chk.php, functions/admin_login.php

tmp/ 配下: page-recruit.php, page-form-contact.php, page-form-contact-thk.php, page-company.php, page-campaign.php, page-qa.php, page-salon-kitatoyama.php, page-sitemap.php, page-menu.php, page-privacy-policy.php, page-recruit-info.php, single-blog.php, single-staff.php, single-style.php, tmp-post.php, archive-post.php, archive-none.php, content/salon-detail.php, content/feed-post-grid.php, content/container-feed-post.php, content/sitemap.php, content/menu-cut.php, content/recruit-info-guiches.php

### 2.3 実使用が確認できた旧クラス群

#### l-row 系（PHP 実使用）

| クラス | 使用件数 | 備考 |
|---|---|---|
| `l-row--container` | 73 | 最頻出。ほぼ全テンプレートで使用 |
| `l-row` | 10 | container なしの flex wrapper |

#### c-col 系（PHP 実使用）

| クラス | 使用件数 | 備考 |
|---|---|---|
| `c-col--12` | 53 | w-full 相当 |
| `c-col--11` | 1 | |
| `c-col__sm--4` | 1 | |
| `c-col__sm--5` | 1 | |
| `c-col__sm--7` | 1 | |
| `c-col__sm--8` | 2 | |
| `c-col__sm--10` | 4 | |
| `c-col__sm--12` | 4 | |
| `c-col__md--4` | 4 | |
| `c-col__md--8` | 5 | |
| `c-col__md--10` | 9 | |
| `c-col__md--11` | 3 | |
| `c-col__md--12` | 7 | |
| `c-col__lg--3` | 1 | |
| `c-col__lg--4` | 1 | |
| `c-col__lg--5` | 1 | |
| `c-col__lg--7` | 3 | |
| `c-col__lg--8` | 1 | |
| `c-col__lg--9` | 8 | |
| `c-col__lg--10` | 1 | |
| `c-col__xl--6` | 2 | |
| `c-col__xl--8` | 4 | |
| `c-col__xl--9` | 1 | |
| `c-col__xl--10` | 4 | |

#### l-grid / c-grid 系（PHP 実使用）

| クラス | 使用件数 | 使用ファイル |
|---|---|---|
| `l-grid` | 4 | front-page.php (3), tmp/content/feed-post-grid.php (1) |
| `c-grid--1` | 4 | 同上（l-grid と常にペア） |
| `c-grid__sm--1` | 2 | front-page.php |
| `c-grid__sm--2` | 2 | front-page.php, tmp/content/feed-post-grid.php |
| `c-grid__md--2` | 1 | front-page.php |
| `c-grid__lg--3` | 2 | front-page.php |

#### c-gutter 系（PHP 実使用）

| クラス | 使用件数 | 備考 |
|---|---|---|
| `c-gutter__row` | 61 | 最頻出。l-row--container とペアで使用されることが大半 |
| `c-gutter__post` | 2 | tmp/single-blog.php, tmp/tmp-post.php |
| `c-gutter__sm--left` | 1 | tmp/page-recruit.php |
| `c-gutter__sm--right` | 1 | front-page.php |
| `c-gutter__md--left` | 2 | tmp/page-company.php, tmp/content/salon-detail.php |

### 2.4 未使用または要注意の旧クラス群

| クラス | 状態 | 判断 |
|---|---|---|
| `c-col--auto` / `c-col__BP--auto` | SCSS で定義あり、PHP 未使用 | 削除して問題なし |
| `c-col` (数字なし flex grow 版) | SCSS で定義あり、PHP 未使用 | 削除して問題なし |
| `c-gutter` (ベース、__row なし) | SCSS で定義あり、PHP 未使用 | 静的テンプレート上では未確認 |
| `c-gutter__sm--left-half` | SCSS で定義あり、PHP 未使用 | 削除して問題なし |
| `c-gutter__sm--right-half` | SCSS で定義あり、PHP 未使用 | 削除して問題なし |
| `c-gutter__md--left-half` | SCSS で定義あり、PHP 未使用 | 削除して問題なし |
| `c-gutter__md--right` | SCSS で定義あり、PHP 未使用 | 削除して問題なし |
| `c-gutter__md--right-half` | SCSS で定義あり、PHP 未使用 | 削除して問題なし |
| c-grid 未使用列数（3〜12 の大半） | SCSS ループで全列数生成、PHP では 1/2/3 のみ使用 | 削除して問題なし |

### 2.5 調査時に発見されたバグ（解消済み）

以下のバグは Phase 3 計画策定時の調査で発見されたが、**Phase 3 計画確定前に修正済み**。Phase 3 の作業項目には含めない。

| # | 内容 | 詳細 | 状態 |
|---|---|---|---|
| 1 | **`c-col_xl--8`** — アンダースコア 1 つ欠損 | `tmp/page-form-contact.php` で使用されていた。正しくは `c-col__xl--8` | ✅ 修正済み |
| 2 | **`l-split__content--pt`** — 存在しないクラス | `front-page.php` で使用されていた。SCSS には `l-split__content--mt`（margin-top）のみ定義 | ✅ 修正済み |

### 2.6 Phase 2 で既に Tailwind 記法に置換済みのクラスとの共存

Phase 2 で `jc__start` → `justify-start`、`jc__between` → `justify-between` 等が置換済み。これらが `l-row--container` と同じ行に混在している:

```
<div class="l-row--container c-gutter__row justify-start">
<div class="l-row--container c-gutter__row md:justify-between">
```

`l-row` の SCSS 定義には `justify-content: center` が含まれている。`justify-start` は Phase 2 で置換された Tailwind クラスで、`l-row` のデフォルト center を上書きしている。

Phase 3 で `l-row` → `flex flex-wrap justify-center` に置換する際、既存の `justify-start` / `justify-between` と `justify-center` が衝突する。**`justify-start` が付いている行では `justify-center` を省略する必要がある**。

### 2.7 progress.md と _class-rename-log.md の状態

- `progress.md`: Phase 2 完了、Phase 3 未着手で一致
- `_class-rename-log.md`: Phase 2 全ステータス ✅、Phase 3 セクションはテンプレートのみ（「Phase 3 開始時に記入」）
- 差分なし。整合性に問題なし

---

## 3. 対象ファイル一覧

### SCSS（削除対象）

| ファイル | 内容 | 削除タイミング |
|---|---|---|
| `layout/_grid.scss` | l-row, l-row--container, c-col 系, l-grid, c-grid 系の全定義 | PHP 置換 + 検証後 |
| `component/_gutter.scss` | c-gutter 系の全定義 | PHP 置換 + 検証後 |

**削除しないファイル**: `global/_gutter.scss` — `gutter` / `gutter_row` mixin は `global/_index.scss` 経由で以下の project/ ファイルが使用中のため、Phase 3 では削除しない:
- `project/_entrystep.scss` (L20)
- `project/_form.scss` (L51)
- `project/_post.scss` (L123, L192)
- `project/_post-single.scss` (L11, L115)

### PHP（置換対象）

l-row / c-col / l-grid / c-grid / c-gutter のいずれかを含む全 PHP ファイル。ルートレベル + tmp/ 配下。

---

## 4. Phase 3 から除外するファイル

| ファイル | クラス群 | 除外理由 |
|---|---|---|
| `layout/_content.scss` | l-main, l-main__nav-less, l-container, l-container__blog, l-container__search, l-blog__main, l-blog__sidebar, l-split 系全て | **Phase 4 で `@layer components` に移行**。Tailwind ユーティリティへの分解はしない。これらは g.rem() 等の SCSS 計算関数を内部で使用しており、コンポーネント層の移行方針に従う |
| `layout/_header.scss` | l-header | Phase 4 |
| `layout/_footer.scss` | l-footer | Phase 4 |

**split を Phase 3 の主対象に含めない理由**:
- split は flex による 2 カラム分割で、計画書 §3.5 で「flexbox のまま維持、`@layer components` にそのまま移行」と確定済み
- 内部で `g.rem()`, `var(--gutter)`, `var(--gutter-row)` 等の SCSS 計算関数・CSS 変数を使用しており、Tailwind ユーティリティへの置換が適切でない
- クラス名の変更もない。PHP 側の修正も不要
- Phase 4 のコンポーネント層移行で `@layer components` に入れるのが自然

---

## 5. Flex と Grid の使い分け基準

| 基準 | Flex | CSS Grid |
|---|---|---|
| 用途 | ページレイアウト・カラム分割 | カード並びなどの反復グリッド |
| 対象旧クラス | `l-row`, `l-row--container`, `c-col` 系 | `l-grid`, `c-grid` 系 |
| 中央寄せ | `justify-center` で実現 | 不要（左寄せ前提） |
| 子要素の幅指定 | `w-{n}/12` | `grid-cols-{n}` |
| 列間隔 | なし（c-gutter で個別管理） | `gap-x-grid-gutter` |
| 使用箇所数 | 83 箇所（l-row 系） | 4 箇所（l-grid） |

---

## 6. 旧クラス → 新 Tailwind クラスの変換表

### 6.1 l-row 系

| 旧クラス | 新 Tailwind クラス | 備考 |
|---|---|---|
| `l-row` | `flex flex-wrap justify-center` | |
| `l-row--container` | `container mx-auto flex flex-wrap justify-center` | container の max-width は tailwind.config.js で定義済み |

**`justify-start` / `justify-between` との共存ルール**:

`l-row` のデフォルトは `justify-center`。Phase 2 で置換済みの `justify-start` や `md:justify-between` が同じ要素にある場合:
- `justify-start` がある → `justify-center` を **省略** し、`justify-start` を残す
- `md:justify-between` がある → `justify-center` を **維持** する（デフォルトは center、md 以上で between に切り替わる意図）

### 6.2 c-col 系

**規則**: `c-col--N` → `w-N/12` / `c-col__BP--N` → `BP:w-N/12`

特殊ケース:
- `c-col--12` → `w-full`（`w-12/12` でも動作するが `w-full` が慣例）
- `c-col__BP--12` → `BP:w-full`

| 旧クラス | 新 Tailwind クラス |
|---|---|
| `c-col--11` | `w-11/12` |
| `c-col--12` | `w-full` |
| `c-col__sm--4` | `sm:w-4/12` |
| `c-col__sm--5` | `sm:w-5/12` |
| `c-col__sm--7` | `sm:w-7/12` |
| `c-col__sm--8` | `sm:w-8/12` |
| `c-col__sm--10` | `sm:w-10/12` |
| `c-col__sm--12` | `sm:w-full` |
| `c-col__md--4` | `md:w-4/12` |
| `c-col__md--8` | `md:w-8/12` |
| `c-col__md--10` | `md:w-10/12` |
| `c-col__md--11` | `md:w-11/12` |
| `c-col__md--12` | `md:w-full` |
| `c-col__lg--3` | `lg:w-3/12` |
| `c-col__lg--4` | `lg:w-4/12` |
| `c-col__lg--5` | `lg:w-5/12` |
| `c-col__lg--7` | `lg:w-7/12` |
| `c-col__lg--8` | `lg:w-8/12` |
| `c-col__lg--9` | `lg:w-9/12` |
| `c-col__lg--10` | `lg:w-10/12` |
| `c-col__xl--6` | `xl:w-6/12` |
| `c-col__xl--8` | `xl:w-8/12` |
| `c-col__xl--9` | `xl:w-9/12` |
| `c-col__xl--10` | `xl:w-10/12` |

**c-col の Flex 基本プロパティ**: 旧 SCSS では `flex: 0 0 auto; min-height: 1px;` が全 c-col に付与されていた。Tailwind の `w-{n}/12` は `width` のみ設定する。`flex-shrink: 0` は必要に応じて `shrink-0` を追加する。実装時に代表テンプレートで確認し、挙動が変わるケースがあれば対応する。

### 6.3 l-grid / c-grid 系

| 旧クラス | 新 Tailwind クラス | 備考 |
|---|---|---|
| `l-grid` | `grid gap-x-grid-gutter` | CSS Grid 化。gap は旧ネガティブマージン方式と同等間隔 |
| `c-grid--1` | `grid-cols-1` | |
| `c-grid__sm--1` | `sm:grid-cols-1` | |
| `c-grid__sm--2` | `sm:grid-cols-2` | |
| `c-grid__md--2` | `md:grid-cols-2` | |
| `c-grid__lg--3` | `lg:grid-cols-3` | |

**l-grid → grid 化で不要になるもの**:
- `l-grid` のネガティブマージン（`margin-right/left: calc(var(--gutter) * -1)`）
- `%l-grid__col` の `padding-right/left: var(--gutter)` + `flex: 0 0 auto`
- c-grid の `width: percentage(1/n)` 計算

これらは CSS Grid の `grid-template-columns` + `gap-x` で置き換わる。

### 6.4 c-gutter 系

| 旧クラス | 新 Tailwind クラス | 備考 |
|---|---|---|
| `c-gutter__row` | `px-gutter-row xl:px-0` | コンテナ両サイド余白。xl で解除 |
| `c-gutter__post` | `md:px-gutter-row xl:px-0` | md で開始、xl で解除 |
| `c-gutter__sm--left` | `sm:pl-gutter-2 md:pl-gutter-3` | |
| `c-gutter__sm--right` | `sm:pr-gutter-2 md:pr-gutter-3` | |
| `c-gutter__md--left` | `md:pl-gutter-3` | |

**PHP 未使用の c-gutter クラス**（SCSS に定義はあるが PHP テンプレートで未確認）:

`c-gutter`（ベース）, `c-gutter__sm--left-half`, `c-gutter__sm--right-half`, `c-gutter__md--left-half`, `c-gutter__md--right`, `c-gutter__md--right-half`

これらは SCSS 削除時に自動的に消える。PHP 側に置換作業は不要。

---

## 7. 既存の wrapper / container / 中央寄せの責務整理

| 責務 | 旧 | 新 | 備考 |
|---|---|---|---|
| Flex wrapper + 中央寄せ | `l-row` | `flex flex-wrap justify-center` | |
| 同上 + container（max-width + auto margin） | `l-row--container` | `container mx-auto flex flex-wrap justify-center` | container の max-width は tailwind.config.js で BP 別に定義済み |
| コンテナ左右余白 | `c-gutter__row` | `px-gutter-row xl:px-0` | l-row--container と同じ要素に付与 |
| セクション上下 padding | `l-container` | Phase 3 では変更しない | Phase 4 で `@layer components` に移行 |

---

## 8. 実施ステップ

### Step 3-1: tailwind.config.js の確認

**変更するもの**: なし（確認のみ）
**触らないもの**: 全ファイル

1. `theme.extend.padding` に `gutter-1`, `gutter-1.5`, `gutter-2`, `gutter-3`, `gutter-row` が定義済みであることを確認
2. `theme.extend.gap` に `grid-gutter` が定義済みであることを確認
3. `theme.container` に max-width が BP 別に定義済みであることを確認
4. 不足があれば追加する

### Step 3-2: PHP のクラス名置換（l-row 系）

**変更するもの**: 全 PHP ファイル（ルート + tmp/）
**触らないもの**: SCSS ファイル

**この Step は機械的な一括置換を禁止する。必ず行単位で判定してから置換すること。**

#### Step 3-2a: 置換前の棚卸し（必須）

実際に置換を始める前に、`l-row--container` / `l-row` を含む行を以下のパターンに分けて棚卸しする。

1. `l-row--container` + `justify-start`
2. `l-row--container` + `justify-between` or `md:justify-between`
3. `l-row--container`（justify 上書きなし）
4. `l-row` + `justify-start` or `sm:justify-start`
5. `l-row`（justify 上書きなし）
6. 上記に素直に当てはまらない要注意行

要注意行の例:
- `c-replace__flex-start` / `c-replace__flex-end` のような別クラスで `justify-content` を上書きする行
- `justify-*` が複数混在する行
- 改行やテンプレート分岐で class 属性の判定が一目で確定しない行

この棚卸しでは、各パターンの件数と代表例を先に確認してから置換に進む。件数だけで置換を始めない。

1. `l-row--container` + `justify-start` がある行 → `container mx-auto flex flex-wrap justify-start` に置換（`justify-center` は省略）
2. `l-row--container` + `justify-between` or `md:justify-between` がある行 → `container mx-auto flex flex-wrap justify-center` + 既存の justify 系を維持
3. `l-row--container`（justify 上書きなし）→ `container mx-auto flex flex-wrap justify-center` に置換
4. `l-row` + `justify-start` or `sm:justify-start` がある行 → `flex flex-wrap justify-start` or `flex flex-wrap sm:justify-start` に置換
5. `l-row`（justify 上書きなし）→ `flex flex-wrap justify-center` に置換
6. ビルド確認
7. `_class-rename-log.md` の l-row 系のステータスを ✅ に更新する

**置換時の注意**:
- `l-row--container` を先に、`l-row` を後に置換する（部分一致を防ぐ）
- 各行の `justify-start` / `justify-between` / `md:justify-between` の有無を確認してから置換する。**一律に `justify-center` を入れると Phase 2 で設定した justify 上書きが無効化される**
- `l-row` 系は regex 一発置換や機械的な bulk replace をしない。**class 属性を行単位で見て判定する**
- `c-replace__flex-start` / `c-replace__flex-end` のような別クラスで揃え方向を変える行は、`justify-*` 文字列の有無だけで判定しない
- 判定に迷う行が 1 件でもあれば、その行は保留にして一覧化し、勝手に置換しない
- `<!-- /.l-row -->` のような HTML コメントは置換対象外

### Step 3-3: PHP のクラス名置換（c-col 系）

**変更するもの**: 全 PHP ファイル
**触らないもの**: SCSS ファイル

1. `c-col__BP--N` → `BP:w-N/12` パターンで置換（レスポンシブ付きを先に）
2. `c-col--12` → `w-full` で置換
3. `c-col--11` → `w-11/12` で置換
4. ビルド確認
5. `_class-rename-log.md` の c-col 系のステータスを ✅ に更新する

**置換順序が重要**: レスポンシブ付き（`c-col__md--10`）を先に、基本形（`c-col--12`）を後に。

### Step 3-4: PHP のクラス名置換（l-grid / c-grid 系）

**変更するもの**: 全 PHP ファイル（4 箇所のみ）
**触らないもの**: SCSS ファイル

1. `l-grid` → `grid gap-x-grid-gutter` で置換
2. `c-grid__BP--N` → `BP:grid-cols-N` で置換（レスポンシブ付きを先に）
3. `c-grid--1` → `grid-cols-1` で置換
4. ビルド確認
5. `_class-rename-log.md` の c-grid 系のステータスを ✅ に更新する

### Step 3-5: PHP のクラス名置換（c-gutter 系）

**変更するもの**: 全 PHP ファイル
**触らないもの**: SCSS ファイル

1. `c-gutter__row` → `px-gutter-row xl:px-0` で置換
2. `c-gutter__post` → `md:px-gutter-row xl:px-0` で置換
3. `c-gutter__sm--left` → `sm:pl-gutter-2 md:pl-gutter-3` で置換
4. `c-gutter__sm--right` → `sm:pr-gutter-2 md:pr-gutter-3` で置換
5. `c-gutter__md--left` → `md:pl-gutter-3` で置換
6. ビルド確認
7. `_class-rename-log.md` の c-gutter 系のステータスを ✅ に更新する

**置換順序**: `c-gutter__row` / `c-gutter__post` を先に、`c-gutter__sm--*` / `c-gutter__md--*` を後に。

### Step 3-6: SCSS ファイルの削除 + style.scss の更新

**Step 3-2〜3-5 の PHP 置換と検証が全て完了した後に実行する**

1. `style.scss` から以下の `@use` を削除:
   - `@use "layout/_grid"`
   - `@use "component/_gutter"`
2. `layout/_grid.scss` を削除する
3. `component/_gutter.scss` を削除する
4. `npm run build` で最終確認
5. Phase 3 対象の旧クラス（l-row, c-col, l-grid, c-grid, c-gutter）が CSS 出力から消え、新 Tailwind クラスのみが出力されることを確認

**削除しないファイル**:
- `global/_gutter.scss` — 共有 mixin（`gutter` / `gutter_row`）が project/ 配下の複数ファイルから使用されている（§3 参照）。`component/_gutter.scss`（c-gutter クラス定義）とは別物
- `layout/_content.scss` — l-split, l-container, l-blog, l-main の定義がここにあり、Phase 4 まで SCSS のまま残す

### Step 3-7: global/_gutter.scss が維持されていることの確認

`global/_gutter.scss` は Phase 3 で削除しない。`gutter` / `gutter_row` mixin は `global/_index.scss` 経由で以下の project/ ファイルが使用中:
- `project/_entrystep.scss` (L20)
- `project/_form.scss` (L51)
- `project/_post.scss` (L123, L192)
- `project/_post-single.scss` (L11, L115)

Step 3-6 で `component/_gutter.scss` を削除した後も、`global/_gutter.scss` と `global/_index.scss` の `@forward "_gutter"` が残っていることを確認する。

---

## 9. 検証手順

### 9.1 旧クラスが PHP から消えたこと

```bash
# wp-thm ルートで実行。結果ゼロであること
grep -rn 'l-row' --include='*.php' . | grep -v '<!-- '
grep -rn 'c-col' --include='*.php' .
grep -rn 'l-grid' --include='*.php' .
grep -rn -e 'c-grid--' -e 'c-grid__' --include='*.php' .
grep -rn 'c-gutter' --include='*.php' .
```

**注意**: `l-row` の grep では HTML コメント（`<!-- /.l-row -->`）を除外する。`l-container`, `l-blog`, `l-main`, `l-split` は Phase 3 の対象外なので grep から除外。

### 9.2 新 Tailwind クラスが意図通り入ったこと

代表テンプレートで新クラスが正しく挿入されていることを目視確認:

| テンプレート | 確認ポイント |
|---|---|
| `front-page.php` L41 | `container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0`（旧: `l-row--container c-gutter__row`） |
| `front-page.php` L42 | `w-full md:w-10/12 xl:w-9/12 flex flex-wrap justify-center`（旧: `c-col--12 c-col__md--10 c-col__xl--9 l-row`） |
| `front-page.php` L165 | `container mx-auto flex flex-wrap justify-start px-gutter-row xl:px-0`（旧: `l-row--container c-gutter__row justify-start`）— justify-center ではなく justify-start |
| `front-page.php` L97 | `grid gap-x-grid-gutter grid-cols-1 sm:grid-cols-1 lg:grid-cols-3`（旧: `l-grid c-grid--1 c-grid__sm--1 c-grid__lg--3`） |
| `footer.php` L64 | `container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0 md:justify-between`（旧: `l-row--container c-gutter__row md:justify-between`） |
| `tmp/page-form-contact.php` L28 | `w-full md:w-10/12 lg:w-9/12 xl:w-8/12`（旧: `c-col--12 c-col__md--10 c-col__lg--9 c-col__xl--8`） |
| `tmp/page-recruit.php` L16 | `w-full sm:w-4/12 md:w-4/12 lg:w-3/12 sm:pl-gutter-2 md:pl-gutter-3`（旧: `c-col--12 c-col__sm--4 c-col__md--4 c-col__lg--3 c-gutter__sm--left`） |

### 9.2a justify 系の厳密確認

Step 3-2 の置換後、`l-row` 系は次の観点で追加確認する:

1. `justify-start` が残るべき行に `justify-center` を混在させていないこと
2. `md:justify-between` がある行では、デフォルトの `justify-center` を消していないこと
3. `c-replace__flex-start` / `c-replace__flex-end` を持つ行で、別クラス由来の揃え方向を壊していないこと
4. 要注意行として棚卸しした行が、判断なしに機械置換されていないこと

少なくとも以下は代表確認対象に含める:
- `front-page.php` の `c-replace__flex-start` を持つ行
- `front-page.php` の `c-replace__flex-end` を持つ行
- `front-page.php` の `justify-start` を持つ `l-row--container`
- `footer.php` の `md:justify-between` を持つ `l-row--container`
- `tmp/archive-post.php` の `sm:justify-start` を持つ `l-row`

### 9.3 style.css に必要な utility が生成されること

```bash
# wp-thm ルートで実行
grep -c 'flex-wrap' css/style.css  # > 0
grep -c 'justify-center' css/style.css  # > 0
grep -c 'w-full' css/style.css  # > 0
grep -c 'grid-cols-3' css/style.css  # > 0
grep -c 'gap-x-grid-gutter' css/style.css  # > 0（トークン名は出力時に異なる可能性があるため、CSS 変数 var(--gutter) で補完確認）
grep -c 'px-gutter-row' css/style.css  # > 0
```

### 9.4 横並びレイアウトが Flex 前提で維持されていること

ブラウザ確認（BrowserSync + viewport リサイズ）:

| 確認ポイント | テンプレート | 操作 |
|---|---|---|
| 2 カラム構成が Flex で横並び | front-page.php L42-54（7:5 → 8:4 分割） | viewport を sm(576px) 未満で 1 カラム、sm 以上で 2 カラムに切り替わることを確認 |
| container の max-width が BP 別に効くこと | front-page.php L41 | viewport を md(811px) 以上にして、コンテナ幅が 960px に制限されていることを DevTools で確認 |
| justify-start が効いている箇所で左寄せ | front-page.php L165 | 子要素が左寄せになっていることを確認。中央寄せになっていたら Phase 2 の justify-start が上書きされている |
| md:justify-between が効いている箇所 | footer.php L64 | viewport を md(811px) 以上にして、子要素が両端寄せになることを確認 |

### 9.5 カードグリッドが CSS Grid 前提で維持されていること

| 確認ポイント | テンプレート | 操作 |
|---|---|---|
| カード 3 列表示 | front-page.php L97 | viewport を lg(1025px) 以上にして 3 列になることを確認 |
| カード 1 列→2 列→3 列の切り替え | front-page.php L97 | viewport を sm 未満→sm→lg で切り替え、1→1→3 列に変わることを確認 |
| 列間隔が旧と同等 | front-page.php L97 | DevTools で gap-x の値を確認。`calc(var(--gutter) * 2)` が適用されていること |
| ネガティブマージンが消えていること | front-page.php L97 | DevTools で grid 要素の margin-left/right がネガティブでないことを確認 |

### 9.6 gutter の左右余白が既存意図どおりか

| 確認ポイント | テンプレート | 操作 |
|---|---|---|
| `px-gutter-row xl:px-0` で左右余白 | front-page.php L41 | viewport を xl 未満で左右 padding があること、xl 以上で 0 になることを確認 |
| `md:px-gutter-row xl:px-0` | tmp/single-blog.php L2 | viewport を md 未満で padding なし、md 以上で padding あり、xl 以上で 0 を確認 |
| `sm:pl-gutter-2 md:pl-gutter-3` | tmp/page-recruit.php L16 | viewport を sm 以上で left padding が付くこと、md 以上でさらに大きくなることを確認 |

---

## 10. リスクと先回り対策

### 10.1 SCSS 定義だけ見て PHP 実使用を見落とすリスク

**リスク**: SCSS で定義されているクラスを「使われている」と想定して変換表を作るが、実際には PHP で未使用のクラスが混在する。
**対策**: 本計画では SCSS 定義と PHP 使用の両方を照合済み。未使用クラスは §2.4 に明記。調査時に発見されたバグ（§2.5）は計画確定前に修正済み。

### 10.2 l-row を CSS Grid に寄せてしまう誤り

**リスク**: l-row を `grid grid-cols-12` + `col-span-N` で置き換えようとする。CSS Grid では `justify-content: center` が効かず、中央寄せレイアウトが崩れる。
**対策**: l-row は **Flex** で置換する。これは計画書 §3.1 で確定済み。計画書の「なぜ CSS Grid 一本化ではないか」の議論経緯（§3.2）を必ず読むこと。

### 10.3 c-gutter を旧クラス温存で済ませてしまう誤り

**リスク**: c-gutter を `@layer components` で旧クラス名のまま維持し、PHP 側の置換を省略する。
**対策**: c-gutter は `tailwind.config.js` のカスタム padding / gap に寄せる方針が確定済み（計画書 §3.4）。PHP 側のクラス名を Tailwind 記法に書き換える。

### 10.4 split を勝手に再設計してしまう誤り

**リスク**: l-split を CSS Grid で再設計する、または Tailwind ユーティリティに分解する。
**対策**: split は Phase 3 の対象外。計画書 §3.5 で「flexbox のまま維持、`@layer components` にそのまま移行」と確定済み。Phase 4 の作業。

### 10.5 Tailwind default spacing 前提で誤変換するリスク

**リスク**: `mt-4 = 16px`（Tailwind デフォルト）と想定してしまう。実際は `mt-4 = 32px`。
**対策**: `tailwind.config.js` の `theme.spacing` で全値が既存スケールに再定義済み。Phase 3 では spacing に触れない。Phase 2 で置換済みの margin クラスはそのまま維持。

### 10.6 動的出力や投稿本文由来クラスの見落とし

**リスク**: `l-row`, `c-col`, `c-grid`, `c-gutter` が投稿本文や管理画面入力に含まれている可能性。
**対策**: これらはレイアウト系クラスであり、WordPress エディタから手入力される可能性は低い。ただし `the_content` 出力でカスタムショートコードがレイアウト系クラスを出力している可能性は排除できない。Phase 2 と同様に SCSS 削除前に確認する。

### 10.7 Phase 2 で置換済みの Tailwind クラスとの衝突

**リスク**: `l-row--container` → `container mx-auto flex flex-wrap justify-center` 置換時に、同じ行にある `justify-start`（Phase 2 で `jc__start` から置換済み）と `justify-center` が衝突する。
**対策**: §6.1 の共存ルールに従い、`justify-start` がある行では `justify-center` を省略する。Step 3-2 の手順で行ごとの判定を明記済み。

### 10.8 progress.md と _class-rename-log.md の状態差分による誤認

**リスク**: Phase 3 開始時に前回セッションの中途半端な状態を引き継ぎ、完了済みの Step を再実行したり、未完了の Step を飛ばしたりする。
**対策**: 各 Step の完了条件に `_class-rename-log.md` のステータス更新を組み込み済み。Phase 3 開始前に `_class-rename-log.md` の Phase 3 セクションのステータスを確認し、✅ の Step は飛ばす。

### 10.9 global/_gutter.scss を誤って削除するリスク

**リスク**: `component/_gutter.scss` の削除時に、同じ「gutter」という名前の `global/_gutter.scss` も一緒に削除してしまう。
**対策**: `global/_gutter.scss` は削除しない。`gutter` / `gutter_row` mixin は project/ 配下の 4 ファイルが使用中（§3 参照）。`component/_gutter.scss`（c-gutter クラス定義）と `global/_gutter.scss`（共有 mixin）は別物であることを認識する。

---

## 11. progress.md と _class-rename-log.md の更新ポイント

### progress.md

Phase 3 完了時に以下を追記:
- 実施した作業（l-row / c-col / l-grid / c-grid / c-gutter の置換、SCSS 削除）
- 次に進める状態か（Phase 4 着手可能）

### _class-rename-log.md

Phase 3 セクションのテンプレートを実データで埋める。以下のカテゴリに分けて記録:

- **l-row 系**: l-row → flex flex-wrap justify-center, l-row--container → container mx-auto flex flex-wrap justify-center
- **c-col 系（ベース幅）**: c-col--12 → w-full, c-col--11 → w-11/12
- **c-col 系（レスポンシブ）**: c-col__sm--4 → sm:w-4/12, c-col__md--8 → md:w-8/12, ...
- **c-grid 系**: l-grid → grid gap-x-grid-gutter, c-grid--1 → grid-cols-1, ...
- **c-gutter 系**: c-gutter__row → px-gutter-row xl:px-0, c-gutter__post → md:px-gutter-row xl:px-0, ...

各 Step 完了時にステータスを ⏳ → ✅ に更新する。

---

## 12. 実装開始前に確認すべき未確定事項

| # | 事項 | 選択肢 | 推奨 |
|---|---|---|---|
| 1 | **c-col の `flex: 0 0 auto` + `min-height: 1px` を明示的に付与するか** | A: `shrink-0` を全 c-col 置換箇所に追加 / B: 追加しない（Tailwind の `w-N/12` だけで十分） | 実装時に代表テンプレートで確認。`w-N/12` だけで旧挙動が再現されるなら **B**。崩れるケースがあれば **A** |

---

## この計画の根拠として実際に確認したファイル一覧

### 必読として読んだファイル

| ファイル | 確認内容 |
|---|---|
| `progress.md` | Phase 1/2 完了状態、Phase 3 未着手の確認 |
| `CLAUDE.md`（リポジトリルート） | 禁止事項、作業ルール、検証ルール |
| `CLAUDE.md`（wp-thm/） | Phase 一覧、参照ファイル |
| `src/scss/tailwind-migration-plan.md` §3 | Flex/Grid 使い分け、c-gutter 変換表、split 方針 |
| `src/scss/_class-rename-log.md` | Phase 2 全完了、Phase 3 テンプレート状態 |
| `tailwind.config.js` | screens, container, spacing, extend.padding, extend.gap の定義 |
| `src/scss/style.scss` | @use 構成、Phase 2 削除済みコメント |
| `src/scss/layout/_grid.scss` | l-row, c-col, l-grid, c-grid の SCSS 全定義 |
| `src/scss/layout/_content.scss` | l-main, l-container, l-blog, l-split の SCSS 全定義 |
| `src/scss/component/_gutter.scss` | c-gutter の SCSS 全定義 |
| `src/scss/global/_gutter.scss` | gutter / gutter_row mixin 定義 |

### PHP ファイル（grep + 目視で確認）

| ファイル | 確認内容 |
|---|---|
| `front-page.php` | l-row--container, c-col, l-grid, c-grid, c-gutter, l-split, l-container の使用パターン |
| `footer.php` | l-row--container + md:justify-between パターン |
| `header.php` | l-main 使用 |
| `404.php` | l-row--container, c-col パターン |
| `index.php` | l-row--container, l-container パターン |
| `archive.php` | l-row--container, l-container パターン |
| `category.php` | l-row--container パターン |
| `search.php` | l-container__search, c-gutter__row パターン |
| `page-form-contact-chk.php` | c-col バリエーション多数 |
| `functions/admin_login.php` | l-main__nav-less, l-row--container |
| `tmp/page-recruit.php` | c-gutter__sm--left 使用 |
| `tmp/page-form-contact.php` | c-col バリエーション多数（調査時に c-col_xl--8 のタイポを発見し修正済み） |
| `tmp/page-company.php` | c-gutter__md--left 使用 |
| `tmp/single-blog.php` | c-gutter__post, l-blog__main/sidebar |
| `tmp/tmp-post.php` | c-gutter__post, l-blog__main/sidebar |
| `tmp/page-campaign.php` | l-row + justify-start/between 混在 |
| `tmp/content/feed-post-grid.php` | l-grid + c-grid パターン |
| `tmp/content/salon-detail.php` | c-col 多数、c-gutter__md--left |
| `tmp/content/container-feed-post.php` | l-row--container ネスト |
| `tmp/archive-post.php` | l-row + sm:justify-start |
| `tmp/page-salon-kitatoyama.php` | l-row--container + justify-start |
| `tmp/page-sitemap.php` | l-row--container パターン |
| `tmp/page-menu.php` | l-row--container パターン |
| `tmp/page-privacy-policy.php` | l-row--container + justify-start |
| `tmp/page-recruit-info.php` | l-row--container パターン |
| `tmp/single-staff.php` | l-row--container パターン |
| `tmp/single-style.php` | l-row--container パターン |
| `tmp/page-form-contact-thk.php` | l-row--container パターン |
| `tmp/page-qa.php` | l-row--container パターン |
| `tmp/content/sitemap.php` | l-row--container パターン |
| `tmp/content/menu-cut.php` | l-row--container パターン |
| `tmp/content/recruit-info-guiches.php` | l-row パターン |
| `tmp/archive-none.php` | c-col パターン |
