# Container リファクタリング計画書

---

## 背景と経緯

### 現状の構成

container に関わるクラスが 3 系統ある:

| クラス | 定義場所 | 責務 |
|---|---|---|
| `.container` | `tailwind-base.css` `@layer utilities` | BP 別 max-width + 左右中央寄せ |
| `.l-container` / `__blog` / `__search` | `layout/_content.scss` `@layer components` | セクション上下 padding |
| `max-w-container-*`（sm/md/lg/xl/xxl） | `tailwind.config.js` `theme.extend.maxWidth` | max-width のみ（中央寄せなし） |

### 何が問題か

1. **名前の衝突**: `.container`（横幅制御）と `.l-container`（上下余白）は責務が全く異なるのに、名前が似ている。PHP テンプレート上で `container l-container` と並ぶと混乱する。

2. **Tailwind built-in との競合**: `.container` は Tailwind のデフォルトクラス名と同一。現状は `@source not inline("container")` で built-in を抑制してカスタム定義しているが、クラス名自体を変えれば抑制が不要になる。

3. **デッドコード**: `max-w-container-*` は元々 `_variables.scss` L37-41 の `$container-max-sm` 〜 `$container-max-xxl` を Phase 1 で `tailwind.config.js` `theme.extend.maxWidth` に移行したもの（L166-172）。しかし、BP 別 max-width を単一クラスで実現する `.container`（`tailwind-base.css` `@layer utilities`）の方が実用的で、`max-w-container-*` ユーティリティは本番 PHP で一度も採用されなかった。`max-w-container-md mx-auto` のように毎回 `mx-auto` を併記する必要があり、単独で中央寄せまで担う `.container` に対して冗長。PHP テンプレートでの使用箇所ゼロ。

### container の BP 別 max-width 設計の意図

1366px アートボードに対して max-width 1260px（padding 込み実質 1200px）にしているのは、左右余白を確保してデザインの窮屈さを回避するため。Tailwind v4 の built-in container は screens 値をそのまま max-width に使うため、xxl = 1366px コンテナとなり窮屈になる。この設計上の理由から、BP と max-width が異なる独自の container 定義が必要。

### 本番のグリッドシステム運用

Phase 3 で旧 SCSS グリッドシステム（`.l-row--container` + `.c-col__md--10`）を Tailwind ユーティリティに移行済み。本番の PHP テンプレートでは以下のパターンが確立している:

```html
<div class="container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
  <div class="w-full md:w-10/12">
```

このパターンの `.container` を `.l-container` にリネームするのが今回の作業の中心。

---

## 解決方針

| 変更内容 | 目的 |
|---|---|
| `.l-container` → `.l-container-py` にリネーム | 上下 padding クラスの責務を名前に反映 |
| `.container` → `.l-container` にリネーム | Tailwind built-in との競合を解消、接頭辞で独自クラスと明示 |
| `max-w-container-*` を削除 | デッドコードの除去 |
| `@source not inline("container")` を削除 | `.l-container` に変更すれば built-in 抑制が不要になる |

### スコープ外（このリファクタリングでは触らない）

- `!important` の要否見直し: 現在 `tailwind-base.css` の `.container` 定義に `!important` が付いているが、これはリネームとは別の問題。現状のまま維持する
- `scss/readme.md` の整備: 別途計画

---

## 影響範囲（概算）

| 対象 | 概算 |
|---|---|
| `.l-container` を含む PHP ファイル | 11 ファイル |
| `.container`（Tailwind）を含む PHP ファイル | 29 ファイル |
| JS ファイル（`container` 参照あり） | app.js, gsap.js, swiper.js, __memo.js を要確認 |
| SCSS ファイル | `layout/_content.scss`、`tailwind-base.css` |
| 設定ファイル | `tailwind.config.js` |

各タスクの事前調査（A-1, B-1）で具体的なファイル名・行番号・該当コードを全件記録し、漏れなく置換する。

---

## タスクと実行順序

### Phase A: `.l-container` → `.l-container-py` リネーム

上下 padding クラスのリネーム。横幅制御の `.container` にはまだ触れない。

**A-1. 事前調査**
- PHP / JS 全ファイルから `.l-container`（`__blog`, `__search` 含む）の利用箇所を洗い出し、ファイル名・行番号・該当コードを記録する

**A-2. SCSS クラス定義のリネーム**
- `layout/_content.scss` 内の `.l-container` → `.l-container-py`
- `&__blog` → `&--blog`、`&__search` → `&--search`（BEM の `__` から `--` に変更。`l-container-py` の子要素ではなくバリエーションのため）
- コメントアウトされている mixin（`l-container--padding-top` / `l-container--padding-bottom`）も名前を揃えるか、不要なら削除

**A-3. PHP テンプレートのクラス名置換**
- A-1 で記録した全箇所を置換

