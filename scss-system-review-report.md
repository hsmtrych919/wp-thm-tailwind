# SCSS System Review Report

sample.html 作成を通じた SCSS + Tailwind ハイブリッドシステムの運用レビュー。

---

## 1. 良い点（Strengths）

### 1-1. CSS 変数ベースのカラーシステムが柔軟
- `--clr1` 〜 `--clr5`、`--clrg50` 〜 `--clrg900` のグレースケール変数が充実
- 新規クラス作成時にカラー値をハードコードせずに済み、一貫性を保てる
- グラデーション変数（`--grd1`, `--grd2`）もあり、ブランドカラーの適用が容易

### 1-2. g.rem() 関数が便利
- `g.rem(16)` のように px 感覚で書けて自動 rem 変換される
- 既存クラスとの spacing 整合が取りやすい

### 1-3. ブレークポイント mixin が統一的
- `@media #{g.$sm}`, `#{g.$md}` ... のパターンで、既存・新規クラス問わず同じ書き方
- Tailwind の `sm:`, `md:` と BP 値が一致しており混乱が少ない

### 1-4. @layer components が明確
- 全カスタムクラスが `@layer components` 内にあるため、Tailwind ユーティリティとの優先度が予測可能
- 新規クラスも同じレイヤーに追加すれば自然に統合される

### 1-5. BEM 命名規則が徹底
- `p-*__*--*` の prefix + BEM で namespace が明確
- コンポーネント間のクラス名衝突がない

---

## 2. 要対応（残課題）

### 2-1. `p-ttl__rhombus--inner` の letter-spacing 移行ミス
- `letter-spacing: 2em` / `4em` が極端に広い
- 移行時のミス。適切な値に修正が必要

### 2-2. container の二重定義 → 解決済み
- `.container`（横幅制御）→ `.l-container` にリネーム（tailwind-base.css @layer utilities）
- `.l-container`（上下padding）→ `.l-container-py` にリネーム（layout/_content.scss @layer components）
- `max-w-container-*` デッドコード削除（tailwind.config.js）
- `@source not inline("container")` 削除
- 詳細: `container-refactor-plan.md`

---

## 3. デザインスタイリング上の発見

### 3-1. clip-path（s-diagonal）
- `clip-path: polygon()` でセクション境界を斜めにカット
- 既存システムとの相性は良い（CSS 変数カラーがそのまま使える）
- モバイルでの clip-path 角度調整が必要（`5%` vs `8%` でモバイル/デスクトップを分けた）

### 3-2. グラデーションテキスト（s-stats）
- `background: var(--grd1); -webkit-background-clip: text;` でブランドグラデーションをテキストに適用
- `-webkit-text-fill-color: transparent` が必要
- 数値のインパクト表現に効果的だが、`-webkit-` prefix に依存

### 3-3. background-attachment: fixed（s-banner）
- パララックス風の固定背景
- iOS Safari では `background-attachment: fixed` が効かない（既知の制限）

### 3-4. Bento Grid
- CSS Grid の `grid-column: span 2` / `grid-row: span 2` で不均等グリッドを実現
- 既存の `gap` 変数と組み合わせ可能
- ただしレスポンシブ時のカラム落ちの制御が複雑になりやすい

### 3-5. Overlap Layout
- `position: absolute` + `transform: translateY(-50%)` でカードを画像に重ねる
- モバイルでは `margin-top: -2rem` で簡易的な重なりに切り替え
- z-index 管理が必要だが、既存の z-index 変数体系（`g.get_zindex()`）とは別管理

---

## 4. 既存クラスの運用観察

### 正常に機能したもの
- `p-ttl__*` タイポグラフィ系: レスポンシブフォントサイズが適切に動作
- `c-button__*` ボタン系: アロー アニメーション、カラーバリエーションが豊富
- `p-post__archive` 記事一覧: flex レイアウト + レスポンシブ幅変更が安定
- `p-form__*` フォーム: CSS 変数ベースのスタイリングで統一感あり
- `p-sidebar__post--*` サイドバー: ウィジェットレイアウトが堅牢

---

## 5. sample.html セクション一覧

