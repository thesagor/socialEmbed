# 🚀 Production Deployment Guide

## Overview

This guide will help you deploy the Social Review Slider plugin and AI Scraper Service to your live server.

---

## 📦 What You're Deploying

### 1. WordPress Plugin
- **Location**: Root directory (all files except `scraper-service/`)
- **Purpose**: WordPress plugin for displaying and managing reviews
- **Deployment**: Upload to WordPress plugins directory

### 2. AI Scraper Service
- **Location**: `scraper-service/` directory
- **Purpose**: Node.js service that scrapes reviews from Airbnb, Google, TripAdvisor
- **Deployment**: Deploy to a server with Node.js (VPS, cloud server, etc.)

---

## 🎯 Deployment Options

### Option A: Plugin + Service on Same Server (Recommended for VPS)
- WordPress and Node.js service on the same server
- Easier to manage
- Lower latency

### Option B: Plugin on Shared Hosting + Service on Separate Server
- WordPress on shared hosting
- Node.js service on VPS/cloud (DigitalOcean, AWS, etc.)
- More flexible but requires HTTPS for security

---

## 📋 Pre-Deployment Checklist

### WordPress Plugin Files to Deploy:
- ✅ `social-review-slider.php` (main plugin file)
- ✅ `includes/` (all PHP class files)
- ✅ `assets/` (CSS, JS, images)
- ✅ `README.md`
- ❌ `demo.html` (NOT needed - remove)
- ❌ `wp-connection-test.php` (NOT needed - remove)
- ❌ `setup.bat` (NOT needed - remove)
- ❌ `doc/` (optional - documentation only)
- ❌ `scraper-service/` (deploy separately)

### Scraper Service Files to Deploy:
- ✅ `server.js`
- ✅ `package.json`
- ✅ `package-lock.json`
- ✅ `.env.example` (rename to `.env` and configure)
- ✅ `scrapers/` (all scraper files)
- ✅ `utils/` (AI extractor)
- ❌ `test-*.js` (NOT needed - remove)
- ❌ `debug-*.js` (NOT needed - remove)
- ❌ `diagnose-*.js` (NOT needed - remove)
- ❌ `setup-*.bat` (NOT needed - remove)
- ❌ `setup-*.ps1` (NOT needed - remove)
- ❌ `configure-*.ps1` (NOT needed - remove)
- ❌ `*.md` files (optional - documentation only)
- ❌ `node_modules/` (will be installed on server)

---

## 🔧 Step-by-Step Deployment

### Part 1: Deploy WordPress Plugin

#### Step 1: Prepare Plugin Files

1. Create a clean plugin folder:
   ```
   social-review-slider/
   ├── social-review-slider.php
   ├── includes/
   ├── assets/
   └── README.md
   ```

2. Remove these files (not needed for production):
   - `demo.html`
   - `wp-connection-test.php`
   - `setup.bat`
   - `embed.jpg`
   - `scraper-service/` (deploy separately)

#### Step 2: Upload to WordPress

**Via FTP/SFTP:**
```
Upload to: /wp-content/plugins/social-review-slider/
```

**Via WordPress Admin:**
1. Zip the plugin folder
2. Go to: Plugins → Add New → Upload Plugin
3. Upload the zip file
4. Activate the plugin

#### Step 3: Configure Plugin Settings

1. Go to: **Reviews → Import Settings**
2. Enter:
   - **Scraper Service URL**: `https://your-scraper-domain.com` (or `http://localhost:3000` if same server)
   - **API Secret Key**: (generate a secure key)
3. Save settings

---

### Part 2: Deploy Scraper Service

#### Step 1: Prepare Service Files

Create a clean deployment folder with only these files:
```
scraper-service/
├── server.js
├── package.json
├── package-lock.json
├── .env
├── scrapers/
│   ├── airbnb-scraper.js
│   ├── google-scraper.js
│   └── tripadvisor-scraper.js
└── utils/
    └── ai-extractor.js
```

#### Step 2: Choose Deployment Platform

**Option 1: VPS (DigitalOcean, Linode, AWS EC2)**
- Full control
- Can run alongside WordPress
- Recommended for production

**Option 2: Cloud Platform (Heroku, Railway, Render)**
- Easy deployment
- Auto-scaling
- Good for separate service deployment

**Option 3: Same Server as WordPress**
- If you have VPS with WordPress
- Simplest setup
- Use `http://localhost:3000`

#### Step 3: Deploy to Server

**For VPS (Ubuntu/Debian):**

```bash
# 1. Connect to server
ssh user@your-server.com

# 2. Install Node.js (if not installed)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# 3. Create directory
mkdir -p /var/www/scraper-service
cd /var/www/scraper-service

# 4. Upload files (via SCP, FTP, or Git)
# Example with SCP:
# scp -r scraper-service/* user@your-server.com:/var/www/scraper-service/

# 5. Install dependencies
npm install --production

# 6. Configure environment
cp .env.example .env
nano .env
# Set: API_SECRET_KEY, OPENAI_API_KEY or GOOGLE_API_KEY, PORT

# 7. Test the service
npm start
# Should see: "Review Scraper Service running on port 3000"
```

