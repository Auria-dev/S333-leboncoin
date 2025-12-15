<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('utilisateur', function (Blueprint $table) {
            // On vérifie si les colonnes existent avant de les créer pour éviter les bugs
            if (!Schema::hasColumn('utilisateur', 'phone_verification_code')) {
                $table->string('phone_verification_code')->nullable();
            }
            if (!Schema::hasColumn('utilisateur', 'telephone_verifie')) {
                $table->boolean('telephone_verifie')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('utilisateur', function (Blueprint $table) {
            $table->dropColumn(['phone_verification_code', 'telephone_verifie']);
        });
    }
};