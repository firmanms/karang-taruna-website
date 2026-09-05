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
        Schema::create('program_divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('division_name', 150);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('work_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('division_id');
            $table->foreignId('unit_id')->constrained('karang_taruna_units')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('program_name', 255);
            $table->string('slug', 255)->unique();
            $table->string('program_code', 50)->nullable();
            $table->string('target_participants', 255);
            $table->text('output_indicators');
            $table->decimal('budget_amount', 15, 2)->default(0.00);
            $table->string('budget_source', 100)->default('APBD / Swadana');
            $table->string('execution_time', 100);
            $table->string('poster_image', 255)->nullable();
            $table->text('short_description')->nullable();
            $table->longText('detailed_description')->nullable();
            $table->enum('progress_status', ['Rencana', 'Sedang Berjalan', 'Selesai', 'Ditunda'])->default('Rencana');
            $table->enum('approval_status', ['draft', 'pending_approval', 'approved', 'rejected', 'revision_required'])->default('pending_approval');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_featured_home')->default(false);
            $table->timestamps();

            $table->foreign('division_id')->references('id')->on('program_divisions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_programs');
        Schema::dropIfExists('program_divisions');
    }
};
