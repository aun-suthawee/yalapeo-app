@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-11">
						<x-alert-error-message />

						<form action="{{ route('admin.ita.store') }}" enctype="multipart/form-data" method="POST">
								@csrf
								<div class="tile">
										<h3 class="tile-title">สร้างใหม่</h3>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>การประเมินคุณธรรมและความโปร่งใสในการดำเนินงานขององค์กรปกครองส่วนท้องถิ่น ประจำปีงบประมาณ</label>
																<select class="form-control" name="year" required>
																		<option value="">-เลือก-</option>
																		@for ($year = YEAR('th') + 1; $year > YEAR('th') - 5; $year--)
																				<option value="{{ $year }}">{{ $year }}</option>
																		@endfor
																</select>
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>Sync ข้อมูลโครงสร้าง ITA</label>
																<input class="form-control" name="url" placeholder="ระบุลิงค์เชื่อมโยง" required type="text" value="{{ config('init.ita') }}">
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
