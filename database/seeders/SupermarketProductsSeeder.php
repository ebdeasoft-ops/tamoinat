<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * استيراد منتجات السوبر ماركت من database/seeders/data/products.csv
 * إلى جدول products بأسماء أعمدة مشروع التموينات (product_name, branchs_id, numberofpice ...).
 */
class SupermarketProductsSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/data/products.csv');
        if (!file_exists($csvPath)) {
            $this->command?->error("Products CSV file not found at: {$csvPath}");
            return;
        }

        $this->command?->info("Starting supermarket products seeding from: {$csvPath}");
        $start = microtime(true);

        $file = fopen($csvPath, 'r');
        if (!$file) {
            $this->command?->error("Failed to open CSV file: {$csvPath}");
            return;
        }

        $header = fgetcsv($file);
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);   // BOM
        $col = array_flip($header);
        $get = function (array $row, string $key, $default = null) use ($col) {
            if (!isset($col[$key]) || !array_key_exists($col[$key], $row)) {
                return $default;
            }
            $v = trim((string) $row[$col[$key]]);
            return $v === '' ? $default : $v;
        };
        $num = fn ($v, $d = 0) => is_numeric($v) ? (float) $v : $d;

        $now = now()->toDateTimeString();

        // الفرع والمستخدم الافتراضي (لازم BranchsSeeder و UserSeeder يشتغلوا قبله)
        $branchId = DB::table('branchs')->value('id');
        if (!$branchId) {
            $branchId = DB::table('branchs')->insertGetId([
                'name' => 'الفرع الرئيسي',
                'place' => 'الرياض',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $userId = DB::table('users')->value('id') ?? 1;

        // الفئة الافتراضية
        $groupId = DB::table('products_groups')->value('id');
        if (!$groupId) {
            $groupId = DB::table('products_groups')->insertGetId([
                'group_ar' => 'فئة عامة',
                'group_en' => 'General',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // لو أسعار البيع شاملة الضريبة (config/pos.php) نخزن السعر النهائي اللي العميل بيدفعه
        $pricesIncludeVat = (bool) config('pos.prices_include_vat', true);

        $updateCols = [
            'product_name', 'branchs_id', 'purchasing_price', 'sale_price', 'numberofpice', 'Status',
            'Product_Location', 'Product_Code', 'Added_Value', 'number_of_sales', 'name_en', 'notes', 'unit',
            'minmum_quantity_stock_alart', 'opening_blance', 'average_cost', 'Wholesale_price', 'product_group', 'updated_at',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $batch = [];
        $total = 0;
        $flush = function () use (&$batch, &$total, $updateCols) {
            if (!$batch) {
                return;
            }
            DB::table('products')->upsert($batch, ['id'], $updateCols);
            $total += count($batch);
            $batch = [];
            $this->command?->info("Seeded {$total} products...");
        };

        while (($row = fgetcsv($file)) !== false) {
            $name = $get($row, 'name');
            if ($name === null) {
                continue;
            }

            $salePrice = $num($get($row, 'sale_price'));
            $tax = $num($get($row, 'tax_value'));

            if ($pricesIncludeVat) {
                // السعر النهائي = السعر + الضريبة المسجلة، مع تقريب الكسور الشاذة (2.99 / 3.01 → 3.00)
                $final = round($salePrice + $tax, 2);
                $cents = (int) round(fmod($final, 1.0) * 100);
                if (in_array($cents, [49, 99, 24, 74], true)) {
                    $final += 0.01;
                } elseif (in_array($cents, [51, 1, 26, 76], true)) {
                    $final -= 0.01;
                }
                $salePrice = round($final, 2);
            }

            $batch[] = [
                'id' => is_numeric($get($row, 'id')) ? (int) $get($row, 'id') : null,
                'product_name' => mb_substr($name, 0, 255),
                'branchs_id' => $branchId,
                'purchasing_price' => max(0, $num($get($row, 'purchase_price'))),
                'sale_price' => max(0, $salePrice),
                'numberofpice' => $num($get($row, 'stock_quantity')),
                'Status' => $get($row, 'status', 'active'),
                'user_id' => $userId,
                'deleted_at' => null,
                'Product_Location' => $get($row, 'location', '-'),
                'Product_Code' => $get($row, 'code', ''),
                'Added_Value' => $tax,
                'number_of_sales' => (int) $num($get($row, 'total_sold')),
                'name_en' => mb_substr((string) $get($row, 'name_en', $name), 0, 255),
                'notes' => $get($row, 'notes', '-'),
                'unit' => $get($row, 'unit', 'piece'),
                'minmum_quantity_stock_alart' => (int) $num($get($row, 'low_stock_alert_quantity'), 10),
                'main_product' => 0,
                'refnumber' => null,
                'opening_blance' => $num($get($row, 'opening_balance')),
                'average_cost' => $num($get($row, 'average_cost')),
                'Wholesale_price' => $num($get($row, 'wholesale_price')),
                'photo' => null,
                'products_mix' => 0,
                'product_group' => $groupId,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($batch) >= 1000) {
                $flush();
            }
        }
        $flush();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        fclose($file);

        $elapsed = round(microtime(true) - $start, 2);
        $this->command?->info("Completed seeding {$total} products in {$elapsed} seconds!");
    }
}
