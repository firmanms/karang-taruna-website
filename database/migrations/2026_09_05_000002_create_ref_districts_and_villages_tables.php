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
        Schema::create('ref_districts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kemendagri_code', 20)->unique();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->decimal('latitude_center', 10, 8)->nullable();
            $table->decimal('longitude_center', 11, 8)->nullable();
            $table->longText('geojson_boundary')->nullable();
            $table->timestamps();
        });

        Schema::create('ref_villages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('district_id');
            $table->string('kemendagri_code', 20)->unique();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->enum('type', ['Desa', 'Kelurahan'])->default('Desa');
            $table->string('postal_code', 10)->nullable();
            $table->integer('total_rw')->default(0);
            $table->integer('total_rt')->default(0);
            $table->decimal('latitude_center', 10, 8)->nullable();
            $table->decimal('longitude_center', 11, 8)->nullable();
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('ref_districts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_villages');
        Schema::dropIfExists('ref_districts');
    }
};
