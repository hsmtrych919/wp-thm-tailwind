# SCSS Structure Investigation v3

調査日: 2026-03-18

## 目的

Tailwind 併合後の現在の SCSS を前提に、次の再構成判断をより具体的な根拠付きで整理する。

- component の `_footer`, `_header`, `_post*`, `_search`, `_typ` を project 側へ統合できるか
- 統合する場合、単なるファイル移動ではなく `c-*` を `p-*` に吸収して廃止するところまで成立するか
- post 系はどういう単位でまとめるのが妥当か
- `_entrystep` を `_form` にまとめられるか
- `_form` は component 化すべきか

## この v3 での「統合」の定義

この文書でいう「統合」は、単に `component` の SCSS を `project` のファイルへ物理移動することではない。

統合の定義は次の通りとする。

1. `c-*` と `p-*` が同一機能を分担している場合、最終的な責務を `p-*` に寄せる
2. 同一 HTML 要素に `class="c-foo p-foo"` が存在する場合、`.p-foo` が両方の宣言を吸収し、`.c-foo` は廃止する
3. 親子関係で `p-*` と `c-*` が分かれている場合は、必要に応じて class rename を伴うが、少なくとも SCSS の責務は project 側へ集約する
4. FLOCSS の component / project 区分の正当性は判断根拠にしない
5. Tailwind 併合後の実態に合う「機能単位での整理」を優先する

## 調査方針

### v2 からのブラッシュアップ点

添付報告書で有効だった情報を取り込み、v2 から次を補強した。

1. 同一要素で本当に `c-*` と `p-*` が共存している箇所をより厳密に整理
2. 親子関係の共存と、同一要素の共存を区別
3. component 側の独立使用クラスを整理し、単純移動で済む領域を明確化
4. 対象 SCSS の行数を実測し、統合後の規模感を見積もりに反映

### この文書の PHP 利用箇所ログの扱い

この v3 では、統合判断に必要な主要な PHP 利用箇所は把握できている。

ただし、これは「実装前の判断材料として十分」という意味であって、実装用の完全な置換台帳とはまだ別である。

特に次は、この文書内で代表例と主要箇所は記録しているが、実装時にそのまま置換チェックリストとして使える粒度までは固定していない。

- header / nav の `c-*`
- footer / footernav の `c-*`
- post / post title の `c-*`
- search の `c-*`
- typ / form title の `c-*`

したがって、実装フェーズに入る前には、対象 class ごとに「どの PHP / functions / tmp テンプレートで使われているか」を列挙した置換台帳を必ず先に作ること。

実装前の必須ルール:

1. 置換対象の `c-*` をクラス単位で列挙する
2. 各 class について、使用 PHP ファイルを全件記録する
3. 同一要素共存か、親子関係か、単独使用かを併記する
4. rename 後の `p-*` 名を先に確定する
5. その台帳を見ながら PHP 置換と SCSS 統合を実施する

要するに、この報告書は判断の土台としては十分だが、実装ミス防止のためには別途「実装用の class 置換台帳」を作る前提で使う。

### 行数の扱い

この文書に行数を書いているのは、あくまで規模感の参考情報としてである。

今後の実装判断や報告では、行数を重要な判断材料として扱わない。

明示方針:

1. 行数の多寡で統合可否を決めない
2. 行数の照合や記録を実装上の主要作業にしない
3. 優先するのは、class の責務、PHP 利用箇所、rename の正確性、中身の整合性である

したがって、以後の報告や実装では「何行あるか」より「何を持っていて、どこで使われているか」を重視する。

### 今回確認した事実

- 対象 SCSS 行数
	- `component/_header.scss`: 258 行
	- `component/_footer.scss`: 188 行
	- `component/_post.scss`: 102 行
	- `component/_post-feed.scss`: 99 行
	- `component/_typ.scss`: 512 行
	- `component/_search.scss`: 61 行
	- `project/_header.scss`: 269 行
	- `project/_footer.scss`: 203 行
	- `project/_post.scss`: 388 行
	- `project/_post-single.scss`: 214 行
	- `project/_typ.scss`: 168 行
- nav 本体は tmp/tmp-nav-main.php で確認
- `%font-min`, `%ttl__xsmall`, `%ttl__small`, `%ttl__medium`, `%ttl__large`, `@mixin typ__line-height` は component/_typ.scss 内だけで閉じている

補足:

