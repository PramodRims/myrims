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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediaable'); // Polymorphic relation columns: fileable_id and fileable_type
            $table->string('file_name')->nullable(); // Name of the uploaded file
            $table->string('file_type')->nullable(); // Type of file (e.g., 'video', 'document')
            $table->string('file_url'); // Path to the file in storage
            $table->string('title')->nullable(); // Title of the file
            $table->string('author')->nullable(); // Author of the file
            $table->date('date'); // Date related to the file
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
