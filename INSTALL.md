# Quick Installation Guide

## Step 1: Install the Plugin

1. **Zip the plugin folder** (if not already zipped)
   - Right-click on the `socialEmbed` folder
   - Select "Send to" → "Compressed (zipped) folder"
   - Rename it to `social-review-slider.zip`

2. **Upload to WordPress**
   - Log in to your WordPress admin panel
   - Go to **Plugins → Add New**
   - Click **Upload Plugin**
   - Choose the `social-review-slider.zip` file
   - Click **Install Now**
   - Click **Activate Plugin**

## Step 2: Add Your First Review

1. In WordPress admin, go to **Reviews → Add New**

2. Fill in the details:
   - **Title**: "Great Service" (internal reference)
   - **Content**: Write the review text
   - **Reviewer Name**: "John Doe"
   - **Rating**: Select 5 stars
   - **Review Date**: Select the date
   - **Review URL**: Paste the link to original review
   - **Reviewer Location**: "New York, USA"
   - **Platform**: Check "Google" (or TripAdvisor/Airbnb)

3. Click **Publish**

## Step 3: Display Reviews on Your Site

### Option A: Using Shortcode (Recommended)

1. Edit any page or post
2. Add a **Shortcode block** (or Custom HTML block)
3. Paste this code:
   ```
   [social_reviews]
   ```
4. **Update** the page
5. View the page to see your reviews!

### Option B: Using Widget

1. Go to **Appearance → Widgets**
2. Find **Social Review Slider** widget
3. Drag it to your sidebar or footer
4. Configure settings
5. Click **Save**

## Step 4: Customize (Optional)

### Show only specific platform:
```
[social_reviews platform="google"]
```

### Show 5 reviews with autoplay:
```
[social_reviews count="5" autoplay="true"]
```

### Show 3 slides at once:
```
[social_reviews slides_per_view="3"]
```

## Quick Tips

✅ **Add multiple reviews** for the best slider effect
✅ **Mix platforms** to show diversity
✅ **Use real review text** for authenticity
✅ **Upload reviewer avatars** for a personal touch
✅ **Link to original reviews** for credibility

## Preview the Demo

Open `demo.html` in your browser to see how the slider looks!

## Need Help?

Check the full `README.md` for detailed documentation and all available options.

---

**That's it! You're ready to showcase your amazing reviews! 🎉**
