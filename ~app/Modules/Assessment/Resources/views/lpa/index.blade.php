@extends('home::layouts.master')

@section('app-content')
		<section class="page-contents">
				<div class="page-header">
						<div class="container">
								<div class="row justify-content-center">
										<div class="col-sm-12">
												<h2>LPA</h2>
										</div>
								</div>
						</div>
				</div>

				<div class="page-detail">
						<div class="container">
								<div class="row justify-content-center">
										<div class="col-sm-12 col-md-5">
												<div class="py-5">
														<div class="list-group">
																@foreach ($lpas as $index => $lpa)
																		<a aria-current="true" class="list-group-item list-group-item-action @if ($index == 0) active @endif" href="{{ route('lpa.show', $lpa) }}">
																				ITA {{ $lpa }}
																		</a>
																@endforeach
														</div>
												</div>
										</div>
								</div>
						</div>
				</div>
		</section>
@endsection
