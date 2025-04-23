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
        Schema::create('dispute_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispute_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['drafted', 'sent', 'assigned', 'resolved']);
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispute_statuses');
    }
};
