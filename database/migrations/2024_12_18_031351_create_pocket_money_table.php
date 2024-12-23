<?php

use App\Models\Eslon;
use App\Models\Region;
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
        Schema::create('pocket_money', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Eslon::class);
            $table->foreignIdFor(Region::class);
            $table->integer('anggaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pocket_money');
    }
};
