<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->timestamps();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('code')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('boms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('finished_product_id')->nullable();
            $table->double('output_quantity', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('bom_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bom_id')->nullable();
            $table->unsignedBigInteger('raw_material_id')->nullable();
            $table->double('quantity', 10, 2)->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->double('scrap_percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('manufacturing_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->unsignedBigInteger('bom_id')->nullable();
            $table->unsignedBigInteger('finished_product_id')->nullable();
            $table->unsignedBigInteger('raw_material_warehouse_id')->nullable();
            $table->unsignedBigInteger('wip_warehouse_id')->nullable();
            $table->unsignedBigInteger('finished_goods_warehouse_id')->nullable();
            $table->double('planned_quantity', 10, 2)->nullable();
            $table->double('produced_quantity', 10, 2)->nullable();
            $table->double('scrap_quantity', 10, 2)->nullable();
            $table->double('estimated_cost', 15, 2)->nullable();
            $table->double('actual_materials_cost', 15, 2)->nullable();
            $table->double('actual_overhead_cost', 15, 2)->nullable();
            $table->double('total_actual_cost', 15, 2)->nullable();
            $table->double('unit_cost', 15, 2)->nullable();
            $table->date('order_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('manufacturing_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->nullable();
            $table->unsignedBigInteger('manufacturing_order_id')->nullable();
            $table->date('expense_date')->nullable();
            $table->string('expense_type')->nullable();
            $table->double('amount', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('material_issue_headers', function (Blueprint $table) {
            $table->id();
            $table->string('issue_number')->nullable();
            $table->unsignedBigInteger('manufacturing_order_id')->nullable();
            $table->date('issue_date')->nullable();
            $table->unsignedBigInteger('raw_warehouse_id')->nullable();
            $table->unsignedBigInteger('wip_warehouse_id')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('material_issue_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('issue_header_id')->nullable();
            $table->unsignedBigInteger('raw_material_id')->nullable();
            $table->double('issued_quantity', 10, 2)->nullable();
            $table->double('unit_cost', 15, 2)->nullable();
            $table->double('total_cost', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('mo_material_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manufacturing_order_id')->nullable();
            $table->unsignedBigInteger('raw_material_id')->nullable();
            $table->double('planned_quantity', 10, 2)->nullable();
            $table->double('issued_quantity', 10, 2)->nullable();
            $table->double('unit_cost', 15, 2)->nullable();
            $table->double('total_cost', 15, 2)->nullable();
            $table->date('issue_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('finished_goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->nullable();
            $table->unsignedBigInteger('manufacturing_order_id')->nullable();
            $table->unsignedBigInteger('finished_product_id')->nullable();
            $table->date('receipt_date')->nullable();
            $table->unsignedBigInteger('wip_warehouse_id')->nullable();
            $table->unsignedBigInteger('finished_goods_warehouse_id')->nullable();
            $table->double('received_quantity', 10, 2)->nullable();
            $table->double('unit_cost', 15, 2)->nullable();
            $table->double('total_cost', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finished_goods_receipts');
        Schema::dropIfExists('mo_material_issues');
        Schema::dropIfExists('material_issue_items');
        Schema::dropIfExists('material_issue_headers');
        Schema::dropIfExists('manufacturing_expenses');
        Schema::dropIfExists('manufacturing_orders');
        Schema::dropIfExists('bom_items');
        Schema::dropIfExists('boms');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('units');
    }
};
