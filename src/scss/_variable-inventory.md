# SCSS 変数 棚卸しログ

> SCSS → Tailwind CSS 移行における foundation 変数の変換先一覧
> 作成日: 2026-02-27
> 参照: [移行計画書 §1.4](./tailwind-migration-plan.md)

---

## カテゴリ凡例

| カテゴリ | 変換先 | 説明 |
|---|---|---|
| **A** | `:root` CSS 変数 | サイト全体で参照されるブランドカラー・グレースケール |
| **B-config** | `tailwind.config.js` | Tailwind がユーティリティを生成する仕組みに収まる値 |
| **B-body** | `@layer base` body ルール直接記述 | body 要素でのみ使用。変数化不要 |
| **C** | `.p-form` スコープ CSS 変数 | フォームコンポーネント内で完結する設計トークン |
| **廃止** | 削除 | コメントアウト済み or 不要になった変数 |
| **維持** | SCSS 関数として維持 | §1.3 方針に基づく |

---

## `_variables-color.scss`（24変数）

| # | 変数 | カテゴリ | 変換後 | 備考 |
|---|---|---|---|---|
| 1 | `$clr1` | A | `--clr1: #4FBA43` | |
| 2 | `$clr2` | A | `--clr2: #9BD22D` | |
| 3 | `$clr3` | A | `--clr3: #725907` | |
| 4 | `$clr4` | A | `--clr4: #B69941` | |
| 5 | `$clr5` | A | `--clr5: #f6f0dd` | |
| 6 | `$clr-prim-green` | A | `--clr-prim-green: #41c45d` | |
| 7 | `$grd1` | A | `--grd1: linear-gradient(120deg, #9BD22D, #4FBA43)` | |
| 8 | `$grd2` | A | `--grd2: linear-gradient(90deg, #FDD88A, #9E8004)` | |
| 9 | `$black` | A | `--black: #222` | |
| 10 | `$clrg50` | A | `--clrg50: #fbfbfb` | `color.scale()` → ハードコード |
| 11 | `$clrg100` | A | `--clrg100: #f5f5f5` | 同上 |
| 12 | `$clrg200` | A | `--clrg200: #e8e8e8` | 同上 |
| 13 | `$clrg300` | A | `--clrg300: #d5d5d5` | 同上 |
| 14 | `$clrg400` | A | `--clrg400: #b0b0b0` | 同上 |
| 15 | `$clrg500` | A | `--clrg500: #959595` | 同上 |
| 16 | `$clrg600` | A | `--clrg600: #858585` | 同上 |
| 17 | `$clrg700` | A | `--clrg700: #767676` | 同上 |
| 18 | `$clrg800` | A | `--clrg800: #5b5b5b` | 同上 |
| 19 | `$clrg900` | A | `--clrg900: #3f3f3f` | 同上 |
| 20 | `$gray` | A | `--gray: var(--clrg700)` | |
| 21 | `$body-bg` | B-body | `#fff` ハードコード | テーブルでの使用(2箇所)も `#fff` ハードコード |
| 22 | `$body-color` | B-body | `var(--black)` | |
| 23 | `$link-color` | A | `--link-color: var(--clrg800)` | |
| 24 | `$link-hover-color` | A | `--link-hover-color:` ハードコード値 | `color.scale()` → ビルド済みCSSから取得 |

---

## `_variables.scss`（25変数 + 1関数）

