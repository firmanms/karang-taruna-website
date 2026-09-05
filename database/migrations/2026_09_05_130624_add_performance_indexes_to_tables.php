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
        // 1. Indexes on articles table for fast public & scoped queries
        Schema::table('articles', function (Blueprint $table) {
            $table->index(['approval_status', 'is_published', 'published_at'], 'idx_articles_pub');
            $table->index(['news_scope', 'approval_status'], 'idx_articles_scope');
            $table->index(['district_id', 'approval_status'], 'idx_articles_dist');
            $table->index(['unit_id', 'approval_status'], 'idx_articles_unit');
        });

        // 2. Indexes on events table
        Schema::table('events', function (Blueprint $table) {
            $table->index(['approval_status', 'event_date'], 'idx_events_date');
            $table->index(['unit_id', 'approval_status'], 'idx_events_unit');
            $table->index(['district_id', 'approval_status'], 'idx_events_dist');
        });

        // 3. Indexes on work_programs table
        Schema::table('work_programs', function (Blueprint $table) {
            $table->index(['approval_status', 'is_featured_home'], 'idx_wp_featured');
            $table->index(['unit_id', 'approval_status'], 'idx_wp_unit');
        });

        // 4. Indexes on ppks_beneficiaries table
        Schema::table('ppks_beneficiaries', function (Blueprint $table) {
            $table->index(['verification_status', 'district_id'], 'idx_ppks_dist');
            $table->index(['unit_id', 'verification_status'], 'idx_ppks_unit');
        });

        // 5. Indexes on karang_taruna_units table
        Schema::table('karang_taruna_units', function (Blueprint $table) {
            $table->index(['is_verified', 'unit_level'], 'idx_units_level');
            $table->index(['district_id', 'status_aktif'], 'idx_units_dist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karang_taruna_units', function (Blueprint $table) {
            $table->dropIndex('idx_units_level');
            $table->dropIndex('idx_units_dist');
        });

        Schema::table('ppks_beneficiaries', function (Blueprint $table) {
            $table->dropIndex('idx_ppks_dist');
            $table->dropIndex('idx_ppks_unit');
        });

        Schema::table('work_programs', function (Blueprint $table) {
            $table->dropIndex('idx_wp_featured');
            $table->dropIndex('idx_wp_unit');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_events_date');
            $table->dropIndex('idx_events_unit');
            $table->dropIndex('idx_events_dist');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('idx_articles_pub');
            $table->dropIndex('idx_articles_scope');
            $table->dropIndex('idx_articles_dist');
            $table->dropIndex('idx_articles_unit');
        });
    }
};
