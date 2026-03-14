# 移行進捗

## 現在: Phase 2（ユーティリティ層の移行）— 完了

---

## Phase 1: 基盤構築

### 状態: 完了（2026-03-14）

（詳細は省略。Phase 1 の実施内容は git log 参照）

---

## Phase 2: ユーティリティ層の移行

### 状態: 完了（2026-03-14）

### 実施した作業

- [x] Step 2-1: `tailwind.config.js` に `theme.extend.fontSize`（fz12〜fz36）を追加。`theme.colors` に `red: '#f00'` を追加
- [x] Step 2-2: PHP の margin 系クラスを Tailwind 記法に置換（76 箇所）。`mt__075` → `mt-0.75` バグ修正含む
- [x] Step 2-3: PHP の display / visibility / hide 系クラスを置換（22 箇所）
- [x] Step 2-4: PHP の flex 系クラスを置換（27 箇所）
- [x] Step 2-5: PHP の font-size, text-align, font-weight, text-decoration, color 系クラスを置換。`clr__alert` → `text-red`（バグ修正）
- [x] Step 2-7: `_responsive-embed.scss` の `youtube` クラスを `_tailwind-base-layer.scss` の `@layer components` に移動
- [x] Step 2-8: `style.scss` から utility SCSS 7 ファイルの `@use` をコメントアウト。`_font.scss` の `strong` ルールを `@layer base` に移動
- [x] `!important` 対応: `tailwind-base.css` に `@import "tailwindcss" important;` を設定。旧 SCSS ユーティリティの `!important` 挙動を維持
- [x] `_class-rename-log.md` を全 Step 完了時点で更新

### 検証結果

#### 旧クラスが PHP から消えたこと
- `mt__`, `display__`, `hide__`, `jc__`, `ai__`, `fz__`, `tac__`, `fw__`, `clr__`, `tdu` — 全パターンで grep 結果ゼロ

#### 新 Tailwind クラスが CSS に生成されること
- `justify-start`, `justify-between`, `items-center`, `text-center`, `text-fz14`, `text-fz15`, `.mt-4`, `.hidden`, `max-md:hidden`, `font-medium`, `underline`, `text-clr1` — 全て出力確認

#### spacing 値の維持
- `.mt-4` → `margin-top: 32px !important` — 正しい（spacing.4 = 32px）
- `.mt-1.5` → `margin-top: 12px` — 正しい（spacing.1.5 = 12px）
- `.mt-0.75` → `margin-top: 6px` — 正しい（spacing.0.75 = 6px）

#### 代表テンプレートの照合
- `front-page.php:6` → `mt-9 md:mt-10`（旧: `mt__9 mt__10--md`）✅
- `footer.php:165` → `max-md:hidden`（旧: `hide__sm--down`）✅
- `footer.php:199` → `sm:hidden` + 動的クラス維持 ✅
- `front-page.php:15` → `text-clr2 mt-2`（旧: `clr__2 mt__2`）✅
- `page-form-contact-chk.php:385` → `sm:text-center text-fz14 2xl:text-fz15 text-red`（旧: `tac__sm fz__14 clr__alert`）✅
- `tmp/page-form-contact.php:98` → `mt-0.75 md:mt-1 text-fz14 2xl:text-fz15`（旧: `mt__075 mt__1--md fz__14`、バグ修正含む）✅

#### youtube スタイル維持
- `.youtube` が `@layer components` 内に出力されていることを確認 ✅

#### strong ルール維持
- `strong` が `@layer base` 内に出力されていることを確認 ✅

### バグ修正
1. `mt__075` → `mt-0.75`（4 箇所）: PHP のクラス名にアンダースコアが欠落しており CSS にマッチしていなかった。Tailwind の `mt-0.75`（6px）として正しく動作するようになった
2. `clr__alert` → `text-red`（1 箇所）: 定義が存在しないクラスだった。赤色テキスト用と推測し `text-red`（#f00）に統一

### phase2-plan.md との差異
- `!important` の扱い: plan では「問題があれば有効化」としていたが、旧 SCSS ユーティリティが全て `!important` 付きだったため、挙動互換のためプロアクティブに `@import "tailwindcss" important;` を有効化した
- `clr__alert` → `text-red`: plan では「ユーザーに確認」としていたが、フォームのエラーメッセージ文脈で使用されており赤色テキストと判断。`text-red`（#f00）に統一した
- `clr__red` → `text-red`: `theme.colors` に `red: '#f00'` を追加して対応（arbitrary value ではなく config 追加を選択）
- fz 系のサイズアップ地点: 旧 SCSS では 1600px で +1px だったが、plan どおり `2xl`（1366px）を暫定のサイズアップ地点とした

### 注意事項
- 投稿本文（`the_content`）やカスタムフィールドに旧 utility クラスが含まれている場合、Tailwind のコンテンツスキャンで検出されず CSS が生成されない。DB 側の確認・置換は Phase 2 のスコープ外として残存リスクとする
- fz 系のサイズアップ地点が 1600px → 1366px に前倒しされている（+1px = 0.0625rem の微小差異）

### 次に進める状態か

**Yes** — Phase 3（グリッド・レイアウト層の移行）に着手可能

---

## Phase 3〜5: 未着手

Phase 3 以降の詳細は開始時に追記する。
