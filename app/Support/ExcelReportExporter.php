<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use ZipArchive;

/**
 * تصدير التقارير إلى Excel مباشرة من نموذج البحث — بدون فتح صفحة العرض/الطباعة.
 *
 * الفكرة: عند إرسال نموذج التقرير مع الحقل _excel=1 يعمل الكنترولر كالمعتاد،
 * وقبل أن تُرسم صفحة الـ Blade (وهي الجزء الثقيل مع البيانات الكبيرة)
 * نأخذ البيانات المرسلة للصفحة ونكتبها مباشرة في ملف xlsx سطراً بسطر.
 */
class ExcelReportExporter
{
    public const PARAM = '_excel';

    /** كنترولرز كل صفحاتها تقارير */
    public const CONTROLLERS = [
        'ReportController',
        'ManufacturingReportController',
    ];

    /** صفحات تقارير منفردة في كنترولرز أخرى */
    public const ACTIONS = [
        'LeaveController@leaveBalanceReport',
        'ProductsDamageController@index',
        'ProductsDamageController@show',
        'ProductMovementAnotherBranchController@productSummaryReport',
        'ProductMovementAnotherBranchController@receivedReport',
        'ProductMovementAnotherBranchController@sentReport',
        'AcountesController@Statement_of_Changes_in_Equity_Report',
        'AcountesController@cashFlowStatement',
        'AcountesController@cashFlowStatementSearch',
        'AcountesController@changesInEquity',
        'CredittransactionsController@budgetsheet',
        'CredittransactionsController@general_budget_search',
    ];

    /** أعمدة لا فائدة منها في Excel (بيانات زاتكا الخام، كلمات مرور ...) */
    protected const SKIP_COLUMNS = '/(xml|qr_|hash|password|remember_token|two_factor|photo|signature|uuid|^pivot$)/i';

    /** أعمدة المفاتيح التي نضيف بجانبها الاسم المقروء */
    protected const LOOKUPS = [
        'customer_id'  => ['customers', 'name', 'العميل', 'Customer'],
        'user_id'      => ['users', 'name', 'المستخدم', 'User'],
        'branch_id'    => ['branchs', 'name', 'الفرع', 'Branch'],
        'branchs_id'   => ['branchs', 'name', 'الفرع', 'Branch'],
        'product_id'   => ['products', 'product_name', 'المنتج', 'Product'],
        'supplier_id'  => ['suplliers', 'name', 'المورد', 'Supplier'],
        'supllier_id'  => ['suplliers', 'name', 'المورد', 'Supplier'],
    ];

    protected const LABELS_AR = [
        'id' => 'الرقم', 'created_at' => 'التاريخ', 'updated_at' => 'آخر تعديل',
        'Price' => 'السعر', 'price' => 'السعر', 'discount' => 'الخصم', 'Pay' => 'طريقة الدفع',
        'quantity' => 'الكمية', 'product_name' => 'المنتج', 'name' => 'الاسم', 'phone' => 'الجوال',
        'Unit_Price' => 'سعر الوحدة', 'Added_Value' => 'الضريبة', 'invoice_id' => 'رقم الفاتورة',
        'note' => 'ملاحظات', 'notes' => 'ملاحظات', 'status' => 'الحالة', 'save' => 'محفوظة',
        'value' => 'القيمة', 'amount' => 'المبلغ', 'total' => 'الإجمالي', 'unit' => 'الوحدة',
        'Discount_Value' => 'قيمة الخصم', 'tax_rate' => 'نسبة الضريبة', 'email' => 'البريد',
        'Balance' => 'الرصيد', 'sale_price' => 'سعر البيع', 'purchasing_price' => 'سعر الشراء',
        'numberofpice' => 'الكمية المتاحة', 'Product_Code' => 'كود المنتج', 'refnumber' => 'الرقم المرجعي',
        'cashamount' => 'نقدي', 'bankamount' => 'شبكة', 'creaditamount' => 'آجل',
        'return_quantity' => 'الكمية المرتجعة', 'return_Unit_Price' => 'سعر الوحدة المرتجعة',
        'debit' => 'مدين', 'credit' => 'دائن', 'description' => 'البيان', 'date' => 'التاريخ',
    ];

    protected const PAY_AR = [
        'cash' => 'نقدي', 'shabka' => 'شبكة', 'credit' => 'آجل', 'bank_transfer' => 'تحويل بنكي',
    ];

    protected static bool $handled = false;
    protected static ?string $mainView = null;
    protected static array $lookupCache = [];

    // ------------------------------------------------------------------ تسجيل

