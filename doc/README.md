# Social Review Slider - WordPress Plugin

A beautiful and modern WordPress plugin to embed TripAdvisor, Google, and Airbnb reviews as an elegant slider on your website.

## Features

✨ **Multi-Platform Support**
- TripAdvisor reviews
- Google reviews
- Airbnb reviews

🤖 **Automatic Import**
- Auto-fetch reviews from Google Places API
- Auto-fetch reviews from TripAdvisor API
- Scheduled imports (hourly, daily, weekly)
- Manual import on-demand
- Duplicate detection
- Import activity logging

🎨 **Beautiful Design**
- Modern, premium card-based design
- Platform-specific color schemes
- Smooth animations and transitions
- Fully responsive layout
- Dark mode support

⚙️ **Flexible Display Options**
- Random or chronological order
- Autoplay with customizable delay
- Configurable slides per view
- Navigation arrows and pagination dots
- Filter by specific platform or show all

🔧 **Easy to Use**
- Custom post type for reviews
- Simple meta boxes for review details
- Shortcode support
- Widget support
- No coding required
- API integration settings page

## Installation

1. **Upload the Plugin**
   - Download the `social-review-slider` folder
   - Upload it to `/wp-content/plugins/` directory
   - Or install via WordPress admin: Plugins → Add New → Upload Plugin

2. **Activate the Plugin**
   - Go to WordPress admin → Plugins
   - Find "Social Review Slider"
   - Click "Activate"

3. **You're Ready!**
   - A new "Reviews" menu will appear in your WordPress admin

## Automatic Import Setup (Recommended for Hotels)

If you want to automatically import reviews from your Google Business and TripAdvisor listings:

1. **Go to Reviews → Import Settings**

2. **Configure Google Business**:
   - Get your API key from [Google Cloud Console](https://console.cloud.google.com/)
   - Enable "Places API"
   - Find your Place ID using [Place ID Finder](https://developers.google.com/maps/documentation/places/web-service/place-id)
   - Enter both in the settings
   - Click "Test Connection"

3. **Configure TripAdvisor**:
   - Apply for API access at [TripAdvisor Developer Portal](https://www.tripadvisor.com/developers)
   - Get your Location ID from your TripAdvisor listing URL
   - Enter credentials in the settings
   - Click "Test Connection"

4. **Set Import Schedule**:
   - Enable "Auto Import"
   - Choose frequency (Daily recommended)
   - Set import limit (50 reviews default)
   - Save changes

5. **Run First Import**:
   - Click "Import Reviews Now" button
   - Wait for completion
   - Check "Recent Import Activity"

📖 **For detailed API setup instructions, see [API-SETUP-GUIDE.md](API-SETUP-GUIDE.md)**

## How to Use

### Adding Reviews

1. **Go to Reviews → Add New**
2. **Fill in the review details:**
   - **Title**: A brief title for the review (for admin reference)
   - **Content**: The actual review text
   - **Reviewer Name**: Name of the person who left the review
   - **Rating**: Select rating from 1 to 5 stars (supports half stars)
   - **Review Date**: When the review was posted
   - **Review URL**: Link to the original review
   - **Reviewer Location**: Where the reviewer is from
   - **Reviewer Avatar**: Upload a profile picture (optional)
   - **Platform**: Select TripAdvisor, Google, or Airbnb

3. **Publish** the review

### Displaying Reviews

#### Method 1: Using Shortcode

Add this shortcode to any page, post, or widget:

```
[social_reviews]
```

**Shortcode Parameters:**

```
[social_reviews 
    platform="all"           // Options: all, tripadvisor, google, airbnb
    count="-1"              // Number of reviews (-1 for all)
    autoplay="true"         // Enable autoplay (true/false)
    autoplay_delay="5000"   // Delay in milliseconds
    loop="true"             // Loop slides (true/false)
    slides_per_view="1"     // Number of slides visible
    space_between="30"      // Space between slides in pixels
    show_navigation="true"  // Show arrow buttons (true/false)
    show_pagination="true"  // Show pagination dots (true/false)
    random="true"           // Random order (true/false)
]
```

**Examples:**

Show only Google reviews:
```
[social_reviews platform="google"]
```

Show 5 random reviews with autoplay:
```
[social_reviews count="5" random="true" autoplay="true"]
```

Show 3 slides at once (great for desktop):
```
[social_reviews slides_per_view="3" space_between="20"]
```

#### Method 2: Using Widget

1. Go to **Appearance → Widgets**
2. Find **Social Review Slider** widget
3. Drag it to your desired widget area
4. Configure the settings
5. Save

### Customization

#### CSS Customization

You can override the default styles by adding custom CSS to your theme:

```css
/* Change primary color */
:root {
    --srs-primary-color: #YOUR_COLOR;
}

/* Customize card appearance */
.srs-review-card {
    border-radius: 20px;
    padding: 3rem;
}

/* Change star color */
.srs-star-full {
    color: #YOUR_COLOR;
}
```

#### Platform Colors

The plugin automatically applies platform-specific colors:
- **TripAdvisor**: Green (#34E0A1)
- **Google**: Blue (#4285F4)
- **Airbnb**: Red (#FF5A5F)

## File Structure

```
social-review-slider/
├── social-review-slider.php          # Main plugin file
├── includes/
│   ├── class-review-post-type.php    # Custom post type
│   ├── class-review-meta-boxes.php   # Meta boxes for review details
│   ├── class-review-shortcode.php    # Shortcode functionality
│   └── class-review-widget.php       # Widget functionality
├── assets/
│   ├── css/
│   │   ├── styles.css                # Frontend styles
│   │   └── admin-styles.css          # Admin styles
│   └── js/
│       ├── scripts.js                # Frontend scripts
│       └── admin-scripts.js          # Admin scripts
└── README.md                          # This file
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- Modern browser with JavaScript enabled

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Dependencies

The plugin uses:
- **Swiper.js** (v11) - For the slider functionality (loaded from CDN)
- **jQuery** - Included with WordPress

## Frequently Asked Questions

**Q: Can I import reviews automatically from these platforms?**
A: Currently, you need to add reviews manually. Automatic import may be added in future versions.

**Q: How many reviews can I add?**
A: There's no limit! Add as many reviews as you want.

**Q: Can I customize the design?**
A: Yes! You can override the CSS styles in your theme's custom CSS.

**Q: Is it mobile-friendly?**
A: Absolutely! The slider is fully responsive and works beautifully on all devices.

**Q: Can I show reviews from only one platform?**
A: Yes! Use the `platform` parameter in the shortcode or widget settings.

**Q: Does it support RTL languages?**
A: The plugin is RTL-ready and will work with RTL languages.

## Support

For support, feature requests, or bug reports, please contact the plugin author.

## Changelog

### Version 1.0.0
- Initial release
- Custom post type for reviews
- Support for TripAdvisor, Google, and Airbnb
- Shortcode and widget support
- Responsive slider with Swiper.js
- Platform-specific styling
- Rating system with stars
- Avatar upload support
- Dark mode support

## Credits

- Developed by: Your Name
- Slider: Swiper.js
- Icons: Custom SVG icons

## License

This plugin is licensed under the GPL v2 or later.

---

**Enjoy using Social Review Slider! ⭐**
