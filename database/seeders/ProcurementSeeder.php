<?php

namespace Database\Seeders;

use App\Models\Procurement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProcurementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : null;

        $items = [
            [
                'title' => 'ประกวดราคาซื้อครุภัณฑ์ชุดปฏิบัติการทางบัญชี',
                'procurement_type' => 'ประกวดราคาอิเล็กทรอนิกส์ (e-Bidding)',
                'budget' => '1,450,000 บาท',
                'description' => 'ประกาศวิทยาลัยเทคนิคตราด เรื่อง ประกวดราคาซื้อครุภัณฑ์ชุดปฏิบัติการทางบัญชีพร้อมซอฟต์แวร์และระบบการจัดการห้องเรียนพร้อมจอแสดงผลแบบอินเตอร์แอคทีฟ จำนวน 1 ชุด ด้วยวิธีประกวดราคาอิเล็กทรอนิกส์ (e-bidding) กำหนดยื่นข้อเสนอและเสนอราคาทางระบบจัดซื้อจัดจ้างภาครัฐด้วยอิเล็กทรอนิกส์',
                'published_at' => Carbon::parse('2026-11-04'),
                'deadline_at' => Carbon::parse('2026-11-28'),
                'status' => 'active',
                'views_count' => 148,
                'uploader_name' => 'งานพัสดุ วท.ตราด',
                'user_id' => $adminId,
                'image' => 'procurements/images/procurement_accounting.png',
                'attachments' => [
                    'procurements/attachments/tor-accounting-lab.pdf',
                    'procurements/attachments/accounting-lab-spec.docx',
                    'procurements/attachments/bidding-form-accounting.xlsx',
                ],
                'link_url' => 'https://drive.google.com/file/d/1YeJcONkosS239ipwlwNW0LGJiMgU-Unk/view?usp=sharing',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'ประกาศ ประกวดราคาซื้อครุภัณฑ์ห้องปฏิบัติการเรียนรู้เทคโนโลยีผสานโลกเสมือน',
                'procurement_type' => 'ประกวดราคาอิเล็กทรอนิกส์ (e-Bidding)',
                'budget' => '2,850,000 บาท',
                'description' => 'ประกาศวิทยาลัยเทคนิคตราด เรื่อง ประกวดราคาซื้อครุภัณฑ์ห้องปฏิบัติการเรียนรู้เทคโนโลยีผสานโลกเสมือน (Virtual & Augmented Reality) สำหรับการจัดการเรียนการสอน ด้วยวิธีประกวดราคาอิเล็กทรอนิกส์ (e-bidding) ประจำปีงบประมาณ พ.ศ. 2569',
                'published_at' => Carbon::parse('2026-09-04'),
                'deadline_at' => Carbon::parse('2026-09-25'),
                'status' => 'active',
                'views_count' => 264,
                'uploader_name' => 'งานพัสดุ วท.ตราด',
                'user_id' => $adminId,
                'image' => 'procurements/images/procurement_vr_lab.png',
                'attachments' => [
                    'procurements/attachments/tor-vr-learning-lab.pdf',
                    'procurements/attachments/vr-lab-equipment-sheet.xlsx',
                    'procurements/attachments/procurement-documents-pack.zip',
                ],
                'link_url' => 'https://drive.google.com/drive/folders/1XJREdM_oUvk65yTDmeOF_KGQT-WM5XSN?usp=sharing',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'ประกาศขายทอดตลาดพัสดุ',
                'procurement_type' => 'ประกาศขายทอดตลาด',
                'budget' => '380,000 บาท',
                'description' => 'ประกาศวิทยาลัยเทคนิคตราด เรื่อง ขายทอดตลาดพัสดุชำรุด เสื่อมสภาพ หรือไม่จำเป็นต้องใช้ในราชการ ประจำปีงบประมาณ พ.ศ. 2569 จำนวน 42 รายการ โดยวิธีประมูลขายทอดตลาดด้วยวาจา ณ อาคารพัสดุกลาง วิทยาลัยเทคนิคตราด',
                'published_at' => Carbon::parse('2026-09-02'),
                'deadline_at' => Carbon::parse('2026-09-18'),
                'status' => 'active',
                'views_count' => 312,
                'uploader_name' => 'งานพัสดุ วท.ตราด',
                'user_id' => $adminId,
                'image' => 'procurements/images/procurement_auction.png',
                'attachments' => [
                    'procurements/attachments/auction-item-list.pdf',
                    'procurements/attachments/auction-terms.docx',
                ],
                'link_url' => 'https://drive.google.com/drive/folders/1QseX3qO9-BoYNSu-pZ1if0mwYncONcSe?usp=sharing',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'ประกวดราคาซื้อครุภัณฑ์เทคโนโลยีผสานโลกเสมือน',
                'procurement_type' => 'ร่างประกาศและเอกสารประกวดราคา',
                'budget' => '2,850,000 บาท',
                'description' => 'วิทยาลัยเทคนิคตราด ขอเผยแพร่ร่างประกาศและร่างเอกสารประกวดราคาซื้อครุภัณฑ์เทคโนโลยีผสานโลกเสมือน เพื่อรับฟังความคิดเห็นจากผู้ประกอบการและสาธารณชน ตามระเบียบกระทรวงการคลังว่าด้วยการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ พ.ศ. 2560',
                'published_at' => Carbon::parse('2026-08-18'),
                'deadline_at' => Carbon::parse('2026-08-26'),
                'status' => 'active',
                'views_count' => 195,
                'uploader_name' => 'งานพัสดุ วท.ตราด',
                'user_id' => $adminId,
                'image' => 'procurements/images/procurement_vr_tech.png',
                'attachments' => [
                    'procurements/attachments/draft-announcement-vr.pdf',
                    'procurements/attachments/draft-bidding-docs.docx',
                ],
                'link_url' => 'https://drive.google.com/drive/folders/1vuZFEDwcq4X9u7SMslT8mcZE__h0-G5d?usp=sharing',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'คุณลักษณะเฉพาะครุภัณฑ์โลกเสมือน',
                'procurement_type' => 'เผยแพร่คุณลักษณะเฉพาะ (TOR)',
                'budget' => '1,500,000 บาท',
                'description' => 'ประกาศเผยแพร่รายละเอียดคุณลักษณะเฉพาะ (Terms of Reference : TOR) โครงการจัดซื้อครุภัณฑ์โลกเสมือน (Metaverse Education Kit) เพื่อใช้สนับสนุนการเรียนการสอนแผนกวิชาคอมพิวเตอร์ธุรกิจและเทคโนโลยีสารสนเทศ วิทยาลัยเทคนิคตราด',
                'published_at' => Carbon::parse('2026-08-18'),
                'deadline_at' => Carbon::parse('2026-08-30'),
                'status' => 'active',
                'views_count' => 178,
                'uploader_name' => 'งานพัสดุ วท.ตราด',
                'user_id' => $adminId,
                'image' => 'procurements/images/procurement_vr_tor.png',
                'attachments' => [
                    'procurements/attachments/tor-metaverse-specs.pdf',
                    'procurements/attachments/technical-specs-metaverse.docx',
                ],
                'link_url' => 'https://drive.google.com/drive/folders/1sOVTB9BL83FuMeiinknkWIIFl0tUgpoj?usp=sharing',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'ยกเลิกประกาศรายชื่อผู้ชนะการเสนอราคา',
                'procurement_type' => 'ยกเลิกประกาศ',
                'budget' => '-',
                'description' => 'ประกาศวิทยาลัยเทคนิคตราด เรื่อง ยกเลิกประกาศรายชื่อผู้ชนะการเสนอราคา โครงการจัดซื้อจัดจ้างพัสดุและครุภัณฑ์ ตามระเบียบกระทรวงการคลังว่าด้วยการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ พ.ศ. 2560 ข้อ 56 เนื่องจากมีข้อผิดพลาดในการตรวจสอบเอกสารหลักฐาน',
                'published_at' => Carbon::parse('2026-08-14'),
                'deadline_at' => null,
                'status' => 'closed',
                'views_count' => 294,
                'uploader_name' => 'งานพัสดุ วท.ตราด',
                'user_id' => $adminId,
                'image' => 'procurements/images/procurement_cancel_winner.jpg',
                'attachments' => [
                    'procurements/attachments/cancellation-announcement.pdf',
                ],
                'link_url' => 'https://trattc.ac.th/v1/khaawprakwdraakhaa/ykelikprakaasraaychuuephuuchnakaaresnraakhaa',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        Procurement::truncate();

        foreach ($items as $data) {
            Procurement::create($data);
        }
    }
}
