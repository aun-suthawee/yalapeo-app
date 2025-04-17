@extends('admin::layouts.master')
@section('app-content')

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title">รายการรูปภาพสไลด์</h3>
                <p>
                    <a class="btn btn-primary icon-btn" href="{{ route('admin.imageboxslider.create') }}">
                        <i class="fa fa-plus"></i>เพิ่มข้อมูล
                    </a>
                </p>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">รูปภาพ</th>
                                <th width="35%">หัวข้อ</th>
                                <th width="10%">สถานะ</th>
                                <th width="10%">มีไฟล์ PDF</th>
                                <th width="15%">วันที่สร้าง</th>
                                <th width="10%">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sliders as $key => $slider)
                            <tr>
                                <td>{{ $sliders->firstItem() + $key }}</td>
                                <td class="text-center">
                                    @if ($slider->image)
                                    <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                    <i class="fa fa-image fa-3x text-muted"></i>
                                    @endif
                                </td>
                                <td>{{ $slider->title }}</td>
                                <td class="text-center">
                                    @if ($slider->is_active)
                                        <span class="badge badge-success p-2"><i class="fa fa-eye mr-1"></i> แสดงผลอยู่</span>
                                    @else
                                        <span class="badge badge-danger p-2"><i class="fa fa-eye-slash mr-1"></i> ซ่อนอยู่</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($slider->pdf_file)
                                    <span class="badge badge-info p-2"><i class="fa fa-file-pdf mr-1"></i> มีไฟล์ PDF</span>
                                    @else
                                    <span class="badge badge-secondary p-2"><i class="fa fa-times mr-1"></i> ไม่มีไฟล์ PDF</span>
                                    @endif
                                </td>
                                <td>{{ $slider->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-info btn-sm" href="{{ route('admin.imageboxslider.edit', $slider->id) }}" title="แก้ไข">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.imageboxslider.destroy', $slider->id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบรูปภาพนี้?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="ลบ">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($sliders->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center">ไม่พบข้อมูล</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-2">
                    {{ $sliders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection