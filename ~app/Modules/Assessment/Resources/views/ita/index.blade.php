@extends('home::layouts.master')

@section('app-content')
		<section class="page-contents">
				<div class="page-header">
						<div class="container">
								<div class="row justify-content-center">
										<div class="col-sm-12">
												<img height="120" src="{{ asset('assets/images/ita_crowth.webp') }}">
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
																@foreach ($itas as $index => $ita)
																		<a aria-current="true" class="list-group-item list-group-item-action @if ($index == 0) active @endif" href="{{ route('ita.show', $ita) }}">
																				ITA {{ $ita }}
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
