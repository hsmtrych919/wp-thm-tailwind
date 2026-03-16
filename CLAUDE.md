# SCSS → Tailwind CSS 移行プロジェクト

## 現在の Phase

**Phase 3: グリッド・レイアウト層の移行**（完了）

次の作業は **Phase 4: コンポーネント・プロジェクト層の移行** の計画と実行。

Phase の詳細は計画書 §7 を参照。Phase 1 の作業内容は §1 を参照。

---

## 禁止事項

1. **spacing スケールを Tailwind デフォルトに合わせるな。** `mt-4` = 32px（現状の `$space_values`）を維持する。Tailwind デフォルトの `mt-4` = 16px にしてはならない。`theme.spacing` で現状スケールを再定義する。
2. **コンポーネントクラスを Tailwind ユーティリティに分解するな。** `.c-button`, `.p-header` 等は `@layer components` でクラスごと定義する。
3. **SCSS 計算関数を削除・書き換えするな。** `g.rem()`, `g.get_vw()`, `g.get_lh()` 等はそのまま維持する。
4. **ベンダー CSS に手を入れるな。** FontAwesome, Swiper, Micromodal, scroll-hint, Ultimate Member, WP Instagram Feed は SCSS 変数→CSS 変数の差し替えのみ。ロジックやセレクタを変更しない。
5. **現在の Phase 以外のファイルを変更するな。** Phase 1 なら §1 の範囲のみ。ユーティリティ SCSS の削除（Phase 2）、PHP テンプレートの書き換え（Phase 2/3/6）等を先行してやらない。
6. **クラス名を変更したら `src/scss/_class-rename-log.md` に記録せよ。** 記録せずに次の作業に進むな。
7. **計画書に書かれていない方針判断を勝手にするな。**「モダン化」「リファクタリング」等の価値判断が必要な場合は必ずユーザーに確認する。
8. **ブレークポイント mixin、`@extend`、hover mixin を書き換えるな。** SCSS コンパイルが Tailwind 処理より先に実行されるため、`@layer` 内でそのまま動作する。`@screen md` への変換、`@extend` の展開、Tailwind バリアントへの置き換えは全て不要（計画書 §4.3 #3, #4, #7 で確定済み）。

---

## 作業ルール

1. **作業開始時に `progress.md` を読め。** 前回セッションの続きから作業を再開する。
2. **計画書を読んでから作業を開始せよ。** 該当 Phase のセクションを必ず確認する。
3. **1 Phase を完了したら `progress.md` を更新せよ。** 完了した作業・残作業・次の Phase の開始条件を記録する。
4. **SCSS 変数を CSS 変数に変換する際は `src/scss/_variable-inventory.md` のカテゴリ分類に従え。** カテゴリ A → `:root`、B-config → `tailwind.config.js`、B-body → `@layer base` body 直書き、C → `.p-form` スコープ。一括で `:root` に入れるな。
5. **リセット CSS の差分補足は `src/scss/_reset-diff-inventory.md` に従え。** destyle.css 削除時に Tailwind Preflight でカバーされない項目を `@layer base` で補足する。
6. **CSS Grid と Flex の使い分けを守れ。** ページレイアウト（旧 `.l-row` 系）→ Flex。カードグリッド（旧 `.l-grid` 系）→ CSS Grid。理由は計画書 §3.2 参照。
7. **ガターは Tailwind カスタムユーティリティを使え。** `theme.extend.padding` / `theme.extend.gap` で定義する。`@layer components` で旧クラスを維持しない。変換表は計画書 §3.4 参照。

---

## 検証ルール

1. **`rem` / `vw` の検証は件数ではなく「クラス名と出力宣言」で確認せよ。** 単純な出現件数の比較は禁止。
2. **比較対象は原則として移行前 CSS と移行後 CSS の 2 ファイルに限定する。** 例: `css/init.css` と `css/style.css`。検証のために無関係なソースファイルや他言語ファイルへ探索を広げるな。
3. **確認単位は「`rem` / `vw` を含む既存クラスセレクタ」と「そのクラス内の宣言値」。** 次の順序で確認すること:
    - クラスが移行後 CSS に残っているか
    - 同じクラスで `rem` / `vw` を使う宣言が残っているか
    - 値の差がある場合、それが実寸を保った正規化（例: `56rem` → `3.5rem`）か、挙動変更かを判定する
4. **gutter 系の `vw` 変数は別枠で必ず直接比較する。** `--unit`, `--gutter`, `--gutter-row` の系列値が移行前後で一致していることを確認する。
5. **検証結果の記録は件数中心で書くな。** `progress.md` には「どの代表クラスを照合し、値が維持されたか」「崩れがなかったか」を優先して記録する。
6. **Phase 1 以降も同じ検証方針を使う。** 特にユーティリティ層・レイアウト層の移行では、件数の一致ではなく既存クラスの出力保持を根拠に判断する。

---

## 参照ファイル

| ファイル | 内容 | いつ読むか |
|---|---|---|
| `src/scss/tailwind-migration-plan.md` | マスター計画書（全 Phase の設計・方針・変換表） | 各 Phase 開始時に該当セクションを読む |
| `src/scss/_variable-inventory.md` | SCSS 変数 71 個のカテゴリ分類と変換先 | Phase 1（§1.4）で参照 |
| `src/scss/_reset-diff-inventory.md` | destyle.css vs Tailwind Preflight の差分一覧 | Phase 1（§1.2）で参照 |
| `src/scss/_class-rename-log.md` | クラス名変更の全記録 | Phase 2/3 でクラス名を変更するたびに更新 |
| `src/scss/_archive/` | 議論資料（handoff-log, grid-strategy, analysis） | 通常は参照不要。経緯を確認したい場合のみ |

---

## ディレクトリ構造

```
wp-thm/                    ← Claude Code 起動ディレクトリ（git root）
├── CLAUDE.md              ← このファイル
├── progress.md            ← 進捗管理
├── *.php                  ← PHP テンプレート（Phase 2/3/6 で編集）
├── package.json
├── webpack.config.js
└── src/
    └── scss/
        ├── tailwind-migration-plan.md
        ├── _variable-inventory.md
        ├── _reset-diff-inventory.md
        ├── _class-rename-log.md
        ├── _archive/
        ├── style.scss
        ├── foundation/
        ├── component/
        ├── project/
        ├── layout/
        ├── global/
        ├── mixins/
        └── utility/
```

---

## Phase 一覧（計画書 §7 より）

| Phase | 内容 | 計画書セクション | 状態 |
|---|---|---|---|
| 1 | 基盤構築（config, base, CSS 変数, パイプライン） | §1 | 完了 |
| 2 | ユーティリティ層の移行 | §2 | 完了 |
| 3 | グリッド・レイアウト層の移行 | §3 | 完了 |
| 4 | コンポーネント・プロジェクト層の移行 | §4 | 着手可能 |
| 5 | ベンダー CSS の整理 | §5 | 未着手 |
