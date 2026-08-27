<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * جدول "purchase_return_logs" بيسجل كل عملية إرجاع مشتريات (مرتجع مشتريات) كسطر
     * منفصل ثابت في التاريخ - بنفس فكرة order_details لكن مخصص لتتبع المرتجعات،
     * سواء إرجاع جزئي لمنتج واحد أو إرجاع الفاتورة بالكامل.
     */
    public function up(): void
    {
        Schema::create('purchase_return_logs', function (Blueprint $table) {
            $table->id();

            // ربط السطر برقم فاتورة الشراء الأصلية (orderId في order_details / resource_purchases)
            $table->unsignedBigInteger('orderId')->index();

            // ربط السطر بصف المنتج الأصلي في order_details (اختياري - ممكن يتمسح الأصل)
            $table->unsignedBigInteger('order_details_id')->nullable()->index();

            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->string('product_name')->nullable();

            // سعر الشراء والقيمة المضافة (الضريبة) وقت الإرجاع
            $table->decimal('purchasing_price', 15, 2)->default(0);
            $table->decimal('Added_Value', 15, 2)->default(0);

            // الكمية المرتجعة في هذه العملية بالذات
            $table->decimal('return_quentity', 15, 2)->default(0);

            // إجمالي قيمة هذا الإرجاع = (السعر + القيمة المضافة) * الكمية المرتجعة
            $table->decimal('total_returned_value', 15, 2)->default(0);

            // هل الإرجاع كان لمنتج واحد فقط ولا إرجاع كامل الفاتورة
            $table->enum('return_type', ['single', 'full_invoice'])->default('single');

            $table->unsignedBigInteger('branchs_id')->nullable()->index();

            // المستخدم اللي نفذ عملية الإرجاع
            $table->unsignedBigInteger('returned_by')->nullable()->index();

            $table->string('return_reason')->nullable();

            $table->timestamps();

            $table->foreign('order_details_id')
                ->references('id')->on('order_details')
                ->onDelete('set null');

            $table->foreign('returned_by')
                ->references('id')->on('users')
                ->onDelete('set null');

            $table->foreign('branchs_id')
                ->references('id')->on('branchs')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_logs');
    }
};