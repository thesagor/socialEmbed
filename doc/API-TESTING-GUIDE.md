# 🔑 API Key Testing Guide

This guide will help you check and verify your API keys for the Social Review Slider project.

## 📋 Overview

This project supports two AI providers:
1. **Google Gemini** (Primary, FREE) ✅ **Recommended**
2. **OpenAI** (Optional fallback, Paid)

---

## ✅ Your Current Status

Based on the diagnostic results:

- **Gemini API**: ✅ **WORKING** (39 characters, valid format)
- **OpenAI API**: ❌ **NOT CONFIGURED**

**Good news!** You don't need OpenAI since Gemini is working perfectly and it's 100% FREE! 🎉

---

## 🧪 How to Test API Keys

### Test Gemini API (Primary)

```bash
cd scraper-service
node diagnose-api.js
```

**What to look for:**
- ✅ API Key found in .env
- ✅ Key format looks correct (starts with AIza)
- ✅ API Key is VALID!
- 📋 List of available models

### Test OpenAI API (Optional)

```bash
cd scraper-service
node test-openai.js
```

**What to look for:**
- ✅ API Key found in .env
- ✅ Key format looks correct (starts with sk-)
- ✅ API Key is VALID!
- 📋 List of available models

---

## 🔧 Setting Up OpenAI API (Optional)

**Note:** OpenAI is OPTIONAL and only used as a fallback if Gemini fails. Since your Gemini API is working, you don't need this!

But if you want to add it anyway:

### Step 1: Get OpenAI API Key