#### Step 4: Set Up Process Manager (PM2)

Keep the service running 24/7:

```bash
# Install PM2
sudo npm install -g pm2

# Start service with PM2
pm2 start server.js --name "review-scraper"

# Save PM2 configuration
pm2 save

# Set PM2 to start on boot
pm2 startup
# Follow the instructions shown

# Check status
pm2 status
pm2 logs review-scraper
```

#### Step 5: Configure Firewall

```bash
# Allow port 3000 (or your chosen port)
sudo ufw allow 3000/tcp
sudo ufw reload
```

#### Step 6: Set Up Reverse Proxy (Optional but Recommended)

Use Nginx to proxy requests and add HTTPS:

```nginx
# /etc/nginx/sites-available/scraper-service
server {
    listen 80;
    server_name scraper.yourdomain.com;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
}
```

Enable site and get SSL certificate:
```bash
sudo ln -s /etc/nginx/sites-available/scraper-service /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx

# Get SSL certificate with Let's Encrypt
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d scraper.yourdomain.com
```

---

## 🔒 Security Configuration

### 1. Generate Secure API Key

```bash
# Generate a random secure key
openssl rand -hex 32
```

Use this as your `API_SECRET_KEY` in both:
- Scraper service `.env` file
- WordPress plugin settings

### 2. Environment Variables

**Production `.env` file:**
```env
# Server Configuration
NODE_ENV=production
PORT=3000

# Security
API_SECRET_KEY=your-super-secure-random-key-here

# AI Service (choose one)
OPENAI_API_KEY=your-openai-key
# OR
GOOGLE_API_KEY=your-google-gemini-key

# Rate Limiting
RATE_LIMIT_WINDOW_MS=900000
RATE_LIMIT_MAX_REQUESTS=100
```

### 3. WordPress Configuration

In WordPress plugin settings:
- Use HTTPS URL if service is on different server
- Use strong API key (same as in `.env`)
- Enable auto-import only if needed

---

## 🧪 Testing Production Deployment

### Test Scraper Service

```bash
# Health check
curl http://your-server:3000/health

# Or with domain
curl https://scraper.yourdomain.com/health

# Expected response:
# {"status":"ok","service":"Review Scraper Service","version":"1.0.0"}
```

### Test from WordPress

1. Go to: **Reviews → Import Settings**
2. Click: **"Test Service Connection"**
3. Should see: ✅ "Connection Successful!"

### Test Review Import

1. Add a platform URL (Airbnb, Google, or TripAdvisor)
2. Click: **"Import Reviews Now"**
3. Wait 20-30 seconds
4. Check: **Reviews → All Reviews**

---

## 📊 Monitoring

### Check Service Status

```bash
# PM2 status
pm2 status

# View logs
pm2 logs review-scraper

# Monitor resources
pm2 monit
```

### WordPress Logs

Enable WordPress debugging (temporarily):
```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

Check logs: `wp-content/debug.log`

---

## 🔄 Updates and Maintenance

### Update Scraper Service

```bash
# Backup current version
cp -r /var/www/scraper-service /var/www/scraper-service-backup

# Upload new files
# ... (via SCP, Git, etc.)

# Install dependencies
npm install --production

# Restart service
pm2 restart review-scraper
```

### Update WordPress Plugin

1. Deactivate plugin
2. Upload new files
3. Reactivate plugin

---

## 🆘 Troubleshooting

### Service Won't Start
```bash
# Check logs
pm2 logs review-scraper

# Check if port is in use
sudo netstat -tulpn | grep 3000

# Check environment variables
cat .env
```

### WordPress Can't Connect
- Check firewall allows port 3000
- Verify API key matches in both places
- Check service is running: `pm2 status`
- Test health endpoint: `curl http://localhost:3000/health`

### Import Fails
- Check service logs: `pm2 logs`
- Verify AI API key is valid (OpenAI or Google)
- Check rate limits
- Verify platform URLs are correct

---

## 💰 Cost Estimates

### Scraper Service Hosting
- **VPS (DigitalOcean)**: $6-12/month
- **Cloud Platform (Railway)**: $5-10/month
- **Same server as WordPress**: $0 extra

### AI API Costs
- **OpenAI GPT-4o-mini**: ~$0.0001 per review
- **Google Gemini**: Free tier available
- **Estimated**: $0.01-0.10 per 100 reviews

---

## ✅ Production Checklist

- [ ] WordPress plugin uploaded and activated
- [ ] Scraper service deployed to server
- [ ] Node.js and dependencies installed
- [ ] Environment variables configured
- [ ] PM2 process manager set up
- [ ] Firewall configured
- [ ] (Optional) Nginx reverse proxy configured
- [ ] (Optional) SSL certificate installed
- [ ] API keys generated and configured
- [ ] Connection test successful
- [ ] Test import successful
- [ ] Monitoring set up

---

## 🎉 You're Live!

Once all steps are complete, your Social Review Slider is ready for production use!

**Support**: Check the documentation in the `doc/` folder for additional help.
