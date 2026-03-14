# リセットCSS 差分棚卸し: destyle.css + \_reboot.scss vs Tailwind Preflight

> 調査日: 2026-03-08
> Tailwind CSS: v4（Preflight + modern-normalize v3.0.1）
> 目的: 現状のリセット環境を Tailwind 移行後も完全に維持するための差分一覧

## 凡例

- ✅ Preflight でカバー済み（補足不要）
- ⚠️ Preflight にない（@layer base で補足必要）
- 🔄 Preflight が異なる値を設定（現状維持には上書き必要）
- 🗑️ IE/旧ブラウザ向け（削除可）

## SCSS 変数の解決値

| 変数参照                     | 解決値                                                                                                                                                                 |
| ---------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `g.$body-bg`                 | `#fff`                                                                                                                                                                 |
| `g.$body-color`              | `#222` ($black)                                                                                                                                                        |
| `g.$font-family-base`        | `avenir,"Noto Sans JP","游ゴシック体",yugothic,"游ゴシック Medium","Yu Gothic Medium","ヒラギノ角ゴ ProN W3","Hiragino Kaku Gothic ProN","メイリオ",meiryo,sans-serif` |
| `g.$font-size-base`          | `16px`                                                                                                                                                                 |
| `g.$font-weight-base`        | `normal`                                                                                                                                                               |
| `g.$line-height-base`        | `1.5`                                                                                                                                                                  |
| `g.$transition`              | `all 0.2s ease-in-out`                                                                                                                                                 |
| `g.$link-color`              | `color.scale(#222, $lightness: 26%)` ($clrg800)                                                                                                                        |
| `g.$link-hover-color`        | `color.scale($link-color, $lightness: 15%)`                                                                                                                            |
| `f.$cursor-disabled`         | `not-allowed`                                                                                                                                                          |
| `f.$input-color-placeholder` | `var(--clrg500)`                                                                                                                                                       |

## サマリー

| 分類                       | 件数 |
| -------------------------- | ---- |
| ✅ カバー済み              | 55   |
| ⚠️ 補足必要                | 35   |
| 🔄 上書き必要              | 14   |
| 🗑️ 削除可（IE/旧ブラウザ） | 9    |

## 高影響項目（レイアウト崩れリスク）

| #   | 項目                                                                       | 影響                                                                     |
| --- | -------------------------------------------------------------------------- | ------------------------------------------------------------------------ |
| 1   | `img, svg, video, canvas, audio, iframe, embed, object { display: block }` | ✅ **対応済み**: 現状は `display` 未指定（ブラウザデフォルト = inline）。Preflight が `block` に変更する。`block` で問題ないため Preflight の挙動をそのまま受け入れる。影響が出た箇所は実装フェーズの目視確認で個別対応 → 末尾備考参照 |
| 2   | `h1-h6 { line-height: inherit }`                                           | ✅ **対応済み**: 補足コードに記載済み |
| 3   | `a { text-decoration: inherit }`                                           | ✅ **対応済み**: 補足コードで `none` に上書き済み |
| 4   | `a { color: inherit }`                                                     | ✅ **対応済み**: 補足コードでカスタムリンク色を設定済み |
| 5   | フォーム要素の `appearance`                                                | ✅ **対応済み**: destyle の `appearance: none` を補足コードに移植済み |
| 6   | `select` の `border-width/style: revert`                                   | ✅ **対応済み**: 補足コードに `revert` を設定済み |

---

## 1. Box Model

| セレクタ               | プロパティ                | 現状の値      | Preflight                    | 判定 | 出典              |
| ---------------------- | ------------------------- | ------------- | ---------------------------- | ---- | ----------------- |
| `*, ::before, ::after` | `box-sizing`              | `border-box`  | `border-box`                 | ✅   | destyle L6-12     |
| `*, ::before, ::after` | `border-style`            | `solid`       | — (`border: 0 solid` で等価) | ✅   | destyle L6-12     |
| `*, ::before, ::after` | `border-width`            | `0`           | — (`border: 0 solid` で等価) | ✅   | destyle L6-12     |
| `*::before, *::after`  | `-webkit-font-smoothing`  | `antialiased` | なし                         | ⚠️   | \_reboot L102-105 |
| `*::before, *::after`  | `-moz-osx-font-smoothing` | `grayscale`   | なし                         | ⚠️   | \_reboot L102-105 |

**注記:** Preflight のユニバーサルリセットは `::backdrop, ::file-selector-button` にも適用（現状にない拡張）。

## 2. Document (html, body)

