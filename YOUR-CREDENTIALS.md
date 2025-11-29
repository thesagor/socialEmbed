# 🎯 YOUR SETUP CREDENTIALS

## ✅ Configuration Complete!

Your AI Scraper Service has been configured with your OpenAI API key.

---

## 🔑 Important Credentials

### For WordPress Plugin Settings:

**Scraper Service URL:**
```
http://localhost:3000
```

**API Secret Key:**
```
social-review-scraper-secret-2024-xyz789
```

⚠️ **SAVE THIS API SECRET KEY!** You'll need it in WordPress.

---

## 📝 WordPress Setup Steps

1. **Go to WordPress Admin**
   - Navigate to: **Reviews → Import Settings**

2. **Configure AI Scraper Service**
   - **Scraper Service URL**: `http://localhost:3000`
   - **API Secret Key**: `social-review-scraper-secret-2024-xyz789`

3. **Add Your Business URLs**
   
   **Google Business URL:**
   - Go to your Google Business listing
   - Copy the full URL from your browser
   - Example: `https://www.google.com/maps/place/Your+Business/@...`
   
   **TripAdvisor URL:**
   - Go to your TripAdvisor listing
   - Copy the full URL
   - Example: `https://www.tripadvisor.com/Hotel_Review-g123456-d789012-Reviews-...`
   
   **Airbnb URL:**
   - Go to your Airbnb listing
   - Copy the full URL
   - Example: `https://www.airbnb.com/rooms/12345678`

4. **Configure Import Settings**
   - ✅ Enable Auto Import
   - Choose frequency: **Twice Daily** (recommended)
   - Import limit: **50** (default)

5. **Save Changes**

6. **Test Import**
   - Scroll to "Manual Import" section
   - Click **"Import Reviews Now"**
   - Wait 30-60 seconds
   - Check "Recent Import Activity" log

---

## 🚀 Starting the Service

### Option 1: Start Now (Terminal)
```bash
cd "c:\Users\Sagor Ahmed\Desktop\n8n\socialEmbed\scraper-service"
npm start
```

### Option 2: Start with Auto-Reload (Development)
```bash
npm run dev
```

### Option 3: Test the Service
```bash
npm test
```

---

## ✅ Verify Service is Running

Open your browser or use curl:
```
http://localhost:3000/health
```

You should see:
```json
{
  "status": "ok",
  "service": "Review Scraper Service",
  "version": "1.0.0"
}
```

---

## 💰 Cost Estimate

With your OpenAI API key:
- **Per review**: ~$0.0001
- **100 reviews**: ~$0.01
- **1,000 reviews**: ~$0.10
- **10,000 reviews**: ~$1.00

**Much cheaper than any commercial API!**

---

## 🎯 Quick Test

Once the service is running, test it with WordPress:

1. Add one business URL (e.g., Google)
2. Click "Import Reviews Now"
3. Wait 20-30 seconds
4. Check if reviews appear in WordPress

If successful, add the other platform URLs!

---

## 🐛 Troubleshooting

### Service won't start
```bash
# Check if Node.js is installed
node --version

# Reinstall dependencies
npm install
```

### WordPress can't connect
- Make sure service is running: `http://localhost:3000/health`
- Verify API secret key matches in both places
- Check Windows Firewall isn't blocking port 3000

### No reviews imported
- Verify URLs are correct and accessible
- Check if pages have reviews visible
- Look at service console for error messages

---

## 📞 Next Steps

1. ✅ Wait for `npm install` to complete
2. ✅ Start the service with `npm start`
3. ✅ Configure WordPress with the credentials above
4. ✅ Add your business URLs
5. ✅ Click "Import Reviews Now"
6. ✅ Enjoy automatic review imports!

---

## 🎉 You're Almost There!

Once `npm install` finishes:
1. Run `npm start`
2. Configure WordPress
3. Import your first reviews!

**The service will cost you ~$1-5/month for 10,000 reviews!**

---

**Need help?** Check `AI-SCRAPER-SETUP.md` for detailed instructions.
