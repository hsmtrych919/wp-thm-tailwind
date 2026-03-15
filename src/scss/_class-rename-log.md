# クラス名変更ログ

> SCSS → Tailwind CSS 移行に伴うクラス名変更の全記録
> PHP テンプレート修正時のリファレンスとして使用
> **各 Phase の作業中にリアルタイムで更新すること**

---

## 利用方法

- **旧クラス名**で検索 → 対応する**新クラス名**を確認
- 一括置換スクリプトの入力データとしても使用可能
- ステータス: ✅ 置換済み / ⏳ 未着手

---

## Phase 2: ユーティリティ層（✅ 完了）

### margin 系

| 旧クラス | 新クラス | 備考 | ステータス |
|---|---|---|---|
| `mt__075` | `mt-0.75` | バグ修正（旧は CSS 未マッチ） | ✅ |
| `mt__0` | `mt-0` | | ✅ |
| `mt__1` | `mt-1` | | ✅ |
| `mt__1_25` | `mt-1.25` | | ✅ |
| `mt__1_5` | `mt-1.5` | | ✅ |
| `mt__2` | `mt-2` | | ✅ |
| `mt__3` | `mt-3` | | ✅ |
| `mt__4` | `mt-4` | | ✅ |
| `mt__5` | `mt-5` | | ✅ |
| `mt__6` | `mt-6` | | ✅ |
| `mt__7` | `mt-7` | | ✅ |
| `mt__8` | `mt-8` | | ✅ |
| `mt__9` | `mt-9` | | ✅ |
| `mt__10` | `mt-10` | | ✅ |
| `mt__N--BP` | `BP:mt-N` | 例: `mt__4--md` → `md:mt-4` | ✅ |
| `mb__0` | `mb-0` | PHP 未使用 | ✅ |
| `mx__auto` | `mx-auto` | PHP 未使用 | ✅ |

### display 系

| 旧クラス | 新クラス | 備考 | ステータス |
|---|---|---|---|
| `display__none` | `hidden` | | ✅ |
| `display__none--sm` | `sm:hidden` | | ✅ |
| `display__none--md` | `md:hidden` | | ✅ |
| `display__none--xl` | `xl:hidden` | | ✅ |
| `display__block` | `block` | | ✅ |
| `display__block--sm` | `sm:block` | | ✅ |
| `display__block--lg` | `lg:block` | | ✅ |
| `display__block--xl` | `xl:block` | | ✅ |
| `display__inline-block` | `inline-block` | | ✅ |
| `display__inline-block--BP` | `BP:inline-block` | | ✅ |

### visibility / hide 系

| 旧クラス | 新クラス | 備考 | ステータス |
|---|---|---|---|
| `hide__xs--up` | `hidden` | 常時非表示 | ✅ |
| `hide__sm--up` | `sm:hidden` | | ✅ |
| `hide__md--up` | `md:hidden` | | ✅ |
| `hide__lg--up` | `lg:hidden` | | ✅ |
| `hide__xl--up` | `xl:hidden` | | ✅ |
| `hide__xxl--up` | `2xl:hidden` | | ✅ |
| `hide__xs--down` | `max-sm:hidden` | | ✅ |
| `hide__sm--down` | `max-md:hidden` | | ✅ |
| `hide__md--down` | `max-lg:hidden` | | ✅ |
| `hide__lg--down` | `max-xl:hidden` | | ✅ |
| `hide__xl--down` | `max-2xl:hidden` | | ✅ |
| `hide__xxl--down` | `hidden` | 常時非表示 | ✅ |
| `invisible` | `invisible` | 同名 | ✅ |

### flex 系

| 旧クラス | 新クラス | 備考 | ステータス |
|---|---|---|---|
| `jc__start` | `justify-start` | | ✅ |
| `jc__end` | `justify-end` | | ✅ |
| `jc__center` | `justify-center` | | ✅ |
| `jc__between` | `justify-between` | | ✅ |
| `jc__around` | `justify-around` | | ✅ |
| `jc__VALUE--BP` | `BP:justify-VALUE` | 例: `jc__between--md` → `md:justify-between` | ✅ |
| `ai__start` | `items-start` | | ✅ |
| `ai__end` | `items-end` | | ✅ |
| `ai__center` | `items-center` | | ✅ |
| `ai__baseline` | `items-baseline` | | ✅ |
| `ai__stretch` | `items-stretch` | | ✅ |
| `ai__VALUE--BP` | `BP:items-VALUE` | 例: `ai__center--md` → `md:items-center` | ✅ |