| セレクタ | プロパティ                      | 現状の値                  | Preflight                      | 判定                         | 出典           |
| -------- | ------------------------------- | ------------------------- | ------------------------------ | ---------------------------- | -------------- |
| `html`   | `line-height`                   | `1.15`                    | `1.5`                          | 🔄                           | destyle L23-27 |
| `html`   | `-webkit-text-size-adjust`      | `100%`                    | `100%`                         | ✅                           | destyle L23-27 |
| `html`   | `-webkit-tap-highlight-color`   | `transparent`             | `transparent`                  | ✅                           | destyle L23-27 |
| `html`   | `text-size-adjust` (unprefixed) | `100%`                    | なし（-webkit- のみ）          | ⚠️                           | \_reboot L33   |
| `html`   | `-ms-overflow-style`            | `scrollbar`               | なし                           | 🗑️                           | \_reboot L34   |
| `html`   | `tab-size`                      | なし                      | `4`                            | ✅ 新規（問題なし）          | —              |
| `html`   | `font-family`                   | なし（html には未設定）   | theme 系フォント               | ✅ 新規（body で上書き予定） | —              |
| `html`   | `font-feature-settings`         | なし                      | `normal`                       | ✅ 新規                      | —              |
| `html`   | `font-variation-settings`       | なし                      | `normal`                       | ✅ 新規                      | —              |
| `body`   | `margin`                        | `0`                       | `0` (ユニバーサル `*`)         | ✅                           | destyle L36-38 |
| `body`   | `position`                      | `relative`                | なし                           | ⚠️                           | \_reboot L90   |
| `body`   | `background-color`              | `#fff`                    | なし                           | ⚠️                           | \_reboot L91   |
| `body`   | `color`                         | `#222`                    | なし                           | ⚠️                           | \_reboot L92   |
| `body`   | `font-family`                   | avenir,"Noto Sans JP",... | theme 系（上書き必要）         | 🔄                           | \_reboot L93   |
| `body`   | `font-size`                     | `16px`                    | なし（html に inherit）        | ⚠️                           | \_reboot L94   |
| `body`   | `font-weight`                   | `normal`                  | なし                           | ⚠️                           | \_reboot L95   |
| `body`   | `line-height`                   | `1.5`                     | `1.5`（html に設定、継承）     | ✅                           | \_reboot L96   |
| `main`   | `display`                       | `block`                   | なし（モダンブラウザでは不要） | ✅ 不要                      | destyle L44-46 |

**注記:**

- Preflight は `html` の `line-height: 1.5` を設定。現状 destyle は `1.15` → body の `1.5` で上書き。Preflight 移行後は html=1.5 → body=1.5 で実質同じだが、html 直下で `1.15` を想定した箇所があれば差異あり。
- `@-ms-viewport { width: device-width }` (\_reboot L59-64) は IE/Edge Legacy 向け → 🗑️ 削除可。

## 3. Headings (h1-h6)

| セレクタ | プロパティ    | 現状の値  | Preflight              | 判定 | 出典           |
| -------- | ------------- | --------- | ---------------------- | ---- | -------------- |
| `h1-h6`  | `font-size`   | `inherit` | `inherit`              | ✅   | destyle L66-76 |
| `h1-h6`  | `font-weight` | `inherit` | `inherit`              | ✅   | destyle L66-76 |
| `h1-h6`  | `line-height` | `inherit` | なし                   | 🔄   | destyle L66-76 |
| `h1-h6`  | `margin`      | `0`       | `0` (ユニバーサル `*`) | ✅   | destyle L66-76 |

## 4. Lists

| セレクタ                     | プロパティ            | 現状の値 | Preflight              | 判定 | 出典              |
| ---------------------------- | --------------------- | -------- | ---------------------- | ---- | ----------------- |
| `ul, ol`                     | `margin`              | `0`      | `0` (ユニバーサル `*`) | ✅   | destyle L81-86    |
| `ul, ol`                     | `padding`             | `0`      | `0` (ユニバーサル `*`) | ✅   | destyle L81-86    |
| `ul, ol`                     | `list-style`          | `none`   | `none`                 | ✅   | destyle L81-86    |
| `dt`                         | `font-weight`         | `bold`   | なし                   | ⚠️   | destyle L91-93    |
| `dd`                         | `margin`              | `0`      | `0` (ユニバーサル `*`) | ✅   | \_reboot L178-183 |
| `dd`                         | `padding`             | `0`      | `0` (ユニバーサル `*`) | ✅   | \_reboot L178-183 |
| `ol ol, ul ul, ol ul, ul ol` | `margin`              | `0`      | `0` (ユニバーサル `*`) | ✅   | \_reboot L168-172 |
| `ol ol, ul ul, ol ul, ul ol` | `padding`             | `0`      | `0` (ユニバーサル `*`) | ✅   | \_reboot L168-172 |
| `ul`                         | `list-style-position` | `inside` | なし                   | ⚠️   | \_reboot L459-461 |

## 5. Vertical Rhythm (margins)

| セレクタ                                                       | プロパティ | 現状の値 | Preflight              | 判定 | 出典           |
| -------------------------------------------------------------- | ---------- | -------- | ---------------------- | ---- | -------------- |
| `p, table, blockquote, address, pre, iframe, form, figure, dl` | `margin`   | `0`      | `0` (ユニバーサル `*`) | ✅   | destyle L51-61 |

## 6. Links (a)

