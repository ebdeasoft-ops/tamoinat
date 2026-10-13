<?php

/*
|--------------------------------------------------------------------------
| أقسام الصلاحيات (بنفس ترتيب القائمة الجانبية)
|--------------------------------------------------------------------------
| كل قسم:
|   title    => العنوان عربي/إنجليزي
|   main     => صلاحية/صلاحيات إظهار القسم في القائمة الجانبية (تُحدَّد تلقائياً عند اختيار أي صلاحية داخله)
|   items    => باقي صلاحيات القسم
| أي صلاحية موجودة في قاعدة البيانات وغير مذكورة هنا تظهر تلقائياً في قسم "أخرى".
| الأسماء هنا هي الاسم الإنجليزي للصلاحية (عمود name) كما يُستخدم في @can.
*/

return [

    'groups' => [

        'dashboard' => [
            'title' => ['ar' => 'لوحة التحكم', 'en' => 'Dashboard'],
            'icon'  => 'fa-home',
            'main'  => [],
            'items' => ['Home'],
        ],

        'sales' => [
            'title' => ['ar' => 'المبيعات', 'en' => 'Sales'],
            'icon'  => 'fa-shopping-cart',
            'main'  => ['Sales'],
            'items' => ['Sales products', 'sales return', 'Previous sales invoices', 'Pending sales invoices',
                        'Sent sales invoices', 'Not sent sales invoices'],
        ],

        'quotations' => [
            'title' => ['ar' => 'عروض الأسعار', 'en' => 'Quotations'],
            'icon'  => 'fa-file-invoice-dollar',
            'main'  => ['Quotations'],
            'items' => ['offer price to customer', 'Previous quotes', 'request price from supplier', 'delivery notes'],
        ],

        'customer_delivery' => [
            'title' => ['ar' => 'تسليم المنتجات للعملاء', 'en' => 'Customer deliveries'],
            'icon'  => 'fa-truck-loading',
            'main'  => ['Product delivery to a customer'],
            'items' => ['Deliver product to customer shortcut', 'Previous deliver invoices'],
        ],

        'purchases' => [
            'title' => ['ar' => 'المشتريات', 'en' => 'Purchases'],
            'icon'  => 'fa-cart-arrow-down',
            'main'  => ['Purchases'],
            'items' => ['Purchases products', 'purchase return', 'purchase order to resources', 'Previous purchase invoices',
                        'View Previous Purchases', 'invoice_purchase_update'],
        ],

        'products' => [
            'title' => ['ar' => 'المنتجات والمخزون', 'en' => 'Products & stock'],
            'icon'  => 'fa-boxes',
            'main'  => ['Products'],
            'items' => ['Produects', 'Available quantity', 'Stock adjustment', 'Product data change',
                        'Product location changent', 'product damage', 'Receipt'],
        ],

        'warehouses' => [
            'title' => ['ar' => 'المستودعات والتحويل بين الفروع', 'en' => 'Warehouses & branch transfers'],
            'icon'  => 'fa-warehouse',
            'main'  => ['Transferring a product to another branch'],
            'items' => ['Receiving a product from another branch'],
        ],

        'deliveries' => [
            'title' => ['ar' => 'التسليمات', 'en' => 'Deliveries'],
            'icon'  => 'fa-dolly',
            'main'  => ['Deliveries'],
            'items' => ['Deliver to another supplier', 'Confirm delivery', 'Recent delivers'],
        ],

        'additions' => [
            'title' => ['ar' => 'الإضافات (منتجات / عملاء / موردين)', 'en' => 'Additions (products / customers / suppliers)'],
            'icon'  => 'fa-plus-square',
            'main'  => ['Additions'],
            'items' => ['Add new product', 'Show groups', 'Add a new customer', 'Update customer',
                        'Add new supplier', 'Update supplier'],
        ],

        'accounting' => [
            'title' => ['ar' => 'الحسابات', 'en' => 'Accounting'],
            'icon'  => 'fa-university',
            'main'  => ['Accounts'],
            'items' => ['Account tree', 'Add new account', 'Account type', 'enpenses_reason', 'Opening entry',
                        'Daily record', 'Receipt document', 'Voucher', 'Cash expenses',
                        'Transfer to main branch', 'Confirm transfer of master branch'],
        ],

        'reports_accounting' => [
            'title' => ['ar' => 'التقارير — الحسابات', 'en' => 'Reports — Accounting'],
            'icon'  => 'fa-chart-pie',
            'main'  => ['Reports', 'Accounts Reports Section'],
            'items' => ['Account statement report', 'Daily record report', 'Daily transactions sheet', 'Transfer cash next day',
                        'Financial accounts', 'Profit and lost report', 'VAT report', 'Expenses report', 'Cost center report',
                        'List of customers', 'Credit collection report', 'Customer debt restructuring',
                        'Supplier credit payment report', 'Supplier debt restructuring', 'Transactions to master branch report'],
        ],

        'reports_sales' => [
            'title' => ['ar' => 'التقارير — المبيعات', 'en' => 'Reports — Sales'],
            'icon'  => 'fa-chart-line',
            'main'  => ['Reports', 'Sales report'],
            'items' => ['History of product sales report', 'Sales product by date report', 'Year sales report',
                        'Sales and return report', 'Sales return report', 'Product sales report', 'Best selling product report',
                        'Customer purchases report', 'Purchase product to customer report', 'Employee sales report',
                        'Sales profits report', 'Sales profit'],
        ],

        'reports_purchases' => [
            'title' => ['ar' => 'التقارير — المشتريات', 'en' => 'Reports — Purchases'],
            'icon'  => 'fa-chart-bar',
            'main'  => ['Reports', 'Purchases report section'],
            'items' => ['Purchases from suppliers report', 'Purchases report detail', 'Purchase product by date report',
                        'Refund of resource purchases report', 'Purchase orders from suppliers report', 'Delivery notes report'],
        ],

        'reports_inventory' => [
            'title' => ['ar' => 'التقارير — المخزون', 'en' => 'Reports — Inventory'],
            'icon'  => 'fa-clipboard-list',
            'main'  => ['Reports', 'Inventory Main Section'],
            'items' => ['Current stock quantity report', 'Update stock quantity report', 'Stocktaking report',
                        'Product sales purchases report', 'Low sell products report', 'Product damage reports',
                        'Transfer of goods report', 'Database backup privilege'],
        ],

        'zatca' => [
            'title' => ['ar' => 'الربط مع هيئة الزكاة', 'en' => 'ZATCA linkage'],
            'icon'  => 'fa-link',
            'main'  => ['Zakat Linkage Section'],
            'items' => ['Zakat Onboarding Privilege'],
        ],

        'users' => [
            'title' => ['ar' => 'المستخدمين والفروع', 'en' => 'Users & branches'],
            'icon'  => 'fa-users-cog',
            'main'  => ['User and branches'],
            'items' => ['List of users', 'Users permissions', 'add branch'],
        ],

        'settings' => [
            'title' => ['ar' => 'الإعدادات', 'en' => 'Settings'],
            'icon'  => 'fa-cogs',
            'main'  => ['Settings Section'],
            'items' => ['System setting', 'AVT Control', 'Technical support'],
        ],

        'hr' => [
            'title' => ['ar' => 'الموارد البشرية', 'en' => 'Human resources'],
            'icon'  => 'fa-user-tie',
            'main'  => ['Human Resource'],
            'items' => ['Employee', 'Add new employee', 'create a department', 'Contracts', 'Attendances', 'Leaves',
                        'Salary document', 'Increase or deduction', 'Employee loans privilege', 'End of Service',
                        'Custody and Assets'],
        ],
    ],

    /* أسماء إنجليزية أوضح لبعض الصلاحيات (الاسم الأصلي في قاعدة البيانات لا يتغير) */
    'labels_en' => [
        'Produects'                   => 'View products & stock',
        'Products'                    => 'Products section',
        'enpenses_reason'             => 'Expense items',
        'add branch'                  => 'Branches & warehouses',
        'Product location changent'   => 'Product movements',
        'invoice_purchase_update'     => 'Edit purchase invoices',
        'View Previous Purchases'     => 'View previous purchases',
        'delivery notes'              => 'Delivery notes',
        'Recent delivers'             => 'Recent deliveries',
        'Receipt'                     => 'Goods receipt note',
        'Receipt document'            => 'Receipt voucher',
        'Voucher'                     => 'Payment voucher',
        'Profit and lost report'      => 'Profit and loss report',
        'AVT Control'                 => 'VAT settings',
        'request price from supplier' => 'Request price from supplier',
        'offer price to customer'     => 'Price offer to customer',
        'sales return'                => 'Sales return',
        'purchase return'             => 'Purchase return',
        'purchase order to resources' => 'Purchase order to supplier',
        'product damage'              => 'Damaged products',
        'create a department'         => 'Create department',
        'Employee'                    => 'Employees',
        'Zakat Onboarding Privilege'  => 'ZATCA onboarding',
        'Zakat Linkage Section'       => 'ZATCA linkage section',
        'Human Resource'              => 'Human resources section',
        'Database backup privilege'   => 'Database backup',
        'Employee loans privilege'    => 'Employee loans',
        'Transfer cash next day'      => 'Transfer cash to next day',
        'List of customers'           => 'Customers & suppliers accounts',
        'Puchases from supplier'      => 'Purchases from supplier',
        'Sales report'                => 'Sales reports section',
        'Reports'                     => 'Reports section',
        'Accounts'                    => 'Accounting section',
        'Sales'                       => 'Sales section',
        'Purchases'                   => 'Purchases section',
        'Quotations'                  => 'Quotations section',
        'Deliveries'                  => 'Deliveries section',
        'Additions'                   => 'Additions section',
        'Settings Section'            => 'Settings section',
        'User and branches'           => 'Users & branches section',
        'Product delivery to a customer' => 'Customer deliveries section',
        'Transferring a product to another branch' => 'Transfer products to another branch',
    ],
];
