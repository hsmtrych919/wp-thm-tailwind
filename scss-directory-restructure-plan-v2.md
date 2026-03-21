# SCSS ディレクトリ構造リストラクチャ計画書 v2

---

## 背景

### 現行の設計経緯

本プロジェクトの SCSS ディレクトリ構造は、元々 FLOCSS（Foundation / Layout / Object）設計手法に基づいて構築された。FLOCSS では以下のルールでクラスを分類する:

- `l-`（Layout）: ページの骨格（header, footer, container 等）
- `c-`（Component）: 再利用可能な UI パーツ（button, pagination 等）
- `p-`（Project）: 特定ページ/機能に固有のスタイル（form, post, search 等）
- `u-`（Utility）: 単機能ユーティリティ（display, margin 等）

各接頭辞に対応するディレクトリ（`layout/`, `component/`, `project/`, `utility/`）にファイルを配置していた。

### FLOCSS の形骸化

Tailwind CSS との併用移行（Phase 1〜5）を経て、FLOCSS の設計原則は形骸化した:

- `utility/` は Phase 2 で全削除。ユーティリティは Tailwind が担当
- `foundation/` は Phase 1 で `_tailwind-base-layer.scss` + `tailwind-base.css` に再編
- グリッドシステムは Phase 3 で Tailwind ユーティリティに移行済み
- 残っているのはディレクトリ名（`layout/`, `component/`, `project/`）とクラスの接頭辞（`l-`, `c-`, `p-`）のみ

現在のシステムは「SCSS + Tailwind のハイブリッド」であり、FLOCSS ではない。ディレクトリ構造とクラス名に FLOCSS の残骸が残っている状態。

### 将来の展望

本プロジェクトの SCSS 資産を Next.js プロジェクトでも活用する構想がある。具体的には:

- Tailwind 併用が完了した SCSS をベースに、モジュール化可能なスタイルを `.module.scss`（CSS Modules）として切り出す
- CSS Modules はコンポーネントスコープでクラス名が自動ハッシュ化されるため、グローバルな接頭辞（`l-`, `c-`, `p-`）は不要になる
- 移行しやすい構造にしておくことで、将来のコストを下げる

---

## 現行構造

```
src/scss/
├── global/                    ← SCSS ツール（変数, BP, mixin, 関数）。CSS 出力なし
│   ├── _breakpoints.scss
│   ├── _calc.scss
│   ├── _font.scss
│   ├── _hover.scss
│   ├── _index.scss
│   ├── _media-queries.scss
│   └── _variables.scss
│
├── component/                 ← 自作 UI パーツ + ベンダー CSS が混在
│   ├── _button.scss           ← 自作（c-button__* クラス）
│   ├── _google-map.scss       ← 自作（c-gmap クラス）
│   ├── _login.scss            ← 自作（c-login__* クラス）
│   ├── _pagenation.scss       ← 自作（c-pagenation クラス）
│   ├── _style.scss            ← 自作（c-replace__*, c-button-2columns__*, c-more__* 等）
│   ├── fontawesome-free-5.14.0/  ← ベンダー
│   ├── micromodal/               ← ベンダー（c-micromodal__* を含む。後述）
│   ├── swiper/                   ← ベンダー
│   └── scroll-hint/              ← ベンダー
│
├── layout/                    ← ページ骨格（FLOCSS の Layout 層）
│   ├── _content.scss          ← l-main, l-blog__*, l-container-py*, l-split__*
│   ├── _header.scss           ← l-header（構造: position, z-index, height）
│   └── _footer.scss           ← l-footer（構造: position, z-index）
│
├── project/                   ← ページ/機能固有スタイル（FLOCSS の Project 層）
│   ├── _button.scss           ← p-button__*
│   ├── _footer.scss           ← p-footer__*（装飾: nav, copyright, list）
│   ├── _form.scss             ← p-form__*
│   ├── _header.scss           ← p-header__*（装飾: nav, logo, menu, hamburger）
│   ├── _post.scss             ← p-post__*
│   ├── _post-single.scss      ← p-post-feed__*, p-sidebar__*
│   ├── _search.scss           ← p-search__*
│   ├── _style.scss            ← p-salon-info__*, p-entrystep__*, p-recruit__* 等
│   └── _typ.scss              ← p-ttl__*, p-typ__*, p-form__ttl 等
│
├── style.scss                 ← エントリポイント（@use で全ファイルを読み込み）
├── _tailwind-base-layer.scss  ← @layer base（CSS 変数 + リセット補足）
└── tailwind-base.css          ← Tailwind 読み込み + config 参照
```

