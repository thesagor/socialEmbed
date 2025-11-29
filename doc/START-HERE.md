# 🎯 GETTING STARTED - Simple 3-Step Guide

## Choose Your Path:

---

## 🟢 PATH 1: Quick & Simple (5 Minutes)
**WordPress Plugin Only - Manual Reviews**

### Step 1: Install Plugin
1. Zip the `socialEmbed` folder → `social-review-slider.zip`
2. WordPress Admin → Plugins → Add New → Upload
3. Activate the plugin

### Step 2: Add Reviews
1. WordPress Admin → Reviews → Add New
2. Fill in: Name, Rating, Text, Platform
3. Publish (add 3-5 reviews minimum)

### Step 3: Display
1. Edit any page
2. Add shortcode: `[social_reviews]`
3. Done! 🎉

**Cost:** FREE  
**Time:** 5 minutes setup + 2 min per review

---

## 🔵 PATH 2: Automated (15 Minutes + OpenAI Setup)
**WordPress Plugin + AI Scraper - Automatic Reviews**

### Step 1: Setup AI Scraper
```powershell
cd "c:\Users\Sagor Ahmed\Desktop\n8n\socialEmbed\scraper-service"
npm install
.\configure-env.ps1
npm start
```

### Step 2: Install WordPress Plugin
1. Zip folder → Upload to WordPress → Activate
2. WordPress → Reviews → Import Settings
3. Add Scraper URL: `http://localhost:3000`
4. Add API Key: `social-review-scraper-secret-2024-xyz789`

### Step 3: Import Reviews
1. Add your Google/TripAdvisor/Airbnb URLs
2. Click "Import Reviews Now"
3. Add shortcode to page: `[social_reviews]`
4. Done! 🎉

**Cost:** ~$1-2/month (OpenAI)  
**Time:** 15 min setup, then automatic

---

## 📊 Quick Comparison

| Feature | Path 1 (Manual) | Path 2 (Automatic) |
|---------|----------------|-------------------|
| Setup Time | 5 minutes | 15 minutes |
| Cost | FREE | ~$1-2/month |
| Review Entry | Manual | Automatic |
| Updates | Manual | Scheduled |
| Best For | Small sites | Hotels/Businesses |

---

## 🚀 What You Get

✅ Beautiful slider with platform badges  
✅ Star ratings (⭐⭐⭐⭐⭐)  
✅ Reviewer photos  
✅ Responsive design  
✅ Autoplay option  
✅ Multiple display options  

---

## 📝 Shortcode Examples

```
[social_reviews]                                    // All reviews
[social_reviews platform="google"]                  // Google only
[social_reviews count="5" autoplay="true"]          // 5 reviews, autoplay
[social_reviews slides_per_view="3"]                // 3 at once
```

---

## 🆘 Need Help?

- **Full Guide:** `QUICK-START-GUIDE.md`
- **Installation:** `INSTALL.md`
- **Demo:** Open `demo.html` in browser
- **API Setup:** `API-SETUP-GUIDE.md`

---

## ⚡ Current Status

✅ AI Scraper Service: **RUNNING** on port 3000  
✅ Environment: **CONFIGURED**  
⚠️ OpenAI Credits: **Need to add billing**  
⏳ WordPress Plugin: **Ready to install**  

---

## 🎯 Recommended Next Steps

### For Quick Start (Path 1):
1. ✅ Create plugin ZIP file
2. ✅ Upload to WordPress
3. ✅ Add 3-5 reviews manually
4. ✅ Add shortcode to page

### For Automated (Path 2):
1. ✅ Add credits to OpenAI account
2. ✅ Install WordPress plugin
3. ✅ Configure scraper settings
4. ✅ Import reviews automatically

---

**Choose your path and get started! 🚀**
