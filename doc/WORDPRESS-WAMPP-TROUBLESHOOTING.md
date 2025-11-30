# WordPress + WAMPP Connection Troubleshooting Guide

## Quick Fix Steps

### 1. **Update WordPress Plugin Settings**

After making the code changes, go to your WordPress admin panel:

1. Navigate to: **Reviews → Import Settings**
2. In the "AI Scraper Service" section:
   - **Scraper Service URL**: Use `http://127.0.0.1:3000` (NOT `localhost`)
   - **API Secret Key**: Copy the value from your `.env` file (the `API_SECRET_KEY` value)
3. Click **"Test Service Connection"** button
4. You should see: ✓ Connection Successful!

### 2. **Verify Scraper Service is Running**

Open a terminal in the `scraper-service` folder and run:

```bash
npm start
```

You should see:
```
🚀 Review Scraper Service running on port 3000
🌐 Accessible at:
   - http://localhost:3000
   - http://127.0.0.1:3000
📊 Environment: development
🔒 API authentication: Enabled
```

### 3. **Test Connection from Command Line**

Run the diagnostic tool:

```bash
node test-wordpress-connection.js
```

This will test both `localhost` and `127.0.0.1` and tell you which one works.

---

## Why Use 127.0.0.1 Instead of localhost?

On Windows with WAMPP, `localhost` can sometimes resolve to IPv6 (`::1`) instead of IPv4 (`127.0.0.1`), causing connection issues. Using `127.0.0.1` explicitly forces IPv4 connection.

---

## Common Issues & Solutions

### Issue 1: "Connection test failed"

**Cause**: Scraper service is not running or not accessible

**Solutions**:
1. Make sure the scraper service is running (`npm start`)
2. Check if port 3000 is not blocked by Windows Firewall
3. Try using `127.0.0.1:3000` instead of `localhost:3000`
4. Restart the scraper service after making changes

### Issue 2: "Service Reachable, but API Key is Invalid"

**Cause**: The API key in WordPress doesn't match the one in `.env`

**Solutions**:
1. Open `scraper-service\.env`
2. Copy the exact value of `API_SECRET_KEY`
3. Paste it in WordPress → Reviews → Import Settings → API Secret Key
4. Save changes and test again

### Issue 3: "Service Unreachable: Connection refused"

**Cause**: The scraper service is not running or listening on the wrong interface

**Solutions**:
1. Stop the service (Ctrl+C)
2. Make sure you've updated `server.js` to listen on `0.0.0.0`
3. Restart the service: `npm start`
4. The server should now show "Accessible at: http://127.0.0.1:3000"

### Issue 4: Port 3000 Already in Use

**Cause**: Another application is using port 3000

**Solutions**:
1. Find what's using port 3000:
   ```powershell
   netstat -ano | findstr :3000
   ```
2. Either:
   - Stop that application, OR
   - Change the port in `.env`: `PORT=3001`
   - Update WordPress settings to use the new port

---

## Testing Checklist

Before testing in WordPress, verify:

- [ ] Scraper service is running (`npm start`)
- [ ] Service responds to health check: `http://127.0.0.1:3000/health`
- [ ] `.env` file has `API_SECRET_KEY` set
- [ ] WordPress plugin is activated
- [ ] WordPress settings have correct URL (`http://127.0.0.1:3000`)
- [ ] WordPress settings have correct API key (from `.env`)

---

## Manual Connection Test

You can test the connection manually using PowerShell:

```powershell
# Test health endpoint
Invoke-WebRequest -Uri "http://127.0.0.1:3000/health" | Select-Object -ExpandProperty Content

# Test with API key (replace YOUR_API_KEY)
$headers = @{
    "X-API-Key" = "YOUR_API_KEY"
    "Content-Type" = "application/json"
}
$body = '{"placeUrl":"test"}'
Invoke-WebRequest -Uri "http://127.0.0.1:3000/api/scrape/google" -Method POST -Headers $headers -Body $body
```

---

## Still Not Working?

1. **Check Windows Firewall**:
   - Open Windows Defender Firewall
   - Allow Node.js through the firewall

2. **Check WAMPP Configuration**:
   - Make sure Apache is running
   - Check if there are any proxy settings in WAMPP

3. **Enable Debug Logging**:
   - In WordPress, add to `wp-config.php`:
     ```php
     define('WP_DEBUG', true);
     define('WP_DEBUG_LOG', true);
     ```
   - Check `wp-content/debug.log` for errors

4. **Test from Browser**:
   - Open: `http://127.0.0.1:3000/health`
   - You should see: `{"status":"ok","service":"Review Scraper Service","version":"1.0.0"}`

---

## Success Indicators

When everything is working correctly:

1. ✅ `npm start` shows service running on port 3000
2. ✅ Browser shows health check response at `http://127.0.0.1:3000/health`
3. ✅ Diagnostic tool shows both URLs working
4. ✅ WordPress "Test Service Connection" button shows success
5. ✅ "Import Reviews Now" button successfully imports reviews

---

## Need More Help?

If you're still experiencing issues:

1. Run the diagnostic tool and save the output:
   ```bash
   node test-wordpress-connection.js > connection-test.txt
   ```

2. Check the scraper service logs for errors

3. Check WordPress debug logs (`wp-content/debug.log`)

4. Verify your `.env` file configuration
