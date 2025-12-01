const http = require("http");

console.log("🧪 Testing TripAdvisor Scrape...\n");

const testData = {
  hotelUrl:
    "https://www.tripadvisor.com/Hotel_Review-g504011-d7691496-Reviews-West_Layton_Manor-Richmond_North_Yorkshire_England.html",
  maxReviews: 5,
};

const postData = JSON.stringify(testData);

const options = {
  hostname: "localhost",
  port: 3000,
  path: "/api/scrape/tripadvisor",
  method: "POST",
  headers: {
    "Content-Type": "application/json",
    "Content-Length": Buffer.byteLength(postData),
    "X-API-Key": "social-review-scraper-secret-2024-xyz789",
  },
};

console.log("📍 Testing URL:", testData.hotelUrl);
console.log("📊 Max Reviews:", testData.maxReviews);
console.log(
  "\n⏳ Scraping... (this may take longer due to anti-bot measures)\n"
);

const req = http.request(options, (res) => {
  let data = "";

  res.on("data", (chunk) => {
    data += chunk;
  });

  res.on("end", () => {
    try {
      const result = JSON.parse(data);

      if (result.success) {
        console.log("✅ Scraping successful!\n");
        console.log("═══════════════════════════════════════");
        console.log(`📊 Results: ${result.count} reviews found`);
        console.log("═══════════════════════════════════════\n");

        if (result.reviews && result.reviews.length > 0) {
          console.log("Sample Review:");
          const sample = result.reviews[0];
          console.log(`  👤 Name: ${sample.author_name}`);
          console.log(`  ⭐ Rating: ${sample.rating}/5`);
          console.log(`  📅 Date: ${sample.date}`);
          console.log(`  💬 Text: ${sample.text.substring(0, 100)}...`);
        } else {
          console.log("❌ No reviews found");
        }
      } else {
        console.log("❌ Scraping failed:", result.error);
      }
    } catch (error) {
      console.error("❌ Error parsing response:", error);
      console.log("Raw response:", data);
    }
  });
});

req.on("error", (error) => {
  console.error("❌ Request error:", error);
});

req.write(postData);
req.end();
