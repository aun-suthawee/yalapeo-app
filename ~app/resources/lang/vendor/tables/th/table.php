<?php

return [

    'columns' => [

        'tags' => [
            'more' => 'และอีก :count รายการ',
        ],

        'messages' => [
            'copied' => 'คัดลอกแล้ว',
        ],

    ],

    'fields' => [

        'bulk_select_page' => [
            'label' => 'เลือก/ยกเลิกการเลือกรายการทั้งหมดสำหรับการดำเนินการแบบกลุ่ม',
        ],

        'bulk_select_record' => [
            'label' => 'เลือก/ยกเลิกการเลือกรายการ :key สำหรับการดำเนินการแบบกลุ่ม',
        ],

        'search_query' => [
            'label' => 'ค้นหา',
            'placeholder' => 'ค้นหา',
        ],

    ],

    'pagination' => [

        'label' => 'การนำทางหน้า',

        'overview' => '{1} แสดง 1 รายการ|[2,*] แสดง :first ถึง :last จากทั้งหมด :total รายการ',

        'fields' => [

            'records_per_page' => [

                'label' => 'ต่อหน้า',

                'options' => [
                    'all' => 'ทั้งหมด',
                ],

            ],

        ],

        'buttons' => [

            'go_to_page' => [
                'label' => 'ไปที่หน้า :page',
            ],

            'next' => [
                'label' => 'ถัดไป',
            ],

            'previous' => [
                'label' => 'ก่อนหน้า',
            ],

        ],

    ],

    'buttons' => [

        'disable_reordering' => [
            'label' => 'เสร็จสิ้นการจัดเรียงรายการ',
        ],

        'enable_reordering' => [
            'label' => 'จัดเรียงรายการ',
        ],

        'filter' => [
            'label' => 'กรอง',
        ],

        'open_actions' => [
            'label' => 'เปิดการดำเนินการ',
        ],

        'toggle_columns' => [
            'label' => 'สลับคอลัมน์',
        ],

    ],

    'empty' => [

        'heading' => 'ไม่พบรายการ',

        'buttons' => [

            'reset_column_searches' => [
                'label' => 'ล้างการค้นหาคอลัมน์',
            ],

        ],

    ],

    'filters' => [

        'buttons' => [

            'remove' => [
                'label' => 'ลบตัวกรอง',
            ],

            'remove_all' => [
                'label' => 'ลบตัวกรองทั้งหมด',
                'tooltip' => 'ลบตัวกรองทั้งหมด',
            ],

            'reset' => [
                'label' => 'รีเซ็ตตัวกรอง',
            ],

        ],

        'indicator' => 'ตัวกรองที่ใช้งานอยู่',

        'multi_select' => [
            'placeholder' => 'ทั้งหมด',
        ],

        'select' => [
            'placeholder' => 'ทั้งหมด',
        ],

        'trashed' => [

            'label' => 'รายการที่ถูกลบ',

            'only_trashed' => 'เฉพาะรายการที่ถูกลบ',

            'with_trashed' => 'รวมรายการที่ถูกลบ',

            'without_trashed' => 'ไม่รวมรายการที่ถูกลบ',

        ],

    ],

    'reorder_indicator' => 'ลากและวางรายการเพื่อจัดเรียงลำดับ',

    'selection_indicator' => [

        'selected_count' => 'เลือก 1 รายการ|เลือก :count รายการ',

        'buttons' => [

            'select_all' => [
                'label' => 'เลือกทั้งหมด :count รายการ',
            ],

            'deselect_all' => [
                'label' => 'ยกเลิกการเลือกทั้งหมด',
            ],

        ],

    ],

    'sorting' => [

        'fields' => [

            'column' => [
                'label' => 'เรียงตาม',
            ],

            'direction' => [

                'label' => 'ทิศทางการเรียง',

                'options' => [
                    'asc' => 'จากน้อยไปมาก',
                    'desc' => 'จากมากไปน้อย',
                ],

            ],

        ],

    ],

];