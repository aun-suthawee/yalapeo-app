<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
		<div class="app-sidebar__user">
				<img class="app-sidebar__user-avatar" height="48" src="{{ isset(Auth::user()->avatar) ? Storage::url('avatar/' . gen_folder(Auth::user()->id) . '/crop/' . Auth::user()->avatar) : asset('assets/images/user2-160x160.jpg') }}" width="48">
				<div>
						<p class="app-sidebar__user-name text-capitalize">{{ Auth::user()->username }}</p>
						<p class="app-sidebar__user-designation text-capitalize">
								{{ Auth::user()->name }}
						</p>
				</div>
		</div>
		<ul class="app-menu">
				@foreach ($cacheNavigation as $role)
						<li>
								<a class="app-menu__item_hd bg-green-dark text-uppercase">
										<span class="app-menu__label"> {{ $role['role']['name'] }}</span>
								</a>
						</li>
						@foreach ($role['data'] as $parent)
								@if (isset($parent['childs']))
										<li class="treeview {{ isset($parent['active_name']) ? (Request::is($parent['active_name']) ? 'is-expanded' : '') : '' }}">
												<a class="app-menu__item" data-toggle="treeview" href="#">
														{!! $parent['webfont_icon'] !!}
														<span class="app-menu__label ml-1">{{ $parent['name'] }}</span>
														<i class="treeview-indicator fa fa-angle-right"></i>
												</a>
												<ul class="treeview-menu">
														@foreach ($parent['childs'] as $child)
																<li>
																		<a class="treeview-item {{ isset($child['active_name']) ? (Request::is($child['active_name']) && $child['permission_prefix'] === $permission_prefix ? 'active' : '') : '' }}" href="{{ $child['url'] }}">
																				<i class="icon far fa-circle"></i> {{ $child['name'] }}
																		</a>
																</li>
														@endforeach
												</ul>
										</li>
								@else
										<li>
												<a class="app-menu__item {{ isset($parent['active_name']) ? (Request::is($parent['active_name']) ? 'active' : '') : '' }}" href="{{ $parent['url'] }}">
														{!! $parent['webfont_icon'] !!}
														<span class="app-menu__label ml-1">{{ $parent['name'] }}</span>
												</a>
										</li>
								@endif
						@endforeach
				@endforeach
		</ul>
</aside>
