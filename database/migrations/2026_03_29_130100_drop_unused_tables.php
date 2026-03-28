<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sent_emails')) {
            Schema::drop('sent_emails');
        }

        if (Schema::hasTable('vehicle_requests')) {
            Schema::drop('vehicle_requests');
        }

        if (Schema::hasTable('vehicle_status_logs')) {
            Schema::drop('vehicle_status_logs');
        }

        // Only drop pages if it exists, has 0 rows, and legal_pages exists as replacement
        if (Schema::hasTable('pages') && Schema::hasTable('legal_pages')) {
            $count = DB::table('pages')->count();
            if ($count === 0) {
                Schema::drop('pages');
            }
        }
    }

    public function down(): void
    {
        // These tables are intentionally not recreated in down().
        // Restore from backup if needed.
    }
};