| # | 変数 | カテゴリ | 変換後 | 備考 |
|---|---|---|---|---|
| 25 | `$grid-breakpoints-sm` | B-config | `theme.screens.sm: '576px'` | |
| 26 | `$grid-breakpoints-md` | B-config | `theme.screens.md: '811px'` | |
| 27 | `$grid-breakpoints-lg` | B-config | `theme.screens.lg: '1025px'` | |
| 28 | `$grid-breakpoints-xl` | B-config | `theme.screens.xl: '1280px'` | |
| 29 | `$grid-breakpoints-xxl` | B-config | `theme.screens.2xl: '1366px'` | |
| 30 | `$grid-breakpoints`（map） | B-config | 同上（個別値で対応） | |
| 31 | `$container-max-sm` | B-config | `theme.extend.maxWidth` | |
| 32 | `$container-max-md` | B-config | 同上 | |
| 33 | `$container-max-lg` | B-config | 同上 | |
| 34 | `$container-max-xl` | B-config | 同上 | |
| 35 | `$container-max-xxl` | B-config | 同上 | |
| 36 | `$container-max-widths`（map） | B-config | 同上（個別値で対応） | |
| 37 | `$grid-columns` | B-config | 不要（Tailwind `grid-cols-12` 標準） | |
| 38 | `$font-family-sans-serif` | B-config | `theme.fontFamily.sans` | |
| 39 | `$font-family-serif` | B-config | `theme.fontFamily.serif` | |
| 40 | `$font-family-monospace` | B-config | `theme.fontFamily.mono` | |
| 41 | `$font-family-base` | B-body | `theme('fontFamily.sans')` | = `$font-family-sans-serif` |
| 42 | `$font-size-base` | B-body | `16px` | ブラウザデフォルトと同じ |
| 43 | `$font-weight-base` | B-body | `normal` | |
| 44 | `$line-height-base` | B-body | `1.5` | |
| 45 | `$border-radius` | B-config | `theme.borderRadius.DEFAULT: '6px'` | |
| 46 | `$transition-base` | B-config | `theme.transitionDuration.DEFAULT: '200ms'` | |
| 47 | `$space_values`（map） | B-config | `theme.spacing` | |
| 48 | `$space_values_with_clamp`（map） | **廃止** | 削除 | `_margin.scss` でもコメントアウト済み |
| 49 | `$layout_zindex`（map） | B-config | `theme.zIndex` | SCSS 関数と併用 |
| 50 | `get_zindex()`（関数） | 維持 | SCSS 関数として維持 | §1.3 方針 |

---

## `_variables-form.scss`（21変数）

| # | 変数 | カテゴリ | 変換後 | 備考 |
|---|---|---|---|---|
| 51 | `$input-padding-x` | C | `--form-padding-x: 0.625rem` | |
| 52 | `$input-padding-y` | C | `--form-padding-y: 0.625rem` | |
| 53 | `$input-line-height` | C | `--form-line-height: 1.25` | |
| 54 | `$input-bg` | C | `--form-bg: #fff` | |
| 55 | `$input-color` | C | `--form-color: var(--clrg700)` | |
| 56 | `$input-border-color` | C | `--form-border-color: var(--clrg500)` | |
| 57 | `$input-btn-border-width` | C | `--form-border-width: 1px` | |
| 58 | `$input-box-shadow` | C | `--form-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075)` | |
| 59 | `$input-border-radius` | C | `--form-border-radius: 6px` | |
| 60 | `$input-color-placeholder` | — | `@layer base` で直接 `var(--clrg500)` | フォームスコープに入れない |
| 61 | `$input-transition` | C | `--form-transition: border-color ease-in-out 0.15s, ...` | |
| 62 | `$cursor-disabled` | — | `cursor: not-allowed` ハードコード | 1箇所のみ |
| 63 | `$custom-select-line-height` | C | = `--form-line-height` | `$input-line-height` と同値で統合 |
| 64 | `$custom-select-color` | C | = `--form-color` | `$input-color` と同値で統合 |
| 65 | `$custom-select-bg` | C | `--form-select-bg: #fff` | = `--form-bg` と同値だが select 固有 |
| 66 | `$custom-select-bg-size` | C | `--form-select-bg-size: 12px` | select 固有 |
| 67 | `$custom-select-indicator-bgi` | C | `--form-select-indicator` | SVG data URI |
| 68 | `$custom-select-indicator-repeat` | C | select ルール内に直接記述 | `no-repeat` 固定値 |
| 69 | `$custom-select-indicator-position` | C | select ルール内に直接記述 | `right 6px center` 固定値 |
| 70 | `$custom-select-border-width` | C | = `--form-border-width` | `$input-btn-border-width` と同値で統合 |
| 71 | `$custom-select-border-color` | C | = `--form-border-color` | `$input-border-color` と同値で統合 |

---

## 集計

| カテゴリ | 変数数 | 変換先 |
|---|---|---|
| A（ブランドカラー） | 22 | `:root` CSS 変数 |
| B-config（Tailwind config） | 20 | `tailwind.config.js` |
| B-body（body 直書き） | 6 | `@layer base` body ルール |
| C（フォームスコープ） | 19 | `.p-form` CSS 変数 |
| ハードコード | 2 | `$input-color-placeholder`, `$cursor-disabled` |
| 廃止 | 1 | `$space_values_with_clamp` |
| SCSS 関数維持 | 1 | `get_zindex()` |
| **合計** | **71** | |

---

## 廃止するファイル

| ファイル | 理由 |
|---|---|
| `foundation/_variables-color.scss` | 全変数が `:root` CSS 変数に移行 |
| `foundation/_variables-form.scss` | 全変数が `.p-form` スコープ CSS 変数に移行 |
| `foundation/_variables.scss` | 全変数が config / @layer base / SCSS 関数に分散 |
