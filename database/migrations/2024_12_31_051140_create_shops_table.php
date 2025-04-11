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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('ref_id')->constrained('refs')->onDelete('cascade');
            $table->string('owner_name');
            $table->string('contact');
            $table->decimal('credit_limit', 10, 2);
            $table->decimal('credit', 10, 2)->nullable();
            $table->text('address');
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