### 現行構造についての整理

1. **`component/` に自作とベンダーが混在**: FontAwesome, Swiper 等のベンダー CSS と自作の `c-button` 等が同居。責務が異なるものが 1 ディレクトリにある
2. **`layout/` と `project/` の分離が FLOCSS 依存**: header と footer がそれぞれ 2 ファイルに分かれている（`layout/_header.scss` = 構造、`project/_header.scss` = 装飾）。FLOCSS の「Layout は構造、Project は装飾」ルールに基づく分離だが、FLOCSS を運用していない現在は分離の理由がない
3. **`project/` が現行設計と合っていない**: FLOCSS の運用が終わった以上、`project/` というディレクトリ名に機能的な意味がない。現行の SCSS/Tailwind ハイブリッド構成に合った、より管理しやすい分類にする余地がある
4. **3 ディレクトリに散在**: 自作スタイルが `component/`（5 ファイル）、`layout/`（3 ファイル）、`project/`（9 ファイル）に分散しており、「header のスタイルはどこ？」→ 2 箇所を探す必要がある
5. **`.l-container` を SCSS 側に戻せる状態になった**: container リファクタリングでクラス名を整理した結果、`.l-container`（横幅制御）を `tailwind-base.css` から SCSS 側に移動できるようになった。`l-container-py*` や `l-split__*` と同じファイルに配置すれば、同じ責務のレイアウトクラスが 1 箇所にまとまる

---

## 変更後の構造

```
src/scss/
├── global/                    ← 変更なし
│
├── vendor/                    ← 旧 component/ からベンダー CSS のみ残す
│   ├── fontawesome-free-5.14.0/
│   ├── micromodal/               ← c-micromodal__* → micromodal__* に接頭辞除外済み
│   ├── swiper/
│   └── scroll-hint/
│
├── features/                  ← 自作スタイルを統合（旧 component/ 自作 + layout/ + project/）
│   ├── _button.scss           ← button__* に統合
│   ├── _footer.scss           ← footer__* に統合
│   ├── _form.scss             ← form__*
│   ├── _google-map.scss       ← gmap
│   ├── _header.scss           ← header__* に統合
│   ├── _layout.scss           ← main, blog__*, container-py-*, split__*, container-width
│   ├── _login.scss            ← login__*
│   ├── _pagenation.scss       ← pagenation
│   ├── _post.scss             ← post__*
│   ├── _post-single.scss      ← post-feed__*, sidebar__*
│   ├── _search.scss           ← search__*
│   ├── _style.scss            ← replace__*, salon-info__*, entrystep__* 等を統合
│   └── _typ.scss              ← ttl__*, typ__*
│
├── style.scss                 ← @use パスを全て書き換え
├── _tailwind-base-layer.scss  ← 変更なし
└── tailwind-base.css          ← .l-container の定義を _layout.scss に移動後、Tailwind import + config のみ
```

### 変更のポイント

