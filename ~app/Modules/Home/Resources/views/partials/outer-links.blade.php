<section id="outer-link">
    <div class="container-fluid">
        <div class="pt-4 pt-md-5 pb-md-5">
            <h2 class="title text-center" data-aos="fade-up">
                <span class="text-highlight">E-service</span>
            </h2>
            <div class="row d-flex justify-content-md-center mb-md-0 mb-2">
                @foreach ($outerlinks as $index => $value)
                    <div class="col-6 col-md-auto col-sm-12 text-center" data-aos="fade-up" data-aos-delay="{{ 100 + ($index * 50) }}">
                        <a href="{{ $value['link'] }}" target="_blank" class="outerlinks hvr-grow-shadow animated-link"
                            style="--background: url('{{ $value['logo'] }}');">
                            <span class="e-service-title">{{ $value['title'] }}</span>
                            <span class="e-service-desc">{!! $value['description'] ?? '' !!}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>