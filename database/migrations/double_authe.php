<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('utilisateur', function (Blueprint $table) {
            if (!Schema::hasColumn('utilisateur', 'google2fa_secret')) {
                $table->text('google2fa_secret')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('utilisateur', function (Blueprint $table) {
            if (Schema::hasColumn('utilisateur', 'google2fa_secret')) {
                $table->dropColumn('google2fa_secret');
            }
        });
    }
};