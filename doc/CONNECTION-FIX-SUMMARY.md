# WordPress + WAMPP Connection Fix - Summary

## Changes Made

### 1. **Server Configuration** (`scraper-service/server.js`)
- Changed server binding from `localhost` to `0.0.0.0` to accept connections from all network interfaces
- Added better logging showing both `localhost` and `127.0.0.1` URLs
- This allows WordPress running on WAMPP to connect to the service

### 2. **WordPress Plugin** (`includes/class-review-importer.php`)
- Changed default service URL from `http://localhost:3000` to `http://127.0.0.1:3000`
- Updated all 5 occurrences throughout the file
- This fixes Windows-specific DNS resolution issues where `localhost` might resolve to IPv6

### 3. **Admin Scripts** (`assets/js/admin-scripts.js`)
- Removed hardcoded PHP nonce declarations
- Nonces are now properly passed via `wp_localize_script` (already configured in main plugin file)

### 4. **New Diagnostic Tools**
- **`test-wordpress-connection.js`**: Tests connectivity using both `localhost` and `127.0.0.1`
- **`setup-wordpress.bat`**: Automated setup script for Windows users
- **`doc/WORDPRESS-WAMPP-TROUBLESHOOTING.md`**: Comprehensive troubleshooting guide

---

## How to Fix Your Connection Issue

### Step 1: Restart the Scraper Service

The service needs to be restarted to apply the server binding changes:

1. Stop the current service (Ctrl+C in the terminal)
2. Start it again:
   ```bash
   cd scraper-service
   npm start
   ```

You should now see:
```
🚀 Review Scraper Service running on port 3000
🌐 Accessible at:
   - http://localhost:3000
   - http://127.0.0.1:3000
```

### Step 2: Update WordPress Settings

1. Go to WordPress Admin → **Reviews → Import Settings**

2. Update the **Scraper Service URL** to:
   ```
   http://127.0.0.1:3000
   ```
   (Change from `localhost` to `127.0.0.1`)

3. Make sure **API Secret Key** matches the value in your `.env` file

4. Click **"Save Changes"**

### Step 3: Test the Connection

1. Click the **"Test Service Connection"** button

2. You should see: ✅ **"Connection Successful! Service is running v1.0.0"**

3. If successful, you can now click **"Import Reviews Now"**

---

## Quick Test (Optional)

Run the automated setup script:

```bash
cd scraper-service
setup-wordpress.bat
```

This will:
- Check your `.env` configuration
- Start the service if not running
- Test the connection
- Show you the exact values to enter in WordPress

---

## Why This Fix Works

### Problem 1: Server Not Accessible
**Before**: Server listened only on `localhost` interface  
**After**: Server listens on `0.0.0.0` (all interfaces)  
**Result**: WordPress can now connect from WAMPP

### Problem 2: DNS Resolution on Windows
**Before**: `localhost` might resolve to IPv6 (`::1`)  
**After**: `127.0.0.1` explicitly uses IPv4  
**Result**: Consistent connection on Windows

### Problem 3: JavaScript Nonces
**Before**: Hardcoded PHP tags in JS file (won't execute)  
**After**: Properly localized via WordPress API  
**Result**: AJAX requests work correctly

---

## Verification Steps

✅ **Service Running**: `npm start` shows service on port 3000  
✅ **Health Check**: Open `http://127.0.0.1:3000/health` in browser  
✅ **Diagnostic Test**: Run `node test-wordpress-connection.js`  
✅ **WordPress Test**: Click "Test Service Connection" button  
✅ **Import Works**: Click "Import Reviews Now" successfully imports

---

## If Still Not Working

1. **Check Windows Firewall**: Allow Node.js through firewall
2. **Check Port**: Make sure nothing else is using port 3000
3. **Check .env**: Verify `API_SECRET_KEY` is set correctly
4. **Check Logs**: Enable WordPress debug logging
5. **See Troubleshooting Guide**: `doc/WORDPRESS-WAMPP-TROUBLESHOOTING.md`

---

## Files Modified

- ✏️ `scraper-service/server.js` - Server binding and logging
- ✏️ `includes/class-review-importer.php` - Default URL changed to 127.0.0.1
- ✏️ `assets/js/admin-scripts.js` - Removed hardcoded nonces
- ➕ `scraper-service/test-wordpress-connection.js` - New diagnostic tool
- ➕ `scraper-service/setup-wordpress.bat` - New setup script
- ➕ `doc/WORDPRESS-WAMPP-TROUBLESHOOTING.md` - New troubleshooting guide

---

## Next Steps

After fixing the connection:

1. ✅ Test the connection (should work now!)
2. 📝 Add your platform URLs (Google, TripAdvisor, Airbnb)
3. 🔄 Click "Import Reviews Now"
4. 🎉 Reviews will be imported automatically!

---

## Support

If you encounter any issues:
- Check the troubleshooting guide: `doc/WORDPRESS-WAMPP-TROUBLESHOOTING.md`
- Run the diagnostic: `node test-wordpress-connection.js`
- Check service logs in the terminal
- Enable WordPress debug logging
