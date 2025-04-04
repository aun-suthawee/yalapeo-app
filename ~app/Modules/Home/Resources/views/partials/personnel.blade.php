<section id="mis">
    <div class="container">
        <div class="pt-4 pt-md-5 pb-md-5">
            <h2 class="title text-center" data-aos="fade-up">
                <span class="text-highlight">ข้อมูลบุคลากร</span>และกฎหมายระเบียบที่เกี่ยวข้อง
            </h2>
            <div class="row justify-content-center">
                @forelse ($global_menus as $index => $value)
                    <div class="col-6 col-sm-6 col-md-2 mt-5 text-center" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                        <div class="menu-item-clickable" data-bs-toggle="modal"
                            data-bs-target="#menuModal{{ $index }}">
                            <img src="{{ $value['cover'] ?? asset('images/default-cover.png') }}"
                                alt="{{ $value['title'] ?? 'เมนู' }}" height="100px" class="menu-image-clickable">
                            <p class="menu-text-clickable">
                                {!! $value['title'] ?? 'ไม่ระบุชื่อ' !!}
                                @if (isset($value['sub_title']))
                                    <small class="d-block fw-lighter">
                                        {!! $value['sub_title'] !!}
                                    </small>
                                @endif
                            </p>
                        </div>

                        <div class="modal fade" id="menuModal{{ $index }}" tabindex="-1"
                            aria-labelledby="menuModalLabel{{ $index }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="menuModalLabel{{ $index }}">
                                            {!! $value['title'] ?? 'ไม่ระบุชื่อ' !!}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div class="row mt-4">
                                            <div class="col-6">
                                                <a href="{{ $value['url'] ?? '#' }}" class="btn btn-primary w-100">
                                                    <i class="fas fa-users mb-2" aria-hidden="true"></i>
                                                    <span class="d-block">ข้อมูลบุคลากร</span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-success w-100" data-bs-toggle="collapse"
                                                    data-bs-target="#lawDocuments{{ $index }}"
                                                    aria-expanded="false"
                                                    aria-controls="lawDocuments{{ $index }}">
                                                    <i class="fa-solid fa-file-pdf mb-2" aria-hidden="true"></i>
                                                    <span class="d-block">แบบฟอร์มกฏหมาย/ระเบียบ</span>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="collapse mt-3" id="lawDocuments{{ $index }}">
                                            <div class="card card-body">
                                                @include('home::partials.documents', ['title' => $value['title'] ?? '', 'index' => $index])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center mt-4">
                        <div class="alert alert-info">
                            ไม่พบข้อมูลรายการเมนู
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>