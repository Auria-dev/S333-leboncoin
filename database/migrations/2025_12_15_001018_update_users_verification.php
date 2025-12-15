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
        Schema::table('utilisateur', function (Blueprint $table) {
            // Pour stocker le code SMS
            if (!Schema::hasColumn('utilisateur', 'phone_verification_code')) {
                $table->string('phone_verification_code')->nullable();
            }
            // Pour savoir si c'est validé
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