- 上記行数は参考情報であり、今後の判断基準としては重視しない

## 1. 現状の class 共存実態

## 1.1 同一要素で `c-*` と `p-*` が共存する主要パターン

### header 系

- `c-header__logo p-header__logo` in header.php:L60

### nav 系

- `p-nav__item--close c-nav__close-button` in tmp/tmp-nav-main.php:L32

補足:

- `c-nav__button` と `p-nav__item` は大半が親子関係であり、同一要素共存ではない
- `c-toolbar__button` と `p-toolbar` も同一要素共存ではなく、別要素で構成されている

### footer 系

- `p-footer c-footer--have-footernavi` in footer.php:L71

補足:

- `c-footer__links--item` と `p-footer__links--item` は親子関係
- `p-footer__fade--button` と `c-footer__fade--button` も親子関係
- `p-footernav__item*` と `c-footernav__button*` も大半は親子関係

### post 系

- `c-post__widget--item-latest p-sidebar__post--item` in sidebar-latest.php:L19
- `p-sidebar__post--thumbnail c-post__widget--thumbnail` in sidebar-latest.php:L21
- `p-sidebar__post--date c-post__archive--date` in sidebar-latest.php:L45
- `p-post__archive--date c-post__archive--date` in tmp/archive-post.php:L33
- `c-post__widget--item p-related__post--item` in tmp/single-blog.php:L67
- `p-related__post--thumbnail c-post__widget--thumbnail` in tmp/single-blog.php:L68
- `p-latest-card__date c-post__archive--date` in tmp/content/feed-post-grid.php:L30

### typ 系

- `p-ttl__post--widget c-ttl__post--widget` in sidebar-latest.php:L27
- `p-ttl__post--widget-related c-ttl__post--widget` in tmp/single-blog.php:L72

### form 系

form での `c-form__ttl` は、`p-form__ttl*` と同一要素共存ではなく親子関係。

- tmp/page-form-contact.php:L75
- page-form-contact-chk.php:L276

### search 系

`c-search` と `p-search` の同一要素共存は現状存在しない。`c-search` は searchform.php:L1 から searchform.php:L5 の専用 UI として単独使用されている。

## 1.2 独立使用の `c-*` が多い領域

この点は v2 より明確になった。

### header / nav

- `c-header__logo-block`
- `c-header__logo-block--login`
- `c-toolbar__button`
- `c-toolbar__line`
- `c-nav__button`
- `c-nav__button--recruit`
- `c-nav__buton--children`

### footer

- `c-footer__info`
- `c-footer__info--alone`
- `c-footer__links`
- `c-footer__fade--button`
- `c-footernav__button`
- `c-footernav__button--reserve`
- `c-footernav__button--tel`
- icon 系 `c-footernav__button--icon*`

### post

- `c-post__archive--thumbnail`
- `c-post__widget--info`
- `c-post-feed__dl` とその子セレクタ群

### typ / form title / body text

- `c-ttl__large`, `c-ttl__small`, `c-ttl__xsmall`
- `c-ttl__ptn-dgnl`, `c-ttl__widget*`, `c-ttl__search*`, `c-ttl__bg-grd`
- `c-form__ttl`
- `c-typ`, `c-typ__s`

重要なのは、これらが「単独使用だから統合不可」という意味ではないことだ。単独使用であっても、それが feature 専用なら `p-*` 側へ rename して project 側に寄せられる。

## 1.3 v2 からの補正点

ここは今回の重要な精度向上ポイント。

1. header / footer / form では、v2 が暗に示していたほど「同一要素での c/p 共存」は多くない
2. 実際には「同じ機能の中で class 体系が二重化している」ケースが多い
3. したがって、統合作業は次の 2 種に分かれる

- A: 同一要素の `c-*` / `p-*` を `p-*` に吸収する作業
- B: 単独使用または親子関係の `c-*` を `p-*` に rename して寄せる作業

方向性自体は v2 と同じだが、実装時の難所は A より B の方が多い。

## 2. 領域別の再評価

## 2.1 header

結論: `p-header` / `p-toolbar` / `p-nav` へ統合してよい。

### 根拠

- header 機能は header.php:L52 から header.php:L60、および tmp/tmp-nav-main.php:L1 から tmp/tmp-nav-main.php:L32 に集約されている
- `c-header`, `c-toolbar`, `c-nav` は他機能への横断再利用ではなく、header 専用部品として閉じている
- 同一要素共存は `c-header__logo p-header__logo` と `p-nav__item--close c-nav__close-button` の 2 系統だけで、残りは rename 主体で処理できる

