{{-- تقرير المبيعات النقدية — البيانات من ReportController ($Invoices) --}}
@php($reportTitle = app()->getLocale() == 'ar' ? 'المبيعات النقدية' : 'Cash sales')
@extends('reports.partials.payment_method_sales')
