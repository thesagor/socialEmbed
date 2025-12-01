const puppeteer = require("puppeteer-extra");
const StealthPlugin = require("puppeteer-extra-plugin-stealth");
const { extractReviewsFromHTML } = require("../utils/ai-extractor");

// Add stealth plugin
puppeteer.use(StealthPlugin());

/**
 * Scrape TripAdvisor hotel reviews
 */
async function scrape(hotelUrl, maxReviews = 50) {
  let browser;

  try {
    console.log("[TripAdvisor] Launching browser with stealth...");
    
    // Use mobile user agent if enabled (reduces CAPTCHA triggers)
    const useMobileUA = process.env.USE_MOBILE_USER_AGENT !== "false";

    browser = await puppeteer.launch({
      headless: process.env.HEADLESS_MODE !== "false" ? "new" : false,
      args: [
        "--no-sandbox",
        "--disable-setuid-sandbox",
        "--disable-dev-shm-usage",
        "--disable-accelerated-2d-canvas",
        "--disable-gpu",
        "--disable-blink-features=AutomationControlled",
        "--window-size=1920,1080",
        "--disable-web-security",
        "--disable-features=IsolateOrigins,site-per-process",
      ],
    });

    const page = await browser.newPage();

    // Mobile user agents (less likely to trigger CAPTCHA)
    const mobileUserAgents = [
      "Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1",
      "Mozilla/5.0 (iPad; CPU OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1",
      "Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36",
    ];
    
    // Desktop user agents (fallback)
    const desktopUserAgents = [
      "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
      "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
      "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
    ];

    const userAgents = useMobileUA ? mobileUserAgents : desktopUserAgents;
    const randomUA = userAgents[Math.floor(Math.random() * userAgents.length)];
    await page.setUserAgent(randomUA);
    
    if (useMobileUA) {
      console.log("[TripAdvisor] Using mobile user agent to reduce CAPTCHA");
    }

    await page.setViewport({ width: 1920, height: 1080 });

    // Enhanced anti-detection
    await page.evaluateOnNewDocument(() => {
      // Remove webdriver property
      Object.defineProperty(navigator, "webdriver", { get: () => undefined });
      
      // Mock plugins
      Object.defineProperty(navigator, "plugins", {
        get: () => [1, 2, 3, 4, 5],
      });
      
      // Mock languages
      Object.defineProperty(navigator, "languages", {
        get: () => ["en-US", "en"],
      });
      
      // Override permissions
      const originalQuery = window.navigator.permissions.query;
      window.navigator.permissions.query = (parameters) =>
        parameters.name === "notifications"
          ? Promise.resolve({ state: Notification.permission })
          : originalQuery(parameters);
    });

    // Set extra headers
    await page.setExtraHTTPHeaders({
      "Accept-Language": "en-US,en;q=0.9",
      "Accept-Encoding": "gzip, deflate, br",
      "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
    });

    console.log("[TripAdvisor] Navigating to:", hotelUrl);
    
    // Random delay before navigation (1-3 seconds)
    await page.waitForTimeout(1000 + Math.random() * 2000);
    
    await page.goto(hotelUrl, {
      waitUntil: "networkidle2",
      timeout: parseInt(process.env.TIMEOUT_MS) || 60000,
    });

    // Random wait time (3-7 seconds)
    await page.waitForTimeout(3000 + Math.random() * 4000);

    // Handle cookie consent if it appears
    try {
      const acceptButton = await page.$(
        'button[id*="accept"], button[class*="accept"]'
      );
      if (acceptButton) {
        await acceptButton.click();
        await page.waitForTimeout(2000);
      }
    } catch (e) {
      console.log("[TripAdvisor] No cookie consent found");
    }

    // Wait for potential CAPTCHA
    await page.waitForTimeout(2000);

    // Check if CAPTCHA is present (multiple types)
    const captchaSelectors = [
      'iframe[src*="captcha"]',
      'iframe[src*="recaptcha"]',
      'iframe[title*="recaptcha"]',
      '#px-captcha',
      '.g-recaptcha',
      '[id*="captcha"]',
      '[class*="captcha"]',
    ];

    let captchaPresent = false;
    for (const selector of captchaSelectors) {
      const element = await page.$(selector);
      if (element) {
        captchaPresent = true;
        console.log(`[TripAdvisor] CAPTCHA detected (${selector})`);
        break;
      }
    }

    if (captchaPresent) {
      const captchaTimeout = parseInt(process.env.CAPTCHA_TIMEOUT_MS) || 120000;
      console.log(
        `[TripAdvisor] ⚠️  CAPTCHA detected - waiting ${captchaTimeout / 1000}s for manual solve...`
      );
      console.log(
        "[TripAdvisor] 💡 TIP: Set HEADLESS_MODE=false to solve manually, or use mobile URL"
      );
      
      // Wait for CAPTCHA to be solved
      await page.waitForTimeout(captchaTimeout);
      
      // Check if still present
      const stillPresent = await page.$(captchaSelectors[0]);
      if (stillPresent) {
        throw new Error("CAPTCHA was not solved in time");
      }
      console.log("[TripAdvisor] ✓ CAPTCHA appears to be solved, continuing...");
    }

    // Try to click "Read all reviews" or similar button
    try {
      const reviewsButton = await page.$('a[href*="Reviews"]');
      if (reviewsButton) {
        await reviewsButton.click();
        await page.waitForTimeout(3000);
      }
    } catch (e) {
      console.log("[TripAdvisor] Already on reviews page");
    }

    // Scroll to load more reviews with longer delays
    console.log("[TripAdvisor] Scrolling to load reviews...");
    await autoScroll(page, 10); // Increased scroll count

    // Try to expand "Read more" buttons
    try {
      await page.evaluate(() => {
        const readMoreButtons = document.querySelectorAll(
          'span[class*="readMore"], div[class*="readMore"], button[class*="readMore"]'
        );
        readMoreButtons.forEach((button) => button.click());
      });
      await page.waitForTimeout(2000);
    } catch (e) {
      console.log("[TripAdvisor] Could not expand reviews");
    }

    // Get the page HTML
    const html = await page.content();

    console.log("[TripAdvisor] Extracting reviews with AI...");
    const reviews = await extractReviewsFromHTML(
      html,
      "tripadvisor",
      maxReviews
    );

    console.log(
      `[TripAdvisor] Successfully extracted ${reviews.length} reviews`
    );

    return reviews;
  } catch (error) {
    console.error("[TripAdvisor] Scraping error:", error);
    throw error;
  } finally {
    if (browser) {
      await browser.close();
    }
  }
}

/**
 * Auto-scroll function with longer delays
 */
async function autoScroll(page, scrollCount = 10) {
  await page.evaluate(async (count) => {
    await new Promise((resolve) => {
      let scrolls = 0;
      const distance = 800; // Increased distance
      const timer = setInterval(() => {
        window.scrollBy(0, distance);
        scrolls++;

        if (scrolls >= count) {
          clearInterval(timer);
          resolve();
        }
      }, 1000); // Increased delay between scrolls
    });
  }, scrollCount);
}

module.exports = { scrape };
