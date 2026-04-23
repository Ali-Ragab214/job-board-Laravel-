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
    Schema::create('applications', function (Blueprint $table) {
        $table->id();

        $table->foreignId('job_id')
            ->constrained('jobs')
            ->onDelete('cascade');

        $table->foreignId('candidate_id')
            ->constrained('candidates')
            ->onDelete('cascade');

        $table->string('resume_path')->nullable();

        $table->text('cover_letter')->nullable();

        $table->enum('status', ['pending', 'accepted', 'rejected'])
            ->default('pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
