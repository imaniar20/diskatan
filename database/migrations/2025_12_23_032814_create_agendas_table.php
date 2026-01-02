<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();

            $table->int('user_id')->nullable();
            $table->int('program_id')->nullable();
            $table->string('title');
            $table->string('thumbnail')->nullable();
            $table->longText('content')->nullable();

            $table->date('date');
            $table->string('location')->nullable();
            $table->string('bidang')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
 