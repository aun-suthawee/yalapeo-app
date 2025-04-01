@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-11">
						<x-alert-error-message />

						<form action="{{ route('admin.gallery.update', $result->id) }}" method="POST" enctype="multipart/form-data">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">แก้ไขรายการ</h3>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-6">
																<label>หัวข้อ</label>
																<input type="text" class="form-control" name="title" placeholder="ระบุหัวข้อ" value="{{ $result->title }}">
																<x-error-message title="title" />
														</div>
														<div class="form-group col-md-6">
																<label>หน้าปก</label>
																<input type="file" name="cover" class="form-control krajee-input" data-msg-placeholder="เลือกไฟล์หน้าปก" accept="image/*" data-initial-caption="{{ $result->cover }}">
																<small class="form-text text-muted">ขนาดรูปภาพที่เหมาะสม 480 × 361 pixel (กว้าง x สูง)</small>
																<x-error-message title="cover" />
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
																<textarea class="form-control" name="detail" id="detail" rows="8" placeholder="ระบุรายละเอียด...">{{ $result->detail }}</textarea>
                                {!! ckeditor_advanced_url('detail') !!}
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>รูปภาพในกิจกรรม</label>
																<div class="file-loading">
																		<input type="file" id="input-slider" name="slider[]" multiple accept="image/*">
																</div>
														</div>
												</div>
										</div>
										<div class="tile-footer">
												<button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> บันทึก</button>
												<button type="reset" class="btn btn-light"><i class="fa fa-times-circle fa-fw"></i>ยกเลิก</button>

												@if (session('message'))
														<div class="alert alert-success mt-2">
																{{ session('message') }}
														</div>
												@endif
										</div>
								</div>
						</form>
				</div>
		</div>
@endsection

@section('script-content')
		<script>
				$.ajaxSetup({
						headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
				});

				$(document).ready(function() {
						$("#input-slider").fileinput({
								language: "th",
								initialPreview: @php echo $result->initialPreview; @endphp,
								initialPreviewAsData: true,
								initialPreviewConfig: @php echo $result->initialPreviewConfig; @endphp,
								deleteUrl: "@php echo route('admin.gallery.slider-destroy', $result->id)  @endphp",
								overwriteInitial: false,
								initialPreviewFileType: 'image',
								uploadAsync: false,
								browseClass: "btn btn-secondary btn-block",
								showCaption: false,
								showRemove: false,
								showUpload: false,
								allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "webp"],
								fileActionSettings: {
										showDrag: true,
										showZoom: true,
										showUpload: false,
										showDelete: true,
								},
								maxFileSize: 300,
								purifyHtml: true,
						}).on('filesorted', function(event, params) {
								// console.log('File sorted ', params.previewId, params.oldIndex, params.newIndex, params.stack);
								$.post("@php echo route('admin.gallery.slider-sort', $result->id) @endphp", {
										stack: params.stack
								}, function(data, textStatus, jqXHR) {
										console.log(data, textStatus, jqXHR);
								}, "JSON");
						});

				});
		</script>
@endsection
