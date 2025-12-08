# CSV Export/Import System

A Laravel-based batch processing system for exporting and importing CSV
files with real-time progress tracking and job management.

## 🚀 Features

-   **Batch CSV Export** -- Export large datasets in chunks using
    Laravel Jobs\
-   **Real-time Progress Tracking** -- Live progress updates during
    export\
-   **Job Management Dashboard** -- View and manage all batch jobs\
-   **File Management** -- Upload, store, and manage CSV files\
-   **Authentication System** -- Secure access with email verification\
-   **Activity Logging** -- Track user activity in the system

## 📁 Project Structure

    app/
    ├── Http/Controllers/
    │   ├── ExportCSVController.php        # Handles CSV export operations
    │   ├── BatchProgressController.php    # Manages batch progress & downloads
    │   ├── ImportCSVController.php        # Handles CSV import operations
    │   └── FileEntryController.php        # Manages file entries
    ├── Models/
    │   ├── File.php                       # File model with soft deletes
    │   ├── FileEntry.php                  # File entry model
    │   └── JobBatch.php                   # Job batch tracking model
    └── Jobs/
        └── ExportCSV.php                  # Batch job for CSV export
        └── ImportCSV.php                  # Batch job for CSV export

## 🛠️ Installation

### 1. Clone the repository

``` bash
git clone https://github.com/kroos/laravel-interview.git
cd laravel-interview
```

### 2. Install dependencies

``` bash
composer install
npm install
npm run build
```

### 3. Configure environment

``` bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup database

-   Update `.env` with your database credentials\
-   Run migrations:

``` bash
php artisan migrate
```

### 5. Configure queue (required for batch processing)

-   Set `QUEUE_CONNECTION` to `database` or `redis` in `.env`
-   Start queue worker:

``` bash
php artisan queue:work
```

### 6. Start the application

``` bash
php artisan serve
```

## 🔧 Configuration

### Database Tables

-   **files** -- uploaded file metadata\
-   **file_entries** -- individual CSV records\
-   **job_batches** -- batch job progress tracking\
-   **activity_logs** -- user activity logs

### Routes

Defined inside `routes/auth.php` and `routes/batch.php`:

-   Authentication routes with middleware\
-   Batch processing routes\
-   Resource controllers for exports/imports

## 📊 Usage

### 1. Exporting CSV Data

#### a. Navigate to Export Page

Visit:

    /exportcsvs/create

#### b. Filter Data

-   Optional filtering by `Industry_code_NZSIOC`
-   System processes data in **300-record chunks**

#### c. Monitor Progress

-   Redirected to progress page\
-   Real-time progress via AJAX\
-   Download link shown when complete

## ⚙️ Batch Processing

Uses **Laravel Bus Batching**:

``` php
$batch = Bus::batch($dat)
    ->name('Export CSV Industry_code_NZSIOC => ' . $request->Industry_code_NZSIOC)
    ->dispatch();
```

### Progress Tracking

-   Live updates via:

```{=html}
<!-- -->
```
    GET /getProgress

-   View all jobs:

```{=html}
<!-- -->
```
    GET /getJobBatchTable

-   Temporary files auto-cleaned after download

## 🔐 Security Features

-   Laravel authentication\
-   Email verification\
-   Protected routes with:
    -   `auth`
    -   `verified`
    -   `password.confirm`
-   CSRF protection\
-   Input validation

## 📈 Performance Optimizations

-   **Chunk Processing** -- 300 rows each\
-   **Queued Jobs** -- smooth UI\
-   **Batch Tracking** -- database-backed\
-   **Streamed CSV** -- efficient file generation

## 🔄 API Endpoints

### Batch Progress

    GET /getProgress
    GET /getJobBatchTable

### File Operations

    GET /progress/downloadCSV
    DELETE /file_entries/{fileEntry}

### Resource Routes

``` php
Route::resources([
    'importcsvs' => ImportCSVController::class,
    'exportcsvs' => ExportCSVController::class,
    'file_entries' => FileEntryController::class,
]);
```

## 🗂️ Models

### **File Model**

-   Soft deletes\
-   Mutators for lowercase\
-   HasMany relationship with FileEntry

### **JobBatch Model**

-   Tracks progress\
-   Stores job statistics

### **FileEntry Model**

-   Represents individual CSV rows\
-   Belongs to File

## 🚦 Error Handling

-   Try/catch in controllers\
-   Logging via Laravel Log\
-   Redirects with user-friendly messages\
-   Session-based temporary data

## 📝 Code Examples

### Creating Export Batch

``` php
$batch = Bus::batch($dat)
    ->name('Export CSV Industry_code_NZSIOC => ' . $request->Industry_code_NZSIOC)
    ->dispatch();

session(['lastBatchId' => $batch->id]);
```

### Progress Tracking

``` php
public function getProgress(Request $request): JsonResponse
{
    $batchId = $request->id ?? session('lastBatchId');
    $batch = Bus::findBatch($batchId);

    return response()->json([
        'processedJobs' => $batch->processedJobs(),
        'totalJobs'     => $batch->totalJobs,
        'progress'      => $batch->progress()
    ]);
}
```

## 🧪 Testing

Run all tests:

``` bash
php artisan test
```

## 📄 License

This project is **proprietary software**.\
All rights reserved.

## 🤝 Support

For issues or feature requests, open an **Issue** in this repository.
