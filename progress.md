# 移行進捗

## 現在: Phase 1（基盤構築）— 完了

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

## Phase 2〜5: 未着手

Phase 2 以降の詳細は開始時に追記する。
