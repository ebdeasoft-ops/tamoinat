<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupermarketProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/data/products.csv');

        // Check fallback location if not found in database_path
        if (!file_exists($csvPath)) {
            $altPath = 'C:/Users/DELL/Downloads/products_202609141300.csv';
            if (file_exists($altPath)) {
                $csvPath = $altPath;
            } else {
                $this->command?->error("Products CSV file not found at: {$csvPath}");
                return;
            }
        }

        $this->command?->info("Starting supermarket products seeding from: {$csvPath}");

        $start = microtime(true);
        $file = fopen($csvPath, 'r');
        if (!$file) {
            $this->command?->error("Failed to open CSV file: {$csvPath}");
            return;
        }

        $header = fgetcsv($file);
        $colMap = array_flip($header);

        $now = now()->toDateTimeString();
        $batch = [];
        $batchSize = 1000;
        $totalInserted = 0;

        // Ensure default branch exists
        $defaultBranchId = DB::table('branches')->value('id');
        if (!$defaultBranchId) {
            $defaultBranchId = DB::table('branches')->insertGetId([
                'name' => 'الفرع الرئيسي',
                'location' => 'الرياض',
                'name_en' => 'Main Branch',
                'type' => 'main',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Ensure default product group exists
        $defaultGroupId = DB::table('productgroup')->value('id');
        if (!$defaultGroupId) {
            $defaultGroupId = DB::table('productgroup')->insertGetId([
                'group_ar' => 'فئة عامة',
                'group_en' => 'General Group',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        while (($row = fgetcsv($file)) !== false) {
            if (empty($row) || !isset($row[$colMap['name']])) {
                continue;
            }

            $id = !empty($row[$colMap['id']]) ? (int)$row[$colMap['id']] : null;
            $name = trim($row[$colMap['name']]);
            if ($name === '') {
                continue;
            }

            $branchId = !empty($row[$colMap['branch_id']]) ? (int)$row[$colMap['branch_id']] : $defaultBranchId;
            $purchasePrice = is_numeric($row[$colMap['purchase_price']] ?? null) ? (float)$row[$colMap['purchase_price']] : 0.0;
            $salePrice = is_numeric($row[$colMap['sale_price']] ?? null) ? (float)$row[$colMap['sale_price']] : 0.0;
            $stockQuantity = is_numeric($row[$colMap['stock_quantity']] ?? null) ? (float)$row[$colMap['stock_quantity']] : 0.0;
            $status = !empty($row[$colMap['status']]) ? trim($row[$colMap['status']]) : 'active';
            $code = !empty($row[$colMap['code']]) ? trim($row[$colMap['code']]) : null;
            $taxValue = is_numeric($row[$colMap['tax_value']] ?? null) ? (float)$row[$colMap['tax_value']] : 0.0;
            $totalSold = is_numeric($row[$colMap['total_sold']] ?? null) ? (int)$row[$colMap['total_sold']] : 0;
            $nameEn = !empty($row[$colMap['name_en']]) ? trim($row[$colMap['name_en']]) : null;
            $notes = !empty($row[$colMap['notes']]) ? trim($row[$colMap['notes']]) : null;
            $unit = !empty($row[$colMap['unit']]) ? trim($row[$colMap['unit']]) : 'piece';
            $lowStockAlert = is_numeric($row[$colMap['low_stock_alert_quantity']] ?? null) ? (int)$row[$colMap['low_stock_alert_quantity']] : 10;
            $location = !empty($row[$colMap['location']]) ? trim($row[$colMap['location']]) : null;
            $openingBalance = is_numeric($row[$colMap['opening_balance']] ?? null) ? (float)$row[$colMap['opening_balance']] : 0.0;
            $averageCost = is_numeric($row[$colMap['average_cost']] ?? null) ? (float)$row[$colMap['average_cost']] : 0.0;
            $wholesalePrice = is_numeric($row[$colMap['wholesale_price']] ?? null) ? (float)$row[$colMap['wholesale_price']] : 0.0;
            $groupId = !empty($row[$colMap['product_group_id']]) ? (int)$row[$colMap['product_group_id']] : $defaultGroupId;

            $priceWithTax = $taxValue > 0 ? round($salePrice + $taxValue, 2) : round($salePrice * 1.15, 2);
            $cents = (int) round(fmod($priceWithTax, 1.0) * 100);
            if ($cents < 0) $cents += 100;
            if (in_array($cents, [49, 99, 24, 74])) $priceWithTax += 0.01;
            elseif (in_array($cents, [51, 1, 26, 76])) $priceWithTax -= 0.01;
            $priceWithTax = round($priceWithTax, 2);

            $record = [
                'id' => $id,
                'name' => $name,
                'branch_id' => $branchId,
                'purchase_price' => $purchasePrice,
                'sale_price' => $salePrice,
                'price_with_tax' => $priceWithTax,
                'stock_quantity' => $stockQuantity,
                'status' => $status,
                'created_by' => null,
                'deleted_at' => null,
                'location' => $location,
                'code' => $code,
                'tax_value' => $taxValue,
                'total_sold' => $totalSold,
                'name_en' => $nameEn,
                'notes' => $notes,
                'unit' => $unit,
                'low_stock_alert_quantity' => $lowStockAlert,
                'parent_product_id' => null,
                'reference_number' => null,
                'opening_balance' => $openingBalance,
                'average_cost' => $averageCost,
                'wholesale_price' => $wholesalePrice,
                'photo' => null,
                'product_mix_id' => null,
                'product_group_id' => $groupId,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $batch[] = $record;

            if (count($batch) >= $batchSize) {
                DB::table('products')->upsert($batch, ['id'], [
                    'name', 'branch_id', 'purchase_price', 'sale_price', 'price_with_tax', 'stock_quantity',
                    'status', 'location', 'code', 'tax_value', 'total_sold', 'name_en',
                    'notes', 'unit', 'low_stock_alert_quantity', 'opening_balance',
                    'average_cost', 'wholesale_price', 'product_group_id', 'updated_at'
                ]);
                $totalInserted += count($batch);
                $batch = [];
                $this->command?->info("Seeded {$totalInserted} products...");
            }
        }

        if (!empty($batch)) {
            DB::table('products')->upsert($batch, ['id'], [
                'name', 'branch_id', 'purchase_price', 'sale_price', 'price_with_tax', 'stock_quantity',
                'status', 'location', 'code', 'tax_value', 'total_sold', 'name_en',
                'notes', 'unit', 'low_stock_alert_quantity', 'opening_balance',
                'average_cost', 'wholesale_price', 'product_group_id', 'updated_at'
            ]);
            $totalInserted += count($batch);
            $this->command?->info("Seeded {$totalInserted} products...");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        fclose($file);

        $elapsed = round(microtime(true) - $start, 2);
        $this->command?->info("Completed seeding {$totalInserted} products in {$elapsed} seconds!");
    }
}
