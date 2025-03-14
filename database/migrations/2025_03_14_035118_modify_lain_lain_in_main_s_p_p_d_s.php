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
        Schema::table('main_s_p_p_d_s', function (Blueprint $table) {
            $table->jsonb('lain_lain')->nullable()->change();
            $table->jsonb('lain_lain_desc')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_s_p_p_d_s', function (Blueprint $table) {
            $table->string('lain_lain')->nullable()->change();
            $table->integer('lain_lain_desc')->nullable()->change();
        });
    }
};
