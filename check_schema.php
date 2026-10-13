<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$models = [];
foreach (glob(app_path('Models/*.php')) as $file) {
    $class = 'App\\Models\\' . basename($file, '.php');
    if (class_exists($class) && is_subclass_of($class, 'Illuminate\Database\Eloquent\Model')) {
        try {
            $model = new $class;
            $table = $model->getTable();
            $fillable = $model->getFillable();
            if (empty($fillable)) continue;
            $columns = Illuminate\Support\Facades\Schema::getColumnListing($table);
            
            // Allow checking for 'TaxNumber' in fillable by normalizing them.
            // But let's just do exact match.
            $missing = array_diff($fillable, $columns);
            
            if (!empty($missing)) {
                echo "Table: $table (Model: " . basename($file) . ")\n";
                echo "Missing in DB/Migration: " . implode(', ', $missing) . "\n\n";
            }
        } catch (\Exception $e) {
            echo "Error with $class: " . $e->getMessage() . "\n";
        }
    }
}
echo "Check complete.\n";
