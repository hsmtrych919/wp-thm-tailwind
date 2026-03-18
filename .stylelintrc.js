module.exports = {
  extends: [
  'stylelint-config-recommended-scss',
  'stylelint-config-property-sort-order-smacss',
  // 'stylelint-config-wordpress',
  // 'stylelint-config-recess-order',
  ],
  rules: {
  'at-rule-no-unknown': null, //scssで使える @include などにエラーがでないようにする
  // 'scss/at-rule-no-unknown': true, //scssでサポートしていない @ルール にはエラーを出す
  'scss/at-rule-no-unknown':[true,{
    'ignoreAtRules':[ "function","if","else if","else","for","each","include","mixin","content","use","forward"]
  }],
  'block-no-empty': null,
  'indentation': 2,
  'string-quotes': 'double',
  'property-no-vendor-prefix': true,
  'font-family-name-quotes': 'always-where-recommended',
  'font-weight-notation': null,
  'no-descending-specificity': null, // オーバーライドチェック。ネスト用にnull
  "no-extra-semicolons": true, // 余分なセミコロン
  "declaration-block-no-duplicate-properties": true, //プロパティの重複チェック
  "color-function-notation": "legacy", // rgba()の書式
  "rule-empty-line-before": [
    "always-multi-line",
    {
      except: ["first-nested"],
      ignore: ["after-comment","inside-block"],
    },
  ],
  "function-comma-space-after": "always",
  "max-empty-lines": 2,
  "custom-property-empty-line-before":"never",
  "declaration-colon-space-before": "never",
  "declaration-colon-space-after": "always",
  "declaration-empty-line-before": "never",
  "selector-list-comma-space-after":"always",
  "block-opening-brace-space-before": "always",
  "no-invalid-double-slash-comments": null,
  "comment-no-empty": null,
  "block-closing-brace-empty-line-before": "never",
  "function-calc-no-unspaced-operator":true,
  "function-parentheses-space-inside":"never",
  "function-comma-space-before":"never",
  "function-comma-space-after":"always"
  }
};