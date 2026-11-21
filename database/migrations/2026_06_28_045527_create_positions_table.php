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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('level')->default(1); // ยิ่งน้อยยิ่งสูง เช่น 1 = อธิการบดี
            $table->integer('sort_order')->default(0);
            $table->string('color')->nullable(); // ใส่ Tailwind Color Class เช่น 'bg-blue-500'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
