{{-- تقرير مبيعات الشبكة (مدى / فيزا) — البيانات من ReportController ($Invoices) --}}
@php($reportTitle = app()->getLocale() == 'ar' ? 'مبيعات الشبكة (مدى / فيزا)' : 'Network sales (Mada / Visa)')
@extends('reports.partials.payment_method_sales')