1. **`component/` → `vendor/`**: ベンダー CSS 専用ディレクトリに名前変更。自作ファイルは `features/` に移動済み
2. **`layout/` + `project/` + `component/` 自作 → `features/`**: 自作スタイルを 1 ディレクトリに統合。「header のスタイルはどこ？」→ `features/_header.scss` と即座に分かる
3. **`_content.scss` → `_layout.scss`**: ディレクトリ名 `layout/` がなくなるため、ファイル名で「レイアウト系スタイル」と分かるようにする
4. **`.l-container` を `tailwind-base.css` → `_layout.scss` に移動**: `l-container-py*` と並べて配置。CSS 構文を SCSS 構文（`@media #{g.$sm}` 等）に書き換える。`@layer components` に入る。`tailwind-base.css` は Tailwind import + config のみになる
5. **header / footer のスタイル統合**: `.l-*` と `.p-*` が同一要素で同時使用されている場合は `.p-*` 側にスタイルを統合（クラス数を減らす）。別要素で使われている場合は同ファイルにまとめてから接頭辞を外す
6. **button / style の統合**: 同上の方針。`.c-*` と `.p-*` の同時使用があれば `.p-*` に統合。なければ同ファイルにまとめてから接頭辞を外す

### `features/` というディレクトリ名の選定理由

- **`modules/`** は不採用。CSS Modules（`.module.scss`）と名前が紛らわしい。将来 CSS Modules 化する際に過渡期の混乱が生じる
- **`features/`** は Next.js / React の feature-based architecture（機能単位でファイルをまとめる設計）と一致する。CSS に限定されない汎用的な概念で、将来 Next.js に移行しても違和感がない

---

## クラス接頭辞の方針

### 現状の接頭辞

| 接頭辞 | 出典 | 例 |
|---|---|---|
| `l-` | FLOCSS Layout | `l-header`, `l-main`, `l-container-py*`, `l-split__*`, `l-container`（横幅制御） |
| `c-` | FLOCSS Component | `c-button__*`, `c-gmap`, `c-pagenation`, `c-replace__*`, `c-micromodal__*` |
| `p-` | FLOCSS Project | `p-header__*`, `p-post__*`, `p-form__*`, `p-ttl__*` |

### 方針: 接頭辞を全て除外する

理由:

1. **FLOCSS の分類基準が消滅する**: `layout/`, `component/`, `project/` のディレクトリ分離をやめるため、接頭辞が指すカテゴリ自体が存在しなくなる
2. **CSS Modules 移行への準備**: CSS Modules ではクラス名がコンポーネントスコープでハッシュ化される。接頭辞は不要。今の段階で外しておけば `.module.scss` 化する際にクラス名の変更が発生しない
3. **BEM のみのシンプルな命名**: `header__nav--active` のように BEM だけで十分にスコープが分かる

### 統合時のクラス名方針

**ケース 1: `.l-*` と `.p-*` が同一要素で同時使用されている場合**

例: PHP テンプレートで `class="l-header p-header__wrap"` のように 2 つの接頭辞クラスが 1 要素に付いている場合

- `.l-header` のスタイル（position, z-index, height 等）を `.p-header__wrap` に統合する
- PHP から `l-header` クラスを削除し、`p-header__wrap` 1 つにする
- その後 `p-header__wrap` → `header__wrap` と接頭辞を外す

**ケース 2: 別々の要素で使われている場合**

例: `.l-header` が要素 A に、`.p-header__nav` が要素 B に、それぞれ単独で使われている場合

- 両方を同じファイル（`_header.scss`）にまとめる
- 接頭辞をそれぞれ外す: `l-header` → `header`, `p-header__nav` → `header__nav`

**`.c-*` と `.p-*` についても同じ方針。** 同一要素で同時使用なら `.p-*` に統合。別要素なら同ファイルにまとめてから接頭辞を外す。

---

## 作業概要と順序

### 工程の方針

