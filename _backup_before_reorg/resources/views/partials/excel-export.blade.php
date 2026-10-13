{{--
    زر "Excel" في نماذج البحث بالتقارير (بجانب زر البحث) + رابط Excel بجانب أزرار الطباعة.
    الزر يرسل نفس النموذج مع _excel=1 فيُنزّل السيرفر ملف xlsx مباشرة بدون فتح صفحة العرض أو الطباعة
    (المنطق في App\Support\ExcelReportExporter). يُضمَّن مرة واحدة في layouts.master.
--}}
@if (\App\Support\ExcelReportExporter::enabledForCurrentRoute())
<style>
    .excel-export-btn { background: #1D6F42 !important; border-color: #1D6F42 !important; color: #fff !important; border-radius: 8px; }
    .excel-export-btn:hover { background: #185c37 !important; }
    .excel-export-btn.is-loading { opacity: .7; pointer-events: none; }
    @media print { .excel-export-btn { display: none !important; } }
</style>
<script>
(function () {
    var PARAM = @json(\App\Support\ExcelReportExporter::PARAM);
    var LABEL = @json(app()->getLocale() == 'ar' ? 'تصدير Excel' : 'Export Excel');
    var WAIT  = @json(app()->getLocale() == 'ar' ? 'جاري التجهيز...' : 'Preparing...');
    var ICON  = '<i class="far fa-file-excel ml-1 mr-1"></i> ';

    // نماذج التقارير = نماذج فيها حقل تاريخ
    function isReportForm(f) {
        return f.querySelector('input[name="start_at"], input[name="end_at"], input[type="date"], .fc-datepicker, input[name*="date" i], input[name*="from" i]');
    }

    function addFormButton(form) {
        if (form.dataset.excelReady) return;
        form.dataset.excelReady = '1';
        var submit = form.querySelector('button[type="submit"], input[type="submit"], button:not([type])');
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = (submit ? submit.className.replace(/btn-(primary|info|success|secondary|warning|danger)\b/g, '') : 'btn') + ' btn excel-export-btn';
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
            btn.classList.add('is-loading'); btn.innerHTML = ICON + WAIT;
            setTimeout(function () { btn.classList.remove('is-loading'); btn.innerHTML = ICON + LABEL; }, 8000);
        });
        if (submit) submit.insertAdjacentElement('afterend', btn);
        else form.appendChild(btn);
    }

    // رابط Excel بجانب أزرار/روابط الطباعة (تُنزّل نفس البيانات بدون فتح صفحة الطباعة)
    function addPrintLinks() {
        document.querySelectorAll('a[href]').forEach(function (a) {
            if (a.dataset.excelReady || a.classList.contains('excel-export-btn')) return;
            var href = a.getAttribute('href') || '';
            if (!/print/i.test(href) || /^(#|javascript:)/i.test(href)) return;
            if (a.closest('.main-sidebar, .app-sidebar, .main-header, .sidebar, .dropdown-menu')) return;
            a.dataset.excelReady = '1';
            var x = document.createElement('a');
            x.href = a.href + (a.href.indexOf('?') === -1 ? '?' : '&') + PARAM + '=1';
            x.className = 'btn excel-export-btn ' + (a.className.match(/btn-(sm|lg)/) || [''])[0];
            x.style.marginInlineStart = '8px';
            x.innerHTML = ICON + LABEL;
            a.insertAdjacentElement('afterend', x);
        });
    }

    function init() {
        document.querySelectorAll('.main-content form').forEach(function (f) {
            if (isReportForm(f) && !f.closest('.modal, .main-header, .main-sidebar')) addFormButton(f);
        });
        addPrintLinks();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
</script>
@endif
