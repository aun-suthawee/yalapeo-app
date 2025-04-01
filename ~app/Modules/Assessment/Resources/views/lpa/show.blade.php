@extends('home::layouts.master')

@section('app-content')
		<section class="page-contents">
				<div class="page-header">
						<div class="container">
								<div class="row justify-content-center">
										<div class="col-sm-12">
												<h4>
														LPA ประจำปีงบประมาณ พ.ศ. {{ $result->year }}
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
										<div class="col-sm-12 col-md-6">
												<div class="py-5">
														@if ($result)
																<div class="list-group">
																		@foreach ($result->lpas as $lpa_index => $lpa)
																				<div class="list-group-item list-group-item-action p-3">
																						<div class="d-flex w-100 justify-content-between">
																								<h5 class="mb-1">{{ $lpa['point'] }}</h5>
																						</div>
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
																				</div>
																		@endforeach
																</div>
														@endif
												</div>
										</div>
								</div>
						</div>
				</div>
		</section>
@endsection
