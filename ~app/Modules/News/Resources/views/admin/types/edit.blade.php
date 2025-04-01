@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-4">
						<x-alert-error-message />

						<form action="{{ route('admin.news.type.update', $result->id) }}" method="POST" enctype="multipart/form-data">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">แก้ไขรายการ</h3>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>ชื่อประเภท</label>
																<input type="text" class="form-control @error('title') is-invalid @enderror" value="{{ $result->title }}" name="title" placeholder="ระบุชื่อประเภท">
																<x-error-message title="title" />
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
