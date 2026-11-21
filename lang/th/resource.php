<?php

return [
    // common lang key
    'created_at' => 'สร้างขึ้นเมื่อ',
    'updated_at' => 'อัปเดตเมื่อ',

    // notifications.
    'notification' => [
        'success' => [
            'default_title' => 'ความสำเร็จ',
            'default_body' => 'ดำเนินการเสร็จสิ้นแล้ว',
        ],
    ],

    // category
    'category' => [
        'navigation' => [
            'label' => 'หมวดหมู่',
            'model_label' => 'หมวดหมู่',
            'plural_model_label' => 'หมวดหมู่',
        ],
        'fields' => [
            'name' => 'ชื่อ',
            'slug' => 'สลัก',
            'parent_category' => 'หมวดหมู่หลัก',
            'description' => 'คำอธิบาย',
            'status' => 'สถานะ',
        ],
        'action' => [
            'toggle' => 'ปุ่มสลับ',
        ],
    ],

    // post resource
    'post' => [
        'navigation' => [
            'label' => 'โพสต์',
            'model_label' => 'โพสต์',
            'plural_model_label' => 'โพสต์',
        ],

        'filters' => [
            'status' => 'กรองตามสถานะ',
        ],

        'fields' => [
            'type' => 'ประเภท',
            'title' => 'หัวข้อ',
            'slug' => 'Slug',
            'excerpt' => 'บทนำ',
            'content' => 'เนื้อหา',
            'feature_image' => 'รูปภาพ',
            'is_featured' => 'แนะนำ',
            'comment_status' => 'สถานะความคิดเห็น',
            'status' => 'สถานะ',
            'published_at' => 'เผยแพร่เมื่อ',
            'parent_id' => 'Parent',
            'author' => 'ผู้เขียน',
            'view_count' => 'จำนวนการดู',
            'categories' => 'หมวดหมู่',
            'attachments' => 'เอกสารแนบ',
        ],
    ],

    // page resource
    'page' => [
        'navigation' => [
            'label' => 'เพจ',
            'model_label' => 'เพจ',
            'plural_model_label' => 'เพจ',
        ],
    ],

    // Menu resource
    'menu' => [
        'navigation' => [
            'label' => 'เมนู',
            'model_label' => 'เมนู',
            'plural_model_label' => 'เมนู',
        ],
    ],

    // User resource
    'user' => [
        'navigation' => [
            'label' => 'ผู้ใช้',
            'model_label' => 'ผู้ใช้',
            'plural_model_label' => 'ผู้ใช้',
        ],
    ],

    // Comment resource
    'comment' => [
        'navigation' => [
            'label' => 'ความคิดเห็น',
            'model_label' => 'ความคิดเห็น',
            'plural_model_label' => 'ความคิดเห็น',
        ],
    ],

    // Gallery resource
    'gallery' => [
        'navigation' => [
            'label' => 'แกลเลอรี',
            'model_label' => 'แกลเลอรี',
            'plural_model_label' => 'แกลเลอรี',
        ],
    ],

    // Image resource
    'image' => [
        'navigation' => [
            'label' => 'ภาพ',
            'model_label' => 'ภาพ',
            'plural_model_label' => 'ภาพ',
        ],
    ],

];
