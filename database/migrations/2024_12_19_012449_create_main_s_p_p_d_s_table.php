<?php

use App\Models\Budget;
use App\Models\Eslon;
use App\Models\User;
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
        Schema::create('main_s_p_p_d_s', function (Blueprint $table) {
            $table->id();
            $table->string('auth_official'); // Yang memerintah
            $table->foreignIdFor(User::class); // Yang diperintah
            $table->foreignIdFor(Eslon::class); // Jabatan
            $table->string('maksud_perjalanan');
            $table->string('alat_angkutan');
            $table->string('tempat_berangkat');
            $table->string('maps_berangkat');
            $table->string('tempat_tujuan');

            $table->string('lama_perjalanan');
            $table->string('date_time_berangkat');
            $table->string('date_time_kembali');

            $table->string('nama_pengikut')->nullable();
            $table->string('jabatan_pengikut')->nullable();
            $table->string('uang_saku');
            $table->integer("e_toll")->default(0);
            $table->integer("makan")->default(0);
            $table->string("lain_lain_desc")->nullable();
            $table->integer("lain_lain")->default(0);

            $table->string("date_time_arrive")->nullable();
            $table->string("arrive_at")->nullable();
            $table->string("foto_arrive")->nullable();

            $table->boolean("continue")->default(0);
            $table->string("date_time_destination")->nullable();
            $table->string("foto_destination")->nullable();

            //Tiba kembali
            $table->string("nama_diperintah")->nullable();
            $table->string("date_time")->nullable();

            $table->boolean("verify")->default(0);
            $table->text("note")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_s_p_p_d_s');
    }
};
