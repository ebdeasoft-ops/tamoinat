<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Disable foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);

        $p = Permission::create(['name' => 'AVT Control', 'name_ar' => 'إعدادات القيمة المضافة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Account statement report', 'name_ar' => 'تقرير كشف حساب', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Account tree', 'name_ar' => 'شجرة الحسابات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Account type', 'name_ar' => 'أنواع الحسابات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Accounts', 'name_ar' => 'الحسابات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Accounts Reports Section', 'name_ar' => 'قسم تقارير الحسابات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Add a new customer', 'name_ar' => 'إضافة عميل جديد', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Add new account', 'name_ar' => 'إضافة حساب جديد', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Add new employee', 'name_ar' => 'إضافة موظف جديد', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Add new product', 'name_ar' => 'إضافة منتج جديد', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Add new supplier', 'name_ar' => 'إضافة مورد جديد', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Additions', 'name_ar' => 'الإضافات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Attendances', 'name_ar' => 'الحضور والانصراف', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Available quantity', 'name_ar' => 'الكمية المتوفرة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Best selling product report', 'name_ar' => 'تقرير المنتجات الأكثر مبيعاً', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Cash expenses', 'name_ar' => 'مصروفات نقدية', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Confirm delivery', 'name_ar' => 'تأكيد التسليم', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Confirm transfer of master branch', 'name_ar' => 'تأكيد تحويل الفرع الرئيسي', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Contracts', 'name_ar' => 'العقود', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Cost center report', 'name_ar' => 'تقرير مراكز التكلفة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Credit collection report', 'name_ar' => 'تقرير تحصيل الآجل', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Current stock quantity report', 'name_ar' => 'تقرير كمية المخزون الحالي', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Custody and Assets', 'name_ar' => 'العهد والأصول', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Customer debt restructuring', 'name_ar' => 'جدولة ديون العملاء', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Customer purchases report', 'name_ar' => 'تقرير مشتريات العملاء', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Daily record', 'name_ar' => 'قيد يومي', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Daily record report', 'name_ar' => 'تقرير القيود اليومية', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Daily transactions sheet', 'name_ar' => 'كشف الحركات اليومية', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Database backup privilege', 'name_ar' => 'صلاحية النسخ الاحتياطي', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Deliver product to customer shortcut', 'name_ar' => 'تسليم منتج لعميل', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Deliver to another supplier', 'name_ar' => 'تسليم لمورد آخر', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Deliveries', 'name_ar' => 'التسليمات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Delivery notes report', 'name_ar' => 'تقرير سندات التسليم', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Employee', 'name_ar' => 'الموظفين', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Employee loans privilege', 'name_ar' => 'سلف الموظفين', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Employee sales report', 'name_ar' => 'تقرير مبيعات الموظفين', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'End of Service', 'name_ar' => 'نهاية الخدمة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Expenses report', 'name_ar' => 'تقرير المصروفات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Financial accounts', 'name_ar' => 'الحسابات المالية', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'History of product sales report', 'name_ar' => 'سجل مبيعات المنتجات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Home', 'name_ar' => 'الرئيسية', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Human Resource', 'name_ar' => 'الموارد البشرية', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Increase or deduction', 'name_ar' => 'زيادة أو خصم', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Inventory Main Section', 'name_ar' => 'قسم المخزون الرئيسي', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Leaves', 'name_ar' => 'الإجازات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'List of customers', 'name_ar' => 'قائمة العملاء', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'List of users', 'name_ar' => 'قائمة المستخدمين', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Low sell products report', 'name_ar' => 'المنتجات الأقل مبيعاً', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Not sent sales invoices', 'name_ar' => 'فواتير المبيعات غير المرسلة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Opening entry', 'name_ar' => 'القيد الافتتاحي', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Pending sales invoices', 'name_ar' => 'فواتير المبيعات المعلقة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Previous deliver invoices', 'name_ar' => 'فواتير التسليم السابقة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Previous purchase invoices', 'name_ar' => 'فواتير المشتريات السابقة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Previous quotes', 'name_ar' => 'عروض الأسعار السابقة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Previous sales invoices', 'name_ar' => 'فواتير المبيعات السابقة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Product damage reports', 'name_ar' => 'تقارير تلف المنتجات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Product data change', 'name_ar' => 'تعديل بيانات المنتج', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Product delivery to a customer', 'name_ar' => 'تسليم منتج للعميل', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Product location changent', 'name_ar' => 'حركة المنتجات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Product sales purchases report', 'name_ar' => 'تقرير مبيعات ومشتريات المنتجات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Product sales report', 'name_ar' => 'تقرير مبيعات المنتج', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Products', 'name_ar' => 'المنتجات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Produects', 'name_ar' => 'عرض المنتجات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Profit and lost report', 'name_ar' => 'تقرير الأرباح والخسائر', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Purchase orders from suppliers report', 'name_ar' => 'تقرير أوامر الشراء من الموردين', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Purchase product by date report', 'name_ar' => 'مشتريات المنتجات بالتاريخ', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Purchase product to customer report', 'name_ar' => 'مشتريات العملاء من المنتجات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Purchases', 'name_ar' => 'المشتريات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Purchases from suppliers report', 'name_ar' => 'تقرير المشتريات من الموردين', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Purchases products', 'name_ar' => 'منتجات المشتريات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Purchases report detail', 'name_ar' => 'تفاصيل المشتريات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Purchases report section', 'name_ar' => 'قسم تقارير المشتريات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Quotations', 'name_ar' => 'عروض الأسعار', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Receipt', 'name_ar' => 'سند استلام', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Receipt document', 'name_ar' => 'سند قبض', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Receiving a product from another branch', 'name_ar' => 'استلام منتج من فرع', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Recent delivers', 'name_ar' => 'التسليمات الأخيرة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Refund of resource purchases report', 'name_ar' => 'تقرير مرتجع المشتريات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Reports', 'name_ar' => 'التقارير', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Salary document', 'name_ar' => 'مسير الرواتب', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Sales', 'name_ar' => 'المبيعات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Sales and return report', 'name_ar' => 'تقرير المبيعات والمرتجعات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Sales product by date report', 'name_ar' => 'تقرير المبيعات بالتاريخ', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Sales products', 'name_ar' => 'منتجات المبيعات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Sales profit', 'name_ar' => 'أرباح المبيعات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Sales profits report', 'name_ar' => 'تقرير أرباح المبيعات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Sales report', 'name_ar' => 'تقارير المبيعات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Sales return report', 'name_ar' => 'تقرير مرتجع المبيعات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Sent sales invoices', 'name_ar' => 'فواتير المبيعات المرسلة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Settings Section', 'name_ar' => 'قسم الإعدادات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Show groups', 'name_ar' => 'عرض المجموعات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Stock adjustment', 'name_ar' => 'تعديل المخزون', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Stocktaking report', 'name_ar' => 'تقرير جرد المخزون', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Supplier credit payment report', 'name_ar' => 'تقرير مدفوعات الموردين', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Supplier debt restructuring', 'name_ar' => 'جدولة ديون الموردين', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'System setting', 'name_ar' => 'إعدادات النظام', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Technical support', 'name_ar' => 'الدعم الفني', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Transactions to master branch report', 'name_ar' => 'تقرير التحويلات للفرع الرئيسي', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Transfer cash next day', 'name_ar' => 'ترحيل النقدية لليوم التالي', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Transfer of goods report', 'name_ar' => 'تقرير نقل البضائع', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Transfer to main branch', 'name_ar' => 'التحويل للفرع الرئيسي', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Transferring a product to another branch', 'name_ar' => 'نقل منتج لفرع آخر', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Update customer', 'name_ar' => 'تحديث العميل', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Update stock quantity report', 'name_ar' => 'تحديث كمية المخزون', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Update supplier', 'name_ar' => 'تحديث المورد', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'User and branches', 'name_ar' => 'المستخدمين والفروع', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Users permissions', 'name_ar' => 'صلاحيات المستخدمين', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'VAT report', 'name_ar' => 'تقرير ضريبة القيمة المضافة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'View Previous Purchases', 'name_ar' => 'عرض المشتريات السابقة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Voucher', 'name_ar' => 'سند صرف', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Year sales report', 'name_ar' => 'تقرير المبيعات السنوي', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Zakat Linkage Section', 'name_ar' => 'قسم ربط الزكاة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'Zakat Onboarding Privilege', 'name_ar' => 'صلاحية ربط الزكاة', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'add branch', 'name_ar' => 'إضافة فرع', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'create a department', 'name_ar' => 'إنشاء قسم', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'delivery notes', 'name_ar' => 'مذكرات التسليم', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'enpenses_reason', 'name_ar' => 'بنود المصروفات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'invoice_purchase_update', 'name_ar' => 'تحديث فاتورة مشتريات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'offer price to customer', 'name_ar' => 'عرض سعر لعميل', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'product damage', 'name_ar' => 'تلف المنتج', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'purchase order to resources', 'name_ar' => 'أمر شراء لمورد', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'purchase return', 'name_ar' => 'مرتجع مشتريات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'request price from supplier', 'name_ar' => 'طلب تسعيرة من مورد', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

        $p = Permission::create(['name' => 'sales return', 'name_ar' => 'مرتجع مبيعات', 'guard_name' => 'web']);
        $role->givePermissionTo($p);

    }
}
