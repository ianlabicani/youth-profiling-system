# Reports System - Quick Start Guide

## Quick Access

### Main Reports Dashboard

```
http://localhost/reports
```

### Individual Reports

```
http://localhost/reports/demographics        # Youth population analysis
http://localhost/reports/leadership          # SK Councils & leadership
http://localhost/reports/engagement          # Event participation tracking
http://localhost/reports/profiles            # Individual youth records
http://localhost/reports/data-quality        # Data completeness metrics
```

## What Was Created

### 1. **Service Layer**

-   `app/Services/ReportGeneratorService.php` - AI insights & data aggregation

### 2. **Controller**

-   `app/Http/Controllers/Reports/ReportController.php` - Report business logic

### 3. **Views**

-   `resources/views/reports/shell.blade.php` - Shared layout
-   `resources/views/reports/index.blade.php` - Dashboard home
-   `resources/views/reports/demographics.blade.php` - Demographics analysis
-   `resources/views/reports/leadership.blade.php` - Leadership tracking
-   `resources/views/reports/engagement.blade.php` - Event participation
-   `resources/views/reports/profiles.blade.php` - Youth profiles
-   `resources/views/reports/data-quality.blade.php` - Data quality metrics

### 4. **Routes**

-   `routes/reports.php` - All report routes
-   Updated `routes/web.php` to include reports routes

## Key Features

### 📊 5 Report Categories

1. **Demographics** - Age, education, income, status analysis
2. **Leadership** - SK Councils and youth leaders overview
3. **Engagement** - Event participation and trends
4. **Profiles** - Individual youth records with search
5. **Data Quality** - System data completeness metrics

### 🤖 AI-Powered Insights

-   Automatic analysis for each report
-   Context-aware recommendations
-   Falls back to default if API unavailable
-   Uses Google Gemini API integration

### 📈 Advanced Visualizations

-   Bar charts, pie charts, doughnut charts, line charts
-   Real-time data aggregation
-   Responsive design
-   Chart.js integration

### 🔍 Flexible Filtering

-   Filter by barangay
-   Date range filtering
-   Status filtering
-   Text search capabilities

### 📦 Export Ready

-   UI buttons for PDF, Excel, CSV exports
-   Ready to connect to export handlers
-   Pagination support for large datasets

## How Filtering Works

### Demographics Report

```
?barangay=1&start_date=2025-01-01&end_date=2025-12-31
```

### Leadership Report

```
?barangay=1
```

### Engagement Report

```
?barangay=2&start_date=2025-11-01&end_date=2025-11-30
```

### Profiles Report

```
?barangay=1&status=active&search=John
```

## Architecture Overview

```
Request → ReportController → Models → Service → View
                                          ↓
                                    AI Insights (Gemini API)
```

### Data Flow

1. User accesses report route
2. Controller retrieves data from database
3. Service prepares and aggregates data
4. AI generates insights via Gemini API
5. View renders with charts and tables

## Statistics Available

### Demographics

-   Total youth count
-   Age distribution (5 age groups)
-   Sex breakdown
-   Educational status distribution
-   Household income distribution
-   Out-of-school youth count

### Leadership

-   Total SK Councils
-   Active councils count
-   Total youth leaders
-   Leadership positions held
-   Organizations count

### Engagement

-   Total events
-   Total participants
-   Participation rate (%)
-   Average participants per event
-   Events by barangay

### Profiles

-   Total youth records
-   Active youth count
-   Average age
-   Records with contact info
-   Searchable listing

### Data Quality

-   Total records
-   Complete records
-   Missing contacts
-   Incomplete profiles
-   Data quality score (%)

## Database Models Used

-   `Youth` - Individual youth records
-   `Barangay` - Barangay divisions
-   `SKCouncil` - Youth councils
-   `BarangayEvent` - Event records
-   `Organization` - Youth organizations

## Chart Types Used

### Visualization Choices

-   **Bar Charts** - Age groups, education status, income, leadership positions, events
-   **Pie Charts** - Sex distribution, status distribution
-   **Doughnut Charts** - Council status, sex distribution
-   **Line Charts** - Participation trends

## Color Scheme

| Report       | Color  | Hex     |
| ------------ | ------ | ------- |
| Demographics | Blue   | #3B82F6 |
| Leadership   | Purple | #A855F7 |
| Engagement   | Orange | #F97316 |
| Profiles     | Green  | #10B981 |
| Data Quality | Red    | #EF4444 |

