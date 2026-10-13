{{--
    أزرار "تصدير Excel" في كل صفحات التقارير:
      - في كل نموذج بحث: بجانب زر البحث (يرسل نفس النموذج مع _excel=1 فيُنزَّل الملف مباشرة بدون فتح الصفحة).
      - بجانب أزرار/روابط الطباعة.
      - في التقارير التي ليس لها نموذج بحث: زر أعلى الصفحة.
    المنطق في App\Support\ExcelReportExporter. يُضمَّن مرة واحدة في layouts.master.
--}}
@if (\App\Support\ExcelReportExporter::isReportPage())
<style>
    .excel-export-btn { background: #1D6F42 !important; border-color: #1D6F42 !important; color: #fff !important; border-radius: 8px; white-space: nowrap; }
    .excel-export-btn:hover { background: #185c37 !important; color: #fff !important; }
    .excel-export-btn.is-loading { opacity: .7; pointer-events: none; }
    @media print { .excel-export-btn { display: none !important; } }
</style>
<script>
(function () {
    var PARAM  = @json(\App\Support\ExcelReportExporter::PARAM);
    var LABEL  = @json(app()->getLocale() == 'ar' ? 'تصدير Excel' : 'Export Excel');
    var WAIT   = @json(app()->getLocale() == 'ar' ? 'جاري التجهيز...' : 'Preparing...');
    var IS_GET = @json(request()->isMethod('get'));
    var ICON   = '<i class="far fa-file-excel ml-1 mr-1"></i> ';

    function loading(btn) {
        btn.classList.add('is-loading'); btn.innerHTML = ICON + WAIT;
        setTimeout(function () { btn.classList.remove('is-loading'); btn.innerHTML = ICON + LABEL; }, 8000);
    }

    // نموذج بحث تقرير: له زر إرسال، وليس حذف/تعديل/خروج، وليس داخل صف جدول أو نافذة منبثقة
    function isSearchForm(f) {
        if (f.closest('.modal, .main-header, .main-sidebar, .app-sidebar, table')) return false;
        if (f.querySelector('input[name="_method"]')) return false;
        if (f.querySelector('[data-excel-submit]')) return true;
        if (/(destroy|delete|logout)/i.test(f.getAttribute('action') || '')) return false;
        return !!f.querySelector('button[type="submit"], input[type="submit"], button:not([type])');
    }

    function addFormButton(form) {
        if (form.dataset.excelReady) return;
        form.dataset.excelReady = '1';
        var submit = form.querySelector('button[type="submit"], input[type="submit"], button:not([type])');
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = submit.className.replace(/\bbtn-(primary|info|success|secondary|warning|danger|dark|light)\b/g, '').replace(/\bbtn-block\b/, '') + ' btn excel-export-btn';
        btn.style.marginInlineStart = '8px';
        btn.innerHTML = ICON + LABEL;
        btn.addEventListener('click', function () {
            if (form.reportValidity && !form.reportValidity()) return;
            var hidden = document.createElement('input');
            hidden.type = 'hidden'; hidden.name = PARAM; hidden.value = '1';
            form.appendChild(hidden);
            // إرسال مباشر (يتجاوز أي معالج ajax للنموذج) — الرد ملف تنزيل فلا تتغير الصفحة
            HTMLFormElement.prototype.submit.call(form);
            setTimeout(function () { hidden.remove(); }, 0);
            loading(btn);
        });
        submit.insertAdjacentElement('afterend', btn);
    }

    function excelUrl(href) {
        var u = new URL(href, location.href);
        u.searchParams.set(PARAM, '1');
        return u.toString();
    }

    // رابط Excel بجانب أزرار/روابط الطباعة
    function addPrintLinks(root) {
        root.querySelectorAll('a[href]').forEach(function (a) {
            if (a.dataset.excelReady || a.classList.contains('excel-export-btn')) return;
            var href = a.getAttribute('href') || '';
            if (!/print/i.test(href) || /^(#|javascript:)/i.test(href)) return;
            if (a.closest('.main-sidebar, .app-sidebar, .main-header, .sidebar, .dropdown-menu')) return;
            a.dataset.excelReady = '1';
            var x = document.createElement('a');
            x.href = excelUrl(a.href);
            x.className = 'btn excel-export-btn ' + (a.className.match(/btn-(sm|lg)/) || [''])[0];
            x.style.marginInlineStart = '8px';
            x.innerHTML = ICON + LABEL;
            x.addEventListener('click', function () { loading(x); });
            a.insertAdjacentElement('afterend', x);
        });
    }

    // تقرير بدون نموذج بحث (قائمة عملاء، أرصدة ...): زر أعلى الصفحة يعيد طلب نفس الصفحة كملف Excel
    function addPageButton(root, hasForm) {
        if (hasForm || !IS_GET || document.getElementById('excel-page-btn')) return;
        if (!root.querySelector('table tbody tr td')) return;
        var x = document.createElement('a');
        x.id = 'excel-page-btn';
        x.href = excelUrl(location.href);
        x.className = 'btn btn-sm excel-export-btn';
        x.innerHTML = ICON + LABEL;
        x.addEventListener('click', function () { loading(x); });
        var header = document.querySelector('.breadcrumb-header');
        if (header) {
            var box = document.createElement('div');
            box.className = 'd-flex my-auto';
            box.appendChild(x);
            header.appendChild(box);
        } else {
            x.style.cssText = 'margin:10px 0;display:inline-block';
            root.insertBefore(x, root.firstChild);
        }
    }

    // الأزرار المضافة داخل ملفات التقارير (partials.excel-button): إرسال مباشر يتجاوز أي معالج ajax
    document.addEventListener('click', function (e) {
        var btn = e.target.closest && e.target.closest('[data-excel-submit]');
        if (!btn || !btn.form) return;
        e.preventDefault();
        var form = btn.form;
        if (form.reportValidity && !form.reportValidity()) return;
        var hidden = document.createElement('input');
        hidden.type = 'hidden'; hidden.name = PARAM; hidden.value = '1';
        form.appendChild(hidden);
        HTMLFormElement.prototype.submit.call(form);
        setTimeout(function () { hidden.remove(); }, 0);
        loading(btn);
    });

    function init() {
        var root = document.querySelector('.main-content .container-fluid') || document.body;
        var forms = Array.prototype.filter.call(root.querySelectorAll('form'), isSearchForm);
        // النماذج التي فيها الزر مسبقاً لا يُضاف لها زر ثانٍ
        forms.filter(function (f) { return !f.querySelector('[data-excel-submit]'); }).forEach(addFormButton);
        addPrintLinks(root);
        addPageButton(root, forms.length > 0);
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
</script>
@endif
