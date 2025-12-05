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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255); // Judul Berita
            $table->string('slug')->unique(); // Slug untuk URL (wajib unik)
            $table->longText('content'); // Konten Lengkap, gunakan longText
            $table->string('image')->nullable(); // Path/Nama file Gambar Utama
            $table->foreignId('user_id')->constrained('users'); // Foreign Key ke tabel users (Penulis)
            $table->enum('status', ['draft', 'published'])->default('draft'); // Status Berita
            $table->integer('views')->default(0); // Jumlah dilihat
            $table->timestamp('published_at')->nullable(); // Kapan berita dipublikasi
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
