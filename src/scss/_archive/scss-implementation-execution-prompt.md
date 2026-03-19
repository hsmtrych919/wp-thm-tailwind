# SCSS Reorganization Execution Prompt

以下は、実装担当 AI にそのまま渡すための実行プロンプトです。

---

あなたはこのリポジトリの実装担当 AI です。今回の仕事は、WordPress theme の SCSS 再構成を、既存の調査結果と置換台帳に厳密に従って実装することです。

## 最重要ルール

1. 今回の source of truth は次の 2 ファイルだけです。
   - src/scss/scss-structure-investigation.md
   - src/scss/scss-class-replacement-ledger.md
2. まずこの 2 ファイルを全文読み、方針・対象 class・置換順序・同時修正が必要な SCSS/PHP の組を完全に理解してから作業を始めてください。
3. src/scss/_archive/tailwind-migration-plan.md, src/scss/_archive/grid-strategy-discussion.md, src/scss/_archive/tailwind-migration-analysis.md は過去資料です。矛盾がある場合は必ず現行の 2 ファイルを優先してください。
4. 行数やファイルサイズは判断材料にしないでください。判断基準は class の責務、PHP 使用箇所、rename の正確性、統合後の責務分離です。
5. FLOCSS の component / project の教条性は判断基準にしないでください。機能単位で `c-*` を `p-*` に寄せることを優先してください。

## 目的

次を実装してください。

- `c-*` と `p-*` が分担している領域を、台帳の方針に従って `p-*` 側へ統合する
- 同一要素で `c-*` と `p-*` が共存している箇所は、`p-*` に吸収して `c-*` を PHP から除去する
- `single` と `parent-child` は、台帳で定義された受け皿 `p-*` へ rename / 移管する
- source partial から target partial へ責務を寄せ、不要になった `c-*` セレクタを削除する
- 最終的に、今回対象の `c-*` については PHP 上の使用が残らない状態にする

## 今回の実装対象

対象 class 群は、必ず台帳の定義に従ってください。

- `c-header__*`
- `c-toolbar__*`
- `c-nav__*`
- `c-footer__*`
- `c-footernav__*`
- `c-post__*`
- `c-post-feed__*`
- `c-search*`
- `c-ttl__*`
- `c-form__ttl*`
- `c-typ*`

対象外 `c-*` は巻き込まないでください。対象外一覧も必ず台帳で確認してください。

## 実装順序

必ず台帳にある全体順序で進めてください。順序を変えないでください。

1. Search
2. Header / Toolbar / Nav
3. Footer / Footernav
4. Post / Post Feed
5. Post Titles / Widget Titles / Search Titles
6. Global Typ / Generic Title / Form Title

## 領域ごとの実装ルール

各領域で必ず次を守ってください。

1. まず target SCSS 側に受け皿 `p-*` を成立させる
2. その後で PHP 側の class を置換する
3. `same-element` は吸収統合と PHP からの `c-*` 除去を同じ作業単位でやる
4. `single` / `parent-child` は source SCSS から target SCSS への移管と PHP rename を同じ作業単位でやる
5. 台帳にある「同時修正が必要な SCSS/PHP の組」は分割せず、一塊で処理する
6. `component/_*.scss` の削除や src/scss/style.scss の import 整理は最後まで後回しにする

## 作業手順

以下の手順で進めてください。

1. src/scss/scss-class-replacement-ledger.md を見ながら、対象 class を作業単位ごとにチェックリスト化する
2. 実装開始前に、対象 class を repo 全体で再 grep して、台帳との差分がないか確認する
3. Search から順に、各領域の target SCSS に受け皿を実装する
4. 同じ作業単位の PHP を一気に置換する
5. 置換後、該当 `c-*` の grep で残件がないか確認する
6. 各領域が終わるごとに、関連 partial と PHP の整合を確認する
7. すべての領域が終わってから、不要になった `c-*` セレクタを削除する
8. 最後に src/scss/style.scss の import を整理する

## 実装時の判断ルール

迷ったら次を優先してください。

1. 台帳に明記された rename 先
2. 既存の `p-*` 命名体系との一貫性
3. feature ごとの責務の閉じ方

勝手に新しい整理方針へ逸脱しないでください。命名や受け皿を変更する必要がある場合でも、台帳の意図から外れない最小限の変更にしてください。

## 検証

実装後は必ず次を実施してください。

1. 対象 `c-*` の grep を再実行し、今回対象の class が PHP / SCSS に残っていないか確認する
2. 変更した SCSS / PHP の構文エラーを確認する
3. 可能なら build を実行して、少なくとも今回の変更起因のエラーがないことを確認する
4. git diff を見て、対象外 `c-*` を巻き込んでいないことを確認する

## 出力のしかた

作業中は、何をしているかではなく、どの領域を処理して何が終わったかを簡潔に報告してください。

最終報告では次だけを簡潔に示してください。

1. 実装した領域
2. まだ残っているリスクがあるか
3. build / grep / 差分確認の結果

変更をステージングしてコミットし、リモートの claude_ph1 ブランチにプッシュした上で、main へのプルリクエストを作成させる。コミットメッセージなどは任意で。