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
        Schema::create('karang_taruna_units', function (Blueprint $table) {
            $table->id();
            $table->enum('unit_level', ['kabupaten', 'kecamatan', 'desa', 'rw']);
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('village_id')->nullable();
            $table->string('rw_number', 10)->nullable();
            $table->string('unit_name', 150);
            $table->string('unit_code', 50)->unique();
            $table->string('chairman_name', 150);
            $table->string('secretary_name', 150)->nullable();
            $table->string('treasurer_name', 150)->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->string('contact_email', 100)->nullable();
            $table->text('office_address')->nullable();
            $table->string('office_photo', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('sk_number', 100)->nullable();
            $table->string('sk_file_path', 255)->nullable();
            $table->year('period_start_year');
            $table->year('period_end_year');
            $table->integer('total_members')->default(0);
            $table->enum('status_aktif', ['Aktif', 'Demisioner', 'Restrukturisasi', 'PJS', 'Nonaktif'])->default('Aktif');
            $table->string('logo_path', 255)->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('ref_districts')->nullOnDelete();
            $table->foreign('village_id')->references('id')->on('ref_villages')->nullOnDelete();
        });

        Schema::create('unit_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('karang_taruna_units')->cascadeOnDelete();
            $table->string('full_name', 150);
            $table->string('position_role', 100);
            $table->string('division_section', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_members');
        Schema::dropIfExists('karang_taruna_units');
    }
};