- **統合先行 → 置換後行 → リネーム最後**: ファイルの統合・移動を先に済ませ、接頭辞の置換は後。ディレクトリのリネームは全作業完了後。同時にやると問題の切り分けが困難になる
- **1 ファイルずつ作業**: 接頭辞除外は SCSS 1 ファイルごとに実行。1 ファイルの SCSS 修正 → 対応する PHP/JS 置換 → ビルド確認、を繰り返す。まとめてやらず負荷を分散する
- **事前に全件洗い出し**: 作業開始前に全接頭辞の利用箇所を PHP/JS/SCSS 横断で調査し、別ファイルに記録する。各ファイルの作業時にダブルチェックに使う
- **`style.scss` の `@use` は随時更新**: ファイル移動・統合のたびに `@use` パスを更新しビルド確認する
- **判断を先に固定してから変更に入る**: 統合作業（Step 3, 6, 7）では、SCSS/PHP の変更に入る前に「同一要素統合か、同ファイル共存か」「どのクラスを残し、どのクラスを統合先へ寄せるか」を判断結果として記録する。判断しながら実装する流れにしない。記録先は `progress.md` とし、各 Step の判断フェーズ完了時に「今回の統合方針」として残す
- **`src/scss/_archive/` は参照対象外とする**: 過去の計画書、監査メモ、旧ファイルは今回の実装判断・置換対象・調査対象に含めない。現行コードと現行計画の source of truth は `style.scss` から到達する build graph 内ファイル、およびこの計画書本文のみとする

### 移動・統合完了チェック（Step 2〜4, 6〜8 共通）

Step 2 から Step 4、および Step 6 から Step 8 はファイルの移動・統合フェーズであり、ビルド確認だけでは不十分。各 Step の完了時に以下の 3 点を確認する:

- **統合元ファイルが空 / 削除済みか**: 移動元に内容が残っていないこと
- **統合先ファイルに内容が移りきっているか**: 統合元にあった全クラス定義が統合先に存在すること
- **`style.scss` の `@use` が新構成と一致しているか**: 削除したファイルへの参照が残っていないこと、新しいパスが正しいこと

このチェックを以下「統合完了チェック」と呼ぶ。各 Step の手順末尾に記載する。

※ Step 5（`.l-container` の移植）はファイル移動・統合ではなく、個別検証 Step として扱う。共通の「統合完了チェック」は適用せず、Step 5 本文に記載した出力 CSS の同等性確認を完了条件とする。

---

### Step 1: 事前調査 -- 全接頭辞の利用箇所洗い出し

全 PHP / JS / SCSS ファイルから `.l-*`, `.c-*`, `.p-*` 接頭辞付きクラスの利用箇所を調査し、別ファイルに記録する。

ただし、`src/scss/_archive/` 配下の過去計画書、過去の監査資料、旧 SCSS ファイルは調査対象から除外する。過去資料に引っ張られて現行判断を誤らないため、利用箇所洗い出しは現役ファイルのみを対象に行う。

この記録は以降の全工程でダブルチェックに使用する。各ファイルの作業完了時に、事前調査の記録と突合して漏れがないことを確認する。

#### 記録形式

記録は **定義記録** と **利用記録** の 2 種類に分けて作成する。

**定義記録（SCSS）:**

- 対象クラス名
- 定義ファイル名
- 行番号は記録しない（ファイル移動・統合で行番号がすぐ古くなるため）

**利用記録（PHP / JS）:**

- 対象クラス名
- 利用ファイル名
- 行番号を記録する（置換箇所の特定に使うため）

理由: SCSS 定義側はファイル移動と rename で行番号がすぐ無効になる。PHP / JS の利用側は移動・統合フェーズでは変更しないため、行番号が置換作業の追跡に役立つ。

---

### Step 2: vendor 系先行処理

影響範囲が限定的なので最初に片付ける。

1. ベンダー SCSS ファイルに `c-*` 接頭辞のクラスがないかチェックする
   - **micromodal**: `c-micromodal__*` が SCSS 定義 4 箇所 + PHP（`footer.php`）4 箇所。接頭辞を除外する（`c-micromodal__*` → `micromodal__*`）
   - **FontAwesome, Swiper, scroll-hint**: `c-*` なし。対処不要
