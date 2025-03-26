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
            $table->string('nama_kendaraan_lain')->after('alat_angkutan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_s_p_p_d_s', function (Blueprint $table) {
            $table->string('nama_kendaraan_lain');
        });
    }
};
