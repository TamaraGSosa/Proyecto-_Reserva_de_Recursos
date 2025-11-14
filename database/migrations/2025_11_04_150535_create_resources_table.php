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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('marca')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('status_resource_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('status_resource_id')->references('id')->on('status_resources');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
