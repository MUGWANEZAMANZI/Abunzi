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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('dispute_id')->constrained()->onDelete('cascade');
            $table->text('victim_resolution')->nullable();
            $table->text('offender_resolution')->nullable();
            $table->text('witnesses')->nullable();
            $table->text('attendees')->nullable();
            $table->text('justice_resolution')->nullable();
            $table->string('evidence_path')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });



        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};