### 判断

- 物理的統合だけで止める意味は薄い
- 最終的には `c-header*`, `c-toolbar*`, `c-nav*` を `p-*` 側へ寄せるべき

### 追加所感

この領域は v2 の方向性を維持してよい。決定的な反証は見つからない。

## 2.2 footer

結論: `p-footer` / `p-footernav` に統合してよい。

### 根拠

- footer 機能は footer.php:L71 から footer.php:L214 にまとまっている
- `c-footer*` と `c-footernav*` は footer 文脈外の独立再利用が見えない
- 同一要素共存は `p-footer c-footer--have-footernavi` の 1 箇所が代表で、実務上は rename と集約で処理できる

### `:root` について

v2 の判断を維持する。

- `--footernavi-height` を feature ファイル内で持つこと自体は問題ない
- 1 箇所集中はルールではない
- 統合後に `project/_footer.scss` 内で `:root` を維持してよい

### 行数感

- 現状: 188 行 + 203 行
- 統合後の目安: 約 375 行前後

この規模なら、肥大化を理由に棄却するほどではない。

## 2.3 post

結論: post 系は project 側へ寄せるべき。ファイル構成は 2 本が妥当。

### 対象

- `component/_post.scss`
- `component/_post-feed.scss`
- `project/_post.scss`
- `project/_post-single.scss`

### 重要な観測

添付報告書の整理はここで特に有効だった。

- `c-post__widget--item-latest` と `p-sidebar__post--item`
- `c-post__widget--item` と `p-related__post--item`
- `c-post__widget--thumbnail` と `p-sidebar__post--thumbnail` / `p-related__post--thumbnail`
- `c-post__archive--date` と `p-sidebar__post--date` / `p-post__archive--date` / `p-latest-card__date`

これらは同一要素で共存しているが、宣言の役割は大きく衝突していない。ベース見た目と文脈側の追加見た目を分担しているだけなので、`p-*` 側への吸収は十分現実的。

### ファイル構成

v2 の結論を維持するが、命名理由を補強する。

- `_post-archive.scss`
- `_post-single.scss`

理由:

- `_post-archive.scss` に archive, sidebar, related, latest, feed を集約できる
- `_post-single.scss` に single article, author, single title 周辺を集約できる
- 細かい `project/post/` 分割より実用的
- 全 4 ファイルを 1 本にすると約 780 行規模になり、見通しが悪い

### 追加補足

`c-ttl__post--archives`, `c-ttl__post--widget`, `c-ttl__post--single` は post 文脈で使われるため、post 側に吸収して整理する方が自然。

## 2.4 search

結論: `project/_search.scss` 新設が自然。

### 根拠

- `component/_search.scss` は 61 行で小規模
- `p-search` は現状存在しない
- searchform.php:L1 から searchform.php:L5 の専用 UI として閉じている

### 判断

- `c-search*` を `p-search*` に rename
- `project/_search.scss` を新設

これは今回の候補の中で最も単純な統合パターン。

## 2.5 typ

結論: `c-ttl*`, `c-typ`, `c-form__ttl` を `p-*` 側へ寄せる方針は成立する。

### v2 からの補強点

v2 は方向性として正しかったが、添付報告書の情報で次がより明確になった。

- `component/_typ.scss` は 512 行あり、今回の候補群で最も移動量が大きい
- ただし placeholder と mixin は同ファイル内で閉じているため、外部参照の破綻リスクは高くない
- `c-typ`, `c-typ__s`, `c-ttl__large`, `c-ttl__small`, `c-ttl__xsmall` などは多くのテンプレートで単独使用されている

### 重要な判断

ここで重視すべきは「共通基盤だから残すべきか」ではなく、「`c-*` を維持する理由があるか」だ。

現状では、`c-*` を残す理由より、class 体系を一つに寄せるメリットの方が大きい。

### ただし実装量は大きい

この領域は blocker ではないが、実装量は最大。

- `project/_typ.scss` への吸収
- post 用 title の整理
- search title の整理
- form title の整理
- PHP テンプレート全体の class rename

### 結論

- `typ` 統合は可能
- 重大な設計障害はない
- ただし実装順としては後ろに回した方がよい

## 2.6 form

### `_entrystep` → `_form`

結論: Yes。

