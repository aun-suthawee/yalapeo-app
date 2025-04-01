@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-11">
						<form action="{{ route('admin.menu-top.update', $result->id) }}" method="POST" enctype="multipart/form-data">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">แก้ไขรายการ</h3>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-6">
																<label>ชื่อเมนู</label>
																<input type="text" class="form-control" name="name" value="{{ $result->name }}" placeholder="ระบุชื่อเมนู">
														</div>
														<div class="form-group col-md-6">
																<label>หมวดเมนู</label>
																<select name="parent_id" class="form-control">
																		<option value="">ตั้งเป็นเมนูหลัก</option>
																		@foreach ($lists as $nl1 => $level1)
																				<option value="{{ $level1['id'] }}" @if ($result->parent_id == $level1['id']) selected @endif>
																						{{ $nl1 + 1 }}. {{ $level1['name'] }}
																				</option>
																				@foreach ($level1->children as $nl2 => $level2)
																						<option value="{{ $level2['id'] }}" @if ($result->parent_id == $level2['id']) selected @endif>
																								&nbsp;&nbsp; {{ $nl1 + 1 }}.{{ $nl2 + 1 }}. {{ $level2['name'] }}
																						</option>
																				@endforeach
																		@endforeach
																</select>
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-8">
																<label>ลิงค์เชื่อมโยง</label>
																<input type="text" class="form-control" name="url" value="{{ $result->url }}" placeholder="ระบุลิงค์เชื่อมโยง">
														</div>
														<div class="form-group col-md-4">
																<label>การแสดง (Target)</label>
																<select name="target" class="form-control">
																		<option value="_parent">-เลือก-</option>
																		<option value="_parent" @if ($result->target == '_parent') selected @endif>_parent (เปิดหน้าต่างที่เป็นหน้าต่างระดับ Parent)</option>
																		<option value="_blank" @if ($result->target == '_blank') selected @endif>_blank (เปิดหน้าต่างใหม่ทุกครั้ง)</option>
																		<option value="_self" @if ($result->target == '_self') selected @endif>_self (เปิดหน้าต่างเดิม)</option>
																		<option value="_top" @if ($result->target == '_top') selected @endif>_top (เปิดหน้าต่างในระดับบนสุด)</option>
																</select>
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>รายละเอียด</label>
																<textarea class="form-control" name="detail" id="detail" placeholder="ระบุรายละเอียด...">{{ $result->detail }}</textarea>
                                {!! ckeditor_advanced_url('detail') !!}
														</div>
												</div>
										</div>
										<div class="tile-footer">
												<button type="submit" class="btn btn-primary">
														<i class="fas fa-check-circle fa-fw"></i>บันทึก
												</button>
												<button type="reset" class="btn btn-light"><i class="fas fa-times-circle fa-fw"></i>ยกเลิก</button>
										</div>
								</div>
						</form>
				</div>
		</div>
@endsection
