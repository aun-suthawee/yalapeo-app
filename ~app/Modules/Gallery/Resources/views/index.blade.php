@extends('home::layouts.master')

@section('app-content')
		<section class="page-contents">
				<div class="page-header">
						<div class="container">
								<div class="row justify-content-center">
										<div class="col-sm-12">
												<h2>กิจกรรม</h2>
										</div>
								</div>
						</div>
				</div>

				<div class="page-detail">
						<div class="container">
								<div class="row justify-content-center py-3">
										<div class="col-sm-12 col-md-6">
												<form action="{{ route('gallery.index') }}" method="GET">
														<div class="input-group">
																<input type="search" name="search" class="form-control" placeholder="ค้นหาตามชื่อกิจกรรม" value="@if (request()->input('search')) {{ request()->input('search') }} @endif" aria-describedby="button-addon2">
																<button class="btn btn-outline-secondary" type="submit" id="button-addon2">
																		<i class="fas fa-search"></i>
																</button>
														</div>
												</form>
										</div>
								</div>

								<div class="row justify-content-center">
										<div class="col-sm-12 col-md-12">
												<div class="row">
														@foreach ($lists as $value)
																<div class="col-md-3">
																		<a href="{{ route('gallery.show', $value->slug) }}" class="gallery-box text-decoration-none">
																				<div class="gallery-logo">
																						<img src="{{ $value->cover }}" class="w-100 img-fluid" alt="{{ Request::getHost() }}">
																				</div>
																				<div class="gallery-title">
																						<h6 class="my-2">
																								{{ $value->title }}
																						</h6>
																						<div class="d-flex justify-content-between">
																								<span>{{ $value->publish_date }}</span>
																								<span>
																										<i class="feather-icon feather-link-2"></i>
																								</span>
																						</div>
																				</div>
																		</a>
																</div>
														@endforeach
												</div>
												{{ $lists->render() }}
										</div>
								</div>
						</div>
				</div>
		</section>
@endsection
