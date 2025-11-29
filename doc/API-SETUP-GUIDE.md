# API Setup Guide for Automatic Review Import

This guide will help you set up automatic review imports from Google, TripAdvisor, and Airbnb for your hotel.

## 📋 Overview

The plugin can automatically fetch reviews from:
- ✅ **Google Business** (via Google Places API)
- ✅ **TripAdvisor** (via TripAdvisor Content API)
- ⚠️ **Airbnb** (limited - see notes below)

---

## 🔵 Google Business Setup

### Step 1: Get Your Google API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project (or select existing)
3. Enable **"Places API"**:
   - Go to "APIs & Services" → "Library"
   - Search for "Places API"
   - Click "Enable"
4. Create credentials:
   - Go to "APIs & Services" → "Credentials"
   - Click "Create Credentials" → "API Key"
   - Copy your API key
   - (Optional) Restrict the key to "Places API" only for security

### Step 2: Find Your Place ID

1. Go to [Place ID Finder](https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder)
2. Search for your hotel
3. Copy the **Place ID** (starts with "ChIJ...")

### Step 3: Configure in WordPress

1. Go to **Reviews → Import Settings**
2. Under "Google Business Settings":
   - Paste your **API Key**
   - Paste your **Place ID**
3. Click **"Test Connection"** to verify
4. Click **"Save Changes"**

### Cost & Limits
- Google Places API: **Free tier** includes $200/month credit
- Each review fetch costs ~$0.017
- You can fetch ~11,000 reviews/month for free

---

## 🟢 TripAdvisor Setup

### Step 1: Get TripAdvisor API Access

