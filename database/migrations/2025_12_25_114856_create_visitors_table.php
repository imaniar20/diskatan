<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();

            $table->string('ip', 45); // IPv4 & IPv6
            $table->text('user_agent')->nullable();
            $table->text('url');
            $table->timestamp('visited_at')->useCurrent();

            $table->timestamps();

            // optional index biar cepet
            $table->index('ip');
            $table->index('visited_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};

