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
        Schema::create('variation_type_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_list_id')->constrained('variation_lists')->onDelete('cascade');
            $table->foreignId('variation_id')->constrained('variations')->onDelete('cascade');
            $table->foreignId('variation_type_id')->constrained('variation_types')->onDelete('cascade');
            $table->foreignId('variation_value_id')->constrained('variation_values')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variation_type_lists');
    }
};
