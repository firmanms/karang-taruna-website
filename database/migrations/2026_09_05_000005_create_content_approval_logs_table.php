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
        Schema::create('content_approval_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('content_type', [
                'article',
                'event',
                'work_program',
                'achievement',
                'gallery_photo',
                'gallery_video',
                'ppks_beneficiary',
                'download',
            ]);
            $table->unsignedBigInteger('content_id');
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('action_status', ['submitted', 'approved', 'rejected', 'revision_required']);
            $table->text('review_notes')->nullable();
            $table->timestamp('action_timestamp')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_approval_logs');
    }
};
