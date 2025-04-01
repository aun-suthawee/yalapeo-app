@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-11">
						<x-alert-error-message />

						<form action="{{ route('admin.banner.small.update', $result->id) }}" method="POST" enctype="multipart/form-data">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">แก้ไขรายการ</h3>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-6">
																<label>หัวข้อ</label>
																<input type="text" class="form-control" name="title" placeholder="ระบุหัวข้อ" value="{{ isset($result->title) ? $result->title : '' }}" required>
														</div>
														<div class="form-group col-md-6">
																<label>หน้าปก</label>
																<input type="file" name="cover" class="form-control krajee-input" data-msg-placeholder="เลือกไฟล์หน้าปก" accept="image/*" data-initial-caption="{{ $result->cover }}">
																<small class="form-text text-muted">ขนาดรูปภาพที่เหมาะสม 966 x 342 pixel (กว้าง x สูง)</small>
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-8">
																<label>ลิงค์เชื่อมโยง</label>
																<input type="text" name="url" class="form-control" value="{{ $result->url }}" placeholder="ระบุลิงค์เชื่อมโยง">
														</div>
														<div class="form-group col-md-4">
																<label>การแสดง (Target)</label>
																<select name="target" class="form-control">
																		<option value="">-เลือก-</option>
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
																<textarea class="form-control" name="detail" id="detail" rows="8" placeholder="ระบุรายละเอียด...">{{ isset($result->detail) ? $result->detail : '' }}</textarea>
                                {!! ckeditor_advanced_url('detail') !!}
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
				</div>
		</div>
@endsection
