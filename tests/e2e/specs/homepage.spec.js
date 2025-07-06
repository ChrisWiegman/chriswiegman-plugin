// @ts-check
const { test, expect } = require('@playwright/test');

test('has plugin title', async ({ page }) => {
  await page.goto('http://localhost:8888/');

  // Expect a title "to contain" a substring.
  await expect(page).toHaveTitle(/chriswiegman-plugin/);
});
