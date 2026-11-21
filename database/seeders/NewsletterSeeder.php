<?php

namespace Database\Seeders;

use App\Models\Newsletter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NewsletterSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : null;

        $items = [
            [
                'title' => 'จดหมายข่าว วท.ตราด ฉบับที่ 24 ประจำเดือนพฤศจิกายน 2569 - การเปิดภาคเรียนที่ 2 และการแข่งขันทักษะวิชาชีพ',
                'issue_no' => 'ฉบับที่ 24/2569',
                'category' => 'ข่าวกิจกรรมสถานศึกษา',
                'description' => 'วิทยาลัยเทคนิคตราด เผยแพร่จดหมายข่าวประชาสัมพันธ์ ฉบับที่ 24 ประจำเดือนพฤศจิกายน 2569 สรุปกิจกรรมความเคลื่อนไหวการเปิดภาคเรียนที่ 2/2569, การเตรียมความพร้อมการแข่งขันทักษะวิชาชีพระดับภาค, และการรับสมัครนักเรียน นักศึกษาใหม่ โควตาพิเศษ',
                'published_at' => Carbon::now()->subDays(1),
                'views_count' => 184,
                'uploader_name' => 'งานประชาสัมพันธ์ วท.ตราด',
                'user_id' => $adminId,
                'image' => 'newsletters/images/newsletter1.jpg',
                'attachments' => [
                    'newsletters/attachments/newsletter-issue-24.pdf',
                    'newsletters/attachments/activity-summary-report.docx',
                    'newsletters/attachments/student-statistics-data.xlsx',
                    'newsletters/attachments/photos-and-documents-pack.zip',
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'จดหมายข่าวพิเศษ: ยินดีต้อนรับคณะกรรมการประเมินสถานศึกษารางวัลพระราชทาน ประจำปีการศึกษา 2568',
                'issue_no' => 'ฉบับพิเศษ 02/2569',
                'category' => 'ข่าววิชาการและทักษะวิชาชีพ',
                'description' => 'จดหมายข่าวประชาสัมพันธ์ฉบับพิเศษ บันทึกภาพบรรยากาศการต้อนรับคณะกรรมการประเมินสถานศึกษาเพื่อรับรางวัลพระราชทาน ประจำปีการศึกษา 2568 ระดับอาชีวศึกษาขนาดใหญ่ ณ หอประชุมวิทยาลัยเทคนิคตราด',
                'published_at' => Carbon::now()->subDays(4),
                'views_count' => 245,
                'uploader_name' => 'งานประชาสัมพันธ์ วท.ตราด',
                'user_id' => $adminId,
                'image' => 'newsletters/images/newsletter2.jpg',
                'attachments' => [
                    'newsletters/attachments/newsletter-issue-23.pdf',
                    'newsletters/attachments/presentation-newsletter-highlights.pptx',
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'จดหมายข่าว วท.ตราด ฉบับที่ 23 - โครงการอาชีวะอาสา ร่วมด้วยช่วยประชาชน เทศกาลส่งท้ายปีเก่าต้อนรับปีใหม่',
                'issue_no' => 'ฉบับที่ 23/2569',
                'category' => 'ข่าวบริการวิชาชีพสู่ชุมชน',
                'description' => 'สรุปกิจกรรมศูนย์อาชีวะอาสา ร่วมด้วยช่วยประชาชน ออกหน่วยให้บริการตรวจสภาพความพร้อมของยานพาหนะฟรีแก่ประชาชนผู้ใช้รถใช้ถนน พร้อมทั้งบริการจุดพักรถและเครื่องดื่ม',
                'published_at' => Carbon::now()->subDays(8),
                'views_count' => 120,
                'uploader_name' => 'งานประชาสัมพันธ์ วท.ตราด',
                'user_id' => $adminId,
                'image' => 'newsletters/images/newsletter3.jpg',
                'attachments' => [
                    'newsletters/attachments/newsletter-issue-22.pdf',
                    'newsletters/attachments/activity-summary-report.docx',
                    'newsletters/attachments/photos-and-documents-pack.zip',
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'จดหมายข่าว วท.ตราด ฉบับที่ 22 - พิธีมอบทุนการศึกษาแก่นักเรียน นักศึกษา ประจำปีการศึกษา 2569',
                'issue_no' => 'ฉบับที่ 22/2569',
                'category' => 'ข่าวพัฒนานักเรียน นักศึกษา',
                'description' => 'วิทยาลัยเทคนิคตราด จัดพิธีมอบทุนการศึกษาจากกองทุนเพื่อความเสมอภาคทางการศึกษา (กสศ.) และทุนสนับสนุนจากภาคเอกชน สถานประกอบการ ให้แก่นักเรียน นักศึกษา ที่มีความประพฤติดี รวมจำนวนกว่า 120 ทุน',
                'published_at' => Carbon::now()->subDays(12),
                'views_count' => 165,
                'uploader_name' => 'งานประชาสัมพันธ์ วท.ตราด',
                'user_id' => $adminId,
                'image' => 'newsletters/images/newsletter4.jpg',
                'attachments' => [
                    'newsletters/attachments/newsletter-issue-24.pdf',
                    'newsletters/attachments/student-statistics-data.xlsx',
                ],
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($items as $data) {
            $existing = Newsletter::where('title', $data['title'])->first();
            if (!$existing) {
                Newsletter::create($data);
            }
        }
    }
}
