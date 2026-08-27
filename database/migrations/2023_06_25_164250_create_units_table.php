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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string( 'name' )->default('-');  
            $table->integer( 'is_master' )->default(1);  
            $table->integer( 'active' )->default(1);  
            $table->string( 'added_by' )->default('-');  
            $table->string( 'note' )->default('-');  
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
        Schema::dropIfExists('units');
    }
};
