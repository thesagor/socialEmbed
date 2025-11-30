# Social Review Slider - Production Deployment Package

## 📦 Package Contents

This is a production-ready deployment package for the Social Review Slider WordPress plugin and AI Scraper Service.

### What's Included:

1. **WordPress Plugin** (`social-review-slider-plugin.zip`)
   - Ready to upload to WordPress
   - No test files or development dependencies
   - Optimized for production

2. **Scraper Service** (`scraper-service-production.zip`)
   - Node.js service for scraping reviews
   - Production-ready configuration
   - No development/test files

3. **Documentation** (`DEPLOYMENT-GUIDE.md`)
   - Complete deployment instructions
   - Server setup guide
   - Security configuration
   - Troubleshooting tips

---

## 🚀 Quick Start

### 1. Deploy WordPress Plugin

**Option A: Via WordPress Admin**
1. Go to: Plugins → Add New → Upload Plugin
2. Upload `social-review-slider-plugin.zip`
3. Click "Install Now" → "Activate"

**Option B: Via FTP/SFTP**
1. Extract `social-review-slider-plugin.zip`
2. Upload to: `/wp-content/plugins/social-review-slider/`
3. Activate in WordPress admin

### 2. Deploy Scraper Service

**Requirements:**
- Server with Node.js 16+ installed
- VPS or cloud hosting (DigitalOcean, AWS, etc.)
- Or same server as WordPress

**Steps:**
1. Extract `scraper-service-production.zip` on your server
2. Run: `npm install --production`
3. Configure `.env` file (copy from `.env.example`)
4. Run: `npm start` or use PM2 for production

### 3. Configure Connection

1. In WordPress: **Reviews → Import Settings**
2. Enter scraper service URL and API key
3. Test connection
4. Start importing reviews!

---

## 📖 Full Documentation

See `DEPLOYMENT-GUIDE.md` for complete instructions including:
- Detailed deployment steps
- Security configuration
- SSL/HTTPS setup
- Process management with PM2
- Nginx reverse proxy setup
- Monitoring and maintenance
- Troubleshooting

---

## 🔒 Security Checklist

Before going live:

- [ ] Generate secure API_SECRET_KEY (use: `openssl rand -hex 32`)
- [ ] Configure firewall rules
- [ ] Set up HTTPS/SSL certificate
- [ ] Use strong WordPress admin password
- [ ] Keep AI API keys secure
- [ ] Enable rate limiting
- [ ] Regular backups

---

## 💰 Cost Estimate

### Hosting:
- **VPS (DigitalOcean)**: $6-12/month
- **Cloud Platform**: $5-10/month
- **Shared Hosting + VPS**: $10-20/month

### AI API:
- **OpenAI GPT-4o-mini**: ~$0.0001 per review
- **Google Gemini**: Free tier available
- **Typical usage**: $0.01-0.10 per 100 reviews

---

## 🆘 Support

- **Documentation**: See `DEPLOYMENT-GUIDE.md`
- **Issues**: Check troubleshooting section
- **Updates**: Keep both plugin and service updated

---

## 📝 Version

- **Plugin Version**: 1.0.0
- **Service Version**: 1.0.0
- **Package Date**: 2025-11-30

---

## ⚖️ License

MIT License - See LICENSE file for details

---

## 🎉 Ready to Deploy!

Follow the deployment guide and you'll have your review slider live in minutes!