| セレクタ                              | プロパティ         | 現状の値                        | Preflight                        | 判定 | 出典              |
| ------------------------------------- | ------------------ | ------------------------------- | -------------------------------- | ---- | ----------------- |
| `a`                                   | `background-color` | `transparent`                   | なし（ユニバーサルに含まれない） | ⚠️   | destyle L138-142  |
| `a`                                   | `text-decoration`  | `none`                          | `inherit`                        | 🔄   | destyle L138-142  |
| `a`                                   | `color`            | `$link-color` (≈ #222の26%明度) | `inherit`                        | 🔄   | \_reboot L194-206 |
| `a`                                   | `transition`       | `all 0.2s ease-in-out`          | なし                             | ⚠️   | \_reboot L195     |
| `a`                                   | hover `color`      | `$link-hover-color`             | なし                             | ⚠️   | \_reboot L199-201 |
| `a:active, a:focus`                   | `outline`          | `0`                             | なし                             | ⚠️   | \_reboot L203-205 |
| `a:not([href]):not([tabindex])`       | `color`            | `inherit`                       | なし（セレクタごと）             | ⚠️   | \_reboot L214-226 |
| `a:not([href]):not([tabindex])`       | `text-decoration`  | `none`                          | なし                             | ⚠️   | \_reboot L214-226 |
| `a:not([href]):not([tabindex]):focus` | `outline`          | `0`                             | なし                             | ⚠️   | \_reboot L223-225 |

## 7. Text-level Semantics

| セレクタ                                 | プロパティ        | 現状の値               | Preflight                        | 判定 | 出典              |
| ---------------------------------------- | ----------------- | ---------------------- | -------------------------------- | ---- | ----------------- |
| `abbr[title]`                            | `text-decoration` | `underline dotted`     | `underline dotted`               | ✅   | destyle L149-152  |
| `abbr[title], abbr[data-original-title]` | `border-bottom`   | `none`                 | なし（`border: 0 solid` で等価） | ✅   | \_reboot L144-151 |
| `abbr[title], abbr[data-original-title]` | `cursor`          | `help`                 | なし                             | ⚠️   | \_reboot L144-151 |
| `b, strong`                              | `font-weight`     | `bolder`               | `bolder`                         | ✅   | destyle L158-161  |
| `code, kbd, samp`                        | `font-family`     | `monospace, monospace` | theme mono stack                 | 🔄   | destyle L168-173  |
| `code, kbd, samp`                        | `font-size`       | `inherit`              | `1em`                            | 🔄   | destyle L168-173  |
| `pre`                                    | `font-family`     | `monospace, monospace` | theme mono stack                 | 🔄   | destyle L122-125  |
| `pre`                                    | `font-size`       | `inherit`              | `1em`                            | 🔄   | destyle L122-125  |
| `pre`                                    | `overflow`        | `auto`                 | なし                             | ⚠️   | \_reboot L233-240 |
| `small`                                  | `font-size`       | `80%`                  | `80%`                            | ✅   | destyle L179-181  |
| `sub, sup`                               | `font-size`       | `75%`                  | `75%`                            | ✅   | destyle L188-194  |
| `sub, sup`                               | `line-height`     | `0`                    | `0`                              | ✅   | destyle L188-194  |
| `sub, sup`                               | `position`        | `relative`             | `relative`                       | ✅   | destyle L188-194  |
| `sub, sup`                               | `vertical-align`  | `baseline`             | `baseline`                       | ✅   | destyle L188-194  |
| `sub`                                    | `bottom`          | `-0.25em`              | `-0.25em`                        | ✅   | destyle L196-198  |
| `sup`                                    | `top`             | `-0.5em`               | `-0.5em`                         | ✅   | destyle L200-202  |
| `address`                                | `font-style`      | `inherit`              | なし                             | ⚠️   | destyle L127-129  |

## 8. Images / Embedded Content

| セレクタ                     | プロパティ            | 現状の値                                        | Preflight                            | 判定                              | 出典                                |
| ---------------------------- | --------------------- | ----------------------------------------------- | ------------------------------------ | --------------------------------- | ----------------------------------- |
| `img, embed, object, iframe` | `vertical-align`      | `bottom`(destyle) → `middle`(img のみ \_reboot) | `middle`                             | ✅(img) / ⚠️(embed,object,iframe) | destyle L211-216, \_reboot L258-270 |
| `img`                        | `max-width`           | `100%`                                          | `100%`                               | ✅                                | \_reboot L262                       |
| `img`                        | `height`              | `auto`                                          | `auto`                               | ✅                                | \_reboot L263                       |
| `img`                        | `border-style`        | `none`                                          | `0 solid` (ユニバーサル border 等価) | ✅                                | \_reboot L266                       |
| `img`                        | `display`             | なし（未設定）                                  | `block`                              | 🔄 → Preflight に任せる（末尾備考参照） | Preflight 独自                      |
| `svg, video, canvas, audio`  | `display`             | なし（未設定）                                  | `block`                              | 🔄 → Preflight に任せる（末尾備考参照） | Preflight 独自                      |
| `embed, object, iframe`      | `vertical-align`      | `bottom`                                        | `middle`                             | 🔄 → 補足コードで `bottom` 維持        | destyle L211-216                    |
| `a img`                      | `backface-visibility` | `hidden`                                        | なし                                 | ⚠️                                | \_reboot L272-276                   |
| `a img`                      | `transition`          | `all 0.2s ease-in-out`                          | なし                                 | ⚠️                                | \_reboot L272-276                   |
| `a img`                      | `box-shadow`          | `#fff 0 0 0`                                    | なし                                 | ⚠️                                | \_reboot L272-276                   |

## 9. Forms

### 9a. フォーム要素共通

| セレクタ                            | プロパティ                          | 現状の値                                    | Preflight                             | 判定 | 出典                                |
| ----------------------------------- | ----------------------------------- | ------------------------------------------- | ------------------------------------- | ---- | ----------------------------------- |
| `button, input, optgroup, textarea` | `-webkit-appearance` / `appearance` | `none`                                      | — (Preflight はボタン系のみ `button`) | 🔄   | destyle L231-246                    |
| `button, input, optgroup, textarea` | `vertical-align`                    | `middle`                                    | なし                                  | ⚠️   | destyle L231-246                    |
| `button, input, optgroup, textarea` | `color`                             | `inherit`                                   | `inherit`                             | ✅   | destyle L231-246                    |
| `button, input, optgroup, textarea` | `font`                              | `inherit` → sans-serif/100%/1.15 (\_reboot) | `inherit`                             | 🔄   | destyle L231-246, \_reboot L304-310 |
| `button, input, optgroup, textarea` | `background`                        | `transparent`                               | `transparent`                         | ✅   | destyle L231-246                    |
| `button, input, optgroup, textarea` | `padding`                           | `0`                                         | `0` (ユニバーサル `*`)                | ✅   | destyle L231-246                    |
| `button, input, optgroup, textarea` | `margin`                            | `0`                                         | `0` (ユニバーサル `*`)                | ✅   | destyle L231-246                    |
| `button, input, optgroup, textarea` | `outline`                           | `0`                                         | なし                                  | ⚠️   | destyle L231-246                    |
| `button, input, optgroup, textarea` | `border-radius`                     | `0`                                         | `0`                                   | ✅   | destyle L231-246                    |
| `button, input, optgroup, textarea` | `text-align`                        | `inherit`                                   | なし                                  | ⚠️   | destyle L231-246                    |

**フォーム font 上書きフロー:**

1. destyle: `font: inherit` (button, input, optgroup, textarea — **select 除外**)
2. \_reboot: `font-family: sans-serif; font-size: 100%; line-height: 1.15` (button, input, optgroup, **select**, textarea)
3. Preflight: `font: inherit` (button, input, **select**, optgroup, textarea, ::file-selector-button)

→ 現状の最終値は \_reboot の `sans-serif / 100% / 1.15`。Preflight は `inherit` に戻すため、body のフォント設定が継承される。意図的に `sans-serif` にしていた場合は 🔄。

### 9b. Checkbox / Radio

| セレクタ                                                        | プロパティ   | 現状の値      | Preflight                       | 判定    | 出典              |
| --------------------------------------------------------------- | ------------ | ------------- | ------------------------------- | ------- | ----------------- |
| `[type="checkbox"]`                                             | `appearance` | `checkbox`    | なし（リセットしない）          | ✅ 等価 | destyle L252-255  |
| `[type="radio"]`                                                | `appearance` | `radio`       | なし（リセットしない）          | ✅ 等価 | destyle L257-260  |
| `[type="checkbox"], [type="radio"]`                             | `box-sizing` | `border-box`  | `border-box` (ユニバーサル `*`) | ✅      | \_reboot L341-344 |
| `[type="checkbox"], [type="radio"]`                             | `padding`    | `0`           | `0` (ユニバーサル `*`)          | ✅      | \_reboot L341-344 |
| `input[type="radio"]:disabled, input[type="checkbox"]:disabled` | `cursor`     | `not-allowed` | なし                            | ⚠️      | \_reboot L346-353 |

### 9c. ボタン系

| セレクタ                                                                                           | プロパティ       | 現状の値                            | Preflight                          | 判定 | 出典              |
| -------------------------------------------------------------------------------------------------- | ---------------- | ----------------------------------- | ---------------------------------- | ---- | ----------------- |
| `button, input`                                                                                    | `overflow`       | `visible`                           | なし                               | ⚠️   | destyle L267-271  |
| `button, select`                                                                                   | `text-transform` | `none`                              | なし                               | ⚠️   | destyle L278-282  |
| `button, [type="button"], [type="reset"], [type="submit"]`                                         | `cursor`         | `pointer`                           | なし                               | ⚠️   | destyle L288-295  |
| `button, [type="button"], [type="reset"], [type="submit"]`                                         | `appearance`     | `none`                              | `button`                           | 🔄   | destyle L288-295  |
| `button[disabled], [type="button"][disabled], [type="reset"][disabled], [type="submit"][disabled]` | `cursor`         | `default`                           | なし                               | ⚠️   | destyle L297-302  |
| `button::-moz-focus-inner` 等                                                                      | `border-style`   | `none`                              | なし（ユニバーサル border で等価） | ✅   | destyle L308-314  |
| `button::-moz-focus-inner` 等                                                                      | `padding`        | `0`                                 | `0` (ユニバーサル `*`)             | ✅   | destyle L308-314  |
| `button:-moz-focusring` 等                                                                         | `outline`        | `1px dotted ButtonText`             | `auto` (`:-moz-focusring`)         | 🔄   | destyle L320-325  |
| `button:focus`                                                                                     | `outline`        | `5px auto -webkit-focus-ring-color` | なし                               | ⚠️   | \_reboot L327-329 |

### 9d. Select

| セレクタ             | プロパティ       | 現状の値  | Preflight                                | 判定 | 出典              |
| -------------------- | ---------------- | --------- | ---------------------------------------- | ---- | ----------------- |
| `select`             | `margin`         | `0`       | `0` (ユニバーサル `*`)                   | ✅   | \_reboot L464-474 |
| `select`             | `padding`        | `0`       | `0` (ユニバーサル `*`)                   | ✅   | \_reboot L464-474 |
| `select`             | `border-width`   | `revert`  | `0` (ユニバーサル `border: 0 solid`)     | 🔄   | \_reboot L467     |
| `select`             | `border-style`   | `revert`  | `solid` (ユニバーサル `border: 0 solid`) | 🔄   | \_reboot L468     |
| `select`             | `outline`        | `0`       | なし                                     | ⚠️   | \_reboot L469     |
| `select`             | `color`          | `inherit` | `inherit`                                | ✅   | \_reboot L470     |
| `select`             | `font`           | `inherit` | `inherit`                                | ✅   | \_reboot L471     |
| `select`             | `text-align`     | `inherit` | なし                                     | ⚠️   | \_reboot L472     |
| `select`             | `vertical-align` | `middle`  | `middle` (Preflight replaced elements)   | ✅   | \_reboot L473     |
| `select::-ms-expand` | `display`        | `none`    | なし                                     | 🗑️   | destyle L331-333  |

### 9e. Label / Legend / Other

| セレクタ     | プロパティ       | 現状の値       | Preflight                | 判定 | 出典              |
| ------------ | ---------------- | -------------- | ------------------------ | ---- | ----------------- |
| `label[for]` | `cursor`         | `pointer`      | なし                     | ⚠️   | destyle L423-425  |
| `label`      | `display`        | `inline-block` | なし                     | ⚠️   | \_reboot L317-321 |
| `label`      | `margin-bottom`  | `5px`          | `0` (ユニバーサル `*`)   | ⚠️   | \_reboot L317-321 |
| `option`     | `padding`        | `0`            | `0` (ユニバーサル `*`)   | ✅   | destyle L339-341  |
| `fieldset`   | `margin`         | `0`            | `0` (ユニバーサル `*`)   | ✅   | destyle L347-351  |
| `fieldset`   | `padding`        | `0`            | `0` (ユニバーサル `*`)   | ✅   | destyle L347-351  |
| `fieldset`   | `min-width`      | `0`            | なし                     | ⚠️   | destyle L347-351  |
| `fieldset`   | `border`         | `0`            | `0 solid` (ユニバーサル) | ✅   | \_reboot L370-381 |
| `legend`     | `color`          | `inherit`      | なし                     | ⚠️   | destyle L360-366  |
| `legend`     | `display`        | `table`        | なし                     | ⚠️   | destyle L360-366  |
| `legend`     | `max-width`      | `100%`         | なし                     | ⚠️   | destyle L360-366  |
| `legend`     | `padding`        | `0`            | `0` (ユニバーサル `*`)   | ✅   | destyle L360-366  |
| `legend`     | `white-space`    | `normal`       | なし                     | ⚠️   | destyle L360-366  |
| `legend`     | `margin-bottom`  | `5px`          | `0` (ユニバーサル `*`)   | ⚠️   | \_reboot L383-391 |
| `legend`     | `font-size`      | `15px`         | なし                     | ⚠️   | \_reboot L383-391 |
| `legend`     | `line-height`    | `inherit`      | なし                     | ⚠️   | \_reboot L383-391 |
| `progress`   | `vertical-align` | `baseline`     | `baseline`               | ✅   | destyle L372-374  |
| `output`     | `display`        | `inline-block` | なし                     | ⚠️   | \_reboot L402-407 |

### 9f. Textarea

| セレクタ   | プロパティ | 現状の値   | Preflight  | 判定 | 出典              |
| ---------- | ---------- | ---------- | ---------- | ---- | ----------------- |
| `textarea` | `overflow` | `auto`     | なし       | ⚠️   | destyle L380-382  |
| `textarea` | `resize`   | `vertical` | `vertical` | ✅   | \_reboot L365-368 |

### 9g. Input Types

| セレクタ                                                                  | プロパティ           | 現状の値  | Preflight                                        | 判定 | 出典              |
| ------------------------------------------------------------------------- | -------------------- | --------- | ------------------------------------------------ | ---- | ----------------- |
| `[type="number"]::-webkit-inner-spin-button, ::-webkit-outer-spin-button` | `height`             | `auto`    | `auto`                                           | ✅   | destyle L388-391  |
| `[type="search"]`                                                         | `outline-offset`     | `-2px`    | なし                                             | ⚠️   | destyle L397-398  |
| `[type="search"]`                                                         | `appearance`         | `none`    | なし                                             | ⚠️   | \_reboot L393-398 |
| `[type="search"]::-webkit-search-decoration`                              | `-webkit-appearance` | `none`    | `none`                                           | ✅   | destyle L405-407  |
| `::-webkit-file-upload-button`                                            | `-webkit-appearance` | `button`  | — (Preflight は `::file-selector-button` を使用) | 🗑️   | destyle L414-417  |
| `::-webkit-file-upload-button`                                            | `font`               | `inherit` | —                                                | 🗑️   | destyle L414-417  |
| `input[type="date/time/datetime-local/month"]`                            | `appearance`         | `listbox` | なし（Preflight は WebKit 疑似要素で処理）       | ⚠️   | \_reboot L356-363 |

### 9h. Placeholder

| セレクタ                           | プロパティ    | 現状の値                   | Preflight                                            | 判定                           | 出典              |
| ---------------------------------- | ------------- | -------------------------- | ---------------------------------------------------- | ------------------------------ | ----------------- |
| `input::-webkit-input-placeholder` | `color`       | `var(--clrg500)`           | — (Preflight は `::placeholder`)                     | ⚠️                             | \_reboot L429-432 |
| `input::-webkit-input-placeholder` | `font-weight` | `400`                      | なし                                                 | ⚠️                             | \_reboot L429-432 |
| `input:-ms-input-placeholder`      | `color`       | `var(--clrg500)`           | なし                                                 | 🗑️                             | \_reboot L434-437 |
| `input::-moz-placeholder`          | `color`       | `var(--clrg500)`           | なし                                                 | ⚠️                             | \_reboot L439-442 |
| `input:-webkit-autofill`           | `box-shadow`  | `0 0 0 1000px white inset` | なし                                                 | ⚠️                             | \_reboot L444-446 |
| `::placeholder`                    | `opacity`     | なし                       | `1`                                                  | ✅ 新規（問題なし）            | Preflight         |
| `::placeholder`                    | `color`       | なし                       | `color-mix(in oklab, currentcolor 50%, transparent)` | 🔄（現状のカスタム色と異なる） | Preflight         |

## 10. Tables

| セレクタ  | プロパティ        | 現状の値   | Preflight                         | 判定    | 出典             |
| --------- | ----------------- | ---------- | --------------------------------- | ------- | ---------------- |
| `table`   | `border-collapse` | `collapse` | `collapse`                        | ✅      | destyle L457-460 |
| `table`   | `border-spacing`  | `0`        | なし（collapse で無効化）         | ✅      | destyle L457-460 |
| `table`   | `text-indent`     | なし       | `0`                               | ✅ 新規 | Preflight        |
| `table`   | `border-color`    | なし       | `inherit`                         | ✅ 新規 | Preflight        |
| `caption` | `text-align`      | `left`     | なし（ブラウザデフォルト=center） | ⚠️      | destyle L462-464 |
| `td, th`  | `vertical-align`  | `top`      | なし（ブラウザデフォルト=middle） | ⚠️      | destyle L466-470 |
| `td, th`  | `padding`         | `0`        | `0` (ユニバーサル `*`)            | ✅      | destyle L466-470 |
| `th`      | `text-align`      | `left`     | なし（ブラウザデフォルト=center） | ⚠️      | destyle L472-475 |
| `th`      | `font-weight`     | `bold`     | なし（ブラウザデフォルト=bold）   | ✅      | destyle L472-475 |

## 11. Grouping Content / Misc

| セレクタ            | プロパティ         | 現状の値          | Preflight                       | 判定    | 出典             |
| ------------------- | ------------------ | ----------------- | ------------------------------- | ------- | ---------------- |
| `hr`                | `box-sizing`       | `content-box`     | なし（ユニバーサル border-box） | 🔄      | destyle L107-115 |
| `hr`                | `height`           | `0`               | `0`                             | ✅      | destyle L107-115 |
| `hr`                | `overflow`         | `visible`         | なし                            | ⚠️      | destyle L107-115 |
| `hr`                | `border-top-width` | `1px`             | `1px`                           | ✅      | destyle L107-115 |
| `hr`                | `margin`           | `0`               | `0` (ユニバーサル `*`)          | ✅      | destyle L107-115 |
| `hr`                | `clear`            | `both`            | なし                            | ⚠️      | destyle L107-115 |
| `hr`                | `color`            | `inherit`         | `inherit`                       | ✅      | destyle L107-115 |
| `details`           | `display`          | `block`           | なし（モダンブラウザでは不要）  | ✅ 不要 | destyle L434-436 |
| `summary`           | `display`          | `list-item`       | `list-item`                     | ✅      | destyle L442-444 |
| `[contenteditable]` | `outline`          | `none`            | なし                            | ⚠️      | destyle L450-452 |
| `template`          | `display`          | `none`            | なし（モダンブラウザでは不要）  | ✅ 不要 | destyle L484-486 |
| `[hidden]`          | `display`          | `none !important` | `none !important`               | ✅      | destyle L492-494 |

**注記:** Preflight の `[hidden]` セレクタは `[hidden]:where(:not([hidden='until-found']))` で、`hidden="until-found"` を除外する。現状は単純な `[hidden]` で全てに適用。実用上の差異は小さい。

## 12. Custom (\_reboot.scss 独自)

| セレクタ                                                                    | プロパティ                | 現状の値               | Preflight | 判定 | 出典              |
| --------------------------------------------------------------------------- | ------------------------- | ---------------------- | --------- | ---- | ----------------- |
| `::selection`                                                               | `background`              | `#AFE14A`              | なし      | ⚠️   | \_reboot L419-421 |
| `::-moz-selection`                                                          | `background`              | `#AFE14A`              | なし      | ⚠️   | \_reboot L423-425 |
| `a, area, button, [role="button"], input, label, select, summary, textarea` | `touch-action`            | `manipulation`         | なし      | ⚠️   | \_reboot L300-302 |
| `[role="button"]`                                                           | `cursor`                  | `pointer`              | なし      | ⚠️   | \_reboot L285-287 |
| `[tabindex="-1"]:focus`                                                     | `outline`                 | `none !important`      | なし      | ⚠️   | \_reboot L113-115 |
| `.clearfix::after`                                                          | `content, display, clear` | `"", block, both`      | なし      | ⚠️   | \_reboot L450-457 |
| `a img`                                                                     | `backface-visibility`     | `hidden`               | なし      | ⚠️   | \_reboot L272-276 |
| `a img`                                                                     | `transition`              | `all 0.2s ease-in-out` | なし      | ⚠️   | \_reboot L272-276 |
| `a img`                                                                     | `box-shadow`              | `#fff 0 0 0`           | なし      | ⚠️   | \_reboot L272-276 |

## 13. IE / 旧ブラウザ向け（🗑️ 削除可）

| セレクタ                       | プロパティ                 | 現状の値                | 出典              |
| ------------------------------ | -------------------------- | ----------------------- | ----------------- |
| `html`                         | `-ms-overflow-style`       | `scrollbar`             | \_reboot L34      |
| `@-ms-viewport`                | `width`                    | `device-width`          | \_reboot L59-64   |
| `select::-ms-expand`           | `display`                  | `none`                  | destyle L331-333  |
| `input:-ms-input-placeholder`  | `color, font-weight`       | `var(--clrg500), 400`   | \_reboot L434-437 |
| `::-webkit-file-upload-button` | `-webkit-appearance, font` | `button, inherit`       | destyle L414-417  |
| `button::-moz-focus-inner` 等  | `border-style, padding`    | `none, 0`               | destyle L308-314  |
| `button:-moz-focusring` 等     | `outline`                  | `1px dotted ButtonText` | destyle L320-325  |
| `::-moz-selection`             | `background`               | `#AFE14A`               | \_reboot L423-425 |
| `main { display: block }`      | —                          | IE11 向け               | destyle L44-46    |

**注記:** `::-moz-selection` は標準の `::selection` で代替可。`-moz-focusring` は Preflight が `outline: auto` で上書き。

## Preflight 固有の新規追加スタイル（現状にないもの）

Preflight 移行で新たに追加されるスタイルのうち、現状の挙動に影響する可能性があるもの:

| セレクタ                                                | プロパティ   | Preflight の値                 | 影響                                    |
| ------------------------------------------------------- | ------------ | ------------------------------ | --------------------------------------- |
| `img, svg, video, canvas, audio, iframe, embed, object` | `display`    | `block`                        | **高** — インライン配置が崩れる         |
| `::placeholder`                                         | `color`      | `color-mix(...)`               | 中 — 現状のカスタム色と競合             |
| `:-moz-ui-invalid`                                      | `box-shadow` | `none`                         | 低 — Firefox のバリデーション表示を抑制 |
| `html`                                                  | `tab-size`   | `4`                            | 低 — pre/code のタブ幅                  |
| Preflight ユニバーサル                                  | `::backdrop` | margin/padding/border リセット | 低 — dialog/fullscreen のみ             |

---

## 補足が必要なスタイル一覧（@layer base に書くべきコード）

```css
@layer base {
  /* ===== Box Model ===== */
  *::before,
  *::after {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  html {
    text-size-adjust: 100%; /* _reboot L33。Preflight は -webkit- のみ、unprefixed も維持 */
  }

  /* ===== Document ===== */
  body {
    position: relative;
    background-color: #fff;
    color: #222;
    font-family:
      avenir, "Noto Sans JP", "游ゴシック体", yugothic, "游ゴシック Medium",
      "Yu Gothic Medium", "ヒラギノ角ゴ ProN W3", "Hiragino Kaku Gothic ProN",
      "メイリオ", meiryo, sans-serif;
    font-size: 16px;
    font-weight: normal;
    /* line-height: 1.5 は Preflight の html で設定済み */
  }

  /* ===== Headings ===== */
  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    line-height: inherit;
  }

  /* ===== Lists ===== */
  dt {
    font-weight: bold;
  }
  ul {
    list-style-position: inside;
  }

  /* ===== Links ===== */
  a {
    background-color: transparent; /* destyle L138-142 */
    text-decoration: none;
    color: var(--link-color, #222); /* $link-color の CSS 変数化を推奨 */
    transition: all 0.2s ease-in-out;

    &:active,
    &:focus {
      outline: 0;
    }

    /* _reboot.scss では @include g.hover で定義していた。
       _hover.scss は @layer components 用にそのまま残す（§4.3 #7 で確定）。
       @layer base では mixin を使わず直接記述で確定。 */
    &:hover {
      color: var(--link-hover-color); /* $link-hover-color */
    }
  }

  a:not([href]):not([tabindex]) {
    color: inherit;
    text-decoration: none;

    &:focus,
    &:hover {
      color: inherit;
      text-decoration: none;
    }

    &:focus {
      outline: 0;
    }
  }

  /* ===== Text-level Semantics ===== */
  abbr[title],
  abbr[data-original-title] {
    cursor: help;
  }

  address {
    font-style: inherit;
  }

  code,
  kbd,
  samp {
    font-family: monospace, monospace; /* destyle の値を維持。Preflight は theme mono stack に変更する */
    font-size: inherit; /* destyle の値を維持。Preflight は 1em に変更する */
  }

  pre {
    font-family: monospace, monospace; /* 同上 */
    font-size: inherit; /* 同上 */
    overflow: auto;
  }

  /* ===== Images ===== */
  /* img 等の display: block は Preflight に任せる（現状 inline → block に変わるが許容）。
     崩れた箇所があれば個別に display: inline を設定する。 */
  embed,
  object,
  iframe {
    vertical-align: bottom; /* destyle の値を維持する場合 */
  }

  a img {
    backface-visibility: hidden;
    transition: all 0.2s ease-in-out;
    box-shadow: #fff 0 0 0;
  }

  /* ===== Forms ===== */
  button,
  input,
  optgroup,
  textarea {
    -webkit-appearance: none;
    appearance: none;
    vertical-align: middle;
    outline: 0;
    text-align: inherit;
  }

  /* フォーム要素の font について:
     現状の最終値は _reboot の sans-serif / 100% / 1.15。
     Preflight は font: inherit に戻す（= body のフォントが継承される）。
     body に font-family を設定済みのため、結果的にフォーム要素にも同じフォントが適用される。
     現状の sans-serif とは異なるが、body のフォントが継承される方が一貫性がある。
     問題があれば下記のコメントを解除して明示的に指定する。 */
  /* button, input, optgroup, select, textarea {
    font-family: sans-serif;
    font-size: 100%;
    line-height: 1.15;
  } */

  button,
  [type="button"],
  [type="reset"],
  [type="submit"] {
    cursor: pointer;
  }

  button[disabled],
  [type="button"][disabled],
  [type="reset"][disabled],
  [type="submit"][disabled] {
    cursor: default;
  }

  input[type="radio"]:disabled,
  input[type="checkbox"]:disabled {
    cursor: not-allowed;
  }

  button,
  select {
    text-transform: none;
  }

  button,
  input {
    overflow: visible;
  }

  button:focus {
    outline: 5px auto -webkit-focus-ring-color;
  }

  label {
    display: inline-block;
    margin-bottom: 5px;
  }

  label[for] {
    cursor: pointer;
  }

  fieldset {
    min-width: 0;
  }

  legend {
    color: inherit;
    display: table;
    max-width: 100%;
    white-space: normal;
    margin-bottom: 5px;
    font-size: 15px;
    line-height: inherit;
  }

  textarea {
    overflow: auto;
  }

  [type="search"] {
    outline-offset: -2px;
    appearance: none;
  }

  input[type="date"],
  input[type="time"],
  input[type="datetime-local"],
  input[type="month"] {
    appearance: listbox;
  }

  output {
    display: inline-block;
  }

  select {
    border-width: revert;
    border-style: revert;
    outline: 0;
    text-align: inherit;
  }

  /* Placeholder（vendor prefix → 標準に統合） */
  ::placeholder {
    color: var(--clrg500);
    font-weight: 400;
  }

  input:-webkit-autofill {
    box-shadow: 0 0 0 1000px white inset;
  }

  /* ===== Tables ===== */
  caption {
    text-align: left;
  }

  td,
  th {
    vertical-align: top;
  }

  th {
    text-align: left;
  }

  /* ===== Grouping / Misc ===== */
  hr {
    overflow: visible;
    clear: both;
    /* box-sizing: content-box は Preflight の border-box と競合。
       hr の表示に影響がなければ Preflight の border-box を受け入れる。
       問題があれば box-sizing: content-box を追加。 */
  }

  [contenteditable] {
    outline: none;
  }

  /* ===== Custom ===== */
  ::selection {
    background: #afe14a;
  }

  a,
  area,
  button,
  [role="button"],
  input,
  label,
  select,
  summary,
  textarea {
    touch-action: manipulation;
  }

  [role="button"] {
    cursor: pointer;
  }

  [tabindex="-1"]:focus {
    outline: none !important;
  }

  .clearfix::after {
    content: "";
    display: block;
    clear: both;
  }
}
```

---

## 移行時の注意事項

1. **`img { display: block }`** — Preflight の最大の変更点。インラインで画像を使用している箇所（テキスト中のアイコン等）は個別に `display: inline` を設定する必要がある。

2. **フォーム要素の `font`** — Preflight は `font: inherit` に戻すため、body のフォント設定が全フォーム要素に継承される。現状の `sans-serif` 指定が意図的なら、@layer base でフォーム要素にも明示的に指定すること。

3. **`hr { box-sizing }`** — destyle は `content-box`、Preflight は `border-box`（ユニバーサル）。hr の見た目に影響がないか実際にテストすること。

4. **`::placeholder` の色** — Preflight は `color-mix(in oklab, currentcolor 50%, transparent)` を設定。カスタム色 `var(--clrg500)` で上書き必要。

5. **vendor prefix の整理** — `::-webkit-input-placeholder` 等は `::placeholder` に統一可。`::-moz-selection` は `::selection` に統一可。IE 向けの `-ms-` 系は全て削除可。

6. **`a { text-decoration }`** — Preflight は `inherit`（destyle は `none`）。親要素に `text-decoration: underline` がある場合、意図せず下線が復活する可能性がある。`none` で明示的に上書きすること。

---

## ダブルチェック留意事項（Preflight 調査員による再検証結果）

ダブルチェックで指摘された全項目は **補足コードに反映済み**。以下は実装時の参考情報として残す。

- **フォーム `appearance`**: ボタン系は Preflight が `button` を設定、非ボタン系は設定なし。どちらも補足コードで `none` に統一済み
- **`hr { box-sizing }`**: 本表では ⚠️ だが厳密には 🔄。Preflight が `border-box` を適用する。hr に padding/border を設定していなければ実害なし
- **`[hidden]`**: Preflight は `hidden="until-found"` を除外するセレクタを使用。実用上の差異は小さい
- **`table { border-spacing: 0 }`**: `border-collapse: collapse` 下では無視されるため実害なし

---

## 備考: `img` 等の `display: inline → block` 変更について

- **現状**: destyle.css も _reboot.scss も `img` の `display` を変更していない。ブラウザデフォルト（`inline`）のまま
- **Preflight 移行後**: Preflight が `img, svg, video, canvas, audio, iframe, embed, object` に `display: block` を設定する
- **決定**: `display: block` の方が一般的に扱いやすいため、Preflight の挙動をそのまま受け入れる
- **リスク**: テキスト中にインラインで画像を使用している箇所（例: 文中のアイコン画像）がある場合、`block` になることで意図しない改行が入り、レイアウトが崩れる可能性がある
- **対処**: 移行後の全ページ目視確認で影響箇所を特定し、崩れた箇所には個別に `display: inline` を設定する（実装フェーズで対応）
