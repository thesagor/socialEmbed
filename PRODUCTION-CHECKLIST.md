# 🚀 Production Deployment Checklist

## Pre-Deployment Preparation

### ✅ Files Ready for Deployment

#### WordPress Plugin Package
- **File**: `deployment/social-review-slider-plugin.zip`
- **Contains**:
  - ✅ `social-review-slider.php` (main plugin file)
  - ✅ `includes/` (5 PHP class files)
  - ✅ `assets/css/` (2 CSS files)
  - ✅ `assets/js/` (2 JavaScript files)
  - ✅ `README.md`
- **Size**: ~50-100 KB
- **Ready**: Upload to WordPress

#### Scraper Service Package
- **File**: `deployment/scraper-service-production.zip`
- **Contains**:
  - ✅ `server.js`
  - ✅ `package.json`
  - ✅ `package-lock.json`
  - ✅ `.env.example`
  - ✅ `.gitignore`
  - ✅ `scrapers/` (3 scraper files)
  - ✅ `utils/` (AI extractor)
- **Size**: ~10-20 KB (without node_modules)
- **Ready**: Deploy to server

---

## Deployment Steps

### Part 1: WordPress Plugin (5 minutes)

- [ ] **1.1** Log in to WordPress admin
- [ ] **1.2** Go to: Plugins → Add New → Upload Plugin
- [ ] **1.3** Upload `social-review-slider-plugin.zip`
- [ ] **1.4** Click "Install Now"
- [ ] **1.5** Click "Activate Plugin"
- [ ] **1.6** Verify plugin appears in Plugins list

### Part 2: Scraper Service (15-30 minutes)

#### Server Setup
- [ ] **2.1** Choose deployment platform:
  - [ ] VPS (DigitalOcean, Linode, AWS EC2)
  - [ ] Cloud Platform (Heroku, Railway, Render)
  - [ ] Same server as WordPress
  
- [ ] **2.2** Ensure Node.js 16+ is installed
  ```bash
  node --version  # Should be v16.0.0 or higher
  ```

#### File Upload
- [ ] **2.3** Extract `scraper-service-production.zip`
- [ ] **2.4** Upload files to server (via SCP, FTP, or Git)
- [ ] **2.5** Navigate to service directory
  ```bash
  cd /path/to/scraper-service
  ```

#### Configuration
- [ ] **2.6** Copy environment file
  ```bash
  cp .env.example .env
  ```

- [ ] **2.7** Generate secure API key
  ```bash
  openssl rand -hex 32
  ```

- [ ] **2.8** Edit `.env` file and set:
  - [ ] `API_SECRET_KEY` (from step 2.7)
  - [ ] `OPENAI_API_KEY` or `GOOGLE_API_KEY`
  - [ ] `PORT` (default: 3000)
  - [ ] `NODE_ENV=production`

#### Installation
- [ ] **2.9** Install dependencies
  ```bash
  npm install --production
  ```

#### Testing
- [ ] **2.10** Test service manually
  ```bash
  npm start
  ```

- [ ] **2.11** Verify health endpoint
  ```bash
  curl http://localhost:3000/health
  # Should return: {"status":"ok","service":"Review Scraper Service","version":"1.0.0"}
  ```

- [ ] **2.12** Stop test (Ctrl+C)

#### Production Setup
- [ ] **2.13** Install PM2 process manager
  ```bash
  sudo npm install -g pm2
  ```

- [ ] **2.14** Start service with PM2
  ```bash
  pm2 start server.js --name "review-scraper"
  ```

- [ ] **2.15** Save PM2 configuration
  ```bash
  pm2 save
  ```

- [ ] **2.16** Set PM2 to start on boot
  ```bash
  pm2 startup
  # Follow the instructions shown
  ```

- [ ] **2.17** Verify service is running
  ```bash
  pm2 status
  ```

### Part 3: Security Configuration (10 minutes)

#### Firewall
- [ ] **3.1** Configure firewall to allow port 3000
  ```bash
  sudo ufw allow 3000/tcp
  sudo ufw reload
  ```

#### SSL/HTTPS (Optional but Recommended)
- [ ] **3.2** Set up subdomain (e.g., scraper.yourdomain.com)
- [ ] **3.3** Configure Nginx reverse proxy
- [ ] **3.4** Install SSL certificate with Let's Encrypt
  ```bash
  sudo certbot --nginx -d scraper.yourdomain.com
  ```

### Part 4: WordPress Configuration (5 minutes)

- [ ] **4.1** Go to: WordPress Admin → Reviews → Import Settings

- [ ] **4.2** Enter Scraper Service URL:
  - [ ] Same server: `http://localhost:3000`
  - [ ] Different server (HTTP): `http://your-server-ip:3000`
  - [ ] Different server (HTTPS): `https://scraper.yourdomain.com`

- [ ] **4.3** Enter API Secret Key (same as in `.env`)

