@extends('home::layouts.master')

@section('app-content')
    <section class="page-contents">
        <div class="page-header">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <h2>ทำเนียบบุคลากร {{ $level1->title }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-detail">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-12">
                        <div class="py-5">
                            @if (isset($level1->tree_format) && count($level1->tree_format))
                                @foreach ($level1->tree_format as $trees)
                                    <div class="row justify-content-center">
                                        @foreach ($trees as $value)
                                            <div class="col-md-4 col-sm-12">
                                                <div class="box-personal">
                                                    <div class="box-personal-cover">
                                                        <img src="{{ $value->cover }}">
                                                    </div>
                                                    <div class="fw-bold py-1">
                                                        {{ $value['title'] }} <br>
                                                        {{ $value['position'] }}
                                                    </div>
                                                    @if ($value['description'] != '')
                                                        <p class="mb-0">
                                                            {{ $value['description'] }}
                                                        </p>
                                                    @endif
                                                    <div class="py-1">
                                                        <i class="fa fa-phone"></i>
                                                        @if($value['tel'])
                                                            {{ formatPhoneNumber($value['tel']) }}
                                                        @else
                                                            -
                                                        @endif
                                                        <br>
                                                        <i class="fa fa-envelope"></i>
                                                        {{ $value['email'] ?: '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@php
/**
 * จัดรูปแบบเบอร์โทรศัพท์จาก 0123456789 เป็น 01 2345 6789
 * 
 * @param string $phone หมายเลขโทรศัพท์
 * @return string เบอร์โทรที่จัดรูปแบบแล้ว
 */
function formatPhoneNumber($phone) {
    // ลบอักขระที่ไม่ใช่ตัวเลขทิ้ง
    $phoneNumber = preg_replace('/[^0-9]/', '', $phone);
    
    // ถ้าเบอร์โทรมี 10 หลัก
    if (strlen($phoneNumber) === 10) {
        return substr($phoneNumber, 0, 2) . ' ' . 
               substr($phoneNumber, 2, 4) . ' ' . 
               substr($phoneNumber, 6);
    }
    
    // ถ้าเป็นเบอร์ 9 หลัก (ไม่มีเลข 0 นำหน้า)
    else if (strlen($phoneNumber) === 9) {
        return substr($phoneNumber, 0, 1) . ' ' . 
               substr($phoneNumber, 1, 4) . ' ' . 
               substr($phoneNumber, 5);
    }
    
    // กรณีอื่นๆ ให้ส่งคืนเบอร์แบบเดิม
    return $phone;
}
@endphp

