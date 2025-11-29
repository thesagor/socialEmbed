# How Automatic Import Works

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Your WordPress Site                       │
│                                                               │
│  ┌──────────────────────────────────────────────────────┐  │
│  │         Social Review Slider Plugin                   │  │
│  │                                                        │  │
│  │  ┌──────────────┐      ┌──────────────┐             │  │
│  │  │   Settings   │      │   Importer   │             │  │
│  │  │    Page      │─────▶│    Class     │             │  │
│  │  └──────────────┘      └───────┬──────┘             │  │
│  │                                 │                     │  │
│  │                        ┌────────▼────────┐           │  │
│  │                        │  WordPress Cron │           │  │
│  │                        │  (Scheduled Job)│           │  │
│  │                        └────────┬────────┘           │  │
│  └─────────────────────────────────┼──────────────────────┘
│                                     │                     │
└─────────────────────────────────────┼─────────────────────┘
                                      │
                    ┌─────────────────┼─────────────────┐
                    │                 │                 │
                    ▼                 ▼                 ▼
            ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
            │   Google     │  │ TripAdvisor  │  │   Airbnb     │
            │ Places API   │  │  Content API │  │  (Manual)    │
            └──────┬───────┘  └──────┬───────┘  └──────────────┘
                   │                 │
                   │                 │
                   ▼                 ▼
            ┌──────────────────────────────┐
            │    Review Data (JSON)         │
            │  - Reviewer name              │
            │  - Rating (1-5 stars)         │
            │  - Review text                │
            │  - Date                       │
            │  - Avatar URL                 │
            └──────────┬───────────────────┘
                       │
                       ▼
            ┌──────────────────────────────┐
            │  Duplicate Check              │
            │  (Name + Text + Platform)     │
            └──────────┬───────────────────┘
                       │
                       ▼
            ┌──────────────────────────────┐
            │  Create WordPress Post        │
            │  - Post Type: srs_review      │
            │  - Add meta data              │
            │  - Set platform taxonomy      │
            └──────────┬───────────────────┘
                       │
                       ▼
            ┌──────────────────────────────┐
            │  Display on Your Website      │
            │  via Shortcode/Widget         │
            └───────────────────────────────┘
```

## Import Flow

### 1. **Manual Import** (Immediate)
```
User clicks "Import Now"
    ↓
AJAX request to WordPress
    ↓
Importer fetches from all APIs
    ↓
Process each review
    ↓
Check for duplicates
    ↓
Create new posts
    ↓
Return results to user
```

### 2. **Automatic Import** (Scheduled)
```
WordPress Cron runs (hourly/daily/weekly)
    ↓
Trigger: srs_import_reviews_cron
    ↓
Importer::import_all_reviews()
    ↓
Fetch from Google API
    ↓
Fetch from TripAdvisor API
    ↓
Process and save reviews
    ↓
Log activity
```

## Data Flow Example

### Google Places API Response
```json
{
  "result": {
    "reviews": [
      {
        "author_name": "John Doe",
        "rating": 5,
        "text": "Amazing hotel!",
        "time": 1699564800,
        "profile_photo_url": "https://..."
      }
    ]
  }
}
```

### Converted to WordPress Post
```
Post Type: srs_review
Title: "Amazing hotel!"
Content: "Amazing hotel!"
Meta Data:
  - _srs_reviewer_name: "John Doe"
  - _srs_rating: 5
  - _srs_review_date: "2025-11-15"
  - _srs_reviewer_avatar: "https://..."
Taxonomy: srs_platform = "google"
```

### Displayed on Frontend
```html
<div class="srs-review-card" data-platform="google">
  <div class="srs-reviewer-info">
    <img src="https://..." alt="John Doe">
    <h3>John Doe</h3>
  </div>
  <div class="srs-rating">★★★★★ 5.0</div>
  <div class="srs-review-content">
    <p>Amazing hotel!</p>
  </div>
  <div class="srs-platform-badge">Google</div>
</div>
```

## Import Schedule Options

| Frequency    | WordPress Cron | Best For                    |
|--------------|----------------|-----------------------------|
| Hourly       | `hourly`       | High-traffic hotels         |
| Twice Daily  | `twicedaily`   | Medium-traffic properties   |
| Daily        | `daily`        | Most hotels (recommended)   |
| Weekly       | `weekly`       | Small properties            |

## Duplicate Detection Logic

```php
Check if review exists:
  1. Match reviewer name (exact)
  2. Match platform (google/tripadvisor/airbnb)
  3. If both match → Skip (duplicate)
  4. If no match → Import (new review)
```

## Error Handling

```
API Request
    ↓
Success? ──Yes──▶ Process reviews
    │
    No
    ↓
Log error
    ↓
Return error message
    ↓
Display in admin
    ↓
Continue with next platform
```

## Security Measures

1. **API Key Storage**: Stored in WordPress options (encrypted in database)
2. **AJAX Nonce**: Prevents unauthorized import requests
3. **Capability Check**: Only admins can import reviews
4. **Input Sanitization**: All data sanitized before saving
5. **Rate Limiting**: Respects API rate limits

## Performance Optimization

1. **Batch Processing**: Import up to 50 reviews per platform
2. **Duplicate Check**: Quick database query before creating post
3. **Caching**: WordPress transients for API responses
4. **Async Processing**: Cron jobs run in background
5. **Timeout Handling**: 30-second timeout for API requests

## Monitoring & Logging

### Import Log Structure
```php
[
  'date' => '2025-11-27 22:30:00',
  'platform' => 'google',
  'count' => 15,
  'success' => true
]
```

### View Logs
- Go to: **Reviews → Import Settings**
- Scroll to: **Recent Import Activity**
- Shows: Last 10 import attempts

## Troubleshooting Decision Tree

```
Import Failed?
    ↓
Check "Test Connection"
    ↓
Success? ──No──▶ Fix API credentials
    │
    Yes
    ↓
Check import logs
    ↓
"No reviews found"? ──Yes──▶ Verify you have reviews on platform
    │
    No
    ↓
"Rate limit"? ──Yes──▶ Wait and try again later
    │
    No
    ↓
Check WordPress cron
    ↓
Working? ──No──▶ Enable WP-Cron or use server cron
    │
    Yes
    ↓
Contact support with error message
```

## Best Practices

✅ **DO:**
- Test connection before enabling auto-import
- Start with manual import to verify setup
- Monitor import logs regularly
- Set reasonable import limits
- Use daily frequency for most cases

❌ **DON'T:**
- Share API keys publicly
- Set import limit too high (API costs)
- Import too frequently (rate limits)
- Ignore failed import logs
- Delete reviews without backup

---

**This automated system ensures your website always displays the latest reviews from all your platforms! 🚀**
