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
        Schema::table('padi_palawijas', function (Blueprint $table) {
            $table->string('kategori', 30)->default('tanam')->after('komoditas');
            $table->string('satuan', 20)->default('Ha')->after('kategori');
            $table->decimal('persentase_sub', 8, 2)->default(0)->after('persentase_capaian');

            $table->index(['komoditas', 'kategori', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('padi_palawijas', function (Blueprint $table) {
            $table->dropIndex(['komoditas', 'kategori', 'tahun']);
            $table->dropColumn(['kategori', 'satuan', 'persentase_sub']);
        });
    }
};
