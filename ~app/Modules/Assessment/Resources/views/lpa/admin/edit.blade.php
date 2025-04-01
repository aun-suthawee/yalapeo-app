@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-11">
						<x-alert-error-message />

						<form action="{{ route('admin.lpa.update', $result->id) . '?i=' . request()->i }}" enctype="multipart/form-data" method="POST">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title mb-2">ปรับปรุงข้อมูล LPA ปีงบประมาณ {{ $result->year }}</h3>
										<h5 class="mb-3">หัวข้อ: {{ $lpas['point'] }}</h5>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-6 col-md-12">
																<label>แนบไฟล์</label>
																<div class="file-loading">
																		<input id="input-slider" multiple name="file[]" type="file">
																</div>
														</div>
												</div>

										</div>
										<div class="tile-footer">
												<button class="btn btn-primary" type="submit">
														<i class="fas fa-check-circle fa-fw"></i>บันทึก
												</button>
												<button class="btn btn-light" type="reset">
														<i class="fa fa-times-circle fa-fw"></i>ยกเลิก
												</button>
										</div>
								</div>
						</form>
				</div>
		</div>
@endsection

@section('script-content')
		<script>
				$(document).ready(function() {
						$("#input-slider").fileinput({
								language: "th",
								browseClass: "btn btn-secondary btn-block",
								showCaption: false,
								showRemove: false,
								showUpload: false,
								allowedFileExtensions: ["pdf", "jpg", "jpeg", "png", "gif", "webp"],
								fileActionSettings: {
										showDrag: false,
										showZoom: true,
										showUpload: false,
										showDelete: true,
								},
								maxFileSize: 300,
						});
				});
		</script>
@endsection