- [ ] **4.4** Click "Save Changes"

- [ ] **4.5** Click "Test Service Connection"
  - [ ] Should show: ✅ "Connection Successful! Service is running v1.0.0"

### Part 5: Test Import (5 minutes)

- [ ] **5.1** Add platform URL (choose one to test):
  - [ ] Google Business URL
  - [ ] TripAdvisor URL
  - [ ] Airbnb URL

- [ ] **5.2** Click "Save Changes"

- [ ] **5.3** Click "Import Reviews Now"

- [ ] **5.4** Wait 20-30 seconds

- [ ] **5.5** Verify success message appears

- [ ] **5.6** Go to: Reviews → All Reviews

- [ ] **5.7** Verify reviews were imported

### Part 6: Display Reviews (2 minutes)

- [ ] **6.1** Create/edit a page or post

- [ ] **6.2** Add shortcode:
  ```
  [social_reviews platform="all"]
  ```

- [ ] **6.3** Publish and view page

- [ ] **6.4** Verify reviews display correctly

---

## Post-Deployment Verification

### Service Health
- [ ] Service is running: `pm2 status`
- [ ] No errors in logs: `pm2 logs review-scraper`
- [ ] Health endpoint responds: `curl http://localhost:3000/health`

### WordPress Integration
- [ ] Plugin is active
- [ ] Connection test passes
- [ ] Reviews import successfully
- [ ] Reviews display on frontend
- [ ] Slider works correctly

### Security
- [ ] Strong API key configured
- [ ] Firewall rules in place
- [ ] HTTPS enabled (if applicable)
- [ ] AI API keys secured
- [ ] WordPress admin password strong

---

## Monitoring Setup

### PM2 Monitoring
- [ ] Set up PM2 monitoring: `pm2 monit`
- [ ] Configure PM2 logs rotation
- [ ] Set up email alerts (optional)

### WordPress Monitoring
- [ ] Enable WordPress debug log (temporarily)
- [ ] Check import logs regularly
- [ ] Monitor review count growth

---

## Maintenance Schedule

### Daily
- [ ] Check PM2 status
- [ ] Review error logs

### Weekly
- [ ] Check import success rate
- [ ] Review AI API usage/costs
- [ ] Verify reviews are up to date

### Monthly
- [ ] Update Node.js dependencies
- [ ] Update WordPress plugin (if updates available)
- [ ] Review security settings
- [ ] Backup configuration files

---

## Troubleshooting Quick Reference

### Service Won't Start
```bash
# Check logs
pm2 logs review-scraper

# Check port availability
sudo netstat -tulpn | grep 3000

# Verify .env configuration
cat .env
```

### WordPress Can't Connect
1. Verify service is running: `pm2 status`
2. Test health endpoint: `curl http://localhost:3000/health`
3. Check firewall: `sudo ufw status`
4. Verify API key matches in both places

### Import Fails
1. Check service logs: `pm2 logs review-scraper`
2. Verify AI API key is valid
3. Check platform URL is correct
4. Review rate limits

---

## Success Metrics

### Technical
- ✅ Service uptime: >99%
- ✅ Import success rate: >95%
- ✅ Average import time: <30 seconds
- ✅ Zero security incidents

### Business
- ✅ Reviews importing daily
- ✅ Reviews displaying on site
- ✅ AI API costs within budget
- ✅ Positive user feedback

---

## Emergency Contacts

### Service Issues
- Server provider support
- Node.js community
- PM2 documentation

### WordPress Issues
- WordPress support forums
- Plugin documentation
- Hosting provider support

### AI API Issues
- OpenAI support (if using OpenAI)
- Google support (if using Gemini)

---

## Rollback Plan

If something goes wrong:

### WordPress Plugin
1. Deactivate plugin in WordPress admin
2. Delete plugin files
3. Restore from backup (if needed)

### Scraper Service
1. Stop service: `pm2 stop review-scraper`
2. Restore previous version from backup
3. Restart: `pm2 restart review-scraper`

---

## 🎉 Deployment Complete!

Once all checkboxes are marked:

- ✅ WordPress plugin is live
- ✅ Scraper service is running
- ✅ Reviews are importing
- ✅ Everything is secure
- ✅ Monitoring is in place

**Congratulations! Your Social Review Slider is now in production!** 🚀

---

## Next Steps

1. **Add more platform URLs** to import from multiple sources
2. **Configure auto-import schedule** for automatic updates
3. **Customize review display** with shortcode parameters
4. **Monitor performance** and optimize as needed
5. **Collect user feedback** and iterate

---

## Support & Documentation

- **Full Guide**: `DEPLOYMENT-GUIDE.md`
- **Quick Start**: `DEPLOYMENT-README.md`
- **Plugin README**: `README.md`
- **Service Logs**: `pm2 logs review-scraper`

---

**Version**: 1.0.0  
**Last Updated**: 2025-11-30  
**Status**: Production Ready ✅
