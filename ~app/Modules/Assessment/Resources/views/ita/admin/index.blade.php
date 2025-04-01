@extends('admin::layouts.master')

@section('app-content')
		<div class="tile">
				<div class="tile-title-w-btn">
						<h3 class="title">
								ข้อมูลรายการ

								@foreach ($years as $year)
										<a class="btn font-weight-normal font-small @if ($request_year == $year) bg-warning @endif bg-secondary px-2 py-0 text-white" href="{{ route('admin.ita.index') . "?y=$year" }}">
												{{ $year }}
										</a>
								@endforeach
						</h3>
						<p>
								@can($permission_prefix . '@*create')
										<a class="btn btn-primary icon-btn" href="{{ route('admin.ita.create') }}"><i class="fa fa-plus-circle fa-fw"></i>สร้าง </a>
								@endcan
								<a class="btn btn-secondary icon-btn" href="{{ url()->current() }}"><i class="fas fa-sync fa-fw"></i>Refresh </a>
						</p>
				</div>
				<div class="tile-body">
						<div class="table-responsive">
								<table class="table-hover table-bordered table-sm table">
										@if ($result)
												@foreach ($result->itas as $ita_index => $ita)
														<thead>
																<tr class="bg-light text-center text-secondary">
																		<th colspan="4">{{ $ita['point'] }}</th>
																</tr>
																<tr class="bg-light text-secondary text-center">
																		<th class="text-nowrap text-sm text-muted">ข้อ</th>
																		<th class="text-nowrap text-sm text-muted">ประเด็นการตรวจ</th>
																		<th class="text-nowrap text-sm text-muted">URL</th>
																		<th class="text-nowrap text-sm text-muted">คำอธิบาย</th>
																</tr>
														</thead>
														<tbody>
																@foreach ($ita['topics'] as $topic_index => $topic)
																		<tr>
																				<td width="1">{{ $topic['sequent'] }}</td>
																				<td width="400">
																						<a href="{{ route('admin.ita.edit', [$result->id]) . "?i=$ita_index&t=$topic_index" }}">
																								{{ $topic['title'] }}
																						</a>
																				</td>
																				<td>
																						<div>
																								{!! backSlashNHiperLink($topic['url']) !!}
																						</div>
																				</td>
																				<td width="400">
																						{!! nl2br($topic['description']) !!}
																				</td>
																		</tr>
																@endforeach
														</tbody>
												@endforeach
										@endif
								</table>
						</div>
				</div>
		</div>
@endsection