### font-size 系

| 旧クラス | 新クラス | 備考 | ステータス |
|---|---|---|---|
| `fz__12` | `text-fz12 2xl:text-fz13` | 基本 0.75rem → 2xl で 0.8125rem | ✅ |
| `fz__14` | `text-fz14 2xl:text-fz15` | 基本 0.875rem → 2xl で 0.9375rem | ✅ |
| `fz__15` | `text-fz15 2xl:text-fz16` | 基本 0.9375rem → 2xl で 1rem | ✅ |
| `fz__16` | `text-fz16 2xl:text-fz17` | 基本 1rem → 2xl で 1.0625rem | ✅ |
| `fz__18` | `text-fz18 2xl:text-fz19` | 基本 1.125rem → 2xl で 1.1875rem | ✅ |
| `fz__20` | `text-fz20 2xl:text-fz21` | 基本 1.25rem → 2xl で 1.3125rem | ✅ |

### text-align 系

| 旧クラス | 新クラス | 備考 | ステータス |
|---|---|---|---|
| `tac` | `text-center` | | ✅ |
| `tac__sm` | `sm:text-center` | element infix（`__`） | ✅ |
| `tac__md` | `md:text-center` | | ✅ |
| `tac__lg` | `lg:text-center` | | ✅ |
| `tal` | `text-left` | | ✅ |
| `tal__sm` | `sm:text-left` | | ✅ |
| `tar` | `text-right` | | ✅ |
| `tar__sm` | `sm:text-right` | | ✅ |

### font-weight 系

| 旧クラス | 新クラス | 備考 | ステータス |
|---|---|---|---|
| `fwl` / `fw__300` | `font-light` | | ✅ |
| `fw__400` | `font-normal` | | ✅ |
| `fw__500` | `font-medium` | | ✅ |
| `fwb` / `fw__600` | `font-semibold` | | ✅ |

### text-decoration 系

| 旧クラス | 新クラス | 備考 | ステータス |
|---|---|---|---|
| `tdu` | `underline` | | ✅ |

### color 系

| 旧クラス | 新クラス | 備考 | ステータス |
|---|---|---|---|
| `clr__1` | `text-clr1` | | ✅ |
| `clr__2` | `text-clr2` | | ✅ |
| `clr__3` | `text-clr3` | | ✅ |
| `clr__4` | `text-clr4` | | ✅ |
| `clr__black` | `text-black` | | ✅ |
| `clr__w` / `clr__white` | `text-white` | | ✅ |
| `clr__gray` / `clr__g700` | `text-gray-700` | | ✅ |
| `clr__g50` | `text-gray-50` | PHP 未使用 | ✅ |
| `clr__g100` | `text-gray-100` | PHP 未使用 | ✅ |
| `clr__g200` | `text-gray-200` | PHP 未使用 | ✅ |
| `clr__g300` | `text-gray-300` | PHP 未使用 | ✅ |
| `clr__g400` | `text-gray-400` | PHP 未使用 | ✅ |
| `clr__g500` | `text-gray-500` | PHP 未使用 | ✅ |
| `clr__g600` | `text-gray-600` | PHP 未使用 | ✅ |
| `clr__g800` | `text-gray-800` | PHP 未使用 | ✅ |
| `clr__g900` | `text-gray-900` | PHP 未使用 | ✅ |
| `clr__red` | `text-red` | PHP 未使用。config に `red: '#f00'` 追加済み | ✅ |
| `clr__alert` | `text-red` | バグ修正（旧は定義なし）。赤色テキストとして `text-red` に統一 | ✅ |

---

## Phase 3: グリッド・レイアウト層（✅ 完了）

### l-row 系

