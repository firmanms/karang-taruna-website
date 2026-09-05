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
        Schema::create('event_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->foreignId('unit_id')->constrained('karang_taruna_units')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('village_id')->nullable();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location_venue', 255);
            $table->text('location_address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('organizer', 150);
            $table->text('description')->nullable();
            $table->string('registration_link', 255)->nullable();
            $table->enum('event_status', ['Mendatang', 'Sedang Berlangsung', 'Selesai', 'Dibatalkan'])->default('Mendatang');
            $table->enum('approval_status', ['draft', 'pending_approval', 'approved', 'rejected', 'revision_required'])->default('pending_approval');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('thumbnail_image', 255)->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('event_categories')->cascadeOnDelete();
            $table->foreign('district_id')->references('id')->on('ref_districts')->nullOnDelete();
            $table->foreign('village_id')->references('id')->on('ref_villages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_categories');
    }
};
