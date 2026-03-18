/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
  ],

  theme: {
    // --- screens ---
    // 出典: _variables.scss L15-19
    screens: {
      sm: '576px',
      md: '811px',
      lg: '1025px',
      xl: '1280px',
      '2xl': '1366px',
    },

    // --- container ---
    // 出典: _variables.scss L37-41 ($container-max-*)
    // 計画書 §7 / Step 3-1: container の max-width を BP 別に定義。
    // Tailwind v4 では v3 compat の container.screens が二重定義を生成するため、
    // tailwind-base.css 側で @source not inline("container") + @layer utilities
    // による単一定義に移行した。BP→max-width 対応は計画書と同一:
    //   sm(576px)→540px, md(811px)→960px, lg(1025px)→1152px,
    //   xl(1280px)→1200px, 2xl(1366px)→1260px

    // --- colors ---
    // 出典: _variables-color.scss → CSS 変数参照
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      white: '#fff',
      black: '#222',
      clr1: 'var(--clr1)',
      clr2: 'var(--clr2)',
      clr3: 'var(--clr3)',
      clr4: 'var(--clr4)',
      clr5: 'var(--clr5)',
      'clr-prim-green': 'var(--clr-prim-green)',
      gray: {
        50:  'var(--clrg50)',
        100: 'var(--clrg100)',
        200: 'var(--clrg200)',
        300: 'var(--clrg300)',
        400: 'var(--clrg400)',
        500: 'var(--clrg500)',
        600: 'var(--clrg600)',
        700: 'var(--clrg700)',
        800: 'var(--clrg800)',
        900: 'var(--clrg900)',
      },
      red: '#f00',
      link: 'var(--link-color)',
      'link-hover': 'var(--link-hover-color)',
    },

    // --- spacing ---
    // 旧 _variables.scss $space_values に基づく。SCSS 側の定義は削除済み（この JS が単一の定義元）
    // mt-4 = 32px を維持。Tailwind デフォルト (mt-4 = 16px) には合わせない
    spacing: {
      0: '0',
      px: '1px',
      0.5: '0.25rem',    // 4px
      0.75: '0.375rem',  // 6px
      1: '0.5rem',       // 8px
      1.25: '0.625rem',  // 10px
      1.5: '0.75rem',    // 12px
      2: '1rem',         // 16px
      2.25: '1.125rem',  // 18px
      2.5: '1.25rem',    // 20px
      3: '1.5rem',       // 24px
      3.5: '1.75rem',    // 28px
      4: '2rem',         // 32px
      4.5: '2.25rem',    // 36px
      5: '2.5rem',       // 40px
      6: '3rem',         // 48px
      7: '3.5rem',       // 56px
      8: '4rem',         // 64px
      9: '4.5rem',       // 72px
      10: '5rem',        // 80px
      12: '6rem',        // 96px
      13: '6.5rem',      // 104px
      14: '7rem',        // 112px
      15: '7.5rem',      // 120px
      16: '8rem',        // 128px
      18: '9rem',        // 144px
      20: '10rem',       // 160px
    },

    // --- zIndex ---
    // 出典: _variables.scss L147-153 ($layout_zindex)
    zIndex: {
      auto: 'auto',
      default: '1',
      swiper: '100',
      footer: '500',
      header: '1000',
      micromodal: '2000',
    },

    // --- fontFamily ---
    // 出典: _variables.scss L79-82
    fontFamily: {
      sans: [
        'avenir', '"Noto Sans JP"', '"游ゴシック体"', 'yugothic',
        '"游ゴシック Medium"', '"Yu Gothic Medium"',
        '"ヒラギノ角ゴ ProN W3"', '"Hiragino Kaku Gothic ProN"',
        '"メイリオ"', 'meiryo', 'sans-serif',
      ],
      serif: [
        '"Times New Roman"', '"游明朝体"', 'yumincho', '"游明朝"',
        '"Yu Mincho"', '"ヒラギノ明朝 ProN W3"', '"Hiragino Mincho ProN"',
        '"HGS明朝E"', '"ＭＳ Ｐ明朝"', '"MS PMincho"', 'serif',
      ],
      mono: [
        'menlo', 'monaco', 'consolas', '"Liberation Mono"',
        '"Courier New"', 'monospace',
      ],
    },

    // --- borderRadius ---
    // 出典: _variables.scss L87
    borderRadius: {
      DEFAULT: '6px',
    },

    // --- transitionDuration ---
    // 出典: _variables.scss L88 ($transition-base の duration 部分)
    transitionDuration: {
      DEFAULT: '200ms',
    },

    extend: {
      // --- fontSize (fz系) ---
      // 出典: _font.scss の fz__ クラス。基本サイズ + 2xl サイズアップ用
      fontSize: {
        fz12: '0.75rem',
        fz13: '0.8125rem',
        fz14: '0.875rem',
        fz15: '0.9375rem',
        fz16: '1rem',
        fz17: '1.0625rem',
        fz18: '1.125rem',
        fz19: '1.1875rem',
        fz20: '1.25rem',
        fz21: '1.3125rem',
        fz22: '1.375rem',
        fz23: '1.4375rem',
        fz24: '1.5rem',
        fz25: '1.5625rem',
        fz26: '1.625rem',
        fz27: '1.6875rem',
        fz28: '1.75rem',
        fz29: '1.8125rem',
        fz30: '1.875rem',
        fz31: '1.9375rem',
        fz32: '2rem',
        fz33: '2.0625rem',
        fz34: '2.125rem',
        fz35: '2.1875rem',
        fz36: '2.25rem',
      },

      // --- maxWidth ---
      // 出典: _variables.scss L37-41 ($container-max-*)
      maxWidth: {
        'container-sm': '540px',
        'container-md': '960px',
        'container-lg': '1152px',
        'container-xl': '1200px',
        'container-xxl': '1260px',
      },

      // --- padding (gutter系) ---
      // 出典: 計画書 §3.4
      padding: {
        'gutter-1': 'calc(var(--gutter) * 1)',
        'gutter-1.5': 'calc(var(--gutter) * 1.5)',
        'gutter-2': 'calc(var(--gutter) * 2)',
        'gutter-3': 'calc(var(--gutter) * 3)',
        'gutter-row': 'var(--gutter-row)',
      },

      // --- gap (grid用) ---
      // 出典: 計画書 §3.4
      gap: {
        'grid-gutter': 'calc(var(--gutter) * 2)',
      },
    },
  },
}
