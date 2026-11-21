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
        Schema::create('college_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('icon')->default('fas fa-chart-pie');
            $table->string('color')->default('maroon');
            $table->string('chart_type')->default('doughnut');
            $table->unsignedBigInteger('total_count')->default(0);
            $table->string('unit')->default('คน');
            $table->json('items')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('college_statistics');
    }
};
