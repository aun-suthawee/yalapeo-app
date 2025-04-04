@extends('admin::layouts.master')

@section('app-content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">รายการวิดีโอ TikTok</h3>
                    <div class="btn-group">
                        <a class="btn btn-primary" href="{{ route('admin.tiktokvideo.create') }}">
                            <i class="fas fa-plus-circle"></i> เพิ่มวิดีโอใหม่
                        </a>
                    </div>
                </div>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>หัวข้อ</th>
                                    <th>URL</th>
                                    <th>การแสดงผล</th>
                                    <th>ยอดเข้าชม</th>
                                    <th>วันที่สร้าง</th>
                                    <th style="width: 150px;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($videos as $index => $video)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $video->title }}</td>
                                        <td class="text-truncate" style="max-width: 200px;">
                                            <a href="{{ $video->url }}" target="_blank">{{ $video->url }}</a>
                                        </td>
                                        <td>
                                            @if ($video->is_active)
                                                <span class="badge badge-success">เผยแพร่</span>
                                            @else
                                                <span class="badge badge-danger">ไม่เผยแพร่</span>
                                            @endif
                                        </td>
                                        <td>{{ $video->view }}</td>
                                        <td>{{ $video->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.tiktokvideo.edit', $video->id) }}" class="btn btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.tiktokvideo.destroy', $video->id) }}" method="POST" onsubmit="return confirm('คุณต้องการลบรายการนี้ใช่หรือไม่?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                @if (count($videos) == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">ไม่พบข้อมูล</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $videos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection