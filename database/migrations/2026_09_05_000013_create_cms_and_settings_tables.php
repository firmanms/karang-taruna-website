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
        Schema::create('hero_sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('subtitle_eyebrow', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('image_path', 255);
            $table->string('button_primary_text', 50)->nullable();
            $table->string('button_primary_url', 255)->nullable();
            $table->string('button_secondary_text', 50)->nullable();
            $table->string('button_secondary_url', 255)->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('profile_organization', function (Blueprint $table) {
            $table->increments('id');
            $table->string('org_name', 150);
            $table->string('legal_basis', 255)->nullable();
            $table->longText('history_content')->nullable();
            $table->text('vision');
            $table->string('logo_path', 255)->nullable();
            $table->string('sk_number', 100)->nullable();
            $table->string('sk_file_path', 255)->nullable();
            $table->string('period_years', 50)->nullable();
            $table->text('address_office')->nullable();
            $table->string('office_photo', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('email_official', 100)->nullable();
            $table->string('phone_official', 30)->nullable();
            $table->timestamps();
        });

        Schema::create('organization_missions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('profile_id');
            $table->text('mission_text');
            $table->integer('order_index')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('profile_id')->references('id')->on('profile_organization')->cascadeOnDelete();
        });

        Schema::create('organization_values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icon_class', 50)->default('ti ti-star');
            $table->string('title', 100);
            $table->text('description');
            $table->integer('order_index')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('question');
            $table->longText('answer');
            $table->string('category', 100)->default('Umum');
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('territory_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stat_key', 50)->unique();
            $table->string('stat_label', 100);
            $table->integer('stat_value');
            $table->string('icon_class', 50)->default('ti ti-map-pin');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('user_agent', 255)->nullable();
            $table->string('page_url', 255);
            $table->string('session_id', 100)->nullable();
            $table->date('visit_date');
            $table->timestamp('last_activity')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('visitor_aggregates', function (Blueprint $table) {
            $table->increments('id');
            $table->date('record_date')->unique();
            $table->unsignedInteger('daily_visits')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('setting_group', 50)->default('general');
            $table->string('setting_key', 100)->unique();
            $table->text('setting_value')->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('visitor_aggregates');
        Schema::dropIfExists('visitor_logs');
        Schema::dropIfExists('territory_statistics');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('organization_values');
        Schema::dropIfExists('organization_missions');
        Schema::dropIfExists('profile_organization');
        Schema::dropIfExists('hero_sliders');
    }
};
