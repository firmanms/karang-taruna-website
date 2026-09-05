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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('karang_taruna_units')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('village_id')->nullable();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('recipient_name', 150);
            $table->enum('achievement_level', ['Kabupaten', 'Provinsi', 'Nasional', 'Internasional'])->default('Kabupaten');
            $table->string('category_field', 100);
            $table->year('year');
            $table->string('rank_position', 50);
            $table->string('awarded_by', 150);
            $table->text('description')->nullable();
            $table->string('certificate_image', 255)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('approval_status', ['draft', 'pending_approval', 'approved', 'rejected', 'revision_required'])->default('pending_approval');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('ref_districts')->nullOnDelete();
            $table->foreign('village_id')->references('id')->on('ref_villages')->nullOnDelete();
        });

        Schema::create('download_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('downloads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('document_title', 255);
            $table->text('description')->nullable();
            $table->string('file_path', 255);
            $table->string('file_format', 20)->default('PDF');
            $table->unsignedInteger('file_size_kb')->default(0);
            $table->unsignedInteger('download_count')->default(0);
            $table->date('release_date');
            $table->enum('approval_status', ['draft', 'pending_approval', 'approved', 'rejected'])->default('approved');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_public')->default(true);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('download_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
        Schema::dropIfExists('download_categories');
        Schema::dropIfExists('achievements');
    }
};
