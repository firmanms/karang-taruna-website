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
        Schema::create('announcements', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('karang_taruna_units')->cascadeOnDelete();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('announcement_number', 100)->nullable();
            $table->enum('category', ['Beasiswa', 'Edaran Resmi', 'Seleksi', 'Bantuan Sosial', 'Umum'])->default('Umum');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('attachment_file', 255)->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_pinned')->default(false);
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
        Schema::dropIfExists('announcements');
    }
};
