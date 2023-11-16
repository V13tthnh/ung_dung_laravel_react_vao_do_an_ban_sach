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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->longText('description')->nullable();
            $table->Integer('unit_price')->nullable();
            $table->double('weight');
            $table->string('format')->nullable();
            $table->year('year');
            $table->string('language');
            $table->string('size');
            $table->Integer('num_pages');
            $table->string('slug')->nullable();
            $table->string('translator')->nullable();
            $table->bigInteger('authors_id');
            $table->bigInteger('suppliers_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
