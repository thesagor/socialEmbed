# 🚀 AI-Powered Review Import - Complete Setup Guide

## Overview

This guide will help you set up the **hassle-free** AI-powered review scraper that eliminates the need for multiple API keys from Google, TripAdvisor, and Airbnb.

### ✨ Benefits

- ✅ **No API Keys Required** - Just paste your business URLs
- ✅ **Works with Airbnb** - Unlike official APIs
- ✅ **Super Cheap** - ~$0.0001 per review (100x cheaper!)
- ✅ **Easy Setup** - 15 minutes to get running
- ✅ **Automatic Updates** - Scheduled imports

---

## 📋 Prerequisites

1. **Node.js** (v16 or higher) - [Download here](https://nodejs.org/)
2. **OpenAI API Key** - [Get one here](https://platform.openai.com/api-keys)
3. **WordPress** with the Social Review Slider plugin installed

---

## 🛠️ Part 1: Setup the AI Scraper Service

### Step 1: Install Dependencies

Open your terminal and navigate to the scraper service directory:

```bash
cd scraper-service
npm install
```

This will install:
- **Puppeteer** - For web scraping
- **OpenAI** - For AI-powered data extraction
- **Express** - For the REST API server

### Step 2: Configure Environment Variables

1. Copy the example environment file:
```bash
cp .env.example .env
```

2. Edit the `.env` file with your settings:

```env
# OpenAI API Configuration
OPENAI_API_KEY=sk-your-actual-openai-api-key-here

# Server Configuration
PORT=3000
NODE_ENV=production

# Security - Generate a random secret key
API_SECRET_KEY=your-super-secret-key-123456

# Rate Limiting
RATE_LIMIT_WINDOW_MS=900000
RATE_LIMIT_MAX_REQUESTS=100

# Scraping Configuration
HEADLESS_MODE=true
TIMEOUT_MS=30000
```

**Important:**
- Replace `sk-your-actual-openai-api-key-here` with your real OpenAI API key
- Replace `your-super-secret-key-123456` with a strong random string
- Keep `HEADLESS_MODE=true` for production (set to `false` for debugging)

### Step 3: Test the Service

Start the service:

```bash
npm start
```

You should see:
```
🚀 Review Scraper Service running on port 3000
📊 Environment: production
🔒 API authentication: Enabled
```

Test the health endpoint:
```bash
curl http://localhost:3000/health
```

Expected response:
```json
{
  "status": "ok",
  "service": "Review Scraper Service",
  "version": "1.0.0"
}
```

---

## 🔧 Part 2: Configure WordPress Plugin

### Step 1: Access Plugin Settings

1. Log in to your WordPress admin panel
2. Go to **Reviews → Import Settings**

### Step 2: Configure AI Scraper Service

In the **AI Scraper Service** section:

1. **Scraper Service URL**: `http://localhost:3000`
   - If running on a different server, use that server's URL
   - For production, use your server's IP or domain

2. **API Secret Key**: Enter the same `API_SECRET_KEY` from your `.env` file

### Step 3: Add Platform URLs

Simply paste the URLs to your business listings:

**Google Business URL:**
```
https://www.google.com/maps/place/Your+Business/@latitude,longitude,17z/...
```
- Go to your Google Business listing
- Copy the full URL from your browser

**TripAdvisor URL:**
```
https://www.tripadvisor.com/Hotel_Review-g123456-d789012-Reviews-Your_Hotel_Name.html
```
- Go to your TripAdvisor listing
- Copy the full URL

**Airbnb URL:**
```
https://www.airbnb.com/rooms/12345678
```
- Go to your Airbnb listing
- Copy the full URL

### Step 4: Configure Import Settings

1. **Enable Auto Import**: ✅ Check this box
2. **Import Frequency**: Choose based on your needs
   - **Hourly**: For high-traffic businesses
   - **Twice Daily**: Recommended for most
   - **Daily**: For smaller businesses
   - **Weekly**: For low-volume listings

3. **Import Limit**: 50 (default)
   - Maximum reviews to import per platform per run

4. Click **Save Changes**

---

## 🎯 Part 3: Import Reviews

### Manual Import (First Time)

1. Scroll to the **Manual Import** section
2. Click **"Import Reviews Now"**
3. Wait 30-60 seconds for the import to complete
4. Check the **Recent Import Activity** log

You should see something like:
```
✓ Google: 25 reviews imported
✓ TripAdvisor: 30 reviews imported
✓ Airbnb: 15 reviews imported
```

### Automatic Imports

Once configured, the plugin will automatically:
- Fetch new reviews based on your schedule
- Avoid duplicates
- Log all import activity

---

## 💰 Cost Breakdown

### OpenAI API Costs

Using **GPT-4o-mini** (cheapest model):
- **Input**: $0.15 per 1M tokens
- **Output**: $0.60 per 1M tokens

**Estimated costs:**
- Per review: ~$0.0001
- 100 reviews: ~$0.01
- 1,000 reviews: ~$0.10
- 10,000 reviews: ~$1.00

### Comparison with Commercial APIs

| Service | Cost per Review | 1,000 Reviews |
|---------|----------------|---------------|
| **AI Scraper** | $0.0001 | $0.10 |
| Apify | $0.01 | $10.00 |
| Bright Data | $0.003 | $3.00 |
| ScraperAPI | $0.002 | $2.00 |

**Savings: 100x cheaper!** 🎉

---

## 🚀 Part 4: Production Deployment

### Option 1: Run on Same Server as WordPress

If WordPress is on a VPS/dedicated server:

1. Install Node.js on your server
2. Copy the `scraper-service` folder to your server
3. Run the service with PM2 (process manager):

```bash
npm install -g pm2
pm2 start server.js --name review-scraper
pm2 save
pm2 startup
```

4. Update WordPress settings to use `http://localhost:3000`

### Option 2: Run on Separate Server

1. Deploy to a cloud provider:
   - **DigitalOcean**: $5/month droplet
   - **AWS EC2**: Free tier for 12 months
   - **Railway**: $5/month
   - **Heroku**: Free tier available

2. Update WordPress settings with your server URL:
   ```
   https://your-scraper-service.com
   ```

### Option 3: Docker Deployment

Create `Dockerfile`:
```dockerfile
FROM node:18-alpine
WORKDIR /app
COPY package*.json ./
RUN npm install --production
COPY . .
EXPOSE 3000
CMD ["node", "server.js"]
```

Build and run:
```bash
docker build -t review-scraper .
docker run -d -p 3000:3000 --env-file .env review-scraper
```

---

## 🔒 Security Best Practices

1. **Use HTTPS** in production
2. **Firewall Rules**: Only allow WordPress server to access port 3000
3. **Rotate API Keys** periodically
4. **Monitor Logs** for suspicious activity
5. **Rate Limiting**: Already configured (100 requests per 15 minutes)

---

## 🐛 Troubleshooting

### Service Won't Start

**Error**: `Cannot find module 'puppeteer'`
```bash
cd scraper-service
npm install
```

**Error**: `EADDRINUSE: address already in use`
- Port 3000 is already in use
- Change `PORT` in `.env` to another port (e.g., 3001)

### WordPress Can't Connect

**Error**: "Connection refused"
- Check if service is running: `curl http://localhost:3000/health`
- Check firewall settings
- Verify the URL in WordPress settings

**Error**: "Unauthorized: Invalid API key"
- Make sure API keys match in `.env` and WordPress settings
- Check for extra spaces or quotes

### No Reviews Imported

**Error**: "No reviews found"
- Verify the URLs are correct
- Try visiting the URLs in your browser
- Check if the pages have reviews visible

**Error**: "Timeout"
- Increase `TIMEOUT_MS` in `.env` to 60000 (60 seconds)
- Check your internet connection
- Some websites may have anti-bot measures

### OpenAI Errors

**Error**: "Invalid API key"
- Verify your OpenAI API key is correct
- Check if you have credits in your OpenAI account

**Error**: "Rate limit exceeded"
- You're making too many requests
- Wait a few minutes and try again
- Upgrade your OpenAI plan if needed

---

## 📊 Monitoring

### Check Service Status

```bash
# If using PM2
pm2 status
pm2 logs review-scraper

# If running directly
# Check the terminal output
```

### Check WordPress Import Logs

1. Go to **Reviews → Import Settings**
2. Scroll to **Recent Import Activity**
3. Review the import history

---

## 🎉 You're All Set!

Your AI-powered review scraper is now running! The plugin will:

✅ Automatically fetch new reviews on schedule
✅ Avoid duplicate imports
✅ Display reviews beautifully on your site
✅ Cost you almost nothing to run

### Next Steps

1. Add the review slider to your website:
```
[social_reviews platform="all" slides_per_view="3" autoplay="true"]
```

2. Monitor the import logs weekly
3. Enjoy automatic review updates!

---

## 📞 Need Help?

If you encounter issues:

1. Check the service logs
2. Check WordPress error logs
3. Verify all URLs and API keys
4. Test each platform individually
5. Check the troubleshooting section above

---

**Happy reviewing! 🌟**
