<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$permissions = [];

$directories = [
    resource_path('views'),
    app_path()
];

foreach ($directories as $dir) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && (str_ends_with($file->getFilename(), '.php'))) {
            $content = file_get_contents($file->getPathname());
            
            // Match @can('...') or @can("...") or can('...')
            preg_match_all('/@can\([\'"](.+?)[\'"]\)/', $content, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $match) {
                    $permissions[] = $match;
                }
            }
            
            // Match hasPermissionTo
            preg_match_all('/hasPermissionTo\([\'"](.+?)[\'"]\)/', $content, $matches2);
            if (!empty($matches2[1])) {
                foreach ($matches2[1] as $match) {
                    $permissions[] = $match;
                }
            }
        }
    }
}

$unique = array_unique($permissions);
sort($unique);
file_put_contents('permissions_found.txt', implode(PHP_EOL, $unique));
echo "Found " . count($unique) . " unique permissions.";
