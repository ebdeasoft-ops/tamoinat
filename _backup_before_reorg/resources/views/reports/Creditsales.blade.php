{{-- تقرير المبيعات الآجلة — البيانات من ReportController ($Invoices) --}}
@php($reportTitle = app()->getLocale() == 'ar' ? 'المبيعات الآجلة' : 'Credit sales')
@extends('reports.partials.payment_method_sales')
