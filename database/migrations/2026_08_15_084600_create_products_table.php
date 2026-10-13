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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 255);
            $table->unsignedBigInteger('branchs_id');
            $table->double('purchasing_price')->unsigned()->default(0);
            $table->double('sale_price')->unsigned()->default(0);
            $table->double('numberofpice')->nullable()->default(0);
            $table->string('Status', 50);
            $table->unsignedBigInteger('user_id');
            $table->timestamp('deleted_at')->nullable();
            $table->string('Product_Location', 255);
            $table->string('Product_Code', 255);
            $table->double('Added_Value', 8, 2)->default(0.00);
            $table->bigInteger('number_of_sales')->default(0);
            $table->string('name_en', 255)->default('Name_En');
            $table->string('notes', 255)->default('ظ„ط§ طھظˆط¬ط¯ ظ…ظ„ط§ط­ط¸ط§طھ');
            $table->string('unit', 255)->default('piece');
            $table->integer('minmum_quantity_stock_alart')->default(10);
            $table->bigInteger('main_product')->default(0);
            $table->text('refnumber')->nullable();
            $table->double('opening_blance')->default(0);
            $table->double('average_cost')->default(0);
            $table->double('Wholesale_price')->default(0);
            $table->text('photo')->nullable();
            $table->bigInteger('products_mix')->default(0);
            $table->bigInteger('product_group')->default(1);
            $table->integer('grace_period_in_days')->default(0);
            $table->string('CRN', 255)->nullable();
            $table->integer('division_unit_count')->default(1);
            $table->index(['branchs_id'], 'products_branchs_id_foreign');
            $table->index(['user_id'], 'products_user_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
