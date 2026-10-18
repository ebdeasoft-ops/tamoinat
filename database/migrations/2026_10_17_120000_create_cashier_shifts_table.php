<?php

use App\Models\CashierShift;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        CashierShift::createTable();
    }

    public function down()
    {
        Schema::dropIfExists('cashier_shifts');
    }
};
