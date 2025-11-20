# Reports Generation System

## Overview

The Reports Generation System is a comprehensive analytics platform integrated into the Youth Profiling System. It provides AI-powered insights combined with advanced data visualization and filtering capabilities. The system includes 5 major report categories with customizable views and export options.

## Architecture

### File Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Reports/
│           └── ReportController.php         # Main report controller
├── Services/
│   └── ReportGeneratorService.php           # AI insights & data preparation
│
routes/
└── reports.php                              # Report routes

resources/views/
└── reports/
    ├── shell.blade.php                      # Shared report layout
    ├── index.blade.php                      # Reports dashboard
    ├── demographics.blade.php               # Demographics report
    ├── leadership.blade.php                 # Leadership report
    ├── engagement.blade.php                 # Engagement report
    ├── profiles.blade.php                   # Youth profiles report
    └── data-quality.blade.php               # Data quality report
```

## Components

### 1. ReportController

**File**: `app/Http/Controllers/Reports/ReportController.php`

Handles all report generation and data aggregation:

#### Methods:

-   `index()` - Main reports dashboard with overview statistics
-   `demographics()` - Youth population analysis by age, education, income, status
-   `leadership()` - SK Councils and youth leadership structure
-   `engagement()` - Event participation and youth engagement metrics
-   `profiles()` - Individual youth profile records with search/filter
-   `dataQuality()` - Data completeness and accuracy metrics

#### Features:

-   Advanced filtering (barangay, date range, status)
-   Pagination for large datasets
-   Data aggregation and grouping
-   Statistical calculations

### 2. ReportGeneratorService

**File**: `app/Services/ReportGeneratorService.php`

Provides AI-powered insights using Google Gemini API:

#### Key Methods:

-   `generateInsights()` - Creates AI-powered analysis of report data
-   `prepareReportData()` - Formats raw data for insight generation
-   Data preparation methods for each report type

#### Features:

-   REST API integration with Gemini API
-   Automatic fallback to default insights if API fails
-   Report-type specific prompt templates
-   Error handling and logging

### 3. Views

#### Shell Layout (`shell.blade.php`)

Provides shared report template with:

-   Header with title and export options
-   Filter section
-   AI Insights card
-   Statistics grid
-   Main content area

#### Index Dashboard (`index.blade.php`)

Main entry point featuring:

-   Quick stats overview (6 KPI cards)
-   Report category cards with descriptions
-   Feature overview section
-   Color-coded category sections (Blue, Purple, Orange, Green, Red)

#### Demographics Report (`demographics.blade.php`)

Analyzes youth population with:

-   Total youth, out-of-school count, sex distribution
-   Age group distribution chart
-   Sex distribution pie chart
-   Status distribution chart
-   Education status chart
-   Household income distribution
-   Filterable youth records table
-   Chart.js visualizations

#### Leadership Report (`leadership.blade.php`)

Reviews youth governance with:

-   SK Councils count and status
-   Youth leaders inventory
-   Organizations overview
-   Council status doughnut chart
-   Leadership positions bar chart
-   SK Councils data table
-   Organizations data table

#### Engagement Report (`engagement.blade.php`)

Tracks youth program participation with:

-   Total events and participants count
-   Participation rate and average per event
-   Events by barangay chart
-   Participation trend line chart
-   Filterable events list
-   Color-coded event status badges

#### Youth Profiles Report (`profiles.blade.php`)

Individual record access with:

-   Total records and completeness metrics
-   Search functionality
-   Advanced filtering (barangay, status)
-   Filterable profiles table
-   Contact information links
-   Export options (PDF, Excel, CSV buttons)
-   Pagination support

#### Data Quality Report (`data-quality.blade.php`)

Data completeness monitoring with:

-   Data quality score and metrics
-   Overall quality gauge visualization
-   Data completeness chart
-   Issues requiring attention section
-   Recommendations section
-   Detailed metrics table
-   Next steps action items
-   AI-powered recommendations

## Routes

**Prefix**: `/reports`
**Middleware**: `auth`, `verified`

```php
GET    /                  → reports.index          (Main dashboard)
GET    /demographics      → reports.demographics   (Demographics report)
GET    /leadership        → reports.leadership     (Leadership report)
GET    /engagement        → reports.engagement     (Engagement report)
GET    /profiles          → reports.profiles       (Youth profiles)
GET    /data-quality      → reports.data-quality   (Data quality)
```

**Access**: Available to authenticated users via `/reports` route

## Features

### 1. AI-Powered Insights

-   Automatic analysis of report data using Google Gemini API
-   Context-aware prompt templates for each report type
-   Fallback to default insights if API unavailable
-   Findings, trends, and recommendations in insights panel

### 2. Advanced Filtering

-   **Demographics**: Filter by barangay, date range
-   **Leadership**: Filter by barangay
-   **Engagement**: Filter by barangay, event date range
-   **Profiles**: Filter by barangay, status, search by name/contact
-   **Data Quality**: Municipality-wide analysis

### 3. Data Visualization

-   Chart.js integration for dynamic charts
-   Bar charts, pie charts, doughnut charts, line charts
-   Color-coded visualizations per report type
-   Responsive chart layouts

### 4. Export Capabilities

-   Ready-to-implement export buttons (PDF, Excel, CSV)
-   Currently shown as UI elements
-   Can be connected to export controllers

### 5. Real-Time Statistics

-   Live data aggregation from database
-   Percentage calculations
-   Age group distribution
-   Status breakdowns
-   Income level analysis

## Data Models Used

The reports system integrates with these existing models:

-   **Youth** - Individual youth records
-   **Barangay** - Barangay administrative divisions
-   **SKCouncil** - SK Council organizations
-   **BarangayEvent** - Event records
-   **Organization** - Youth organizations
-   **User** - System users

## Styling & Design

### Color Scheme

-   **Demographics**: Blue (#3B82F6)
-   **Leadership**: Purple (#A855F7)
-   **Engagement**: Orange (#F97316)
-   **Youth Profiles**: Green (#10B981)
-   **Data Quality**: Red (#EF4444)

### Component Classes

-   Gradient headers: `bg-gradient-to-br from-[color]-50 to-[color]-100`
-   Border accents: `border-l-4 border-[color]-500`
-   Cards: Rounded corners, shadows, hover effects
-   Tables: Alternating rows, hover highlighting
-   Badges: Pill-shaped status indicators

## Usage Examples

### Access Reports

```
https://your-domain.com/reports
```

### View Specific Report

```
https://your-domain.com/reports/demographics
https://your-domain.com/reports/leadership
https://your-domain.com/reports/engagement
https://your-domain.com/reports/profiles
https://your-domain.com/reports/data-quality
```

### Filter Reports

```
https://your-domain.com/reports/demographics?barangay=1&start_date=2025-01-01&end_date=2025-12-31
https://your-domain.com/reports/profiles?barangay=1&status=active&search=John
https://your-domain.com/reports/engagement?barangay=2&start_date=2025-11-01
```

## Implementation Details

### Age Grouping

Youth are grouped into age ranges:

-   13-15 years
-   16-18 years
-   19-21 years
-   22-25 years
-   26-30 years

### Data Accuracy

-   Complete records: Have first_name, last_name, date_of_birth, barangay_id
-   Incomplete records: Missing one or more required fields
-   Data quality score: Percentage of complete records

### AI Insights Generation

Each report type has a custom prompt template:

1. **Demographics**: Population analysis, trends, resource allocation recommendations
2. **Leadership**: Coverage analysis, representation gaps, engagement opportunities
3. **Engagement**: Event effectiveness, participation patterns, program optimization
4. **Profiles**: Population characteristics, support needs, targeted program recommendations
5. **Data Quality**: Completeness assessment, critical gaps, improvement priorities

## Future Enhancements

### Planned Features

1. **Export functionality** - PDF, Excel, CSV download
2. **Scheduled reports** - Automated report generation
3. **Email delivery** - Send reports via email
4. **Custom dashboards** - User-defined report combinations
5. **Trend analysis** - Historical data comparison
6. **Advanced filtering** - More complex filter combinations
7. **User preferences** - Save favorite reports and filters
8. **Report sharing** - Share reports with colleagues
9. **Audit logging** - Track report access and exports
10. **Mobile optimization** - Mobile-friendly report views

## API Integration

### Google Gemini API

-   **Endpoint**: `generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent`
-   **Model**: `gemini-2.0-flash`
-   **Auth**: API key via `x-goog-api-key` header
-   **Fallback**: Default insights if API fails

## Performance Considerations

1. **Database Queries**: Optimized with groupBy and selectRaw
2. **Pagination**: Youth profiles use pagination (50 records per page)
3. **Caching**: Can be added to AI insights for repeated reports
4. **Chart.js**: Lazy loaded via CDN
5. **Table Display**: Limited to 20-50 records with indicator of total

## Security

-   All routes require authentication (`auth` middleware)
-   Email verification required (`verified` middleware)
-   Database queries use Eloquent ORM for SQL injection prevention
-   User role-based access can be added as needed

## Testing

### Manual Testing Checklist

-   [ ] Access reports dashboard
-   [ ] View each report type
-   [ ] Apply filters and verify data updates
-   [ ] Verify AI insights generate
-   [ ] Check pagination on profiles report
-   [ ] Verify chart rendering
-   [ ] Test search functionality
-   [ ] Check responsive design on mobile

### Example Test Data

Reports work with existing youth, barangay, SK Council, event, and organization data in the database.

## Troubleshooting

### AI Insights Not Showing

1. Verify `GEMINI_API_KEY` is set in `.env`
2. Check API key has proper Gemini API access
3. Check application logs for API errors
4. Verify network connectivity to Google API

### Charts Not Rendering

1. Verify Chart.js CDN is accessible
2. Check browser console for JavaScript errors
3. Verify data is being passed to charts
4. Check for JavaScript syntax errors in chart code

### Filtering Not Working

1. Verify form parameter names match controller expected names
2. Check database column names match filter conditions
3. Verify relationships are correctly defined in models

### Performance Issues

1. Check database query performance with `EXPLAIN`
2. Consider adding indexes on filtered columns
3. Add query caching for repeated reports
4. Limit table display sizes with pagination

## Support

For issues or questions about the reports system:

1. Check the logs: `storage/logs/laravel.log`
2. Review AI insights prompt templates in `ReportGeneratorService.php`
3. Verify database relationships in models
4. Test routes directly to ensure they're registered

## Version History

### v1.0.0 (Current)

-   Initial release with 5 report categories
-   AI-powered insights integration
-   Chart.js visualizations
-   Advanced filtering
-   Responsive design
-   Export UI (ready for implementation)
