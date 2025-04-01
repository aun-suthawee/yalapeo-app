@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-4">
						<form action="{{ route('admin.account.change-password.update', $account->id) }}" method="POST" enctype="multipart/form-data">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">เปลี่ยนรหัสผ่าน</h3>
										<div class="tile-body">
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>รหัสผ่าน</label>
																<input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" placeholder="ระบุรหัสผ่าน">
																<x-error-message title="current_password" />
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>รหัสผ่านใหม่</label>
																<input type="password" class="form-control" name="new_password" placeholder="ระบุรหัสผ่าน">
														</div>
												</div>
												<div class="form-row">
														<div class="form-group col-md-12">
																<label>ยืนยันรหัสผ่านใหม่</label>
																<input type="password" class="form-control" name="new_confirm_password" placeholder="ระบุรหัสผ่าน">
																<x-error-message title="new_confirm_password" />
														</div>
												</div>
										</div>
										<div class="tile-footer">
												<button type="submit" class="btn btn-primary"><i class="fa-fw fas fa-check-circle"></i>บันทึก</button>
												<button type="reset" class="btn btn-light"><i class="fa-fw fas fa-times-circle"></i>ยกเลิก</button>
										</div>
								</div>
						</form>
				</div>
		</div>
@endsection
