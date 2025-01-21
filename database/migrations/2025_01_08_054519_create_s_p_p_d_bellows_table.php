<?php

use App\Models\MainSPPD;
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
        Schema::create('s_p_p_d_bellows', function (Blueprint $table) {
            $table->id();
            $table->string('code_sppd');
            $table->string("date_time_arrive")->nullable();
            $table->string("arrive_at")->nullable();
            $table->string("foto_arrive")->nullable();
            $table->string('maps_tiba')->nullable();

            $table->boolean("continue")->nullable()->default(1);
            $table->string("date_time_destination")->nullable();
            $table->string("foto_destination")->nullable();
            $table->string('maps_tujuan')->nullable();

            //Tiba kembali
            $table->string("nama_diperintah")->nullable();
            $table->string("date_time")->nullable();

            $table->boolean("verify")->nullable()->default(0);
            $table->text("note")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_p_p_d_bellows');
    }
};