| 旧クラス | 新クラス | 備考 | ステータス |
|---|---|---|---|
| `l-row--container` | `container mx-auto flex flex-wrap justify-center` | justify-start/between がある行では justify-center を省略 | ✅ |
| `l-row` | `flex flex-wrap justify-center` | 同上 | ✅ |

### c-col 系（ベース幅）

| 旧クラス | 新クラス | ステータス |
|---|---|---|
| `c-col--12` | `w-full` | ✅ |
| `c-col--11` | `w-11/12` | ✅ |

### c-col 系（レスポンシブ）

| 旧クラス | 新クラス | ステータス |
|---|---|---|
| `c-col__sm--4` | `sm:w-4/12` | ✅ |
| `c-col__sm--5` | `sm:w-5/12` | ✅ |
| `c-col__sm--7` | `sm:w-7/12` | ✅ |
| `c-col__sm--8` | `sm:w-8/12` | ✅ |
| `c-col__sm--10` | `sm:w-10/12` | ✅ |
| `c-col__sm--12` | `sm:w-full` | ✅ |
| `c-col__md--4` | `md:w-4/12` | ✅ |
| `c-col__md--8` | `md:w-8/12` | ✅ |
| `c-col__md--10` | `md:w-10/12` | ✅ |
| `c-col__md--11` | `md:w-11/12` | ✅ |
| `c-col__md--12` | `md:w-full` | ✅ |
| `c-col__lg--3` | `lg:w-3/12` | ✅ |
| `c-col__lg--4` | `lg:w-4/12` | ✅ |
| `c-col__lg--5` | `lg:w-5/12` | ✅ |
| `c-col__lg--7` | `lg:w-7/12` | ✅ |
| `c-col__lg--8` | `lg:w-8/12` | ✅ |
| `c-col__lg--9` | `lg:w-9/12` | ✅ |
| `c-col__lg--10` | `lg:w-10/12` | ✅ |
| `c-col__xl--6` | `xl:w-6/12` | ✅ |
| `c-col__xl--8` | `xl:w-8/12` | ✅ |
| `c-col__xl--9` | `xl:w-9/12` | ✅ |
| `c-col__xl--10` | `xl:w-10/12` | ✅ |

### l-grid / c-grid 系

| 旧クラス | 新クラス | ステータス |
|---|---|---|
| `l-grid` | `grid gap-x-grid-gutter` | ✅ |
| `c-grid--1` | `grid-cols-1` | ✅ |
| `c-grid__sm--1` | `sm:grid-cols-1` | ✅ |
| `c-grid__sm--2` | `sm:grid-cols-2` | ✅ |
| `c-grid__md--2` | `md:grid-cols-2` | ✅ |
| `c-grid__lg--3` | `lg:grid-cols-3` | ✅ |

### c-gutter 系

| 旧クラス | 新クラス | ステータス |
|---|---|---|
| `c-gutter__row` | `px-gutter-row xl:px-0` | ✅ |
| `c-gutter__post` | `md:px-gutter-row xl:px-0` | ✅ |
| `c-gutter__sm--left` | `sm:pl-gutter-2 md:pl-gutter-3` | ✅ |
| `c-gutter__sm--right` | `sm:pr-gutter-2 md:pr-gutter-3` | ✅ |
| `c-gutter__md--left` | `md:pl-gutter-3` | ✅ |

---

## Phase 4: コンポーネント・プロジェクト層（⏳ 未着手）

> Phase 4 ではコンポーネントクラス名自体は変更しない（`@layer components` で維持）。
> ただし内部で使用しているクラスの変更があれば、ここに記録する。

---

## 更新履歴

| 日付 | Phase | 内容 |
|---|---|---|
| 2026-02-26 | — | 初版作成（テンプレートのみ） |
| 2026-03-14 | 2 | Phase 2 全変換表を転記（ステータスは ⏳ 未着手） |
| 2026-03-14 | 2 | Phase 2 全 Step 完了。全ステータスを ✅ に更新 |
| 2026-03-15 | 3 | Phase 3 全変換表を記入。全ステータスを ✅ に更新 |
