@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-11">
						<x-alert-error-message />

						<form action="{{ route('admin.banner.small.store') }}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="tile">
										<h3 class="tile-title">สร้างใหม่</h3>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-6">
																<label>หัวข้อ</label>
																<input type="text" class="form-control" name="title" placeholder="ระบุหัวข้อ" required>
														</div>
														<div class="form-group col-md-6">
																<label>หน้าปก</label>
																<input type="file" name="cover" class="form-control @error('cover') is-invalid @enderror krajee-input" data-msg-placeholder="เลือกไฟล์หน้าปก" accept="image/*">
																<small class="form-text text-muted">ขนาดรูปภาพที่เหมาะสม 966 x 342 pixel (กว้าง x สูง)</small>
																<x-error-message title="cover" />
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-8">
																<label>ลิงค์เชื่อมโยง</label>
																<input type="text" name="url" class="form-control" placeholder="ระบุลิงค์เชื่อมโยง">
														</div>
														<div class="form-group col-md-4">
																<label>การแสดง (Target)</label>
																<select name="target" class="form-control">
																		<option value="">-เลือก-</option>
																		<option value="_parent">_parent (เปิดหน้าต่างที่เป็นหน้าต่างระดับ Parent)</option>
																		<option value="_blank">_blank (เปิดหน้าต่างใหม่ทุกครั้ง)</option>
																		<option value="_self">_self (เปิดหน้าต่างเดิม)</option>
																		<option value="_top">_top (เปิดหน้าต่างในระดับบนสุด)</option>
																</select>
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>รายละเอียด</label>
																<textarea class="form-control" name="detail" id="detail" rows="8" placeholder="ระบุรายละเอียด..."></textarea>
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
