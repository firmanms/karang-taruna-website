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
        Schema::create('news_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->enum('type', ['pusat', 'daerah', 'umum'])->default('umum');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('karang_taruna_units')->cascadeOnDelete();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('village_id')->nullable();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image', 255);
            $table->string('image_caption', 255)->nullable();
            $table->enum('news_scope', ['pusat', 'daerah'])->default('daerah');
            $table->string('tags', 255)->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->enum('approval_status', ['draft', 'pending_approval', 'approved', 'rejected', 'revision_required'])->default('pending_approval');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('news_categories')->cascadeOnDelete();
            $table->foreign('district_id')->references('id')->on('ref_districts')->nullOnDelete();
            $table->foreign('village_id')->references('id')->on('ref_villages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('news_categories');
    }
};
