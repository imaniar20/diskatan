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
        Schema::table('news', function (Blueprint $table) {
            $table->index('published_at');
            $table->index('views');
        });

        // Sinkronisasi data views dari riwayat visitors untuk berita lama yang masih bernilai 0
        try {
            DB::statement("UPDATE news SET views = (SELECT COUNT(*) FROM visitors WHERE visitors.url LIKE CONCAT('%', news.slug)) WHERE views = 0");
        } catch (\Throwable $e) {
            // Lewati jika terjadi kegagalan sintaks atau database lock
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex(['published_at']);
            $table->dropIndex(['views']);
        });
    }
};
