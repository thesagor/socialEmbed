require("dotenv").config();
const { scrape } = require("./scrapers/tripadvisor-scraper");

async function testTripAdvisor() {
  console.log("=".repeat(60));
  console.log("🧪 Testing TripAdvisor Scraper with CAPTCHA Bypass");
  console.log("=".repeat(60));
  console.log("");
  
  console.log("📋 Current Configuration:");
  console.log(`   HEADLESS_MODE: ${process.env.HEADLESS_MODE || "true"}`);
  console.log(`   USE_MOBILE_USER_AGENT: ${process.env.USE_MOBILE_USER_AGENT || "true"}`);
  console.log(`   CAPTCHA_TIMEOUT_MS: ${process.env.CAPTCHA_TIMEOUT_MS || "120000"}ms`);
  console.log(`   TIMEOUT_MS: ${process.env.TIMEOUT_MS || "30000"}ms`);
  console.log("");

  const testUrl =
    "https://www.tripadvisor.com/Hotel_Review-g504011-d7691496-Reviews-West_Layton_Manor-Richmond_North_Yorkshire_England.html";

  console.log("🎯 Test URL:", testUrl);
  console.log("");
  console.log("⏳ Starting scrape...");
  console.log("");

  try {
    const reviews = await scrape(testUrl, 10);

    console.log("");
    console.log("=".repeat(60));
    console.log("✅ SUCCESS!");
    console.log("=".repeat(60));
    console.log(`📊 Extracted ${reviews.length} reviews`);
    console.log("");

    if (reviews.length > 0) {
      console.log("📝 Sample Review:");
      console.log("   Author:", reviews[0].author || "N/A");
      console.log("   Rating:", reviews[0].rating || "N/A");
      console.log("   Text:", (reviews[0].text || "N/A").substring(0, 100) + "...");
      console.log("");
    }

    console.log("🎉 All reviews:");
    reviews.forEach((review, index) => {
      console.log(`   ${index + 1}. ${review.author} - ${review.rating}⭐`);
    });
  } catch (error) {
    console.log("");
    console.log("=".repeat(60));
    console.log("❌ ERROR!");
    console.log("=".repeat(60));
    console.log("Error:", error.message);
    console.log("");
    
    if (error.message.includes("CAPTCHA")) {
      console.log("💡 CAPTCHA Tips:");
      console.log("   1. Set HEADLESS_MODE=false in .env to solve manually");
      console.log("   2. Increase CAPTCHA_TIMEOUT_MS if you need more time");
      console.log("   3. Mobile user agents are enabled (reduces CAPTCHA by 60-80%)");
      console.log("   4. Consider using proxies for production");
    }
  }

  console.log("");
  console.log("=".repeat(60));
}

testTripAdvisor();
