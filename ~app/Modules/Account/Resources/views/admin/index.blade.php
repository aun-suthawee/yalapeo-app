@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-11">
						<form action="{{ route('admin.account.update', $account->id) }}" method="POST" enctype="multipart/form-data">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">แก้ไขข้อมูลส่วนตัว</h3>
										<div class="tile-body">
												<div class="row">
														<div class="col-md-8">
																<div class="form-row">
																		<div class="form-group col-md-6">
																				<label>ชื่อผู้ใช้งาน</label>
																				<input type="text" disabled name="username" value="{{ $account->username }}" class="form-control" placeholder="ระบุชื่อผู้ใช้งาน">
																				<small class="form-text text-muted">(ใช้ได้เฉพาะตัวอักษร ภาษาอังกฤษและตัวเลขเท่านั้น)</small>
																		</div>
																		<div class="form-group col-md-6">
																				<label>รหัสผ่าน</label>
																				<input type="password" disabled name="password" value="******" class="form-control" placeholder="ระบุรหัสผ่าน">
																		</div>
																</div>
																<hr>
																<div class="form-row">
																		<div class="form-group col-md-6">
																				<label>ชื่อ-นามสกุล</label>
																				<input type="text" name="name" class="form-control" value="{{ $account->name }}" placeholder="ระบุชื่อ">
																		</div>
																		<div class="form-group col-md-2">
																				<label><abbr data-toggle="tooltip" data-placement="top" title="ชื่อที่แสดงให้คนทั่วไปได้เห็น">ชื่อเล่น</abbr></label>
																				<input type="text" name="nickname" class="form-control" value="{{ $account->nickname }}" placeholder="ระบุชื่อเล่น">
																		</div>
																		<div class="form-group col-md-4">
																				<label>ตำแหน่ง</label>
																				<input type="text" name="position" class="form-control" value="{{ $account->position }}" placeholder="ระบุตำแหน่ง">
																		</div>
																</div>
																<div class="form-row">
																		<div class="form-group col-md-12">
																				<label>ที่อยู่</label>
																				<input type=" text" name="address" class="form-control" value="{{ $account->address }}" placeholder="ระบุที่อยู่">
																		</div>
																</div>
																<div class="form-row">
																		<div class="form-group col-md-6">
																				<label>เมือง</label>
																				<input type="text" name="city" class="form-control" value="{{ $account->city }}" placeholder="ระบุเมือง">
																		</div>
																		<div class="form-group col-md-4">
																				<label>จังหวัด</label>
																				<select name="province_id" class="form-control">
																						<option value="">-เลือก-</option>
																						<?php foreach ($provinces as  $province) {?>
																						<option value="{{ $province->id }}" <?php if ($province->id == $account->province_id) {
																						    echo 'selected';
																						} ?>>{{ $province->name_th }}</option>
																						<?php }?>
																				</select>
																		</div>
																		<div class="form-group col-md-2">
																				<label>รหัสไปรษณีย์</label>
																				<input type="text" name="zip_code" class="form-control" value="{{ $account->zip_code }}" placeholder="ระบุ">
																		</div>
																</div>
														</div>
														<div class="col-md-4">
																<div class="tile">
																		<h3 class="tile-title">ภาพโปรไฟล์</h3>
																		<div class="tile-body">
																				<div class="avatar-container">
																						<div class="avatar-container-img">
																								<img id="avatar-img" src="{{ Storage::url('avatar/' . gen_folder($account->id) . '/crop/' . $account->avatar) }}" class="rounded-circle" width="160" height="160">
																						</div>
																				</div>
																				<div class="text-center">
																						<p class="help-block">ไฟล์ต้องมีขนาดไม่เกิน 200 x 200 pixel.</p>
																						<input type="file" name="cover" id="avatar" accept="image/*" class="d-none">
																						<button type="button" onclick="$('#avatar').trigger('click');" class="btn btn-outline-secondary"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> เลือกรูปภาพ...</button>
																				</div>
																		</div>
																</div>
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
