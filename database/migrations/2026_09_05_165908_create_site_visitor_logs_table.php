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
        Schema::create('site_visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('session_id', 100)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('page_url', 255)->nullable();
            $table->date('visit_date');
            $table->timestamp('last_activity_at')->useCurrent();
            $table->timestamps();

            $table->index(['visit_date', 'ip_address']);
            $table->index('last_activity_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visitor_logs');
    }
};
