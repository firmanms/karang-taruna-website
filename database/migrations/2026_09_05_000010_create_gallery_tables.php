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
        Schema::create('photo_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('photo_galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->foreignId('unit_id')->constrained('karang_taruna_units')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('district_id')->nullable();
            $table->string('title', 255);
            $table->text('caption_description')->nullable();
            $table->string('image_path', 255);
            $table->string('location', 150)->nullable();
            $table->date('event_date')->nullable();
            $table->integer('order_index')->default(0);
            $table->enum('approval_status', ['draft', 'pending_approval', 'approved', 'rejected'])->default('pending_approval');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('photo_categories')->cascadeOnDelete();
            $table->foreign('district_id')->references('id')->on('ref_districts')->nullOnDelete();
        });

        Schema::create('video_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('karang_taruna_units')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('category', 100)->default('Dokumentasi');
            $table->enum('video_platform', ['youtube', 'vimeo', 'local_mp4'])->default('youtube');
            $table->string('video_url', 255);
            $table->string('video_id', 100)->nullable();
            $table->string('thumbnail_path', 255)->nullable();
            $table->string('duration_text', 20)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('approval_status', ['draft', 'pending_approval', 'approved', 'rejected'])->default('pending_approval');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_galleries');
        Schema::dropIfExists('photo_galleries');
        Schema::dropIfExists('photo_categories');
    }
};
