const puppeteer = require("puppeteer");
const fs = require("fs");

async function debugTripAdvisor() {
  let browser;
  try {
    console.log("Launching browser...");
    browser = await puppeteer.launch({ headless: false }); // Set to "new" to use new headless mode, or false to see what's happening
    const page = await browser.newPage();

    const url =
      "https://www.tripadvisor.com/Hotel_Review-g504011-d7691496-Reviews-West_Layton_Manor-Richmond_North_Yorkshire_England.html";

    console.log("Navigating to:", url);
    await page.goto(url, { waitUntil: "networkidle2", timeout: 30000 });

    await page.waitForTimeout(5000);

    // Check if reviews are visible
    const reviewElements = await page.$$(
      ".review-container, [data-reviewid], .review"
    );
    console.log("Found review elements:", reviewElements.length);

    // Get some text from reviews
    if (reviewElements.length > 0) {
      const firstReview = await page.evaluate(
        (el) => el.textContent,
        reviewElements[0]
      );
      console.log(
        "First review text (first 200 chars):",
        firstReview.substring(0, 200)
      );
    }

    // Save HTML
    const html = await page.content();
    fs.writeFileSync("tripadvisor-debug.html", html);
    console.log("HTML saved to tripadvisor-debug.html");

    // Try scrolling
    await page.evaluate(() => window.scrollTo(0, 1000));
    await page.waitForTimeout(2000);

    const reviewElementsAfterScroll = await page.$$(
      ".review-container, [data-reviewid], .review"
    );
    console.log(
      "Review elements after scroll:",
      reviewElementsAfterScroll.length
    );
  } catch (error) {
    console.error("Error:", error);
  } finally {
    if (browser) await browser.close();
  }
}

debugTripAdvisor();
