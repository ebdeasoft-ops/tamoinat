<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_code')->unsigned();
            $table->string('barcode');
            $table->string('name');
            $table->integer('item_type');
            $table->integer('inv_itemcard_categories_id');
            $table->bigInteger('parent_inv_itemcard_id')->unsigned();
            $table->integer('does_has_retailunit');
            $table->bigInteger('retail_uom_id')->default(0);
            $table->bigInteger('uom_id')->unsigned();
            $table->float('retail_uom_quntToParent')->default(0);
            $table->integer('added_by');
            $table->integer('updated_by');
            $table->integer('active');
            $table->date('date');
            $table->date('expaire_date')->nullable();
            $table->integer('branchs_id')->default(0);
            $table->integer('minmum_quantity_stock_alart')->default(0);
            $table->string('Product_Location')->default('A12');
            $table->unsignedDouble('price')->default(0);
            $table->unsignedDouble('price_retail')->default(0);
            $table->unsignedDouble('cost_price')->default(0);
            $table->unsignedDouble('cost_price_retail')->default(0);
            $table->integer('has_fixced_price')->default(0);
            $table->float('All_QUENTITY')->default(0);
            $table->float('QUENTITY')->default(0);
            $table->float('QUENTITY_Retail')->default(0);
            $table->float('QUENTITY_all_Retails')->default(0);
            $table->string('photo')->default('-');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
