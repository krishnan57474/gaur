module.exports = {
    parser: "@typescript-eslint/parser",
    plugins: [
        "@typescript-eslint"
    ],
    extends: [
        "eslint:recommended",
        "plugin:@typescript-eslint/eslint-recommended",
        "plugin:@typescript-eslint/recommended"
    ],
    rules: {
        "@typescript-eslint/no-inferrable-types": "off",

        "indent": "off",
        "@typescript-eslint/indent": ["error", 4],

        "@typescript-eslint/member-ordering": [
            "error",
            {
              "default": [
                "private-static-field",
                "protected-static-field",
                "public-static-field",

                "private-instance-field",
                "protected-instance-field",
                "public-instance-field",

                "private-field",
                "protected-field",
                "public-field",

                "static-field",
                "instance-field",

                "field",

                "constructor",

                "private-static-method",
                "protected-static-method",
                "public-static-method",

                "private-instance-method",
                "protected-instance-method",
                "public-instance-method",

                "private-method",
                "protected-method",
                "public-method",

                "static-method",
                "instance-method",

                "method"
              ]
            }
        ],

        "no-extra-parens": "off",
        "@typescript-eslint/no-extra-parens": ["error"],

        "semi": "off",
        "@typescript-eslint/semi": ["error"]
    }
};