**A-4. JS の確認**
- JS 内に `l-container` への参照がないか確認。あれば置換

**A-5. 中間チェック**
- ビルドして CSS 出力に `.l-container-py` が正しく出力されることを確認
- 旧 `.l-container` が CSS 出力に残っていないことを確認
- `_class-rename-log.md` に記録

---

### Phase B: `.container` → `.l-container` リネーム

BP 別 max-width の横幅制御クラスのリネーム。

**B-1. 事前調査**
- PHP / JS 全ファイルから `.container`（Tailwind の横幅クラス）の利用箇所を洗い出し、記録する
- JS で DOM 操作やクラス名判定に `container` を使っている箇所がないか重点確認（`.container` は汎用的な名前のため、文字列マッチだけで拾うと誤検出の可能性がある）

**B-2. `tailwind-base.css` のクラス名リネーム**
- `@layer utilities` 内の `.container` → `.l-container` にリネーム
- コメントブロック（L4-13）の記述を現状に合わせて更新
- `!important` と `@source not inline("container")` はこのステップでは触らない

**B-3. PHP テンプレートのクラス名置換**
- B-1 で記録した全箇所を置換

**B-4. JS の確認と置換**
- B-1 の調査結果に基づき、JS 内のクラス名参照を置換

**B-5. `@source not inline("container")` の削除**
- B-3, B-4 完了後に実行する。この順序は固定。前倒し禁止
- PHP / JS 全ファイルに `.container` 参照が残っていないことを grep で確認してから削除する
- 削除しても Tailwind の built-in `.container` は出力されない。Tailwind v4 は JIT であり、content ファイル（PHP）に `.container` が存在しなければ生成しない。B-3 で PHP から `.container` を全て除去済みのため、`@source not inline` を外しても built-in が復活することはない
- ただし B-3 より前に削除すると、PHP にまだ `.container` が残っているため Tailwind が content scan で検出し、built-in `.container`（screens 値をそのまま max-width に使う版）を生成してカスタム定義と競合する。だから順序固定

**B-6. 中間チェック**
- ビルドして CSS 出力に `.l-container`（横幅制御）が正しく出力されることを確認
- CSS 出力に Tailwind built-in `.container` が存在しないことを確認（PHP に `.container` が残っていなければ生成されないはずだが、念のため確認）
- 旧パターン `container mx-auto` が PHP に残っていないことを grep で確認
- `_class-rename-log.md` に記録

---

### Phase C: デッドコード削除

**C-1. `max-w-container-*` の削除**
- `tailwind.config.js` `theme.extend.maxWidth`（L166-172）から `container-sm` 〜 `container-xxl` の 5 エントリを削除。`extend.maxWidth` が空になる場合はキー自体を削除
- `tailwind.config.js` L18-25 の container コメントブロックを更新: `.container` → `.l-container` のリネームと `@source not inline` 削除を反映し、現在の構成（`tailwind-base.css` `@layer utilities` で `.l-container` を定義）を正確に記述する

**C-2. sample.html の修正**
- `max-w-container-*` を使っている箇所を `.l-container` パターンに修正

**C-3. 最終チェック**
- ビルドして `max-w-container-*` が CSS 出力に残っていないことを確認

---

### Phase D: ドキュメント更新

**D-1. `_class-rename-log.md` の最終確認**
- Phase A〜C のリネーム記録が全て揃っていることを確認

**D-2. `tailwind-migration-plan.md`（_archive 内）の更新**
- §3 のグリッド変換例の `.container` を `.l-container` に更新
- container 定義の説明を現状に合わせる

**D-3. `scss/readme.md` の整備**（別途計画。本計画書のスコープ外）

**D-4. `scss-system-review-report.md` の更新**
- セクション 2-2「container の二重定義」を解決済みに移動

---

## 実行上の注意

- **Phase A → B の順序は厳守**。`.l-container` という名前を先に空けてから `.container` → `.l-container` のリネームを行う。逆にすると名前が衝突する
- **`@source not inline("container")` の削除は B-5 固定**。B-3（PHP 置換）, B-4（JS 置換）完了後に削除する。前倒し禁止。理由: PHP に `.container` が残った状態で抑制を外すと、Tailwind が content scan で `.container` を検出し built-in を生成する。PHP 置換完了後なら content に `.container` が存在しないため built-in は生成されない
- **各 Phase の中間チェックを省略しない**。リネームが正しく反映されたことを確認してから次の Phase に進む
- **JS の `container` 参照は文字列マッチだけでは不十分**。DOM 操作（`classList.add('container')` 等）、セレクタ（`document.querySelector('.container')` 等）、条件分岐での文字列比較も確認する
- **ビルド済み CSS（`css/style.css`）での確認**は、SCSS ソースだけでなく PostCSS / Tailwind の処理結果まで含めた最終出力で行う
