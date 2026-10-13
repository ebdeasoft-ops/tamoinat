<?php

namespace App\View;

use Illuminate\Support\Facades\Log;
use Illuminate\View\FileViewFinder;
use InvalidArgumentException;

/**
 * لو طُلب view باسمه القديم (قبل إعادة التنظيم) يتم تحويله تلقائياً للاسم الجديد
 * مع تسجيل تحذير في اللوج حتى يتم تحديث الاستدعاء.
 */
class LegacyViewFinder extends FileViewFinder
{
    protected ?array $legacyMap = null;

    public function find($name)
    {
        try {
            return parent::find($name);
        } catch (InvalidArgumentException $e) {
            $this->legacyMap ??= require __DIR__ . '/legacy_view_map.php';
            $key = str_replace('/', '.', $name);
            if (isset($this->legacyMap[$key])) {
                Log::warning("[views] استدعاء باسم قديم: '{$name}' -> استخدم '{$this->legacyMap[$key]}'");
                return parent::find($this->legacyMap[$key]);
            }
            throw $e;
        }
    }
}