1. Go to [OpenAI Platform](https://platform.openai.com/api-keys)
2. Sign in or create an account
3. Click **"Create new secret key"**
4. Copy the key (starts with `sk-...`)
5. **Important:** Save it somewhere safe - you can only see it once!

### Step 2: Add to .env File

Open `scraper-service/.env` and add:

```env
# OpenAI API (Optional fallback)
OPENAI_API_KEY=sk-your-actual-key-here
```

### Step 3: Test the Key

```bash
cd scraper-service
node test-openai.js
```

You should see:
```
✅ API Key is VALID!
🎉 SUCCESS! Your OpenAI API key is working!
```

---

## 📊 API Comparison

| Feature | Gemini (Current) | OpenAI (Optional) |
|---------|------------------|-------------------|
| **Cost** | **FREE** ✅ | Paid ($0.0001-$0.001/review) |
| **Status** | **Working** ✅ | Not configured |
| **Speed** | Fast | Fast |
| **Accuracy** | Excellent | Excellent |
| **Rate Limit** | 15 req/min (free tier) | Varies by plan |
| **Recommended** | **YES** ✅ | Only as fallback |

---

## 🎯 Recommended Configuration

### For Most Users (FREE)

**Use Gemini only** (your current setup):

```env
# .env file
GEMINI_API_KEY=AIza...your-key
API_SECRET_KEY=your-secret-key
PORT=3000
NODE_ENV=production
```

✅ **This is what you have now - perfect!**

### For High-Availability (Paid Fallback)

If you want 99.9% uptime with automatic fallback:

```env
# .env file
GEMINI_API_KEY=AIza...your-key
OPENAI_API_KEY=sk-...your-key
API_SECRET_KEY=your-secret-key
PORT=3000
NODE_ENV=production
```

The system will:
1. Try Gemini first (FREE)
2. If Gemini fails, automatically use OpenAI (Paid)

---

## 🔍 Available Models

### Gemini Models (Your current setup)

Based on your diagnostic:
- ✅ `gemini-2.5-flash` - **Recommended** (Fastest, newest)
- ✅ `gemini-2.5-pro` - Most capable
- ✅ `gemini-2.0-flash` - Fast and reliable
- ✅ `gemini-2.0-flash-lite` - Ultra-fast, lightweight

**Currently using:** The scraper service automatically selects the best model.

### OpenAI Models (If you add it)

Recommended models:
- `gpt-4o-mini` - Fastest, cheapest (~$0.0001/review)
- `gpt-4o` - Most capable (~$0.001/review)
- `gpt-3.5-turbo` - Good balance (~$0.0005/review)

---

## 🐛 Troubleshooting

### Gemini API Issues

**Problem:** "API Key not found"
```bash
# Solution: Check .env file exists
ls -la scraper-service/.env

# If missing, copy from example
cp scraper-service/.env.example scraper-service/.env
# Then edit and add your key
```

**Problem:** "API Key invalid"
```bash
# Solution: Get a new key
# 1. Visit: https://aistudio.google.com/app/apikey
# 2. Create new API key
# 3. Update .env file
```

**Problem:** "Rate limit exceeded"
```bash
# Solution: Wait 1 minute
# Free tier: 15 requests per minute
# Just wait a bit and try again
```

### OpenAI API Issues

**Problem:** "Unauthorized (401)"
```bash
# Solution: Check your key
# 1. Visit: https://platform.openai.com/api-keys
# 2. Verify key is active
# 3. Create new key if needed
# 4. Update .env file
```

**Problem:** "Rate limit (429)"
```bash
# Solution: Wait or upgrade
# 1. Check usage: https://platform.openai.com/usage
# 2. Wait a few minutes
# 3. Or upgrade your plan
```

---

## 🧪 All Available Test Scripts

### 1. Test Gemini API
```bash
cd scraper-service
node diagnose-api.js
```
Shows detailed Gemini API diagnostics.

### 2. Test OpenAI API
```bash
cd scraper-service
node test-openai.js
```
Shows detailed OpenAI API diagnostics.

### 3. Test Gemini Integration
```bash
cd scraper-service
node test-gemini.js
```
Tests actual text generation with Gemini.

### 4. Test Full Scraping
```bash
cd scraper-service
node test-scrape.js
```
Tests complete scraping workflow with real websites.

### 5. Debug Environment
```bash
cd scraper-service
node debug-env.js
```
Shows all environment variables (safely).

### 6. List Available Models
```bash
cd scraper-service
node list-models.js
```
Lists all available AI models.

---

## 💡 Quick Reference Commands

```bash
# Check if service is running
curl http://localhost:3000/health

# Start the service
cd scraper-service
npm start

# Start with auto-reload (development)
npm run dev

# Test everything at once
node diagnose-api.js && node test-gemini.js

# View logs (if using PM2)
pm2 logs review-scraper
```

---

## 📞 Getting Help

If you encounter issues:

1. **Run diagnostics:**
   ```bash
   cd scraper-service
   node diagnose-api.js
   ```

2. **Check service health:**
   ```bash
   curl http://localhost:3000/health
   ```

3. **View detailed logs:**
   ```bash
   # If using PM2
   pm2 logs review-scraper
   
   # Or check console output
   npm start
   ```

4. **Common solutions:**
   - Restart the service: `pm2 restart review-scraper`
   - Check .env file has correct keys
   - Verify internet connection
   - Check API status pages:
     - Gemini: https://status.cloud.google.com/
     - OpenAI: https://status.openai.com/

---

## ✅ Your Next Steps

Since your Gemini API is working:

1. ✅ **You're all set!** No need to add OpenAI
2. 🚀 **Start the service:**
   ```bash
   cd scraper-service
   npm start
   ```
3. 🔗 **Connect WordPress:**
   - Go to WordPress → Reviews → Import Settings
   - Add service URL: `http://localhost:3000`
   - Add your API secret key
   - Test the connection
4. 📥 **Import reviews:**
   - Click "Import Reviews Now"
   - Watch the magic happen! ✨

---

## 💰 Cost Summary

Your current setup:
- **Gemini API**: FREE ✅
- **Total monthly cost**: $0.00 🎉

If you add OpenAI (optional):
- **Gemini API**: FREE (primary)
- **OpenAI API**: ~$1-2/month for 10,000 reviews (fallback only)
- **Total monthly cost**: ~$1-2 (only if Gemini fails)

**Recommendation:** Stick with Gemini only! It's working great and it's FREE! 🚀

---

**Last Updated:** 2025-11-30  
**Your Status:** ✅ Gemini API Working - Ready to Go!
