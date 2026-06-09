<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasilitas', 100)->unique();
            $table->timestamps();
        });

        // Seed default facilities
        $defaultFasilitas = ['Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Gazebo', 'Penginapan', 'Area Bermain', 'Spot Foto', 'WiFi'];
        foreach ($defaultFasilitas as $fas) {
            DB::table('fasilitas')->insertOrIgnore([
                'nama_fasilitas' => $fas,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};
