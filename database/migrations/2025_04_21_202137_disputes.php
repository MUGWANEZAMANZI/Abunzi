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
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('offender_name');
            $table->string('offender_phone')->nullable()->default(null);
            $table->string('witness_name')->nullable()->default(null);
            $table->string('province')->nullable()->default(null);
            $table->string('district')->nullable()->default(null);
            $table->string('sector')->nullable()->default(null);
            $table->string('cell')->nullable()->default(null);
            $table->string('village')->nullable()->default(null);
            $table->enum('status', ['kirabitse', 'cyoherejwe', 'kizasomwa', 'cyakemutse']);
            $table->foreignId('citizen_id')->constrained('users')->onDelete('cascade');
            $table->string('location_name'); // Changed from location_id to location_name
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
