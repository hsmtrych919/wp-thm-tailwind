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

PHP テンプレートでの実際の使用パターン:

```
container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0
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
- `scss/readme.md` の整備: 後日計画

---

## 影響範囲（調査済み。詳細は `container-refactor-investigation.md`）

| 対象 | 箇所数 | ファイル数 |
|---|---|---|
| `.l-container` 系 PHP | 11 箇所 | 10 ファイル |
| `.container` PHP | 68 箇所 | 25 ファイル |
| JS（`.container` / `.l-container` 直接参照） | 0 箇所 | 0 ファイル |
| SCSS 定義 | — | `layout/_content.scss`、`tailwind-base.css` |
| 設定ファイル | — | `tailwind.config.js`（`max-w-container-*` 5 エントリ） |

事前調査結果は `container-refactor-investigation.md` に全件記録済み。実装時の A-1, B-1 でも同じ調査を独立に実行し、事前調査結果と照合する。件数・ファイル名・行番号が一致しなければ即停止し、差分の原因を特定してから進める。

---

## タスクと実行順序

### Phase A: `.l-container` → `.l-container-py` リネーム

上下 padding クラスのリネーム。横幅制御の `.container` にはまだ触れない。理由: Phase B で `.container` → `.l-container` にリネームするが、現時点で `.l-container` は上下 padding クラスとして使用中。先にこの名前を空けないと衝突する。

**A-1. 事前調査とダブルチェック**（置換前に全件把握する。把握せずに置換を始めると漏れが発生する）
- PHP / JS 全ファイルから `.l-container`（`__blog`, `__search` 含む）の利用箇所を洗い出し、ファイル名・行番号・該当コードを記録する
- `container-refactor-investigation.md` の Phase A 調査結果と照合する。期待値: PHP 11 箇所 / 10 ファイル、JS 0 件。件数・ファイル名・行番号が一致しなければ即停止し、差分の原因を特定する

**A-2. SCSS クラス定義のリネーム**（定義元を先に変更する。参照先を先に変えると、ビルド時に定義が見つからずスタイルが消える）
- `layout/_content.scss` 内の `.l-container` → `.l-container-py`
- `&__blog` → `&--blog`、`&__search` → `&--search`（BEM の `__` から `--` に変更。`l-container-py` の子要素ではなくバリエーションのため）
- コメントアウトされている mixin（`l-container--padding-top` / `l-container--padding-bottom`）: 事前調査で参照箇所ゼロを確認済み。削除する

**A-3. PHP テンプレートのクラス名置換**（A-2 で定義を変更済みなので、参照側も合わせる。漏れがあるとそのテンプレートの上下 padding が効かなくなる）
- A-1 で記録した全箇所を置換

**A-4. JS の確認**（JS が classList や querySelector でクラス名を文字列として参照している可能性がある。PHP だけ置換して JS を見落とすと実行時に不一致が起きる）
- JS 内に `l-container` への参照がないか確認。あれば置換

**A-5. 中間チェック**（Phase B に進む前に Phase A の完了を保証する。不完全な状態で Phase B に進むと `.l-container` の名前衝突が発生する）
- ビルドして CSS 出力に `.l-container-py` が正しく出力されることを確認。理由: SCSS 定義変更が PostCSS / Tailwind パイプラインを通過して最終 CSS に正しく反映されたことを検証する
- 旧 `.l-container` が CSS 出力に残っていないことを確認。理由: 残っていれば A-2 の置換漏れ
- `_class-rename-log.md` に記録。理由: CLAUDE.md のルール「クラス名を変更したら記録せよ。記録せずに次の作業に進むな」

---

### Phase B: `.container` → `.l-container` リネーム

BP 別 max-width の横幅制御クラスのリネーム。

**B-1. 事前調査とダブルチェック**（A-1 と同じ理由。置換前に全件把握する）
- PHP / JS 全ファイルから `.container`（横幅制御クラス）の利用箇所を洗い出し、記録する
- `container-refactor-investigation.md` の Phase B 調査結果と照合する。期待値: PHP 68 箇所 / 25 ファイル、JS 0 件（`.container` クラスへの直接参照なし）。件数・ファイル名・行番号が一致しなければ即停止し、差分の原因を特定する
- JS は重点確認。理由: `.container` は汎用的な名前のため、DOM 操作（`classList.add('container')`）やセレクタ（`querySelector('.container')`）、条件分岐での文字列比較など、文字列マッチだけでは拾えないパターンがある。誤検出も多いため、各ヒットが横幅制御の `.container` への参照かどうかを個別に判定する。事前調査では `__memo.js` の `.main-container`（別クラス）と `swiper.js` の Swiper config パラメータ（CSS クラス参照ではない）を対象外と判定済み

**B-2. `tailwind-base.css` のクラス名リネーム**（A-2 と同じ理由。定義元を先に変更する）
- `@layer utilities` 内の `.container` → `.l-container` にリネーム
- コメントブロック（L4-13）の記述を現状に合わせて更新。理由: コメントが旧クラス名のままだと後から読んだ人が混乱する
- `!important` はこのステップでは触らない。理由: スコープ外（リネームとは別の問題）
- `@source not inline("container")` はこのステップでは触らない。理由: B-5 で削除する。PHP にまだ `.container` が残っている状態で抑制を外すと Tailwind が built-in を生成するため

**B-3. PHP テンプレートのクラス名置換**（B-2 で定義を変更済みなので参照側も合わせる。漏れがあるとそのテンプレートの横幅制御が効かなくなる）
- B-1 で記録した全箇所を置換

**B-4. JS の確認と置換**（A-4 と同じ理由。JS の文字列参照を見落とすと実行時に不一致が起きる）
- B-1 の調査結果に基づき、JS 内のクラス名参照を置換

**B-5. `@source not inline("container")` の削除**
- B-3, B-4 完了後に実行する。この順序は固定。前倒し禁止
- PHP / JS 全ファイルに `.container` 参照が残っていないことを grep で確認してから削除する
- 削除しても Tailwind の built-in `.container` は出力されない。Tailwind v4 は JIT であり、content ファイル（PHP）に `.container` が存在しなければ生成しない。B-3 で PHP から `.container` を全て除去済みのため、`@source not inline` を外しても built-in が復活することはない
- ただし B-3 より前に削除すると、PHP にまだ `.container` が残っているため Tailwind が content scan で検出し、built-in `.container`（screens 値をそのまま max-width に使う版）を生成してカスタム定義と競合する。だから順序固定

**B-6. 中間チェック**（Phase C に進む前に Phase B の完了を保証する）
- ビルドして CSS 出力に `.l-container`（横幅制御）が正しく出力されることを確認。理由: B-2 のリネームがパイプライン通過後も正しいことを検証する
- CSS 出力に Tailwind built-in `.container` が存在しないことを確認。理由: B-5 で `@source not inline` を削除済み。PHP に `.container` が残っていなければ Tailwind は生成しないはずだが、想定通りかを検証する
- 旧パターン `container mx-auto` が PHP に残っていないことを grep で確認。理由: B-3 の置換漏れ検出
- `_class-rename-log.md` に記録。理由: CLAUDE.md ルール

---

### Phase C: デッドコード削除

**C-1. `max-w-container-*` の削除**（PHP 使用箇所ゼロのデッドコード。config に残すと Tailwind がクラスを生成可能な状態が続き、誤使用の原因になる）
- `tailwind.config.js` `theme.extend.maxWidth`（L166-172）から `container-sm` 〜 `container-xxl` の 5 エントリを削除。`extend.maxWidth` が空になる場合はキー自体を削除。理由: 空のオブジェクトを残すと「何か入る予定がある」と誤解される
- `tailwind.config.js` L18-25 の container コメントブロックを更新: Phase B で `.container` → `.l-container` にリネーム済み、`@source not inline` 削除済みの現状を正確に記述する。理由: コメントが旧構成のままだと config を読んだ人が混乱する

**C-2. sample.html の修正**（sample.html は `max-w-container-*` を使っている唯一のファイル。config から定義を削除するとスタイルが効かなくなるため、Phase B で確立した `.l-container` パターンに合わせる）
- `max-w-container-*` を使っている箇所を `.l-container` パターンに修正

**C-3. 最終チェック**（C-1 の削除が最終 CSS に反映されたことを検証する）
- ビルドして `max-w-container-*` が CSS 出力に残っていないことを確認

---

### Phase D: ドキュメント更新

**D-1. `_class-rename-log.md` の最終確認**（A-5, B-6 で各 Phase ごとに記録済みだが、全 Phase 通しで漏れがないかを確認する）
- Phase A〜C のリネーム記録が全て揃っていることを確認

**D-2. `tailwind-migration-plan.md`（_archive 内）の更新**（この文書は Phase 3 のグリッド移行で参照されており、旧クラス名のままだと後から読んだ人が誤った名前を使う）
- §3 のグリッド変換例の `.container` を `.l-container` に更新
- container 定義の説明を現状に合わせる

**D-3. `scss/readme.md` の整備**（別途計画。本計画書のスコープ外）

**D-4. `scss-system-review-report.md` の更新**（セクション 2-2 で「container の二重定義」を残課題として記載している。Phase A〜C で解消されるため、解決済みに移動する）
- セクション 2-2「container の二重定義」を解決済みに移動

---

## 実行上の注意

- **Phase A → B の順序は厳守**。`.l-container` という名前を先に空けてから `.container` → `.l-container` のリネームを行う。逆にすると名前が衝突する
- **`@source not inline("container")` の削除は B-5 固定**。B-3（PHP 置換）, B-4（JS 置換）完了後に削除する。前倒し禁止。理由: PHP に `.container` が残った状態で抑制を外すと、Tailwind が content scan で `.container` を検出し built-in を生成する。PHP 置換完了後なら content に `.container` が存在しないため built-in は生成されない
- **各 Phase の中間チェックを省略しない**。理由: 各 Phase の結果は次の Phase の前提条件になっている。Phase A が不完全な状態で Phase B に進むと `.l-container` の名前衝突が発生し、問題が複合化する
- **JS の `container` 参照は文字列マッチだけでは不十分**。理由: JS はクラス名を文字列として扱うため、grep の `.container` パターンだけでは `classList.add('container')` や `querySelector('.container')` を拾えない場合がある。`container` 単体での検索も併用し、各ヒットが横幅制御クラスへの参照かどうかを個別に判定する
- **ビルド済み CSS（`css/style.css`）での確認**は、SCSS ソースだけでなく PostCSS / Tailwind の処理結果まで含めた最終出力で行う。理由: SCSS のリネームが正しくても、PostCSS / Tailwind パイプラインで予期しない変換が起きる可能性がある。最終成果物で確認しなければ保証にならない
