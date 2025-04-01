@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-md-6 col-sm-12">
						<x-alert-error-message />

						<form action="{{ route('admin.personal.update', $result->id) }}" method="POST" enctype="multipart/form-data">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">แก้ไขรายการ</h3>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>หัวข้อ</label>
																<input type="text" class="form-control" name="title" placeholder="ระบุหัวข้อ" value="{{ isset($result->title) ? $result->title : '' }}" required>
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
