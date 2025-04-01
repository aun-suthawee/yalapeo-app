@extends('admin::layouts.master')

@section('app-content')
		<div class="row">
				<div class="col-md-12">
						<form action="{{ route('admin.setting.basic.update', $basic->id) }}" enctype="multipart/form-data" method="POST">
								@csrf
								{{ method_field('PUT') }}
								<div class="tile">
										<h3 class="tile-title">ปรับปรุงรายการ</h3>
										<div class="tile-body">
												<div class="row">
														<div class="col-md-4">
																<div class="tile">
																		<h3 class="tile-title">เริ่มต้น</h3>
																		<div class="tile-body">
																				<div class="form-row">
																						<div class="form-group col-md-12">
																								<label class="control-label">Logo</label>
																								<input accept="image/*" class="form-control" name="logo" type="file">
																								<div class="d-flex justify-content-between my-2" style="width: fit-content;">
																										@if (isset($basic->headers->logo))
																												<img height="101px" src="{{ Storage::url('basic/logo/' . gen_folder($basic->id) . '/' . $basic->headers->logo) }}">
																										@else
																												<img height="101px" src="{{ asset('assets/images/logo.png') }}">
																										@endif
																								</div>
																						</div>
																				</div>
																				<div class="form-row">
																						<div class="form-group col-md-12">
																								<label class="control-label">ชื่อเว็บไซต์</label>
																								<input class="form-control" name="headers[title]" placeholder="ระบุชื่อเว็บไซต์" type="text" value="{{ isset($basic->headers->title) ? $basic->headers->title : '' }}">
																						</div>
																				</div>
																				<div class="form-row">
																						<div class="form-group col-md-12">
																								<label class="control-label">ชื่อเว็บไซต์ (ย่อย)</label>
																								<input class="form-control" name="headers[sub_title]" placeholder="ระบุชื่อเว็บไซต์ (ย่อย)" type="text" value="{{ isset($basic->headers->sub_title) ? $basic->headers->sub_title : '' }}">
																						</div>
																				</div>
																		</div>
																</div>
																<div class="tile">
																		<h3 class="tile-title">โทนสี</h3>
																		<div class="tile-body">
																				<div class="form-row">
																						<div class="form-group col-md-6">
																								<label class="control-label">สีหลัก</label>
																								<input class="form-control" name="colors[base]" placeholder="เลือกสี" type="color" value="{{ $basic->colors->base }}">
																						</div>
																						<div class="form-group col-md-6">
																								<label class="control-label">สีตัวอักษร</label>
																								<input class="form-control" name="colors[text]" placeholder="เลือกสี" type="color" value="{{ $basic->colors->text }}">
																						</div>
																				</div>
																		</div>
																</div>
														</div>
														<div class="col-md-4">
																<div class="tile">
																		<h3 class="tile-title">ส่วนล่างของเว็บไซต์</h3>
																		<div class="tile-body">
																				<div class="form-row">
																						<div class="form-group col-md-12">
																								<label class="control-label">ช่องทางการติดต่อ</label>
																								<textarea class="form-control" cols="30" id="footer_contact" name="footers[contact]" placeholder="ระบุรายละเอียด" rows="11">{{ isset($basic->footers->contact) ? $basic->footers->contact : '' }}</textarea>
																						</div>
																				</div>
																				<div class="form-row">
																						<div class="form-group col-md-12">
																								<label class="control-label">เกี่ยวกับหลักสูตร</label>
																								<textarea class="form-control" cols="30" id="footer_about" name="footers[about]" placeholder="ระบุรายละเอียด" rows="11">{{ isset($basic->footers->about) ? $basic->footers->about : '' }}</textarea>
																						</div>
																				</div>
																		</div>
																</div>
														</div>
														<div class="col-md-4">
																<div class="tile">
																		<h3 class="tile-title">ข้อมูลติดต่อ</h3>
																		<div class="tile-body">
																				<div class="form-row">
																						<div class="form-group col-md-6">
																								<label class="control-label">เบอร์โทรศัพท์</label>
																								<input class="form-control" name="contacts[tel]" placeholder="ระบุเบอร์โทรศัพท์" type="text" value="{{ isset($basic->contacts->tel) ? $basic->contacts->tel : '' }}">
																						</div>
																						<div class="form-group col-md-6">
																								<label class="control-label">Facebook</label>
																								<input class="form-control" name="contacts[facebook]" placeholder="ระบุ Facebook ID" type="text" value="{{ isset($basic->contacts->facebook) ? $basic->contacts->facebook : '' }}">
																						</div>
																						<div class="form-group col-md-12">
																								<label class="control-label">E-mail</label>
																								<input class="form-control" name="contacts[email]" placeholder="ระบุอีเมล์" type="text" value="{{ isset($basic->contacts->email) ? $basic->contacts->email : '' }}">
																						</div>
																						<div class="form-group col-md-6">
																								<label class="control-label">Line ID</label>
																								<input class="form-control" name="contacts[line_id]" placeholder="ระบุ Line ID" type="text" value="{{ isset($basic->contacts->line_id) ? $basic->contacts->line_id : '' }}">
																						</div>
																						<div class="form-group col-md-6">
																								<label class="control-label">Line OA</label>
																								<input class="form-control" name="contacts[line_oa]" placeholder="ระบุ Line OA" type="text" value="{{ isset($basic->contacts->line_oa) ? $basic->contacts->line_oa : '' }}">
																						</div>
																						<div class="form-group col-md-6">
																								<label class="control-label">Youtube</label>
																								<input class="form-control" name="contacts[youtube]" placeholder="ระบุ Chanal Youtubr" type="text" value="{{ isset($basic->contacts->youtube) ? $basic->contacts->youtube : '' }}">
																						</div>
																						<div class="form-group col-md-6">
																								<label class="control-label">Instagram</label>
																								<input class="form-control" name="contacts[instagram]" placeholder="ระบุบัญชี Instagram" type="text" value="{{ isset($basic->contacts->instagram) ? $basic->contacts->instagram : '' }}">
																						</div>
																				</div>
																		</div>
																</div>
														</div>
												</div>
										</div>
										<div class="tile-footer">
												<button class="btn btn-primary" type="submit">
														<i class="fas fa-check-circle"></i> บันทึก
												</button>
												<button class="btn btn-light" type="reset">
														<i class="fas fa-times-circle"></i> ยกเลิก
												</button>
										</div>
								</div>
						</form>
				</div>
		</div>
@endsection
