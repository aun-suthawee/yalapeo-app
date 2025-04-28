<section class="pt-4 pt-md-5 pb-4 pb-md-5 webboard-section">
    <div class="container-fluid px-md-5">
        <div class="row">
            <div class="col-md-6 mt-4 mt-md-0">
                <div class="card h-100 webboard-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="card-title">
                                <div class="h5 fw-bold webboard-title">
                                    กระดานสนทนา
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('webboard.create') }}"
                                    class="btn btn-outline-warning btn-sm rounded-2 create-thread-btn">
                                    <i class="fas fa-plus"></i> ตั้งกระทู้ใหม่
                                </a>
                                <a href="{{ route('webboard.index') }}"
                                    class="btn btn-outline-success btn-sm rounded-2 view-all-btn">
                                    ดูทั้งหมด <i class="feather feather-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <table class="table webboard-table">
                            @foreach ($webboard as $index => $value)
                                <tr class="webboard-row">
                                    <td>
                                        <a href="{{ $value->url }}" class="text-decoration-none webboard-link">
                                            {{ $value->title }}
                                        </a>
                                    </td>
                                    <td class="webboard-author">{{ $value->author }}</td>
                                    <td width="100" class="text-nowrap webboard-date">
                                        {{ $value->created_at }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <x-eit-iit />
            </div>
        </div>
    </div>
</section>