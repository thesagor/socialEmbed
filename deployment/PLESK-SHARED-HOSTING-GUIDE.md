# 🌐 Deploying to Plesk or Shared Hosting

This guide explains how to deploy the Social Review Slider on Plesk or Shared Hosting environments.

---

## 📦 Component 1: The WordPress Plugin
**Compatibility**: ✅ 100% Compatible with ALL hosting.

1.  **Log in** to your WordPress Admin on your live site.
2.  Go to **Plugins → Add New → Upload Plugin**.
3.  Upload `deployment/social-review-slider-plugin.zip`.
4.  Activate it.

---

## 📦 Component 2: The Scraper Service (Node.js)

This is the tricky part. This service needs to run 24/7.

### Scenario A: Plesk Server (Recommended)
Most modern Plesk servers support Node.js.

1.  **Log in to Plesk**.
2.  Look for **"Node.js"** or **"Node.js App"** in your domain dashboard.
    *   *If you don't see it, ask your hosting provider to enable the "Node.js Extension".*
3.  **Create the App**:
    *   Click **Enable Node.js**.
    *   **Document Root**: Point to a folder (e.g., `/httpdocs/scraper-service`).
    *   **Application Startup File**: `server.js`.
4.  **Upload Files**:
    *   Upload the contents of `deployment/scraper-service-production.zip` to that folder.
5.  **Install Dependencies**:
    *   Click the **"NPM Install"** button in the Plesk Node.js dashboard.
6.  **Environment Variables**:
    *   In Plesk, look for "Environment Variables" or create a `.env` file in the folder.
    *   Add your `API_SECRET_KEY`, `OPENAI_API_KEY`, etc.
7.  **Start App**: Click **"Restart App"**.
8.  **Get the URL**: Your service will be running at `https://your-domain.com/scraper-service` (or whatever path you configured).
    *   *Note: You might need to configure a "Proxy Rule" in Plesk to map port 3000 to a URL, or Plesk might handle this automatically via Phusion Passenger.*

---

### Scenario B: Shared Hosting (cPanel) with Node.js
Some shared hosts (like Namecheap, Bluehost, SiteGround) offer Node.js support.

1.  Log in to **cPanel**.
2.  Look for **"Setup Node.js App"**.
3.  **Create App**:
    *   **Node.js Version**: Select 16.x or higher.
    *   **App Root**: `scraper-service`.
    *   **Application URL**: `scraper`.
    *   **Startup File**: `server.js`.
4.  **Upload Files**: Use File Manager to upload the zip contents to the `scraper-service` folder.
5.  **Install Dependencies**:
    *   In the Node.js App page, click **"Run NPM Install"**.
6.  **Start**: Click **Start App**.
7.  **URL**: Your service URL will be `https://your-domain.com/scraper`.

---

### Scenario C: Shared Hosting WITHOUT Node.js (The "Hybrid" Approach)
If your shared hosting **does not** support Node.js, you cannot run the scraper service there.

**The Solution:**
1.  **Host the Plugin** on your Shared Hosting (as normal).
2.  **Host the Scraper Service** on a free/cheap cloud platform:
    *   **Render.com** (Free tier available)
    *   **Railway.app** (Cheap)
    *   **Glitch** or **Replit**
3.  **Connect them**:
    *   Deploy the service to Render/Railway.
    *   Get the URL (e.g., `https://my-scraper.onrender.com`).
    *   Go to your WordPress Plugin Settings.
    *   Enter that URL as the **Scraper Service URL**.

---

## 🚀 Summary Checklist for Plesk/Shared

1.  [ ] **Check Hosting**: Does it have a "Node.js" icon?
    *   **Yes**: Follow Scenario A or B.
    *   **No**: Follow Scenario C (Hybrid).
2.  [ ] **Deploy Plugin**: Upload zip to WordPress.
3.  [ ] **Deploy Service**: Upload files to Node.js folder OR Cloud provider.
4.  [ ] **Connect**: Update WordPress settings with the live URL.
