@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-11">
						<x-alert-error-message />

						<form action="{{ route('admin.video.update', $result->id) }}" method="POST" enctype="multipart/form-data">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">แก้ไขรายการ</h3>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-7">
																<label>ที่อยู่วิดิโอ <span class="badge badge-danger">YouTube</span> <span class="badge badge-primary">Facebook</span> </label>
																<input type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ $result->url }}" placeholder="ex. https://www.youtube.com/watch?v=6Oo5trPKpOo">
																<small class="form-text text-muted">
																	สำหรับวิดีโอจาก
																	<b>Facebook</b> เพื่อให้สามารถบันทึกข้อมูลลงสู่ระบบได้ให้ทำตามขั้นตอนดังนี้<br>
																	1. คัดลอกตัวเลข ID ตรงท้ายลิงก์ (ตัวเลขหลังคำว่า "videos/")<br>
																	2. แล้วใส่ตัวเลขนั้นหลัง
																	"watch?v=" แทน<br><br>
																		
																		<b>ตัวอย่าง:</b><br>
																		ลิงก์เดิมที่บันทึกมาจากเพจ ศธจ.ยะลา: https://www.facebook.com/yalaedu/videos/1367499458026596<br>
																		ลิงก์ใหม่ เพื่อที่จะสามารถบันทึกได้: https://www.facebook.com/watch?v=1367499458026596
																</small>
																<x-error-message title="url" />
														</div>
														<div class="form-group col-md-5">
																<label>หัวข้อ</label>
																<input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $result->title }}" placeholder="ระบุหัวข้อ" required>
																<x-error-message title="title" />
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
												<button type="reset" class="btn btn-light">
														<i class="fa fa-times-circle fa-fw"></i>ยกเลิก
												</button>
										</div>
								</div>
						</form>
				</div>
		</div>
@endsection
