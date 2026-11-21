<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : null;

        $items = [
            [
                'title' => 'รางวัลชนะเลิศ เหรียญทอง การประกวดสิ่งประดิษฐ์ของคนรุ่นใหม่ ระดับชาติ',
                'position' => 'ผลงาน "ระบบสมาร์ตฟาร์มอัจฉริยะ IOT" แผนกวิชาช่างอิเล็กทรอนิกส์',
                'description' => 'ขอแสดงความยินดีกับคณะครูและนักศึกษา แผนกวิชาช่างอิเล็กทรอนิกส์และเทคโนโลยีสารสนเทศ วิทยาลัยเทคนิคตราด ได้รับรางวัลชนะเลิศ เหรียญทอง ในการประกวดสิ่งประดิษฐ์ของคนรุ่นใหม่ ประจำปีการศึกษา 2568 ณ ศูนย์แสดงสินค้าและการประชุม อิมแพ็ค เมืองทองธานี',
                'facebook_url' => 'https://www.facebook.com/trattech.ac.th',
                'published_at' => Carbon::now()->subDays(1),
                'views_count' => 128,
                'uploader_name' => 'นายศิริรักษ์',
                'user_id' => $adminId,
                'sort_order' => 1,
                'is_active' => true,
                'file' => public_path('images/achievements/award1.jpg'),
            ],
            [
                'title' => 'รางวัลชนะเลิศ เหรียญทอง ทักษะงานยานยนต์ ระดับ ปวส. แข่งขันทักษะวิชาชีพระดับภาค',
                'position' => 'นายธนกฤต มณีรัตน์ และคณะ แผนกวิชาช่างยนต์',
                'description' => 'ขอแสดงความยินดีกับตัวแทนนักศึกษาระดับ ปวส. แผนกวิชาช่างยนต์ คว้ารางวัลชนะเลิศ เหรียญทอง ทักษะงานยานยนต์ ในงานประชุมวิชาการองค์การนักวิชาชีพในอนาคตแห่งประเทศไทย (อวท.) ระดับภาคตะวันออกและกรุงเทพมหานคร ได้เป็นตัวแทนเข้าแข่งขันระดับชาติต่อไป',
                'facebook_url' => 'https://www.facebook.com/trattech.ac.th',
                'published_at' => Carbon::now()->subDays(2),
                'views_count' => 95,
                'uploader_name' => 'นายศิริรักษ์',
                'user_id' => $adminId,
                'sort_order' => 2,
                'is_active' => true,
                'file' => public_path('images/achievements/award2.jpg'),
            ],
            [
                'title' => 'เกียรติยศสูงสุด สถานศึกษารางวัลพระราชทาน ระดับการศึกษาวิชาชีพ ขนาดใหญ่',
                'position' => 'วิทยาลัยเทคนิคตราด สำนักงานคณะกรรมการการอาชีวศึกษา',
                'description' => 'ความภาคภูมิใจอย่างยิ่งของคณะผู้บริหาร ครู บุคลากร และนักเรียน นักศึกษา วิทยาลัยเทคนิคตราด ได้รับการคัดเลือกเป็นสถานศึกษารางวัลพระราชทาน ระดับการศึกษาวิชาชีพ (ขนาดใหญ่) ประจำปีการศึกษา 2567-2568 จากกระทรวงศึกษาธิการ',
                'facebook_url' => 'https://www.facebook.com/trattech.ac.th',
                'published_at' => Carbon::now()->subDays(3),
                'views_count' => 210,
                'uploader_name' => 'นายศิริรักษ์',
                'user_id' => $adminId,
                'sort_order' => 3,
                'is_active' => true,
                'file' => public_path('images/achievements/award3.jpg'),
            ],
            [
                'title' => 'รางวัลครูผู้ฝึกสอนยอดเยี่ยม ระดับเหรียญทอง การแข่งขันทักษะวิชาชีพระดับชาติ',
                'position' => 'นายสุรศักดิ์ วงศ์สวรรค์ แผนกวิชาการบัญชี',
                'description' => 'ขอแสดงความยินดีและเชิดชูเกียรติ นายสุรศักดิ์ วงศ์สวรรค์ ครูผู้ฝึกสอนยอดเยี่ยม ได้รับรางวัลระดับเหรียญทอง การแข่งขันทักษะการใช้โปรแกรมสำเร็จรูปเพื่องานบัญชี ระดับชาติ ในงานประชุมวิชาการ อวท. ระดับชาติ',
                'facebook_url' => 'https://www.facebook.com/trattech.ac.th',
                'published_at' => Carbon::now()->subDays(4),
                'views_count' => 74,
                'uploader_name' => 'นายศิริรักษ์',
                'user_id' => $adminId,
                'sort_order' => 4,
                'is_active' => true,
                'file' => public_path('images/achievements/award4.jpg'),
            ],
        ];

        foreach ($items as $data) {
            $filePath = $data['file'];
            unset($data['file']);

            $existing = Achievement::where('title', $data['title'])->first();
            if (!$existing) {
                $record = Achievement::create($data);
                if (file_exists($filePath)) {
                    $record->addMedia($filePath)
                        ->preservingOriginal()
                        ->toMediaCollection('poster');
                }
            }
        }
    }
}

