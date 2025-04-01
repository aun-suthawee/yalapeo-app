@extends('admin::layouts.master')

@section('app-content')
		<div class="tile">
				<div class="tile-title-w-btn">
						<h3 class="title">
								ข้อมูลรายการ

								@foreach ($years as $year)
										<a class="btn font-weight-normal font-small @if ($request_year == $year) bg-warning @endif bg-secondary px-2 py-0 text-white" href="{{ route('admin.lpa.index') . "?y=$year" }}">
												{{ $year }}
										</a>
								@endforeach
						</h3>
						<p>
								@can($permission_prefix . '@*create')
										<a class="btn btn-primary icon-btn" href="{{ route('admin.lpa.create') }}"><i class="fa fa-plus-circle fa-fw"></i>สร้าง </a>
								@endcan
								<a class="btn btn-secondary icon-btn" href="{{ url()->current() }}"><i class="fas fa-sync fa-fw"></i>Refresh </a>
						</p>
				</div>
				<div class="tile-body">
						<div class="table-responsive">
								<table class="table-hover table-bordered table-sm table">
										@if ($result)
												@foreach ($result->lpas as $lpa_index => $lpa)
														<thead>
																<tr class="bg-light text-secondary">
																		<th colspan="2">{{ $lpa['point'] }}</th>
																</tr>
														</thead>
														<tbody>
																<tr>
																		<td class="text-nowrap">
																				<a href="{{ route('admin.lpa.edit', [$result->id]) . "?i=$lpa_index" }}">
																						แนบไฟล์
																				</a>
																		</td>
																		<td class="text-nowrap">
																				@if ($lpa['files'])
																						<ul>
																								@foreach ($lpa['files'] as $file)
																										<li>
																												<a class="text-decoration-none" href="{{ Storage::url('lpa/' . gen_folder($result->id) . '/' . $file['name_uploaded']) }}" target="_blank">
																														{{ $file['name'] }}
																												</a>
																										</li>
																								@endforeach
																						</ul>
																				@endif
																		</td>
																</tr>
														</tbody>
												@endforeach
										@endif
								</table>
						</div>
				</div>
		</div>
@endsection