1. Go to [TripAdvisor Developer Portal](https://www.tripadvisor.com/developers)
2. Sign up for an account
3. Apply for API access:
   - Fill out the application form
   - Describe your use case (hotel website)
   - Wait for approval (usually 1-3 business days)
4. Once approved, you'll receive your **API Key**

### Step 2: Find Your Location ID

1. Go to your hotel's TripAdvisor page
2. Look at the URL, it will look like:
   ```
   https://www.tripadvisor.com/Hotel_Review-g123456-d789012-Reviews-Your_Hotel_Name
   ```
3. The Location ID is the part after `-d` (e.g., `d789012`)
4. Copy just the number part: `789012`

### Step 3: Configure in WordPress

1. Go to **Reviews → Import Settings**
2. Under "TripAdvisor Settings":
   - Paste your **API Key**
   - Paste your **Location ID**
3. Click **"Test Connection"** to verify
4. Click **"Save Changes"**

### Cost & Limits
- TripAdvisor API: **Free for basic usage**
- Rate limits: 1000 requests/day
- Commercial use may require partnership agreement

---

## 🔴 Airbnb Setup (Important Notes)

### ⚠️ Current Limitations

**Airbnb does NOT provide a public API for reviews.** Here are your options:

### Option 1: Manual Entry (Recommended)
- Copy reviews from your Airbnb listing
- Add them manually through WordPress admin
- Most reliable and compliant with Airbnb's terms

### Option 2: Third-Party Services
Some services offer Airbnb review scraping:
- [ReviewTrackers](https://www.reviewtrackers.com/)
- [TrustYou](https://www.trustyou.com/)
- [ReviewPro](https://www.reviewpro.com/)

These are paid services and may have their own terms.

### Option 3: Custom Solution
If you have technical expertise:
- Use web scraping (check Airbnb's terms of service)
- Build a custom integration with Airbnb's private API
- Note: This may violate Airbnb's terms

### Step 3: Find Your Listing ID

1. Go to your Airbnb listing page
2. Look at the URL:
   ```
   https://www.airbnb.com/rooms/12345678
   ```
3. The Listing ID is the number: `12345678`

---

## ⚙️ Configure Automatic Import

### General Settings

1. Go to **Reviews → Import Settings**
2. Configure these options:

   **Enable Auto Import**: ✅ Check this box
   
   **Import Frequency**: Choose how often to check for new reviews
   - Hourly (for high-traffic hotels)
   - Twice Daily (recommended)
   - Daily (default)
   - Weekly (for smaller properties)
   
   **Import Limit**: Maximum reviews per platform (default: 50)

3. Click **"Save Changes"**

### Manual Import

To import reviews immediately:
1. Scroll to "Manual Import" section
2. Click **"Import Reviews Now"**
3. Wait for the process to complete
4. Check the "Recent Import Activity" log

---

## 🎯 Best Practices

### 1. Start with Manual Import
- After setting up APIs, do a manual import first
- This will fetch all existing reviews
- Verify everything looks correct

### 2. Choose the Right Frequency
- **High-volume hotels**: Hourly or Twice Daily
- **Medium-volume**: Daily
- **Low-volume**: Weekly

### 3. Monitor Import Logs
- Check "Recent Import Activity" regularly
- Look for any failed imports
- Verify review counts match your platforms

### 4. API Key Security
- Never share your API keys publicly
- Restrict API keys to specific domains if possible
- Rotate keys periodically for security

### 5. Review Quality
- The plugin imports reviews automatically
- You can still manually edit or delete any review
- Consider moderating before displaying publicly

---

## 🔧 Troubleshooting

### Google Import Issues

**Problem**: "Invalid API Key"
- ✅ Verify API key is correct
- ✅ Ensure Places API is enabled
- ✅ Check API key restrictions

**Problem**: "Place ID not found"
- ✅ Double-check Place ID format (starts with "ChIJ")
- ✅ Use the Place ID Finder tool
- ✅ Ensure your business is verified on Google

### TripAdvisor Import Issues

**Problem**: "API access denied"
- ✅ Verify your API application is approved
- ✅ Check if API key is active
- ✅ Ensure you're within rate limits

**Problem**: "Location ID invalid"
- ✅ Use only the numeric part of the ID
- ✅ Don't include the "d" prefix
- ✅ Verify the ID matches your listing

### General Issues

**Problem**: No reviews imported
- ✅ Test connection for each platform
- ✅ Check if you have reviews on those platforms
- ✅ Verify import limit isn't set too low
- ✅ Check WordPress cron is working

**Problem**: Duplicate reviews
- ✅ The plugin checks for duplicates automatically
- ✅ Based on reviewer name and review text
- ✅ If duplicates appear, they may have different text

---

## 📊 Import Status Codes

When you see import results:

- ✅ **Success**: Reviews imported successfully
- ⚠️ **No new reviews**: All reviews already imported
- ❌ **Failed - Missing credentials**: API key or ID not configured
- ❌ **Failed - API error**: Check your API settings
- ❌ **Failed - Rate limit**: Wait and try again later

---

## 🔐 Privacy & Compliance

### GDPR Compliance
- Reviews are public information
- Include reviewer names only if they're public
- Provide option to remove reviews on request

### Platform Terms of Service
- ✅ Google: Allowed with proper attribution
- ✅ TripAdvisor: Check your API agreement
- ⚠️ Airbnb: Manual copying is safer

### Attribution
The plugin automatically:
- Links to original reviews
- Shows platform badges
- Maintains reviewer attribution

---

## 💡 Pro Tips

1. **Test First**: Always use "Test Connection" before saving
2. **Start Small**: Import 10-20 reviews first to test
3. **Monitor Regularly**: Check import logs weekly
4. **Update APIs**: Keep API keys current
5. **Backup**: Export reviews periodically as backup

---

## 📞 Need Help?

If you encounter issues:

1. Check the **Recent Import Activity** log
2. Test each platform connection individually
3. Verify your API credentials are correct
4. Check WordPress error logs
5. Contact support with specific error messages

---

## 🎉 You're All Set!

Once configured, the plugin will:
- ✅ Automatically fetch new reviews
- ✅ Avoid duplicates
- ✅ Display them beautifully on your site
- ✅ Keep everything up to date

**Next Steps:**
1. Complete API setup for each platform
2. Run manual import to fetch existing reviews
3. Add the shortcode to your website
4. Enjoy automatic review updates!

```
[social_reviews platform="all" slides_per_view="3" autoplay="true"]
```

---

**Happy reviewing! 🌟**