- `p-entrystep` は問い合わせ導線専用
- tmp/page-form-contact.php:L29, page-form-contact-chk.php:L250, tmp/page-form-contact-thk.php:L17 の限られた範囲でしか使っていない

### `_form` を component 化するか

結論: No。

- `_form.scss` は generic input library ではなく、問い合わせ feature の SCSS
- `p-form`, `p-form__group`, `p-form-caution`, `p-entrystep` をまとめて project に置く方が自然

### 補足

`c-form__ttl` は component/_typ.scss:L368 に残っているが、使用先は form feature 内で完結している。よって form 統合時に `p-form` 側へ一緒に寄せるべき。

## 3. 実施単位の整理

この再構成をやる場合、実作業は次の 2 種に分かれる。

## 3.1 A: `p-*` への吸収統合

対象例:

- `c-header__logo` → `p-header__logo`
- `c-nav__close-button` → `p-nav__item--close`
- `c-post__widget--item-latest` → `p-sidebar__post--item`
- `c-post__widget--thumbnail` → `p-sidebar__post--thumbnail`
- `c-post__archive--date` → `p-post__archive--date`
- `c-ttl__post--widget` → `p-ttl__post--widget`

## 3.2 B: 単独使用 `c-*` の rename + 移管

対象例:

- `c-toolbar__button`, `c-toolbar__line`
- `c-nav__button`, `c-nav__button--recruit`, `c-nav__buton--children`
- `c-footer__info`, `c-footer__links`, `c-footernav__button*`
- `c-post-feed__dl`
- `c-search*`
- `c-typ`, `c-ttl__large`, `c-ttl__small`, `c-form__ttl`

実際の工数は B の方が多い。

## 4. 参考規模感

このセクションは参考情報であり、今後の実装判断の主軸にはしない。

| 統合先 | 現状行数 | 統合後の目安 | 判断 |
|---|---:|---:|---|
| `project/_header.scss` | 258 + 269 | 約 510 行 | 許容範囲 |
| `project/_footer.scss` | 188 + 203 | 約 375 行 | 許容範囲 |
| `project/_search.scss` | 61 | 約 55-60 行 | 問題なし |
| `project/_typ.scss` | 512 + 168 | 約 660 行 | 大きいが成立する |
| post 系 1 本化 | 102 + 99 + 388 + 214 | 約 780 行 | 非推奨 |
| post を 2 本化 | 102 + 99 + 388 / 214 | 約 575 行 + 約 210 行 | 推奨 |

## 5. 優先順位

実行順まで考えるなら次が妥当。

1. search
2. header
3. footer
4. post
5. form
6. typ

理由:

- search は独立度が高く最小
- header / footer は方向性が明快
- post は影響が大きいが整理効果も大きい
- form は feature 内完結なので比較的扱いやすい
- typ は影響範囲が最大で最後が安全

## 6. 総合結論

### v3 判定

| 対象 | v3 判定 | コメント |
|---|---|---|
| header | 統合推奨 | `p-header` 系へ寄せて `c-header` / `c-toolbar` / `c-nav` を整理してよい |
| footer | 統合推奨 | `p-footer` / `p-footernav` 系へ寄せて `c-footer` 系を整理してよい |
| post | 統合推奨 | project 側へ寄せ、`_post-archive.scss` と `_post-single.scss` の 2 本が妥当 |
| search | 統合推奨 | `project/_search.scss` 新設が自然 |
| typ | 統合可能 | 実装量は大きいが、構造的 blocker はない |
| entrystep | 統合推奨 | `_form` に含めるべき |
| form の場所 | project 維持 | component 化は不要 |

### v2 からの最終補正

1. 方向性に決定的な誤りはなかった
2. ただし `c-*` / `p-*` の「同一要素共存」は v2 より限定的だった
3. 実際の主戦場は、同一要素統合より「単独使用 `c-*` の rename + 移管」である
4. typ は方向性としては統合可能だが、移動量と rename 範囲は想像以上に大きい
5. post は 2 本化がより妥当で、細分化や 1 本化よりバランスが良い
6. 実装前には、対象 `c-*` ごとの PHP 利用箇所台帳を別途作る必要がある
7. 行数は参考値に留め、以後の判断は中身と利用箇所ベースで行う

要するに、v2 の結論は維持しつつ、v3 では「どこが本当に同一要素統合なのか」「どこが rename 主体の移管なのか」を明確化した。