## Configuration

### Required Environment Variables

```env
GEMINI_API_KEY=your_api_key_here
```

### Optional Configuration

Can be added to `config/reports.php` for:

-   Records per page
-   Chart display preferences
-   Export formats
-   Insight refresh interval

## Next Steps

### To Enable Exports (Future)

1. Create `app/Exports/YouthExport.php`
2. Create export routes in `routes/reports.php`
3. Add export methods to `ReportController`
4. Install Laravel Excel: `composer require maatwebsite/excel`

### To Add Caching (Optimization)

```php
// In ReportGeneratorService
$insights = Cache::remember(
    'report_insights_' . $reportType,
    3600,
    fn() => $this->generateInsights($reportType, $data, $context)
);
```

### To Add Role-Based Access

```php
// In routes/reports.php
->middleware(['can:view-reports'])
```

### To Add Email Reports

1. Create `app/Mail/ReportMail.php`
2. Create scheduled task in `app/Console/Kernel.php`
3. Add export logic for email attachment

## Testing the System

### Manual Test Flow

1. Navigate to `/reports`
2. Click each report category
3. Apply filters and verify data changes
4. Check AI insights generate
5. Verify charts render correctly
6. Test search in profiles report
7. Check pagination works

### Check Logs

```bash
tail -f storage/logs/laravel.log
```

### Test API Connection

```php
php artisan tinker
> $service = new \App\Services\ReportGeneratorService();
> $insights = $service->generateInsights('youth_demographics', [...]);
```

## Common Routes

| Task                | URL                     |
| ------------------- | ----------------------- |
| View all reports    | `/reports`              |
| See demographics    | `/reports/demographics` |
| See leadership      | `/reports/leadership`   |
| See engagement      | `/reports/engagement`   |
| View youth profiles | `/reports/profiles`     |
| Check data quality  | `/reports/data-quality` |

## Troubleshooting

### Reports page shows 500 error

-   Check `/storage/logs/laravel.log`
-   Verify database connection
-   Check routes are registered: `php artisan route:list | grep reports`

### Insights not showing

-   Check `GEMINI_API_KEY` in `.env`
-   Verify API key is valid
-   Check network connectivity
-   Look for API errors in logs

### Charts not rendering

-   Check browser console (F12 - Console tab)
-   Verify Chart.js CDN is accessible
-   Check data format in chart initialization

### Filters not working

-   Verify form submits to correct URL
-   Check controller receives filter parameters
-   Verify database columns exist
-   Check model relationships

## Performance Tips

1. **Add indexes** to frequently filtered columns (barangay_id, created_at)
2. **Implement caching** for AI insights (rarely change)
3. **Paginate** large result sets
4. **Use eager loading** to prevent N+1 queries
5. **Monitor** slow queries in logs

## File Size Summary

| File                       | Size    | Purpose                 |
| -------------------------- | ------- | ----------------------- |
| ReportGeneratorService.php | 8.3 KB  | AI insights & data prep |
| ReportController.php       | 10.6 KB | Report business logic   |
| shell.blade.php            | 5.8 KB  | Shared layout           |
| index.blade.php            | 19.6 KB | Main dashboard          |
| demographics.blade.php     | 11.2 KB | Demographics report     |
| leadership.blade.php       | 8.9 KB  | Leadership report       |
| engagement.blade.php       | 7.7 KB  | Engagement report       |
| profiles.blade.php         | 7.3 KB  | Profiles report         |
| data-quality.blade.php     | 16.4 KB | Data quality report     |

## Success Indicators

✅ All reports accessible at `/reports`
✅ AI insights generating for each report type
✅ Charts rendering with data
✅ Filtering working correctly
✅ Search functionality in profiles
✅ Pagination working
✅ Responsive design on mobile
✅ No console errors

## Support & Documentation

-   Full documentation: `REPORTS_DOCUMENTATION.md`
-   Model documentation: Check model files in `app/Models/`
-   API docs: Google Generative AI documentation
-   Laravel docs: https://laravel.com/docs
-   Chart.js docs: https://www.chartjs.org/docs/latest/

---

**Created**: November 20, 2025
**Status**: ✅ Complete and Ready to Use
