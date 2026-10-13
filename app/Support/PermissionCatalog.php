<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * يرتّب الصلاحيات في أقسام (حسب config/permission_groups.php) ويعطي اسم كل صلاحية حسب لغة الواجهة.
 */
class PermissionCatalog
{
    public static function isArabic(): bool
    {
        return app()->getLocale() === 'ar';
    }

    /** اسم الصلاحية حسب اللغة الحالية */
    public static function label($permission): string
    {
        if (static::isArabic()) {
            return trim((string) ($permission->name_ar ?? '')) ?: (string) $permission->name;
        }
        $overrides = config('permission_groups.labels_en', []);
        return $overrides[$permission->name] ?? Str::ucfirst(str_replace('_', ' ', (string) $permission->name));
    }

    /**
     * @param  Collection  $permissions  كل الصلاحيات (Permission models)
     * @return array  [ ['key','title','icon','items' => [ ['permission','label','main','requires' => [ids]] ]] ]
     */
    public static function groups(Collection $permissions): array
    {
        $lang = static::isArabic() ? 'ar' : 'en';
        $byName = $permissions->keyBy('name');
        $placed = [];
        $result = [];

        foreach (config('permission_groups.groups', []) as $key => $group) {
            // صلاحيات إظهار القسم الموجودة فعلاً
            $mainIds = [];
            foreach ($group['main'] ?? [] as $name) {
                if (isset($byName[$name])) {
                    $mainIds[] = $byName[$name]->id;
                }
            }

            $items = [];
            foreach (array_merge($group['main'] ?? [], $group['items'] ?? []) as $name) {
                $p = $byName[$name] ?? null;
                if (!$p || isset($placed[$p->id])) {
                    continue;      // غير موجودة، أو ظهرت في قسم سابق (مثل "التقارير")
                }
                $placed[$p->id] = true;
                $mainPos = array_search($p->id, $mainIds, true);
                $items[] = [
                    'permission' => $p,
                    'label'      => static::label($p),
                    'main'       => $mainPos !== false,
                    // اختيار أي صلاحية داخل القسم يحدد صلاحيات إظهار القسم تلقائياً
                    // (وصلاحية إظهار القسم الفرعي تحتاج صلاحية القسم الأب، مثل: تقارير المبيعات ← التقارير)
                    'requires'   => $mainPos === false ? $mainIds : array_slice($mainIds, 0, $mainPos),
                ];
            }

            if ($items) {
                $result[] = [
                    'key'   => $key,
                    'title' => $group['title'][$lang] ?? $key,
                    'icon'  => $group['icon'] ?? 'fa-folder',
                    'items' => $items,
                ];
            }
        }

        // أي صلاحية جديدة غير مصنفة
        $others = $permissions->reject(fn ($p) => isset($placed[$p->id]));
        if ($others->isNotEmpty()) {
            $result[] = [
                'key'   => 'other',
                'title' => static::isArabic() ? 'صلاحيات أخرى' : 'Other permissions',
                'icon'  => 'fa-th-large',
                'items' => $others->map(fn ($p) => [
                    'permission' => $p,
                    'label'      => static::label($p),
                    'main'       => false,
                    'requires'   => [],
                ])->values()->all(),
            ];
        }

        return $result;
    }
}
