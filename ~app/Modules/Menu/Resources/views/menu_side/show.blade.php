@extends('home::layouts.master')

@section('app-content')
		<section class="page-contents">
				<div class="page-header">
						<div class="container">
								<div class="row justify-content-center">
										<div class="col-sm-12">
												<h2>{{ $result->name }}</h2>
										</div>
								</div>
						</div>
				</div>

				<div class="page-detail">
						<div class="container">
								<div class="row justify-content-center">
										<div class="col-sm-12 col-md-12">
												<div class="py-5">
														@if (count($result->children) == 0)
																@if ($result->detail != '')
																		{!! $result->detail !!}
																@endif
														@else
																@foreach ($result->children as $value)
																		<div class="card my-3">
																				<div class="card-body">
																						<h5 class="card-title">
																								<i class="feather-icon feather-hash text-black-50"></i> {{ $value->name }}
																						</h5>
																						{!! $value->detail !!}

																						@if (!empty($value->attach))
																								<p class="m-0 mt-3">ไฟล์แนบ</p>
																								<ul>
																										@foreach ($value->attach as $attach)
																												<li>
																														{!! __fileExtension($attach['name']) !!}
																														<a href="{{ route('menu-side.download', [$value->id, $attach['name_uploaded'], time()]) }}">
																																{{ $attach['name'] }}
																														</a>
																												</li>
																										@endforeach
																								</ul>
																						@endif

																				</div>
																		</div>
																@endforeach
														@endif
												</div>
										</div>
								</div>
						</div>
				</div>
		</section>
@endsection
