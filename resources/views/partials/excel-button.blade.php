{{-- زر "تصدير Excel" بجانب زر البحث في نماذج التقارير: يرسل نفس النموذج مع _excel=1 فيُنزّل الملف مباشرة --}}
<button type="submit" name="{{ \App\Support\ExcelReportExporter::PARAM }}" value="1" data-excel-submit
        class="btn excel-export-btn" style="margin-inline-start:8px; background:#1D6F42; border-color:#1D6F42; color:#fff; border-radius:8px; white-space:nowrap;">
    <i class="far fa-file-excel ml-1 mr-1"></i> {{ app()->getLocale() == 'ar' ? 'تصدير Excel' : 'Export Excel' }}
</button>