2. micromodal の SCSS 定義と PHP のクラス名を置換
3. ビルドして確認
4. `component/` 内のベンダーディレクトリ（fontawesome, micromodal, swiper, scroll-hint）を `vendor/` ディレクトリに移動
5. `style.scss` の `@use` パスを更新
6. ビルドして確認
7. 統合完了チェック:
   - `component/` 内にベンダーディレクトリが残っていないこと
   - `vendor/` に全ベンダーディレクトリが存在すること
   - `style.scss` のベンダー系 `@use` が `vendor/` パスに更新されていること

---

### Step 3: layout/header, footer → project/ に統合

`.l-*` のスタイルを `.p-*` 側のファイルに統合する。

**判断フェーズ（変更に入る前に確定させる）:**

1. `layout/_header.scss` と `project/_header.scss` の中身を確認する
2. `.l-header` と `.p-header__*` の PHP 上での使われ方を確認する（同一要素で同時使用か、別要素か）
3. 判断結果を記録する: どの方針で統合するか（ケース 1: 同一要素統合 / ケース 2: 同ファイル共存）、どのクラスを残し、どのクラスを統合先へ寄せるか
4. footer についても同様に判断結果を記録する

**変更フェーズ（判断結果に従って実行する）:**

5. header: 判断結果に基づいて SCSS / PHP を変更する
6. `layout/_header.scss` を削除
7. footer: 判断結果に基づいて SCSS / PHP を変更する
8. `layout/_footer.scss` を削除
9. `style.scss` の `@use` から `layout/_header`, `layout/_footer` を削除
10. ビルドして確認
11. 統合完了チェック:
    - `layout/_header.scss`, `layout/_footer.scss` が削除されていること
    - header / footer の全クラス定義が `project/_header.scss`, `project/_footer.scss` に存在すること
    - `style.scss` に `layout/_header`, `layout/_footer` への参照が残っていないこと

---

### Step 4: layout/_content.scss → _layout.scss にリネームして project/ へ移動

`_content.scss` のリネームとディレクトリ移動のみ。`.l-container` の移植は次 Step で行う。

1. `layout/_content.scss` を `_layout.scss` にリネーム
2. `_layout.scss` を `project/` に移動
3. `layout/` ディレクトリが空になるので削除
4. `style.scss` の `@use` パスを更新
5. ビルドして確認
6. 統合完了チェック:
   - `layout/` ディレクトリが存在しないこと
   - `project/_layout.scss` が存在し、内容が旧 `layout/_content.scss` と同一であること
   - `style.scss` に `layout/` への参照が残っていないこと

---

### Step 5: .l-container を tailwind-base.css → _layout.scss に移植

Step 4 とは性質が異なる作業（単なるファイル移動ではなく、CSS → SCSS への構文書き換えを伴う）であるため、独立 Step とする。

1. `tailwind-base.css` 内の `.l-container` 定義とコメントブロックを `project/_layout.scss` に移動する
2. CSS 構文（`@media (width >= 576px)` 等）を SCSS 構文（`@media #{g.$sm}` 等）に書き換える
3. `@layer components` 内に配置する（`l-container-py*` と同じレイヤー）
4. `tailwind-base.css` から `.l-container` の定義とコメントブロックを削除。`tailwind-base.css` は `@import "tailwindcss" important;` と `@config` の 2 行のみになる
5. ビルドして確認
6. `.l-container` の CSS 出力が移動前と同一であることを検証する（`margin-inline: auto`, `width: 100%`, BP 別 max-width 5 段階）

---

### Step 6: component/_button.scss → project/_button.scss に統合

**判断フェーズ（変更に入る前に確定させる）:**

