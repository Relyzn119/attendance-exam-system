<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('token_absensis', 'durasi_menit')) {
            Schema::table('token_absensis', function (Blueprint $table) {
                $table->integer('durasi_menit')->default(60)->after('kode_token');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('token_absensis', 'durasi_menit')) {
            Schema::table('token_absensis', function (Blueprint $table) {
                $table->dropColumn('durasi_menit');
            });
        }
    }
};