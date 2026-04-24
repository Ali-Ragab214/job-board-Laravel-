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
      Schema::create('jobs', function (Blueprint $table) {
    $table->id();

    $table->foreignId('employer_id')
        ->constrained('employers')
        ->onDelete('cascade');

    $table->foreignId('category_id')
        ->nullable()
        ->constrained('job_categories')
        ->nullOnDelete();

    $table->string('title');
    $table->string('slug')->unique();

    $table->text('description');
    $table->text('responsibilities')->nullable();
    $table->text('qualifications')->nullable();

    $table->decimal('salary_min', 10, 2)->nullable();
    $table->decimal('salary_max', 10, 2)->nullable();

    $table->string('location')->nullable();

    $table->enum('work_type', [
        'full_time', 'part_time', 'contract', 'temporary', 'internship'
    ])->default('full_time');

    $table->enum('experience_level', [
        'entry', 'mid', 'senior', 'director', 'executive'
    ])->default('entry');

    $table->enum('status', [
        'open', 'closed', 'draft'
    ])->default('open');

    $table->date('application_deadline')->nullable();

    $table->unsignedBigInteger('views_count')->default(0);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
