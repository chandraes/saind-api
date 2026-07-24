<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('allowed_ip');

            // Tambahkan kolom baru: JSON untuk multiple IP, dan Boolean untuk bypass
            $table->json('allowed_ips')->nullable()->after('name');
            $table->boolean('bypass_ip_whitelist')->default(false)->after('allowed_ips');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('allowed_ip', 45)->nullable();
            $table->dropColumn(['allowed_ips', 'bypass_ip_whitelist']);
        });
    }
};
