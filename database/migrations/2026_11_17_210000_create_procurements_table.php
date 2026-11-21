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
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('procurement_type')->nullable(); // e-Bidding, ประกาศราคากลาง, ประกาศผู้ชนะการเสนอราคา, เผยแพร่แผนจัดซื้อจัดจ้าง, สอบราคา
            $table->string('budget')->nullable(); // วงเงินงบประมาณ / ราคากลาง
            $table->text('description')->nullable();
            $table->date('published_at')->nullable();
            $table->date('deadline_at')->nullable(); // วันสิ้นสุดรับข้อเสนอ
            $table->string('status')->default('active'); // active (เปิดรับข้อเสนอ), closed (สิ้นสุดรับข้อเสนอ), awarded (ประกาศผู้ชนะแล้ว)
            $table->unsignedBigInteger('views_count')->default(0);
            $table->string('uploader_name')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('image')->nullable(); // รูปภาพประกาศ / ปกเอกสาร
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
        Schema::dropIfExists('procurements');
    }
};