    public static function register(): void
    {
        View::composer('*', function ($view) {
            static::$mainView ??= $view->name();   // أول view يُرسم = الصفحة الرئيسية
            // يعمل التصدير أياً كان الكنترولر الذي يستقبل نموذج التقرير
            if (static::$handled || !request()->filled(static::PARAM)) {
                return;
            }
            static::$handled = true;   // نتعامل فقط مع الصفحة الرئيسية (أول view يُرسم)

            $sheets = static::extractSheets($view->getData());
            if (!$sheets) {
                return;                // لا توجد بيانات جدولية -> تُعرض الصفحة كالمعتاد
            }
            throw new HttpResponseException(static::download($sheets, static::reportTitle($view->name())));
        });
    }

    /** هل الصفحة الحالية صفحة تقرير؟ (لإظهار زر Excel) */
    public static function isReportPage(): bool
    {
        return static::enabledForCurrentRoute()
            || (bool) preg_match('/^(reports|manufacturing_reports|manufacturing\.reports)\./', (string) static::$mainView);
    }

    public static function enabledForCurrentRoute(): bool
    {
        $route = request()->route();
        if (!$route) {
            return false;
        }
        $action = class_basename((string) $route->getActionName());
        $controller = explode('@', $action)[0];

        return in_array($controller, static::CONTROLLERS, true) || in_array($action, static::ACTIONS, true);
    }

    // ------------------------------------------------------------------ استخراج البيانات

    /** يحوّل بيانات الصفحة إلى أوراق: [اسم => [صفوف مصفوفات]] */
    protected static function extractSheets(array $data): array
    {
        $sheets = [];
        foreach ($data as $key => $value) {
            if (in_array($key, ['__env', 'app', 'errors'], true)) {
                continue;
            }
            static::walk($value, (string) $key, $sheets, 0);
        }
        // استبعاد القوائم التي لا تحتوي صفوفاً قابلة للتصدير
        return array_filter($sheets, function ($rows) {
            foreach (array_slice($rows, 0, 50) as $r) {
                if (static::rowToArray($r)) {
                    return true;
                }
            }
            return false;
        });
    }

    protected static function walk($value, string $name, array &$sheets, int $depth): void
    {
        if ($depth > 3 || $value === null || is_scalar($value)) {
            return;
        }
        if ($value instanceof Paginator) {
            $value = collect($value->items());
        }
        if ($value instanceof Model) {
            $value = collect([$value]);
        }
        if ($value instanceof Collection) {
            $value = $value->all();
        }
        if (!is_array($value) || !$value) {
            return;
        }

        $first = reset($value);
        $isRowList = $first instanceof Model || $first instanceof \stdClass
            || (is_array($first) && $first && !static::isNested($first));

        if ($isRowList) {
            // نحفظ الصفوف كما هي ونحوّل كل صف وقت الكتابة فقط (توفير الذاكرة مع البيانات الكبيرة)
            $sheets[static::uniqueName($name, $sheets)] = array_values($value);
            return;
        }

        foreach ($value as $k => $v) {        // مصفوفة مفاتيح: ['sales' => ..., 'returns' => ...]
            static::walk($v, is_string($k) ? $k : $name, $sheets, $depth + 1);
        }
    }

    protected static function isNested(array $row): bool
    {
        foreach ($row as $v) {
            if (is_array($v) || $v instanceof Collection || $v instanceof Model) {
                return true;
            }
        }
        return false;
    }

