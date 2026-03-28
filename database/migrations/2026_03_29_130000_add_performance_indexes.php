<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->index('is_active', 'idx_vehicles_is_active');
            $table->index('is_featured', 'idx_vehicles_is_featured');
            $table->index(['is_active', 'is_featured'], 'idx_vehicles_active_featured');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index('is_published', 'idx_blog_posts_is_published');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->index('is_read', 'idx_contact_messages_is_read');
        });

        Schema::table('evaluation_requests', function (Blueprint $table) {
            $table->index('is_read', 'idx_evaluation_requests_is_read');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index('source', 'idx_customers_source');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex('idx_vehicles_is_active');
            $table->dropIndex('idx_vehicles_is_featured');
            $table->dropIndex('idx_vehicles_active_featured');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex('idx_blog_posts_is_published');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex('idx_contact_messages_is_read');
        });

        Schema::table('evaluation_requests', function (Blueprint $table) {
            $table->dropIndex('idx_evaluation_requests_is_read');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('idx_customers_source');
        });
    }
};
