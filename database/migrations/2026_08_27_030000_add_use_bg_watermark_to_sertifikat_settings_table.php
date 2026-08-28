<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sertifikat_settings', 'use_bg_watermark')) {
            Schema::table('sertifikat_settings', function (Blueprint $table) {
                $table->boolean('use_bg_watermark')->default(true)->after('tipe_ttd');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sertifikat_settings', 'use_bg_watermark')) {
            Schema::table('sertifikat_settings', function (Blueprint $table) {
                $table->dropColumn('use_bg_watermark');
            });
        }
    }
};
