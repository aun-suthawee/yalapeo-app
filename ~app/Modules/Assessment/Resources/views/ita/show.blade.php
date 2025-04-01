@extends('home::layouts.master')

@section('app-content')
		<section class="page-contents">
				<div class="page-header">
						<div class="container">
								<div class="row justify-content-center">
										<div class="col-sm-12">
												<h4>
														การประเมินคุณธรรมและความโปร่งใสในการดำเนินงานขององค์กรปกครองส่วนท้องถิ่น <br>
														(Integrity and transparency Assessment: ITA) ประจำปีงบประมาณ พ.ศ. {{ $result->year }}
												</h4>
										</div>
								</div>
						</div>
				</div>

				<nav aria-label="breadcrumb">
						<div class="container">
								@if (isset($breadcrumb))
										{!! breadcrumb($breadcrumb) !!}
								@endif
						</div>
				</nav>

				<div class="page-detail">
						<div class="container">
								<div class="row justify-content-center">
										<div class="col-sm-12 col-md-12">
												<div class="py-5">
														<table class="table-hover table-bordered table">
																@if ($result)
																		@foreach ($result->itas as $ita_index => $ita)
																				<thead>
																						<tr class="bg-success text-center text-white">
																								<th colspan="4">{{ $ita['point'] }}</th>
																						</tr>
																						<tr class="bg-light text-secondary text-center">
																								<th class="text-nowrap">ข้อ</th>
																								<th class="text-nowrap">ประเด็นการตรวจ</th>
																								<th class="text-nowrap">URL</th>
																								<th class="text-nowrap">คำอธิบาย</th>
																						</tr>
																				</thead>
																				<tbody>
																						@foreach ($ita['topics'] as $topic_index => $topic)
																								<tr>
																										<td width="1">{{ $topic['sequent'] }}</td>
																										<td width="400">
																												{{ $topic['title'] }}
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
						</div>
				</div>
		</section>
@endsection
