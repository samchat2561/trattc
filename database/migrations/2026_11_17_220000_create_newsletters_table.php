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
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('issue_no')->nullable(); // ฉบับที่ เช่น "ฉบับที่ 24/2569"
            $table->string('category')->nullable(); // หมวดหมู่ เช่น ข่าวกิจกรรม, ข่าววิชาการ, ข่าวทั่วไป
            $table->text('description')->nullable();
            $table->date('published_at')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->string('uploader_name')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('image')->nullable(); // รูปภาพหน้าปกจดหมายข่าว
            $table->json('attachments')->nullable(); // ไฟล์แนบ (pdf, docx, xlsx, pptx, zip)
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
        Schema::dropIfExists('newsletters');
    }
};