| # | セクション | クラス種別 | 特徴 |
|---|---|---|---|
| 1 | Hero | 独自 (s-hero) | グラデーションオーバーレイ + 背景画像 |
| 2 | Typography | 既存 (p-ttl, p-typ) | 全タイトル・本文サイズのショーケース |
| 3 | Buttons | 既存 (c-button) | 全ボタンバリエーション |
| 4 | Feature Cards | 独自 (s-features) | 3カラムグリッド + hover |
| 5 | Bento Grid | 独自 (s-bento) | 不均等 CSS Grid |
| 6 | Stats | 独自 (s-stats) | グラデーションテキスト数字 |
| 7 | Overlap Layout | 独自 (s-overlap) | 画像 + フローティングカード |
| 8 | Diagonal Section | 独自 (s-diagonal) | clip-path 斜めカット |
| 9 | Post Archive | 既存 (p-post__archive) | 記事一覧レイアウト |
| 10 | Sidebar/Widget | 既存 (p-sidebar, p-latest-card) | ウィジェット系 |
| 11 | CTA Banner | 独自 (s-cta) | 非対称画像 + テキスト |
| 12 | Split Layout | 既存 (l-split) | 左右分割 |
| 13 | Split Accent | 独自 (s-split-accent) | 半面背景 + 半面画像 |
| 14 | Timeline | 独自 (s-timeline) | 縦タイムライン |
| 15 | Testimonials | 独自 (s-testimonials) | 引用カード |
| 16 | Pricing | 独自 (s-pricing) | 料金比較テーブル |
| 17 | Stacked Cards | 独自 (s-stacked) | 画像オーバーレイカード |
| 18 | Banner | 独自 (s-banner) | 固定背景バナー |
| 19 | Form | 既存 (p-form) | フォーム入力 |
| 20 | Search | 既存 (p-search) | 検索バー |
| 21 | Pagination | 既存 (c-pagenation) | ページネーション |
| 22 | Post Headings | 既存 (p-ttl__post--single-h2/h3) | 記事内見出し |
| 23 | Post Feed | 既存 (p-post-feed) | ニュースリスト |
| 24 | Q&A | 既存 (p-qa) | 質問回答 |
| 25 | Google Map | 既存 (c-gmap) | 地図埋め込み |
| 26 | Entry Step | 既存 (p-entrystep) | フォーム進捗 |
| 27 | Salon Info | 既存 (p-salon-info) | 定義リスト |

---

## 付録: 的外れ・解決済みの指摘

以下はレビュー時に挙げたが、的外れまたは問題ではなかった項目。記録として残す。

### Tailwind content に .html が含まれない（的外れ）
- WordPress 環境を用意する手間を省くために即席で HTML を使っただけ。本番運用の問題ではない。一時的な作業都合をシステムの課題として挙げた的外れな分析だった。

### レイアウトユーティリティが SCSS 側に偏っている（的外れ）
- レイアウトの複雑さによって SCSS か Tailwind かは変わる。一律にルール化できるものではない。

### spacing の二重体系（的外れ・事実誤認）
- `g.rem()` は「人間が px 感覚で読み書きするために px で書いて rem にコンパイルさせる」アプローチで、計画段階から繰り返し説明されていた。それを「二重体系」として問題視したのは過去の経緯を無視している。
- `mt-2 = 1rem` であり、tailwind.config.js の spacing も rem で定義されている。`mt-2(= 16px)` と px 表記したのは誤り。

### hover mixin が SCSS 限定（的外れ）
- Tailwind の `hover:` variant で対応できるスタイリングはそもそも限定的。複雑な hover 表現は SCSS クラスで書くのが自然。

### hover テキスト色が変わらない（的外れ）
- `p-post__archive--button-category` の hover で文字色を変えず背景色だけ変えるのは意図的なデザイン判断。問題ではない。

### pagenation の px（不問）
- `c-pagenation li` の `width/height: 40px` について。不問。

### p-entrystep の活性状態制御（的外れ）
- WordPress では活性状態は PHP/JS で制御可能。CSS 側で `:nth-child` による自動制御が必要という前提が間違っていた。存在しない問題を作り出していた。

### 推奨アクション表（大半が無効）
- 上記の的外れな分析に基づいて作成した推奨アクション表は大半が無効だった。
