<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('smartcar_access_token')->nullable();
            $table->text('smartcar_refresh_token')->nullable();
            $table->timestamp('smartcar_expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['smartcar_access_token', 'smartcar_refresh_token', 'smartcar_expires_at']);
        });
    }
};


