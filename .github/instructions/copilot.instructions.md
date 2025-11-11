# Copilot Instructions for Laravel Blade Projects

**Project**: Any Laravel 10+ application using Blade templates
**Architecture**: Root shell + role-based shells, role-specific controllers, multi-role routing

---

## Core Architecture

### Blade Shell Pattern

-   **Root Shell** (`resources/views/shell.blade.php`):

    -   Global layout: `<head>`, global scripts/styles, notifications, modals, footer
    -   Shared by all roles or features

-   **Role or Feature Shells** (`resources/views/{role}/shell.blade.php`):

    -   Extend or include the root shell
    -   Add role-specific or feature-specific UI elements: sidebars, navigation, headers
    -   Wrap all related views

-   **Views**:

    -   Views are rendered **inside their respective shells**
    -   Example structure:

        -   `resources/views/{role}/feature/view.blade.php`
        -   `resources/views/auth/login.blade.php`

**Hierarchy Example**:

```
Root Shell
 └─ Role/Feature Shell
      └─ Individual View
```

---

### Controller Organization

-   Controllers can be **grouped by role or feature** in `app/Http/Controllers/`

    -   Example: `Priest/`, `Secretary/`, `Admin/`, `Auth/`

-   Controllers return views in their corresponding folder:

```php
return view('{role}.feature.view', compact('data'));
```

-   Benefits:

    -   Clear separation of concerns
    -   Role or feature logic isolation
    -   Easier maintenance & scalability

---

### Routes & Middleware

-   Routes are **grouped by role or feature** in `routes/` (e.g., `priest.php`, `secretary.php`, `web.php`)
-   Middleware ensures access control per role or feature:

```php
Route::middleware(['auth:role'])->group(function () {
    Route::get('/role/dashboard', [DashboardController::class, 'index']);
});
```

---

## Blade Coding Conventions

### Templates

-   Use **shells** as wrappers, not components/layouts unless needed
-   Include Blade directives: `@if`, `@foreach`, `@include`, `@csrf`, etc.
-   Avoid inline PHP; use `{{ }}` or `{!! !!}` for output
-   Name views clearly by folder and feature: `{role}/feature/view.blade.php`

### Forms

-   Use **POST** with `@csrf` for form security
-   Validation errors displayed with `@error('field')`

### Components

-   Minimal use; shells replace global components/layouts
-   Reusable UI elements can be included via `@include('partials.button')` if needed

---

## Critical Integration Points

### Authentication & Authorization

-   Login, registration, and password management handled by **Auth controllers**
-   Role-based guards implemented via middleware
-   Example:

```php
Route::middleware(['auth:role'])->group(function () {
    Route::get('/role/dashboard', [DashboardController::class, 'index']);
});
```

### Role/Feature-Based Loading

-   Controllers, routes, and views should be **strictly role/feature-specific**
-   Each shell ensures isolation at the UI level
-   Root shell provides shared structure and global assets

---

## File Naming & Locations

-   **Blade Views**: `resources/views/{role}/{feature}/`
-   **Role/Feature Shells**: `resources/views/{role}/shell.blade.php`
-   **Root Shell**: `resources/views/shell.blade.php`
-   **Controllers**: `app/Http/Controllers/{RoleOrFeature}/`
-   **Routes**: `routes/{role_or_feature}.php`

---

## When Adding Features

1. **New Role/Feature View**: Create inside the role/feature folder; render within the shell
2. **New Controller**: Place in the role/feature folder; methods return corresponding views
3. **New Route**: Add to role/feature-specific route file and protect with middleware
4. **Form & Validation**: Use `@csrf`, `old()`, and `@error()` for secure forms
5. **Shell Modifications**: Only update root shell for global changes; shells for role/feature-specific UI

---

## Styling & UI

-   **Framework**: Tailwind CSS or any CSS framework
-   **Scripts**: Place JS globally in root shell; role/feature-specific scripts in shells if needed
-   Use Blade syntax for conditional classes:

```blade
<div class="@if($active) text-blue-500 @endif">
```

---

## Testing & Maintenance

-   Each role/feature controller can have dedicated PHPUnit tests if needed
-   Blade views tested via browser or Dusk for end-to-end validation
-   Maintain consistent naming conventions for files and folders for clarity
