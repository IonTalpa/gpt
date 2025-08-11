import { describe, it, expect } from "vitest";
import i18n from "../src/i18n";

describe("i18n", () => {
  it("translates", () => {
    expect(i18n.t("welcome", { lng: "en" })).toBe("Welcome");
    expect(i18n.t("welcome", { lng: "tr" })).toBe("Hoşgeldiniz");
  });
});
