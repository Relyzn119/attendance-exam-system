<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('bank_soals', 'tingkat_kesulitan')) {
            Schema::table('bank_soals', function (Blueprint $table) {
                $table->enum('tingkat_kesulitan', ['tidak_ada', 'mudah', 'normal', 'sulit'])->default('tidak_ada')->after('kunci_jawaban');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('bank_soals', 'tingkat_kesulitan')) {
            Schema::table('bank_soals', function (Blueprint $table) {
                $table->dropColumn('tingkat_kesulitan');
            });
        }
    }
};
