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
        Schema::table('parking_pays', function (Blueprint $table) {
            $table->string('message')->default('on time paid');
            $table->string('pay_status')->default(1)->comment('1 on time paid 2 late paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_pays', function (Blueprint $table) {
            //
        });
    }
};
