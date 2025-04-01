@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-md-5">
						<form action="{{ route('admin.setting.statistics.update', $result->id) }}" method="POST">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">ปรับปรุงรายการ</h3>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-12">
																<label class="control-label">หัวเว็บไซต์</label>
																<input class="form-control" name="laksoot" placeholder="ระบุหัวเว็บไซต์" type="text" value="{{ $result->laksoot }}">
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-12">
																<label class="control-label">คำอธิบายเว็บไซต์</label>
																<input class="form-control" name="student" placeholder="ระบุคำอธิบายเว็บไซต์" type="text" value="{{ $result->student }}">
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-12">
																<label class="control-label">คำค้นหา</label>
																<input class="form-control" name="staff" placeholder="ระบุคำค้นหา" type="text" value="{{ $result->staff }}">
														</div>
												</div>
										</div>
										<div class="tile-footer">
												<button class="btn btn-primary" type="submit"><i class="fas fa-check-circle"></i> บันทึก</button>
												<button class="btn btn-light" type="reset"><i class="fas fa-times-circle"></i> ยกเลิก</button>
										</div>
								</div>
						</form>
				</div>
		</div>
@endsection
