@extends('admin::layouts.master')

@section('app-content')
    <x-alert-error-message />

    @if (!empty($result))
        <form action="{{ route('admin.personal.struct.update', [request()->personal, $result->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            <div class="tile">
                <h3 class="tile-title">
                    {{ $personal->title }}
                </h3>
                <div class="tile-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>ชื่อ-สกุล</label>
                            <input type="text" class="form-control" name="title" placeholder="ระบุหัวข้อ"
                                value="{{ $result->title }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>หน้าปก</label>
                            <input type="file" name="cover" class="form-control krajee-input"
                                data-msg-placeholder="เลือกไฟล์หน้าปก" accept="image/*"
                                data-initial-caption="{{ $result->cover }}">
                            <small class="form-text text-muted">ขนาดรูปภาพที่เหมาะสม 120 x 160 pixel (กว้าง x สูง)</small>
                            <x-error-message title="cover" />
                        </div>
                        <div class="form-group col-md-4">
                            <label>ตำแหน่ง</label>
                            <input type="text" name="position" class="form-control" placeholder="ระบุตำแหน่ง"
                                value="{{ $result->position }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>คำอธิบาย</label>
                            <input type="text" name="description" class="form-control" placeholder="ระบุคำอธิบาย"
                                value="{{ $result->description }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>เบอร์โทรศัพท์</label>
                            <input type="text" name="tel" class="form-control" placeholder="ระบุเบอร์โทรศัพท์"
                                value="{{ $result->tel }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>อีเมล</label>
                            <input type="text" name="email" class="form-control" placeholder="ระบุอีเมล"
                                value="{{ $result->email }}" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>แถว</label>
                            <select name="sequent_row" class="form-control">
                                @for ($n = 1; $n <= 10; $n++)
                                    <option value="{{ $n }}" @if ($result->sequent_row == $n) selected @endif>
                                        แถวที่ {{ $n }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>คอลัม</label>
                            <select name="sequent_col" class="form-control">
                                @for ($n = 1; $n <= 3; $n++)
                                    <option value="{{ $n }}" @if ($result->sequent_col == $n) selected @endif>
                                        คอลัมที่ {{ $n }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle fa-fw"></i>บันทึก
                    </button>
                    <button type="reset" class="btn btn-light">
                        <i class="fa fa-times-circle fa-fw"></i>ยกเลิก
                    </button>
                </div>
            </div>
        </form>
    @else
        <form action="{{ route('admin.personal.struct.store', request()->personal) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="tile">
                <h3 class="tile-title">
                    {{ $personal->title }}
                </h3>
                <div class="tile-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>ชื่อ-สกุล</label>
                            <input type="text" class="form-control" name="title" placeholder="ระบุหัวข้อ" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>หน้าปก</label>
                            <input type="file" name="cover"
                                class="form-control @error('cover') is-invalid @enderror krajee-input"
                                data-msg-placeholder="เลือกไฟล์หน้าปก" accept="image/*">
                            <small class="form-text text-muted">ขนาดรูปภาพที่เหมาะสม 120 x 160 pixel (กว้าง x สูง)</small>
                            <x-error-message title="cover" />
                        </div>
                        <div class="form-group col-md-4">
                            <label>ตำแหน่ง</label>
                            <input type="text" name="position" class="form-control" placeholder="ระบุตำแหน่ง" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>คำอธิบาย</label>
                            <input type="text" name="description" class="form-control" placeholder="ระบุคำอธิบาย">
                        </div>
                        <div class="form-group col-md-4">
                            <label>เบอร์โทรศัพท์</label>
                            <input type="text" name="tel" class="form-control" placeholder="ระบุเบอร์โทรศัพท์"
                                required>
                        </div>
						<div class="form-group col-md-4">
                            <label>อีเมล</label>
                            <input type="text" name="email" class="form-control" placeholder="ระบุอีเมล"
                                required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>แถว</label>
                            <select name="sequent_row" class="form-control">
                                @for ($n = 1; $n <= 10; $n++)
                                    <option value="{{ $n }}">แถวที่ {{ $n }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>คอลัม</label>
                            <select name="sequent_col" class="form-control">
                                @for ($n = 1; $n <= 3; $n++)
                                    <option value="{{ $n }}">คอลัมที่ {{ $n }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle fa-fw"></i>บันทึก
                    </button>
                    <button type="reset" class="btn btn-light">
                        <i class="fa fa-times-circle fa-fw"></i>ยกเลิก
                    </button>
                </div>
            </div>
        </form>
    @endif

    <div class="tile">
        <div class="tile-title-w-btn">
            <h3 class="title">ข้อมูลรายการ</h3>
        </div>
        <div class="tile-body">
            <div class="table-responsive">
                <table class="table-hover table-sm table-bordered table">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center">ชื่อ-สกุล</th>
                            <th class="text-nowrap text-center">ตำแหน่ง</th>
                            <th class="text-nowrap text-center">คำอธิบาย</th>
                            <th scope="col" class="text-nowrap text-center">เบอร์โทรศัพท์</th>
                            <th scope="col" class="text-nowrap text-center">อีเมล</th>
                            <th scope="col" class="text-nowrap text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $index => $value)
                            <tr>
                                <td>
                                    <span class="badge badge-success">{{ $value->sequent_row }} /
                                        {{ $value->sequent_col }}</span>
                                    <a data-toggle="tooltip-image" title="<img src='{{ $value->cover }}' height='120'>">
                                        {{ $value->title }}
                                    </a>
                                </td>
                                <td class="text-nowrap">{{ $value->position }}</td>
                                <td class="text-nowrap">{{ $value->description }}</td>
                                <td class="text-nowrap">{{ $value->tel }}</td>
								<td class="text-nowrap">{{ $value->email }}</td>
                                <td width="1">
                                    <div class="option-link">
                                        <form method="POST"
                                            action="{{ route('admin.personal.struct.destroy', [$personal->id, $value->id]) }}">
                                            @can($permission_prefix . '@*edit')
                                                <a class="btn btn-sm btn-info"
                                                    href="{{ route('admin.personal.struct.edit', [$personal->id, $value->id]) }}">
                                                    แก้ไข</a>
                                            @endcan
                                            @can($permission_prefix . '@*delete')
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('ท่านต้องการลบรายการนี้ใช่หรือไม่ ?')">ยกเลิก</button>
                                            @endcan
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tile-footer">
            {{ $lists->render() }}
        </div>
    </div>
@endsection
