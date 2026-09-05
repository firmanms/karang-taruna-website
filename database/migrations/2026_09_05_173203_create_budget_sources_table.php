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
        Schema::create('budget_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('work_programs', function (Blueprint $table) {
            $table->foreignId('budget_source_id')->nullable()->after('budget_amount')->constrained('budget_sources')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_programs', function (Blueprint $table) {
            $table->dropForeign(['budget_source_id']);
            $table->dropColumn('budget_source_id');
        });

        Schema::dropIfExists('budget_sources');
    }
};
