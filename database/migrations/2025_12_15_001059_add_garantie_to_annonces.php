<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('annonce', function (Blueprint $table) {
            // Pour que l'admin puisse garantir l'annonce
            if (!Schema::hasColumn('annonce', 'est_garantie')) {
                $table->boolean('est_garantie')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('annonce', function (Blueprint $table) {
            $table->dropColumn('est_garantie');
        });
    }
};
