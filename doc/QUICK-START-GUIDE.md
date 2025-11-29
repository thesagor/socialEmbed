# 🚀 Quick Start Guide - Social Review Slider

## Overview

This project has **TWO components**:

1. **WordPress Plugin** - Display reviews in a beautiful slider
2. **AI Scraper Service** - Automatically scrape reviews from Google, TripAdvisor, Airbnb

You can use **EITHER** or **BOTH** depending on your needs!

---

## 📋 Table of Contents

- [Option A: WordPress Plugin Only (Manual Reviews)](#option-a-wordpress-plugin-only)
- [Option B: WordPress Plugin + AI Scraper (Automatic)](#option-b-wordpress-plugin--ai-scraper)
- [Troubleshooting](#troubleshooting)

---

# Option A: WordPress Plugin Only

**Best for:** Quick setup, manual review management, no API costs

## Step 1: Install the WordPress Plugin

### 1.1 Create Plugin ZIP File

1. Navigate to: `c:\Users\Sagor Ahmed\Desktop\n8n\socialEmbed`
2. Right-click the folder → **Send to** → **Compressed (zipped) folder**
3. Rename to: `social-review-slider.zip`

### 1.2 Upload to WordPress

1. Login to your WordPress admin panel
2. Go to **Plugins → Add New → Upload Plugin**
3. Click **Choose File** and select `social-review-slider.zip`
4. Click **Install Now**
5. Click **Activate Plugin**

✅ **Success!** You should now see a **"Reviews"** menu in WordPress admin.

---

## Step 2: Add Your First Review

1. In WordPress admin, go to **Reviews → Add New**

2. Fill in the review details:
   - **Title**: "Great Experience" *(internal reference only)*
   - **Content**: Write the full review text
   - **Reviewer Name**: "John Doe"
   - **Rating**: Select 5 stars (⭐⭐⭐⭐⭐)
   - **Review Date**: Select when the review was posted
   - **Review URL**: Paste link to original review (optional)
   - **Reviewer Location**: "New York, USA"
   - **Reviewer Avatar**: Upload a profile picture (optional)
   - **Platform**: Check ☑️ **Google** (or TripAdvisor/Airbnb)

3. Click **Publish**

4. **Repeat** to add more reviews (minimum 3-5 recommended)

---

## Step 3: Display Reviews on Your Website

### Method 1: Using Shortcode (Recommended)

1. Edit any **Page** or **Post**
2. Add a **Shortcode block** (or Custom HTML block)
3. Paste this code:
   ```
   [social_reviews]
   ```
4. Click **Update** or **Publish**
5. View the page to see your reviews!

### Method 2: Using Widget

1. Go to **Appearance → Widgets**
2. Find **Social Review Slider** widget
3. Drag it to your desired widget area (sidebar, footer, etc.)
4. Configure the settings
5. Click **Save**

---

## Step 4: Customize the Display

### Show Only Specific Platform

```
[social_reviews platform="google"]
```

Options: `google`, `tripadvisor`, `airbnb`, `all`

### Show Limited Number of Reviews

```
[social_reviews count="5"]
```

### Enable Autoplay

```
[social_reviews autoplay="true" autoplay_delay="5000"]
```

### Show Multiple Slides at Once

```
[social_reviews slides_per_view="3" space_between="20"]
```

### Random Order

```
[social_reviews random="true"]
```

### Complete Example

```
[social_reviews platform="all" count="10" autoplay="true" slides_per_view="3" random="true"]
```

---

## Step 5: Preview the Demo

Open `demo.html` in your browser to see how the slider looks with sample data!

---

# Option B: WordPress Plugin + AI Scraper

**Best for:** Automatic review imports, hands-free updates, large review volumes

**Requirements:**
- ✅ Node.js installed
- ✅ OpenAI API key with credits ([Get here](https://platform.openai.com/api-keys))
- ✅ Google Maps, TripAdvisor, or Airbnb listing URLs

---

## Step 1: Setup AI Scraper Service

### 1.1 Install Node.js (if not installed)

Download from: https://nodejs.org/ (LTS version recommended)

### 1.2 Enable PowerShell Scripts

Open **PowerShell as Administrator** and run:

```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### 1.3 Install Dependencies

Open **PowerShell** (normal, not admin) and run:

```powershell
cd "c:\Users\Sagor Ahmed\Desktop\n8n\socialEmbed\scraper-service"
npm install
```

Wait for installation to complete (~1-2 minutes).

### 1.4 Configure Environment

**Option A: Use the Setup Script (Easy)**

```powershell
cd "c:\Users\Sagor Ahmed\Desktop\n8n\socialEmbed\scraper-service"
.\configure-env.ps1
```

**Option B: Manual Configuration**

1. Copy `.env.example` to `.env`
2. Edit `.env` file with Notepad
3. Replace these values:
   ```env
   OPENAI_API_KEY=sk-your-actual-api-key-here
   API_SECRET_KEY=your-secret-key-here
   PORT=3000
   ```

### 1.5 Start the Service

```powershell
cd "c:\Users\Sagor Ahmed\Desktop\n8n\socialEmbed\scraper-service"
npm start
```

You should see:
```
🚀 Review Scraper Service running on port 3000
📊 Environment: production
🔒 API authentication: Enabled
```

✅ **Success!** The scraper service is now running.

**Important:** Keep this PowerShell window open while using the scraper.

---

## Step 2: Test the Scraper Service

Open a **NEW PowerShell window** and run:

```powershell
cd "c:\Users\Sagor Ahmed\Desktop\n8n\socialEmbed\scraper-service"
node test.js
```

You should see all tests pass:
- ✅ Service is running
- ✅ Authentication is working
- ✅ Environment configured

---

## Step 3: Install WordPress Plugin

Follow **Option A, Steps 1-2** above to:
1. Install the WordPress plugin
2. Activate it

---

## Step 4: Configure WordPress to Use the Scraper

### 4.1 Add Scraper Settings

1. In WordPress admin, go to **Reviews → Import Settings**

2. Enter the scraper service details:
   - **Scraper Service URL**: `http://localhost:3000`
   - **API Secret Key**: `social-review-scraper-secret-2024-xyz789`
     *(or your custom key from .env file)*

3. Click **Save Settings**

### 4.2 Add Your Business URLs

In the same settings page, add your listing URLs:

**For Google Reviews:**
1. Go to your Google Business listing
2. Copy the full URL (e.g., `https://www.google.com/maps/place/...`)
3. Paste in **Google Place URL** field

**For TripAdvisor Reviews:**
1. Go to your TripAdvisor listing
2. Copy the full URL (e.g., `https://www.tripadvisor.com/Hotel_Review-...`)
3. Paste in **TripAdvisor URL** field

**For Airbnb Reviews:**
1. Go to your Airbnb listing
2. Copy the full URL (e.g., `https://www.airbnb.com/rooms/12345678`)
3. Paste in **Airbnb URL** field

### 4.3 Test Connection

Click **Test Connection** button for each platform to verify the setup.

---

## Step 5: Import Reviews

### Manual Import (On-Demand)

1. Go to **Reviews → Import Settings**
2. Set **Max Reviews**: `50` (or your preferred number)
3. Click **Import Reviews Now**
4. Wait for completion (10-60 seconds)
5. Check **Recent Import Activity** for results

### Automatic Import (Scheduled)

1. Go to **Reviews → Import Settings**
2. Enable **Auto Import**: ☑️
3. Set **Import Frequency**: Daily (recommended)
4. Set **Import Limit**: `50` reviews
5. Click **Save Settings**

✅ Reviews will now import automatically every day!

---

## Step 6: Display Reviews

Follow **Option A, Steps 3-4** to display reviews on your website.

---

# 💰 Cost Breakdown

## WordPress Plugin Only
- **Cost**: FREE
- **Time**: Manual entry (~2 min per review)

## AI Scraper Service
- **OpenAI API**: ~$0.0001 per review
- **Monthly cost for 1,000 reviews**: ~$0.10
- **Monthly cost for 10,000 reviews**: ~$1-2
- **100x cheaper than commercial APIs!**

---

# 🎨 Customization Examples

## Basic Display
```
[social_reviews]
```

## Show Only Google Reviews
```
[social_reviews platform="google"]
```

## Show 5 Random Reviews with Autoplay
```
[social_reviews count="5" random="true" autoplay="true"]
```

## Show 3 Slides at Once (Desktop)
```
[social_reviews slides_per_view="3" space_between="20"]
```

## TripAdvisor Only, 10 Reviews
```
[social_reviews platform="tripadvisor" count="10"]
```

## All Platforms, Random, Autoplay
```
[social_reviews platform="all" random="true" autoplay="true" autoplay_delay="5000"]
```

---

# 🆘 Troubleshooting

## WordPress Plugin Issues

### Reviews Not Showing?
1. Check if reviews are **Published** (not Draft)
2. Verify shortcode is correct: `[social_reviews]`
3. Clear cache if using a caching plugin
4. Check browser console for JavaScript errors

### Slider Not Working?
1. Check if theme has jQuery conflicts
2. Disable other slider plugins temporarily
3. Try adding to a fresh page

### Styling Issues?
1. Check theme CSS conflicts
2. Add custom CSS to override styles
3. Use browser inspector to debug

---

## AI Scraper Issues

### Service Won't Start?
```powershell
# Check if Node.js is installed
node --version

# Check if npm is working
npm --version

# Reinstall dependencies
cd "c:\Users\Sagor Ahmed\Desktop\n8n\socialEmbed\scraper-service"
npm install
```

### "OpenAI API Quota Exceeded"?
1. Go to [OpenAI Billing](https://platform.openai.com/account/billing)
2. Add payment method
3. Add credits (minimum $5 recommended)
4. Wait 5 minutes for activation

### "Authentication Failed"?
1. Check API Secret Key matches in:
   - `.env` file (scraper service)
   - WordPress settings
2. Restart the scraper service

### "Connection Refused"?
1. Make sure scraper service is running
2. Check the port (default: 3000)
3. Verify URL: `http://localhost:3000` (not https)

### Reviews Not Importing?
1. Click **Test Connection** in WordPress
2. Check scraper service console for errors
3. Verify URLs are correct (full URLs, not shortened)
4. Check **Recent Import Activity** for error messages

---

# 📚 Additional Resources

- **README.md** - Complete plugin documentation
- **INSTALL.md** - Quick installation guide
- **API-SETUP-GUIDE.md** - Detailed API setup
- **HOW-IT-WORKS.md** - Technical architecture
- **demo.html** - Live preview of the slider

---

# ✅ Quick Checklist

## WordPress Plugin Setup
- [ ] Plugin ZIP created
- [ ] Plugin uploaded to WordPress
- [ ] Plugin activated
- [ ] First review added
- [ ] Shortcode added to page
- [ ] Reviews displaying correctly

## AI Scraper Setup (Optional)
- [ ] Node.js installed
- [ ] Dependencies installed (`npm install`)
- [ ] `.env` file configured
- [ ] OpenAI API key added
- [ ] Service started (`npm start`)
- [ ] Tests passed (`node test.js`)
- [ ] WordPress configured with scraper URL
- [ ] Business URLs added
- [ ] Test import successful
- [ ] Auto-import enabled

---

# 🎉 You're All Set!

Your Social Review Slider is now ready to showcase your amazing reviews!

**Need Help?**
- Check the troubleshooting section above
- Review the documentation files
- Test with `demo.html` to see expected results

**Enjoy your beautiful review slider! ⭐**
