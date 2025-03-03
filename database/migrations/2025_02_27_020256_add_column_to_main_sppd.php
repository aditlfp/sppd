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
        Schema::table('s_p_p_d_bellows', function (Blueprint $table) {
            $table->string('departed_at')->after('foto_arrive')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('s_p_p_d_bellows', function (Blueprint $table) {
            $table->dropColumn('departed_at');
        });
    }
};
