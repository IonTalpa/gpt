module.exports = {
  parser: "@typescript-eslint/parser",
  extends: ["plugin:react/recommended", "prettier"],
  plugins: ["react"],
  env: {
    browser: true,
    es2021: true,
  },
  settings: {
    react: {
      version: "detect",
    },
  },
};
