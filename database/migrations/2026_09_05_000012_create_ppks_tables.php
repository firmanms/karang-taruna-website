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
        Schema::create('ppks_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_code', 20)->unique();
            $table->string('category_name', 150);
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('ppks_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 64)->unique();
            $table->string('full_name', 150);
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('district_id');
            $table->unsignedInteger('village_id');
            $table->foreignId('unit_id')->constrained('karang_taruna_units')->cascadeOnDelete();
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->text('address_detail')->nullable();
            $table->string('social_assistance_status', 255);
            $table->string('mentor_unit', 150)->default('Karang Taruna Desa Setempat');
            $table->date('last_survey_date')->nullable();
            $table->enum('verification_status', ['pending_verification', 'verified', 'rejected'])->default('pending_verification');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('ppks_categories')->cascadeOnDelete();
            $table->foreign('district_id')->references('id')->on('ref_districts')->cascadeOnDelete();
            $table->foreign('village_id')->references('id')->on('ref_villages')->cascadeOnDelete();
        });

        Schema::create('ppks_check_logs', function (Blueprint $table) {
            $table->id();
            $table->string('searched_nik_masked', 20);
            $table->string('ip_address', 45);
            $table->string('user_agent', 255)->nullable();
            $table->enum('result_status', ['found', 'not_found', 'invalid_captcha']);
            $table->timestamp('searched_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppks_check_logs');
        Schema::dropIfExists('ppks_beneficiaries');
        Schema::dropIfExists('ppks_categories');
    }
};
