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
        Schema::create('inv_itemcard_categories', function (Blueprint $table) {
            $table->id();
            $table->string( 'name' )->default('-');  
            $table->integer( 'active' )->default(1);  
            $table->bigInteger( 'added_by' )->default(1);
            $table->string( 'note' )->default('-');  
            $table->bigInteger( 'updated_by' )->default(1);
            $table->bigInteger( 'branchs_id' )->default(1);
            $table->bigInteger( 'comp_id' )->default(1);
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
        Schema::dropIfExists('inv_itemcard_categories');
    }
};
