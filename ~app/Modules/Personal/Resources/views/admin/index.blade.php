@extends('admin::layouts.master')

@section('app-content')
		<div class="tile">
				<div class="tile-title-w-btn">
						<h3 class="title">ข้อมูลรายการ</h3>
						<p>
								<a class="btn btn-primary icon-btn" href="{{ route('admin.personal.create') }}"><i class="fa fa-plus-circle fa-fw"></i>สร้าง
								</a>
								<a class="btn btn-secondary icon-btn" href="{{ url()->current() }}"><i class="fas fa-sync fa-fw"></i>Refresh
								</a>
						</p>
				</div>
				<div class="tile-body">
						<div class="table-responsive">
								<table class="table-hover table-sm m-0 table">
										<tbody class="sortable" data-href="{{ route('admin.personal.sort') }}">
												@foreach ($lists as $level1)
														<tr id="item_{{ $level1->id }}">
																<td class="p-0">
																		<table class="table-sm table-hover m-0 table">
																				<tr>
																						<td width="1">
																								<span class="handle ui-sortable-handle">
																										<i class="fas fa-sort-alpha-down fa-lg"></i>
																								</span>
																						</td>
																						<td>
																								{{ $level1->title }}
																								<small class="d-block text-muted text-light">
																										<i class="fas fa-link"></i>
																										<a href="{{ $level1->url }}" class="text-muted text-light" target="_blank">{{ $level1->url }}</a>
																								</small>
																						</td>
																						<td width="1">
																								<div class="option-link">
																										<form method="POST" action="{{ route('admin.personal.destroy', $level1->id) }}">
																												@csrf
																												{{ method_field('DELETE') }}
																												<a class="btn btn-sm btn-outline-warning" href="{{ route('admin.personal.struct.index', [$level1->id]) }}">
																														โครงสร้าง
																												</a>
																												<a class="btn btn-sm btn-info" href="{{ route('admin.personal.edit', [$level1->id]) }}">
																														แก้ไข</a>
																												<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('ท่านต้องการลบรายการนี้ใช่หรือไม่ ?')">
																														ยกเลิก
																												</button>
																										</form>
																								</div>
																						</td>
																				</tr>
																		</table>
																</td>
														</tr>
												@endforeach
										</tbody>
								</table>
						</div>
				</div>
				<div class="tile-footer">

				</div>
		</div>
@endsection
@section('script-content')
		<script type="text/javascript">
				$.ajaxSetup({
						headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
				});

				$(function() {
						$('.sortable, .group_items').sortable({
								handle: "span",
								cursor: "move",
								placeholder: "ui-state-highlight",
								opacity: 0.7,
								items: "> tr",
								tolerance: "pointer",
								helper: function(e, ui) {
										ui.children().each(function() {
												$(this).width($(this).width());
										});

										return ui;
								},
								start: function(e, ui) {
										ui.placeholder.width(ui.item.width());
										ui.placeholder.height(ui.item.height());
								},
								revert: 100,
								update: function(event, ui) {
										var href = $(this).data('href');
										var data = $(this).sortable('serialize');

										$.ajax({
												url: href,
												type: 'POST',
												data: {
														serialize: data,
												},
												cache: false,
												processData: true,
												success: function(result) {
														// console.log(result)
												}
										});
								}
						}).disableSelection();
				});
		</script>
@endsection
