<?php

namespace Database\Seeders;

use App\Models\TeacherCongratulation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TeacherCongratulationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : null;

        $items = [
            [
                'title' => 'นางสาวสุภาพร สายจันทร์',
                'position' => 'ครูผู้ช่วย ประจำแผนกวิชาการโรงแรม',
                'description' => 'ขอแสดงความยินดีกับ นางสาวสุภาพร สายจันทร์ เนื่องในโอกาสได้รับการบรรจุแต่งตั้งเป็นข้าราชการครูและบุคลากรทางการศึกษา ตำแหน่ง ครูผู้ช่วย ประจำแผนกวิชาการโรงแรม สู่รั้ววิทยาลัยเทคนิคตราด ด้วยความยินดียิ่ง',
                'facebook_url' => 'https://www.facebook.com/trattech.ac.th',
                'published_at' => Carbon::now()->subDays(1),
                'views_count' => 24,
                'uploader_name' => 'นายศิริรักษ์',
                'user_id' => $adminId,
                'sort_order' => 1,
                'is_active' => true,
                'file' => public_path('images/congratulations/teacher1.svg'),
            ],
            [
                'title' => 'นางสาวศิริรินทร์ วงค์คำลือ',
                'position' => 'ครูผู้ช่วย ประจำแผนกวิชาการตลาด',
                'description' => 'ขอแสดงความยินดีกับ นางสาวศิริรินทร์ วงค์คำลือ เนื่องในโอกาสได้รับการบรรจุแต่งตั้งเป็นข้าราชการครูและบุคลากรทางการศึกษา ตำแหน่ง ครูผู้ช่วย ประจำแผนกวิชาการตลาด สู่รั้ววิทยาลัยเทคนิคตราด ด้วยความยินดียิ่ง',
                'facebook_url' => 'https://www.facebook.com/trattech.ac.th',
                'published_at' => Carbon::now()->subDays(2),
                'views_count' => 53,
                'uploader_name' => 'นายศิริรักษ์',
                'user_id' => $adminId,
                'sort_order' => 2,
                'is_active' => true,
                'file' => public_path('images/congratulations/teacher2.svg'),
            ],
            [
                'title' => 'นางสาวมยุรินทร์ พิชัย',
                'position' => 'ครูผู้ช่วย ประจำแผนกวิชาอาหารและโภชนาการ',
                'description' => 'ขอแสดงความยินดีกับ นางสาวมยุรินทร์ พิชัย เนื่องในโอกาสได้รับการบรรจุแต่งตั้งเป็นข้าราชการครูและบุคลากรทางการศึกษา ตำแหน่ง ครูผู้ช่วย ประจำแผนกวิชาอาหารและโภชนาการ สู่รั้ววิทยาลัยเทคนิคตราด ด้วยความยินดียิ่ง',
                'facebook_url' => 'https://www.facebook.com/trattech.ac.th',
                'published_at' => Carbon::now()->subDays(3),
                'views_count' => 48,
                'uploader_name' => 'นายศิริรักษ์',
                'user_id' => $adminId,
                'sort_order' => 3,
                'is_active' => true,
                'file' => public_path('images/congratulations/teacher3.svg'),
            ],
            [
                'title' => 'นางสาวปัทมา คำยอด',
                'position' => 'ครูผู้ช่วย ประจำแผนกวิชาการบัญชี',
                'description' => 'ขอแสดงความยินดีกับ นางสาวปัทมา คำยอด เนื่องในโอกาสได้รับการบรรจุแต่งตั้งเป็นข้าราชการครูและบุคลากรทางการศึกษา ตำแหน่ง ครูผู้ช่วย ประจำแผนกวิชาการบัญชี สู่รั้ววิทยาลัยเทคนิคตราด ด้วยความยินดียิ่ง',
                'facebook_url' => 'https://www.facebook.com/trattech.ac.th',
                'published_at' => Carbon::now()->subDays(4),
                'views_count' => 25,
                'uploader_name' => 'นายศิริรักษ์',
                'user_id' => $adminId,
                'sort_order' => 4,
                'is_active' => true,
                'file' => public_path('images/congratulations/teacher4.svg'),
            ],
        ];

        foreach ($items as $data) {
            $filePath = $data['file'];
            unset($data['file']);

            $existing = TeacherCongratulation::where('title', $data['title'])->first();
            if (!$existing) {
                $record = TeacherCongratulation::create($data);
                if (file_exists($filePath)) {
                    $record->addMedia($filePath)
                        ->preservingOriginal()
                        ->toMediaCollection('poster');
                }
            }
        }
    }
}