1. `component/_button.scss`（`c-button__*`）と `project/_button.scss`（`p-button__*`）の中身を確認する
2. `.c-button__*` と `.p-button__*` が同一要素で同時使用されているケースがあるか PHP を調査する
3. 判断結果を記録する: どの方針で統合するか、どのクラスを残し、どのクラスを統合先へ寄せるか

**変更フェーズ（判断結果に従って実行する）:**

4. 同時使用あり: `.c-*` のスタイルを `.p-*` に統合し、PHP から `.c-*` クラスを削除
5. 同時使用なし: `component/_button.scss` の内容を `project/_button.scss` に追記（`c-*` 接頭辞はこの時点では維持）
6. `component/_button.scss` を削除
7. `style.scss` の `@use` を更新
8. ビルドして確認
9. 統合完了チェック:
   - `component/_button.scss` が削除されていること
   - button 関連の全クラス定義が `project/_button.scss` に存在すること
   - `style.scss` に `component/_button` への参照が残っていないこと

---

### Step 7: component/_style.scss → project/_style.scss に統合

Step 6 と同じ構成。

**判断フェーズ（変更に入る前に確定させる）:**

1. `component/_style.scss`（`c-replace__*`, `c-button-2columns__*`, `c-more__*` 等）と `project/_style.scss` の中身を確認する
2. 同時使用の有無を PHP で調査する
3. 判断結果を記録する: どの方針で統合するか、どのクラスを残し、どのクラスを統合先へ寄せるか

**変更フェーズ（判断結果に従って実行する）:**

4. 判断結果に基づいて統合
5. `component/_style.scss` を削除
6. `style.scss` の `@use` を更新
7. ビルドして確認
8. 統合完了チェック:
   - `component/_style.scss` が削除されていること
   - style 関連の全クラス定義が `project/_style.scss` に存在すること
   - `style.scss` に `component/_style` への参照が残っていないこと

---

### Step 8: component/ に残った SCSS → project/ に移動

component/ に残っている自作ファイル:
- `_google-map.scss`
- `_login.scss`
- `_pagenation.scss`

1. 3 ファイルを `project/` に移動
2. `component/` ディレクトリが空になるので削除（ベンダーは Step 2 で `vendor/` に移動済み）
3. `style.scss` の `@use` を更新
4. ビルドして確認
5. 統合完了チェック:
   - `component/` ディレクトリが存在しないこと
   - 3 ファイルが `project/` に存在すること
   - `style.scss` に `component/` への参照が残っていないこと
6. **progress.md を更新する**: 「構造再編フェーズ完了（Step 2〜8）。全自作 SCSS が `project/` に集約済み。接頭辞除外フェーズ（Step 9〜11）は未着手」を記録する

---

### Step 9: project/ 内の SCSS を 1 ファイルずつ接頭辞除外

この時点で全自作 SCSS が `project/` に集約されている。1 ファイルずつ以下を実行する:

1. SCSS ファイル内のクラス定義から接頭辞（`l-`, `c-`, `p-`）を除外
2. Step 1 の事前調査記録（利用記録）を参照し、対応する PHP / JS の全箇所を置換
3. ビルドして CSS 出力が正しいことを確認
4. 事前調査記録と突合し、漏れがないことを確認
5. `_class-rename-log.md` に記録
6. 次のファイルへ

ファイルの作業順: 小さいファイル・依存が少ないファイルから着手し、`_style.scss`（36KB・最大）は最後に回す。

#### `_layout.scss` の対象範囲

`_layout.scss` は他のファイルと異なり、container 系クラスを含む。対象と除外を以下に明記する:

**Step 9 で接頭辞を外す対象:**

- `l-main` → `main`
- `l-blog__*` → `blog__*`
- `l-split__*` → `split__*`

**Step 9 では変更しない（Step 10・Step 11 で個別処理する）:**

- `l-container-py*`（Step 10 で `container-py-*` にリネーム）
- `l-container`（Step 11 で `container-width` にリネーム）

