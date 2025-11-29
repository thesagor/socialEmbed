# 🚀 Quick Reference Card

## 📦 What You Built

**AI-Powered Review Scraper** - Hassle-free imports from Google, TripAdvisor & Airbnb

---

## ⚡ Quick Start (3 Commands)

```bash
cd scraper-service
npm install
npm start
```

Then configure WordPress at: **Reviews → Import Settings**

---

## 🔑 Required Credentials

1. **OpenAI API Key** → https://platform.openai.com/api-keys
2. **API Secret Key** → Any random string (e.g., `my-secret-123`)

---

## 📝 WordPress Settings

| Setting | Value |
|---------|-------|
| Service URL | `http://localhost:3000` |
| API Secret Key | (from your .env file) |
| Google URL | Your Google Maps listing URL |
| TripAdvisor URL | Your TripAdvisor hotel URL |
| Airbnb URL | Your Airbnb listing URL |

---

## 💰 Costs

- **Per Review**: $0.0001
- **1,000 Reviews**: ~$0.10
- **10,000 Reviews**: ~$1.00

**100x cheaper than commercial APIs!**

---

## 🎯 Common Commands

```bash
# Start service
npm start

# Start with auto-reload (development)
npm run dev

# Test service
npm test

# Check if running
curl http://localhost:3000/health

# View logs (if using PM2)
pm2 logs review-scraper
```

---

## 📊 Import Schedule Options

- **Hourly** - High-traffic businesses
- **Twice Daily** - Recommended
- **Daily** - Most businesses
- **Weekly** - Low-volume

---

## 🔧 File Locations

```
.env                          # Configuration
scraper-service/server.js     # Main service
includes/class-review-importer.php  # WordPress importer
AI-SCRAPER-SETUP.md          # Full setup guide
SUMMARY.md                    # Complete overview
```

---

## 🐛 Quick Troubleshooting

### Service won't start
```bash
node --version  # Check Node.js installed
npm install     # Reinstall dependencies
```

### WordPress can't connect
```bash
curl http://localhost:3000/health  # Test service
# Check firewall, verify API keys match
```

### No reviews imported
- Verify URLs are correct
- Check pages have reviews
- Increase timeout in .env

---

## 📱 Display Reviews

```php
[social_reviews platform="all" slides_per_view="3" autoplay="true"]
```

---

## 🎓 Documentation

- **Setup**: AI-SCRAPER-SETUP.md
- **Overview**: SUMMARY.md  
- **Service**: scraper-service/README.md

---

## ✅ Success Checklist

- [ ] Node.js installed
- [ ] OpenAI API key obtained
- [ ] Dependencies installed (`npm install`)
- [ ] .env file configured
- [ ] Service started (`npm start`)
- [ ] WordPress settings configured
- [ ] Business URLs added
- [ ] Test import successful
- [ ] Reviews displaying on site

---

## 🎉 You're Done!

Your AI-powered review scraper is ready!

**Next**: Enable automatic imports and enjoy hassle-free review management! 🌟