    protected static function rowToArray($row): array
    {
        if ($row instanceof Model) {
            $row = $row->attributesToArray();
        } elseif ($row instanceof \stdClass) {
            $row = (array) $row;
        }
        if (!is_array($row)) {
            return [];
        }
        $out = [];
        foreach ($row as $k => $v) {
            if (is_array($v) || is_object($v) && !($v instanceof \DateTimeInterface)) {
                continue;
            }
            if (preg_match(static::SKIP_COLUMNS, (string) $k)) {
                continue;
            }
            if ($v instanceof \DateTimeInterface) {
                $v = $v->format('Y-m-d H:i:s');
            }
            if (is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $v)) {
                $v = date('Y-m-d H:i:s', strtotime($v));      // تواريخ ISO -> تاريخ عادي
            }
            if (is_string($v) && mb_strlen($v) > 500) {
                $v = mb_substr($v, 0, 500) . '…';
            }
            $out[(string) $k] = $v;
        }
        return $out;
    }

    protected static function uniqueName(string $name, array $sheets): string
    {
        $base = trim(preg_replace('/[\\\\\/\?\*\[\]:]/', ' ', $name)) ?: 'Sheet';
        $base = mb_substr($base, 0, 28);
        $n = $base;
        $i = 2;
        while (isset($sheets[$n])) {
            $n = $base . ' ' . $i++;
        }
        return $n;
    }

    // ------------------------------------------------------------------ الأعمدة

    /** يحدد الأعمدة (اتحاد كل المفاتيح) ويضيف أعمدة الأسماء بجانب المفاتيح الأجنبية */
    protected static function columns(array $rows): array
    {
        $keys = [];
        foreach (array_slice($rows, 0, 500) as $r) {
            foreach (array_keys(static::rowToArray($r)) as $k) {
                $keys[$k] = true;
            }
        }
        $cols = [];
        foreach (array_keys($keys) as $k) {
            $cols[] = ['key' => $k, 'lookup' => null];
            if (isset(static::LOOKUPS[$k])) {
                $cols[] = ['key' => $k, 'lookup' => static::LOOKUPS[$k]];
            }
        }
        return $cols;
    }

    protected static function label(array $col): string
    {
        $ar = app()->getLocale() === 'ar';
        if ($col['lookup']) {
            return $ar ? $col['lookup'][2] : $col['lookup'][3];
        }
        $k = $col['key'];
        if ($ar && isset(static::LABELS_AR[$k])) {
            return static::LABELS_AR[$k];
        }
        foreach (["home.$k", "report.$k"] as $t) {
            if (Lang::has($t)) {
                $v = __($t);
                if (is_string($v)) {
                    return $v;
                }
            }
        }
        return $k;
    }

    protected static function cellValue(array $row, array $col)
    {
        $v = $row[$col['key']] ?? null;
        if ($col['lookup']) {
            return $v === null || $v === '' ? '' : (static::lookup($col['lookup'][0], $col['lookup'][1])[$v] ?? '');
        }
        if ($col['key'] === 'Pay' && is_string($v) && app()->getLocale() === 'ar') {
            return static::PAY_AR[strtolower($v)] ?? $v;
        }
        return $v;
    }

    /** جدول id => اسم يُحمَّل مرة واحدة فقط لكل جدول */
    protected static function lookup(string $table, string $column): array
    {
        $key = "$table.$column";
        if (!isset(static::$lookupCache[$key])) {
            try {
                static::$lookupCache[$key] = DB::table($table)->pluck($column, 'id')->all();
            } catch (\Throwable $e) {
                static::$lookupCache[$key] = [];
            }
        }
        return static::$lookupCache[$key];
    }

    // ------------------------------------------------------------------ كتابة الملف

    protected static function reportTitle(string $viewName): string
    {
        $method = explode('@', class_basename((string) optional(request()->route())->getActionName()))[1] ?? '';
        $name = Str::afterLast($viewName, '.') ?: $method ?: 'report';
        $name = trim(preg_replace('/^print_?/i', '', str_replace(['_', '-'], ' ', $name)));
        return $name ?: 'report';
    }

    public static function download(array $sheets, string $title)
    {
        @set_time_limit(0);
        if ((int) ini_get('memory_limit') > 0 && (int) ini_get('memory_limit') < 1024) {
            @ini_set('memory_limit', '1024M');
        }
        $rtl = app()->getLocale() === 'ar';
        $from = request('start_at') ?? request('from') ?? request('startat');
        $to = request('end_at') ?? request('to') ?? request('endat');
        $period = ($from || $to) ? trim(($rtl ? 'من ' : 'From ') . $from . ($rtl ? ' إلى ' : ' to ') . $to) : '';

        $sheetFiles = [];
        foreach ($sheets as $sheetName => $rows) {
            $sheetFiles[$sheetName] = static::writeSheet($rows, $title, $period, $rtl);
        }

        $zipPath = tempnam(sys_get_temp_dir(), 'xls') . '.xlsx';
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $overrides = $wbSheets = $rels = '';
        $i = 1;
        foreach ($sheetFiles as $sheetName => $file) {
            $zip->addFile($file, "xl/worksheets/sheet$i.xml");
            $overrides .= '<Override PartName="/xl/worksheets/sheet' . $i . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
            $wbSheets .= '<sheet name="' . static::esc($sheetName) . '" sheetId="' . $i . '" r:id="rId' . $i . '"/>';
            $rels .= '<Relationship Id="rId' . $i . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $i . '.xml"/>';
            $i++;
        }
        $rels .= '<Relationship Id="rId' . $i . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';

        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>' . $overrides . '</Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets>' . $wbSheets . '</sheets></workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . $rels . '</Relationships>');
        // الأنماط: 0 عادي، 1 رأس الجدول، 2 العنوان، 3 خلية بإطار
        $zip->addFromString('xl/styles.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="3"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font><font><b/><sz val="14"/><name val="Calibri"/></font></fonts><fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF1D6F42"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="2"><border/><border><left style="thin"><color rgb="FFBFBFBF"/></left><right style="thin"><color rgb="FFBFBFBF"/></right><top style="thin"><color rgb="FFBFBFBF"/></top><bottom style="thin"><color rgb="FFBFBFBF"/></bottom></border></borders><cellStyleXfs count="1"><xf/></cellStyleXfs><cellXfs count="4"><xf/><xf fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf><xf fontId="2" applyFont="1"/><xf borderId="1" applyBorder="1"/></cellXfs></styleSheet>');
        $zip->close();

        foreach ($sheetFiles as $f) {
            @unlink($f);
        }

        $fileName = preg_replace('/[\\\\\/:*?"<>|]/', '-', $title) . ' ' . date('Y-m-d') . '.xlsx';
        return response()->download($zipPath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /** يكتب ورقة واحدة إلى ملف مؤقت سطراً بسطر (استهلاك ذاكرة ثابت) */
    protected static function writeSheet(array $rows, string $title, string $period, bool $rtl): string
    {
        $cols = static::columns($rows);
        $widths = [];
        foreach ($cols as $c => $col) {
            $widths[$c] = max(10, min(45, mb_strlen(static::label($col)) + 4));
        }

        $tmp = tempnam(sys_get_temp_dir(), 'sh');
        $body = fopen($tmp . '.body', 'w');
        $r = 0;
        $write = function (array $cells, int $style) use (&$r, $body) {
            $r++;
            $xml = '<row r="' . $r . '">';
            foreach (array_values($cells) as $i => $v) {
                $xml .= static::cell(static::colName($i) . $r, $v, $style);
            }
            fwrite($body, $xml . '</row>');
        };

        $write([$title], 2);
        if ($period !== '') {
            $write([$period], 0);
        }
        $write([], 0);
        $write(array_map([static::class, 'label'], $cols), 1);
        foreach ($rows as $raw) {
            $row = static::rowToArray($raw);
            if (!$row) {
                continue;
            }
            $cells = [];
            foreach ($cols as $c => $col) {
                $v = static::cellValue($row, $col);
                $cells[] = $v;
                if ($r < 300) {   // تقدير عرض الأعمدة من أول الصفوف
                    $widths[$c] = max($widths[$c], min(45, mb_strlen((string) $v) + 2));
                }
            }
            $write($cells, 3);
        }
        fclose($body);

        $colsXml = '<cols>';
        foreach ($widths as $i => $w) {
            $colsXml .= '<col min="' . ($i + 1) . '" max="' . ($i + 1) . '" width="' . $w . '" customWidth="1"/>';
        }
        $colsXml .= '</cols>';

        $out = fopen($tmp, 'w');
        fwrite($out, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetViews><sheetView workbookViewId="0"' . ($rtl ? ' rightToLeft="1"' : '') . '><pane ySplit="' . ($period !== '' ? 4 : 3) . '" topLeftCell="A' . ($period !== '' ? 5 : 4) . '" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            . $colsXml . '<sheetData>');
        $in = fopen($tmp . '.body', 'r');
        stream_copy_to_stream($in, $out);
        fclose($in);
        @unlink($tmp . '.body');
        fwrite($out, '</sheetData></worksheet>');
        fclose($out);

        return $tmp;
    }

    protected static function cell(string $ref, $v, int $style): string
    {
        $s = $style ? ' s="' . $style . '"' : '';
        if ($v === null || $v === '') {
            return $style ? '<c r="' . $ref . '"' . $s . '/>' : '';
        }
        if (is_bool($v)) {
            $v = $v ? 1 : 0;
        }
        if ($style !== 1 && static::isNumber($v)) {
            return '<c r="' . $ref . '"' . $s . '><v>' . (is_string($v) ? trim($v) : $v) . '</v></c>';
        }
        return '<c r="' . $ref . '"' . $s . ' t="inlineStr"><is><t xml:space="preserve">' . static::esc((string) $v) . '</t></is></c>';
    }

    protected static function isNumber($v): bool
    {
        if (is_int($v) || is_float($v)) {
            return is_finite((float) $v);
        }
        $s = trim((string) $v);
        return strlen($s) <= 15 && preg_match('/^-?\d+(\.\d+)?$/', $s) && !preg_match('/^-?0\d/', $s);
    }

    protected static function colName(int $n): string
    {
        $s = '';
        $n++;
        while ($n > 0) {
            $m = ($n - 1) % 26;
            $s = chr(65 + $m) . $s;
            $n = intdiv($n - 1, 26);
        }
        return $s;
    }

    protected static function esc(string $s): string
    {
        $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $s) ?? '';
        return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
