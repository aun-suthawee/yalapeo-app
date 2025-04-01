@extends('home::layouts.master')

@section('app-content')
		<section class="page-contents">
				<div class="page-header">
						<div class="container">
								<div class="row justify-content-center">
										<div class="col-sm-12">
												<h2>ติดต่อเรา</h2>
										</div>
								</div>
						</div>
				</div>

				<div class="page-detail">
						<div class="container-fluid">
								<div class="row justify-content-center">
										<div class="col-sm-12 col-md-12">
												<div class="my-5 py-5">
														<div class="row justify-content-center g-3">
																@foreach ($data as $value)
																		<div class="col-lg-4 p-3 shadow-sm">
																				<div class="row justify-content-between">
																						<div class="col">
																								<h3>
																										<i class="fas fa-map-pin fa-2x text-danger"></i>
																										{{ $value['title'] }}
																								</h3>
																						</div>
																						<div class="col">
																								<ul class="mb-0">
																										<li>
																												<i class="fab fa-facebook"></i>
																												<a class="text-decoration-none" target="_blank" href="{{ $value['facebook'] }}">
																														{{ $value['compony'] }}
																												</a>
																										</li>
																										<li>
																												<i class="fab fa-line"></i>
																												<a class="text-decoration-none" target="_blank" href="http://line.me/ti/p/~{{ $value['lineId'] }}">
																														LineID : {{ $value['lineId'] }}
																												</a>
																										</li>
																										<li>
																												<i class="fas fa-phone-square-alt"></i>
																												<a class="text-decoration-none" target="_blank" href="tel:{{ $value['phone'] }}">
																														{{ $value['phone'] }}
																												</a>
																										</li>
																								</ul>
																						</div>
																				</div>

																				<div>
																						<iframe src="{{ $value['iframe'] }}" width="100%" height="450" frameborder="0" style="border:0;" aria-hidden="false" tabindex="0"></iframe>
																				</div>
																		</div>
																@endforeach
														</div>
												</div>
										</div>
								</div>
						</div>
				</div>
		</section>
@endsection
