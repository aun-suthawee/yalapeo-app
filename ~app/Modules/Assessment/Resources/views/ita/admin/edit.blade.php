@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-11">
						<x-alert-error-message />

						<form action="{{ route('admin.ita.update', $result->id) . '?i=' . request()->i . '&t=' . request()->t }}" enctype="multipart/form-data" method="POST">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title mb-2">ปรับปรุงข้อมูล ITA ปีงบประมาณ {{ $result->year }}</h3>
										<h5 class="mb-3">หัวข้อ: {{ $itas['title'] }}</h5>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>ลิงค์เชื่อมโยง</label>
																<textarea class="form-control" name="url" placeholder="ระบุลิงค์เชื่อมโยง" rows="8">{{ $itas['url'] }}</textarea>
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>คำอธิบาย</label>
																<textarea class="form-control" name="description" placeholder="ระบุคำอธิบาย" rows="8">{{ $itas['description'] }}</textarea>
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
