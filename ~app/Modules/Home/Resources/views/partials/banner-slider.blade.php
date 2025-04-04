<section class="wp-slide">
    <div class="flexslider image-slider">
        <ul class="slides">
            @foreach ($banners as $value)
                <li>
                    <a href="{{ route('banner.large.show', $value->slug) }}">
                        <img src="{{ $value->cover }}" width="1920" height="476" class="aos-init aos-animate"
                            data-aos="fade-up" data-aos-offset="200" data-aos-delay="5" data-aos-duration="1000"
                            data-aos-easing="ease-in-out" data-aos-mirror="true" data-aos-once="true"
                            data-aos-anchor-placement="top-center" alt="{{ Request::getHost() }}">
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