理由: container 系クラスは container リファクタリングで一度置換済みであり、2 度目のリネームとなるため慎重に分離して実施する。`_layout.scss` は Step 11 完了まで接頭辞除外フェーズが未完了の状態として扱う。

---

### Step 10: l-container-py* → container-py-* にリネーム

container リファクタリングで確定した `l-container-py*` を、FLOCSS 接頭辞を外した最終名にリネームする。一度置換済みのクラスの 2 度目のリネームであるため、Step 11（`l-container`）とは並列にせず、本 Step 単独で完了・検証してから次に進む。

変換対象:

| 現行 | 変更後 |
|---|---|
| `l-container-py` | `container-py` |
| `l-container-py--blog` | `container-py--blog` |
| `l-container-py--search` | `container-py--search` |

命名の根拠: `container-py-*` は `{noun}-{property}` の語順であり、Tailwind ユーティリティの `{property}-{value}` 語順（`py-4` 等）と逆。Tailwind が `container-py-*` パターンのクラスを生成することはなく、衝突・誤認のリスクがない。

手順:

1. Step 1 の事前調査記録から `l-container-py` の全利用箇所（SCSS / PHP / JS）を抽出
2. `_layout.scss` 内のクラス定義を `l-container-py` → `container-py` にリネーム（BEM modifier `--blog`, `--search` も同様）
3. 対応する PHP / JS の全箇所を置換
4. ビルドして CSS 出力が正しいことを確認
5. 事前調査記録と突合し、漏れがないことを確認
6. `_class-rename-log.md` に記録
7. **この Step の検証が完了するまで Step 11 に進まない**

---

### Step 11: l-container → container-width にリネーム

Step 10 の検証完了後に着手する。`l-container`（横幅制御）を最終名にリネームする。

変換対象:

| 現行 | 変更後 |
|---|---|
| `l-container` | `container-width` |

命名の根拠: Tailwind のビルトイン `.container` との衝突を避けつつ、横幅制御という責務を明示する。`container-width` は Tailwind が生成するクラスパターンに存在しない。`container-py-*` と先頭語 `container-` を共有するため、grep やエディタ補完で関連クラスをまとめて引ける。

手順:

1. Step 1 の事前調査記録から `l-container` の全利用箇所（SCSS / PHP / JS）を抽出。`l-container-py*` を含めないよう完全一致で抽出すること
2. `_layout.scss` 内のクラス定義を `l-container` → `container-width` にリネーム
3. 対応する PHP / JS の全箇所を置換
4. ビルドして CSS 出力が正しいことを確認（`margin-inline: auto`, `width: 100%`, BP 別 max-width 5 段階が維持されていること）
5. 事前調査記録と突合し、漏れがないことを確認
6. `_class-rename-log.md` に記録
7. **progress.md を更新する**: 「接頭辞除外フェーズ完了（Step 9〜11）。全クラスの接頭辞除外と container リネームが完了。ディレクトリリネーム（Step 12）は未着手」を記録する

---

### Step 12: project/ → features/ にリネーム

全ファイルの接頭辞除外と container リネームが完了した後に実行する。

1. `project/` を `features/` にリネーム
2. `style.scss` の全 `@use` パスを `project/` → `features/` に書き換え
3. ビルドして確認

---

### Step 13: ドキュメント更新

1. `CLAUDE.md` のディレクトリ構造セクションを更新
2. `scss/readme.md` を更新
3. `_class-rename-log.md` の最終確認
4. `progress.md` に完了記録

---

## スコープ外（この計画では触らない）

- **`global/` ディレクトリ**: 変更なし
- **`_tailwind-base-layer.scss`**: 変更なし
- **Tailwind ユーティリティクラス**: `mx-auto`, `flex`, `px-gutter-row` 等は Tailwind が生成するクラスであり、SCSS 側の管理対象ではない
- **`src/scss/_archive/` 配下のファイル**: 過去計画、過去監査、退避済み SCSS は参照・調査・置換対象に含めない
