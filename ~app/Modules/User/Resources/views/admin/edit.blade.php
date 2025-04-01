@extends('admin::layouts.master')

@section('app-content')
		<div class="row justify-content-center">
				<div class="col-11">
						<x-alert-error-message />

						<form action="{{ route('admin.user.update', $result->id) }}" enctype="multipart/form-data" method="POST">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">แก้ไขรายการ</h3>
										<div class="tile-body">
												<div class="row">
														<div class="col-md-8">
																<div class="form-row">
																		<div class="form-group col-md-6">
																				<label>ชื่อผู้ใช้งาน</label>
																				<input class="form-control" disabled name="username" placeholder="ระบุชื่อผู้ใช้งาน" type="text" value="{{ $result->username }}">
																				<x-error-message title="username" />
																		</div>
																		<div class="form-group col-md-6">
																				<label>รหัสผ่าน</label>
																				<a class="btn btn-outline-info btn d-block" href="{{ route('admin.user.change-password.edit', $result->id) }}"><i class="fas fa-key"></i> แก้ไขรหัสผ่าน</a>
																		</div>
																</div>
																<div class="form-row">
																		<div class="form-group col-md-6">
																				<label>อีเมล์</label>
																				<input class="form-control" disabled name="email" placeholder="ระบุอีเมล์" type="text" value="{{ $result->email }}">
																				<x-error-message title="email" />
																		</div>
																</div>
																<div class="form-row">
																		<div class="form-group col-md-12">
																				<label>กำหนดบทบาท</label>
																				<ul class="m-0 ml-3 p-0">
																						@foreach ($roles as $role)
																								<li class="d-inline-block mr-3">
																										<label>
																												<input @if (false !== array_search($role->id, array_column($user_roles, 'id'))) checked @endif name="roles[]" type="checkbox" value="{{ $role->id }}">
																												{{ $role->display_name }}
																										</label>
																								</li>
																						@endforeach
																				</ul>
																		</div>
																</div>
																<hr>
																<div class="form-row">
																		<div class="form-group col-md-6">
																				<label>ชื่อ-นามสกุล</label>
																				<input class="form-control" name="name" placeholder="ระบุชื่อ" type="text" value="{{ $result->name }}">
																				<x-error-message title="name" />
																		</div>
																		<div class="form-group col-md-2">
																				<label><abbr data-placement="top" data-toggle="tooltip" title="ชื่อที่แสดงให้คนทั่วไปได้เห็น">ชื่อเล่น</abbr></label>
																				<input class="form-control" name="nickname" placeholder="ระบุชื่อเล่น" type="text" value="{{ $result->nickname }}">
																				<x-error-message title="nickname" />
																		</div>
																		<div class="form-group col-md-4">
																				<label>ตำแหน่ง</label>
																				<input class="form-control" name="position" placeholder="ระบุตำแหน่ง" type="text" value="{{ $result->position }}">
																		</div>
																</div>
																<div class="form-row">
																		<div class="form-group col-md-12">
																				<label>ที่อยู่</label>
																				<input class="form-control" name="address" placeholder="ระบุที่อยู่" type=" text" value="{{ $result->address }}">
																		</div>
																</div>
																<div class="form-row">
																		<div class="form-group col-md-5">
																				<label>เมือง</label>
																				<input class="form-control" name="city" placeholder="ระบุเมือง" type="text" value="{{ $result->city }}">
																		</div>
																		<div class="form-group col-md-4">
																				<label>จังหวัด</label>
																				<select class="form-control" name="province_id">
																						<option value="">-เลือก-</option>
																						@foreach ($provinces as $province)
																								<option @if ($result->province_id == $province->id) selected @endif value="{{ $province->id }}">{{ $province->name_th }}</option>
																						@endforeach
																				</select>
																		</div>
																		<div class="form-group col-md-3">
																				<label>รหัสไปรษณีย์</label>
																				<input class="form-control" name="zip_code" placeholder="ระบุ" type="text" value="{{ $result->zip_code }}">
																		</div>
																</div>
														</div>
														<div class="col-md-4">
																<div class="tile">
																		<h3 class="tile-title">ภาพโปรไฟล์</h3>
																		<div class="tile-body">
																				<div class="avatar-container">
																						<div class="avatar-container-img">
																								<img class="rounded-circle" height="160" id="avatar-img" src="{{ Storage::url('avatar/crop/' . $result->avatar) }}" width="160">
																						</div>
																				</div>
																				<div class="text-center">
																						<p class="help-block">ไฟล์ต้องมีขนาดไม่เกิน 200 x 200 pixel.</p>
																						<x-error-message title="cover" />
																						<input accept="image/*" class="d-none" id="avatar" name="cover" type="file">
																						<button class="btn btn-outline-secondary" onclick="$('#avatar').trigger('click');" type="button"><span aria-hidden="true" class="glyphicon glyphicon-folder-open"></span> เลือกรูปภาพ...
																						</button>
																				</div>
																		</div>
																</div>
														</div>
												</div>
										</div>
										<div class="tile-footer">
												<button class="btn btn-primary" type="submit"><i class="fa-fw fas fa-check-circle"></i>บันทึก
												</button>
												<button class="btn btn-light" type="reset"><i class="fa-fw fas fa-times-circle"></i>ยกเลิก
												</button>
										</div>
								</div>
						</form>
				</div>
		</div>
@endsection
