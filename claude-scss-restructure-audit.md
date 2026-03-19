# SCSS 再構成 PR#6 実装精査報告

調査日: 2026-03-19

---

## 目次

1. [精査の目的と方法](#1-精査の目的と方法)
2. [結論サマリ](#2-結論サマリ)
3. [問題 A: c-nav__overlay の取り残し](#3-問題-a-c-nav__overlay-の取り残し)
4. [問題 B: component stub ファイルの残存](#4-問題-b-component-stub-ファイルの残存)
5. [PHP 側の状態](#5-php-側の状態)
6. [project SCSS 側の状態](#6-project-scss-側の状態)
7. [原因の所在](#7-原因の所在)
8. [対処案](#8-対処案)

---

## 1. 精査の目的と方法

PR#6（feat: c-\* → p-\* SCSS再構成）マージ後の現状を、以下3つの計画書と突合して精査した。

- `src/scss/scss-structure-investigation.md`（調査報告書 v3）
- `src/scss/scss-class-replacement-ledger.md`（置換台帳）
- `src/scss/scss-implementation-execution-prompt.md`（実装プロンプト）

精査対象:

- component/ 配下の全 SCSS ファイル
- project/ 配下の全 SCSS ファイル
- 全 PHP ファイル（42ファイル）
- src/js/header.js

---

## 2. 結論サマリ

| 項目 | 状態 |
|---|---|
| PHP 側の c-\* → p-\* 置換 | **完了。対象 c-\* の残存ゼロ** |
| project SCSS 側の受け皿定義 | **完了。台帳の全対象クラスに p-\* が定義済み** |
| component stub の残存 | **問題あり。stub 化されたが削除されていない（6ファイル）** |
| c-nav__overlay | **問題あり。component/_header.scss に死コードとして残存** |
| 対象外 c-\* の巻き込み | **なし。対象外クラスは未変更** |
| ビルド | **成功（PR#6 で確認済み）** |

**重大な実装ミス（スタイル欠落・PHP置換漏れ）はない。**
問題は「不要になったファイル/コードの掃除が未完了」に集約される。

---

## 3. 問題 A: c-nav__overlay の取り残し

### 現状

`component/_header.scss` に以下が残っている:

```scss
.c-nav {
  &__overlay {
    display: none;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
  }
}
```

### 一方で

- `project/_header.scss` には `.p-nav__overlay` が定義済み（z-index + background）
- `src/js/header.js:12` では `class="p-nav__overlay"` で要素を生成している
- PHP テンプレートには `c-nav__overlay` の参照ゼロ

### つまり

`.c-nav__overlay` は **どこからも参照されていない死コード**。JS 側が `p-nav__overlay` を使っているため、component 側の定義は CSS に出力されるが一切適用されない。

ただし `.c-nav__overlay` の `display: none; position: fixed; top/right/bottom/left: 0` は **レイアウト基盤のスタイル**で、`.p-nav__overlay` にはこれがない（z-index と background のみ）。JS で動的に追加される要素なので `display: none` は不要な可能性が高いが、`position: fixed` と `inset: 0` は `.p-nav__overlay` 側に吸収すべきかどうか要確認。

### 原因の所在

**台帳の設計段階で除外された。**

- 置換台帳の Header 対象 class 群に `c-nav__overlay` が含まれていない
- 調査報告書 v3 にも `c-nav__overlay` は記載なし（investigation v2 の 2.2 節で `c-nav__overlay — SCSS にのみ定義（PHP 使用箇所未発見）` と記録されている）
- 実装プロンプトは台帳を忠実に実行する設計なので、台帳にないものは触らない

→ **計画段階で PHP 未使用 = 対象外と判断したが、JS で動的生成されるクラスであることを見落とした。**

---

## 4. 問題 B: component stub ファイルの残存

### 現状

以下 6 ファイルが stub 化されて残っている:

| ファイル | 内容 |
|---|---|
| `component/_header.scss` | `c-nav__overlay` + migration コメント（22行） |
| `component/_footer.scss` | migration コメントのみ（6行） |
| `component/_search.scss` | migration コメントのみ（3行） |
| `component/_post.scss` | `img.size-*` セレクタ + migration コメント（15行） |
| `component/_post-feed.scss` | migration コメントのみ（6行） |
| `component/_typ.scss` | 対象外 c-\* クラス 5 個（105行） |

### これらの問題点

1. `style.scss` がこれら全てを import している → CSS に空の `@layer components {}` や stub コメントが出力される
2. `component/_search.scss` と `project/_search.scss` が両方存在する → 「2箇所にある」ように見える
3. `component/_footer.scss` と `component/_post-feed.scss` は中身が migration コメントだけで実質空

### ただし壊れてはいない

- `component/_typ.scss` に残る 5 クラス（`c-ttl__underline--ta`, `c-ttl__rhombus`, `c-ttl__horizontal`, `c-form__ttl-detail`, `c-typ__marker`）は **対象外として正しく保護されている**
- `component/_post.scss` の `img.size-*` は WordPress コア画像セレクタであり、component/project のどちらにも属さないグローバルスタイル
- stub ファイルの import が残っていてもビルドは通り、実害はない

### 原因の所在

**実装プロンプトの設計通り。** 実装プロンプト L67:

> `component/_*.scss` の削除や src/scss/style.scss の import 整理は最後まで後回しにする

台帳 L96:

> `component/_*.scss` の削除や import 整理は、その partial 内の対象 class が全廃できる最後の段階まで行わない

→ これは意図的な設計判断。ただし「最後の段階」の実行がまだ行われていない。

---

## 5. PHP 側の状態

**台帳対象の c-\* クラスは PHP から完全に除去されている。**

全 42 PHP ファイルを以下のパターンで grep した結果、該当ゼロ:

- `c-header`, `c-toolbar`, `c-nav`, `c-footer`, `c-footernav`
- `c-post__`, `c-post-feed`, `c-search`
- `c-ttl__`, `c-form__ttl`, `c-typ`

PHP に残っている c-\* は全て対象外クラス（`c-headline`, `c-replace`, `c-likepost`, `c-button`, `c-micromodal` 等）であり、台帳通り。

---

## 6. project SCSS 側の状態

台帳の全 6 領域について、受け皿 p-\* の定義状況を確認した。

| 領域 | 状態 | 詳細 |
|---|---|---|
| Search | **完了** | `p-search`, `p-search__form`, `p-search__control`, `p-search__submit`, `p-search__icon` 全定義済み |
| Header/Toolbar/Nav | **完了** | `p-header__logo-block`, `p-toolbar__button`, `p-toolbar__line`, `p-nav__button`, `p-nav__button--children`, `p-nav__button--recruit`, `p-nav__item--close` 全定義済み |
| Footer/Footernav | **完了** | `p-footer__info`, `p-footer__links`, `p-footer__fade-*`, `p-footernav__button` 及び全バリエーション定義済み |
| Post/Post Feed | **完了** | `p-post__archive--thumbnail`, `p-sidebar__post--*`, `p-related__post--*`, `p-latest-card__*`, `p-post-feed__dl` 全定義済み |
| Post Titles/Widget Titles/Search Titles | **完了** | `p-ttl__post--archives`, `p-ttl__post--widget`, `p-ttl__post--single`, `p-ttl__ptn-dgnl`, `p-ttl__widget*`, `p-ttl__search*` 全定義済み |
| Global Typ/Generic Title/Form Title | **完了** | `p-ttl__large/medium/small/xsmall`, `p-ttl__bg-grd`, `p-form__ttl`, `p-typ`, `p-typ__xs/s/m/l` 全定義済み。placeholder `%font-min`, `%ttl__*` と mixin `typ__line-height` も移管済み |

---

## 7. 原因の所在

| 問題 | 計画の問題か | 実装の問題か | プロンプトの問題か |
|---|---|---|---|
| c-nav__overlay の取り残し | **計画** | — | — |
| stub ファイル未削除 | — | — | **プロンプト（意図的後回し）** |

### 計画の問題: c-nav__overlay

調査報告書で `c-nav__overlay` を「PHP 使用箇所未発見」として記録したが、JS で動的生成される要素であることを確認しなかった。置換台帳の対象 class 群から漏れたため、実装 AI はこれに触れなかった。

実装プロンプトとしては「台帳にないものは触るな」が正しい設計であり、プロンプトと実装 AI は台帳に忠実に動いた。

### プロンプトの設計判断: stub 後回し

「component の削除は最後の段階で」は安全策として合理的だった。ただし、PR#6 が「最後の段階」として完結したのに、その掃除フェーズが実行されていない。

---

## 8. 対処案

### A. c-nav__overlay → p-nav__overlay に統合

- `.c-nav__overlay` の全スタイル（`display: none; position: fixed; top/right/bottom/left: 0`）を `project/_header.scss` の `.p-nav__overlay` に吸収させる
- JS 側（`src/js/header.js:12`）は既に `p-nav__overlay` で要素生成しており、統一済み。JS の修正は不要
- 吸収後、`component/_header.scss` を削除し、`style.scss` から import 除去

### B. 空 stub ファイルの削除

- `component/_footer.scss`（空）→ 削除、style.scss から import 除去
- `component/_search.scss`（空）→ 削除、style.scss から import 除去
- `component/_post-feed.scss`（空）→ 削除、style.scss から import 除去

### C. img.size-\* の移動

- `component/_post.scss` の `img.size-orig_thumbnail_small, img.size-square_thumbnail_small` を `project/_post.scss` に移動
- 元の記載位置は `@layer components {` 直後、archive セクションの前
- 移動後、`component/_post.scss` を削除し、`style.scss` から import 除去

### D. component/_typ.scss に残る 5 クラス + h1/h2 reset の移行計画

#### 経緯

- `c-ttl__underline--ta`, `c-ttl__rhombus`, `c-ttl__horizontal`, `c-form__ttl-detail`, `c-typ__marker` の 5 クラス
- 3つの計画書（調査報告書 v3、置換台帳、実装プロンプト）のどこにも明示的に対象外とは書かれていない
- 台帳のセクション6「Global Typ」の対象 class 群リストに含まれていなかったため、実装 AI が「リストにない＝対象外」と判断して残した
- **原因: 台帳の対象リストが網羅的でなかった（計画の問題）**

#### PHP 利用状況

全 5 クラスについて PHP / JS を全件 grep した結果:

| クラス | PHP 利用箇所 | JS 利用箇所 |
|---|---|---|
| `c-ttl__underline--ta` | ゼロ | ゼロ |
| `c-ttl__rhombus` | ゼロ | ゼロ |
| `c-ttl__horizontal` | ゼロ | ゼロ |
| `c-form__ttl-detail` | ゼロ | ゼロ |
| `c-typ__marker` | ゼロ | ゼロ |

#### c-ttl__rhombus と p-ttl__rhombus の関係

**親子関係**。p がコンテナ、c が文字装飾。

- `p-ttl__rhombus`（project/_typ.scss:19）: `padding; background-color; text-align: center`
- `c-ttl__rhombus`（component/_typ.scss:21）: `display: inline-block; font-size; letter-spacing; ::before/::after` でひし形 SVG

#### c-ttl__horizontal と p-ttl__horizontal の関係

**親子関係**。p が外枠（`::after` で横線装飾）、c が内側の文字ブロック（`display: inline-block; padding; background-color`）。

- `p-ttl__horizontal`（project/_typ.scss:32）: `position: relative; z-index: 1; text-align: center` + `::after` 横線
- `c-ttl__horizontal`（component/_typ.scss:74）: `display: inline-block; padding; background-color` + `--bgc-y` バリエーション

本番テンプレートでは `p-ttl__horizontal--body` が子要素として既に使用されている（`tmp/content/container-feed-post.php:4`）。

#### h1/h2 reset の扱い

`component/_typ.scss:12-14` に `h1, h2 { font-weight: normal; }` がある。

- `_tailwind-base-layer.scss` の h1-h6 定義は `line-height: inherit` のみで、`font-weight` を設定していない
- この `font-weight: normal` は有効なスタイルとして機能している

→ `_tailwind-base-layer.scss` の h1-h6 ブロック（L129-136）に `font-weight: normal` を追加する。

#### 移行計画

全 5 クラスを p-\* に rename して project 側に移動する。

| 対象 | rename 先 | 移動先ファイル | 備考 |
|---|---|---|---|
| `c-ttl__rhombus` | `p-ttl__rhombus--inner` | `project/_typ.scss` | 既存 `p-ttl__rhombus` の子要素として統合 |
| `c-ttl__horizontal` | `p-ttl__horizontal--inner` | `project/_typ.scss` | 既存 `p-ttl__horizontal` の子要素として統合 |
| `c-ttl__horizontal--bgc-y` | `p-ttl__horizontal--inner-bgc-y` | `project/_typ.scss` | `c-ttl__horizontal` のバリエーション。一緒に移動 |
| `c-ttl__underline--ta` | `p-ttl__underline--ta` | `project/_typ.scss` | PHP 未使用だが p-\* として保持 |
| `c-form__ttl-detail` | `p-form__ttl-detail` | `project/_typ.scss` | p-\* として保持 |
| `c-typ__marker` | `p-typ__marker` | `project/_typ.scss` | p-typ 系として保持 |
| `h1, h2 { font-weight: normal }` | — | `_tailwind-base-layer.scss` L129-136 に追加 | base reset として移動 |

#### 同時修正が必要な箇所

- SCSS: `component/_typ.scss`（ソース） → `project/_typ.scss` + `_tailwind-base-layer.scss`（ターゲット）
- style.scss: 全移動完了後に `component/_typ` の import を除去
- component/_typ.scss: 全移動完了後に削除
