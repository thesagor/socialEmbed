# ☁️ Deploying Scraper Service to Render.com

This is the easiest way to host your scraper service for free (or cheap).

---

## 📋 Prerequisites

1.  **GitHub Account** (You'll need to push your code there first).
2.  **Render.com Account** (Sign up with GitHub).

---

## 🚀 Step 1: Push Code to GitHub

Render deploys directly from GitHub. You need to put your `scraper-service` code into a repository.

1.  **Create a new repository** on GitHub (e.g., `review-scraper-service`).
2.  **Upload your files**:
    *   Upload ONLY the contents of the `scraper-service` folder.
    *   *Do NOT upload the `node_modules` folder.*
    *   Ensure `render.yaml` is included.

**Your GitHub repo structure should look like this:**
```
/
├── server.js
├── package.json
├── render.yaml
├── scrapers/
│   ├── google-scraper.js
│   └── ...
└── utils/
    └── ai-extractor.js
```

---

## 🚀 Step 2: Create Service on Render

1.  Log in to **[dashboard.render.com](https://dashboard.render.com)**.
2.  Click **"New +"** → **"Web Service"**.
3.  Connect your GitHub account if you haven't already.
4.  **Select the repository** you just created (`review-scraper-service`).
5.  **Configure the Service**:
    *   **Name**: `review-scraper` (or whatever you like).
    *   **Region**: Choose one close to you (e.g., Frankfurt, Oregon).
    *   **Branch**: `main` (or master).
    *   **Root Directory**: Leave blank (since we uploaded the service files to the root).
    *   **Runtime**: `Node`.
    *   **Build Command**: `npm install` (Render usually detects this).
    *   **Start Command**: `node server.js` (Render usually detects this).
    *   **Instance Type**: `Free` (good for testing) or `Starter` ($7/mo, recommended for production).

---

## 🔑 Step 3: Configure Environment Variables

Scroll down to the **"Environment Variables"** section on the setup page. Add these:

| Key | Value |
| :--- | :--- |
| `NODE_ENV` | `production` |
| `API_SECRET_KEY` | (Generate a strong random key, e.g., `my-secret-key-123`) |
| `OPENAI_API_KEY` | (Your OpenAI API Key) |
| `PORT` | `10000` (Render expects this port usually, but 3000 works too) |

*Note: If you included `render.yaml` in your repo, Render might auto-detect these settings!*

---

## 🚀 Step 4: Deploy & Get URL

1.  Click **"Create Web Service"**.
2.  Render will start building your app. This takes 1-2 minutes.
3.  Once finished, you'll see a green **"Live"** badge.
4.  **Copy your Service URL** from the top left (e.g., `https://review-scraper.onrender.com`).

---

## 🔗 Step 5: Connect WordPress

1.  Go to your **WordPress Admin**.
2.  Navigate to **Reviews → Import Settings**.
3.  **Scraper Service URL**: Paste your Render URL (e.g., `https://review-scraper.onrender.com`).
    *   *Note: No port number needed for Render URLs.*
4.  **API Secret Key**: Enter the key you set in Step 3.
5.  Click **"Test Service Connection"**.

---

## ⚠️ Important Note on Free Tier

If you use the **Free** plan on Render:
*   The service will **"sleep"** after 15 minutes of inactivity.
*   When you try to import reviews, the first request might take **30-60 seconds** to wake it up.
*   **Solution**: Just wait a bit, or upgrade to the **Starter** plan ($7/mo) to keep it awake 24/7.
