<?php

use Livewire\Volt\Volt;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::livewire('/executive-board', 'committee')->name('executive.board');

// Route::get('/debug-db', function () {
//     try {
//         // 1. เช็คชื่อฐานข้อมูลที่กำลังใช้อยู่
//         $dbName = DB::connection()->getDatabaseName();

//         // 2. ดึงข้อมูลทั้งหมดในตาราง categories มาดูว่ามีอะไรบ้าง
//         $categories = Category::all()->map(function ($item) {
//             return [
//                 'id' => $item->id,
//                 'slug' => "'{$item->slug}'", // ใช้เครื่องหมาย ' ครอบเพื่อดูช่องว่าง
//                 'name' => $item->name,
//                 'active' => $item->is_active
//             ];
//         });
//         return response()->json([
//             'status' => 'Connected!',
//             'database' => $dbName,
//             'count' => $categories->count(),
//             'data' => $categories
//         ]);
//     } catch (\Exception $e) {
//         return "เกิดข้อผิดพลาดในการเชื่อมต่อ: " . $e->getMessage();
//     }
// });

Route::livewire('/', 'index')->name('index');
Route::livewire('/app', 'home-page')->name('home');

Route::livewire('/category/{category:slug}', 'category-page')->name('category.show');
Route::livewire('/post/tags/{slug}', 'tag-page')->name('posts.bytag');

// Route::livewire('/comment', 'comment-page')->name('comments.store'); //ไม่ได้ใช้
Route::livewire('/{post:slug}', 'post-page')->name('posts.show');
Route::livewire('/gallery/{image:slug}', 'image-page')->name('images.show');

// ใน v4 ต้องดึงไฟล์เข้ามาทำงานก่อน เพื่อให้แอปพลิเคชันมองเห็น Class ที่สร้างไว้ข้างใน
// include_once resource_path('views/components/⚡committee.blade.php');

// ชี้ตรงไปยังไฟล์ executive-committee ในโฟลเดอร์ livewire
// Route::livewire('/executive-board', 'committee')->name('executive.board');
// Route::view('/executive-board', 'components.⚡committee')->name('executive.board');



// เรียกใช้งาน Class ผ่านคำสั่งมาตรฐานได้เลยครับ
// Route::get('/executive-board', \App\Livewire\ExecutiveCommittee::class)->name('executive.board');
// Route::view('/executive-board', 'components.⚡committee')->name('executive.board');

