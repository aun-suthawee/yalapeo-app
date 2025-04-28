<section class="wp-slide">
    <div class="flexslider image-slider">
        <ul class="slides">
            @foreach ($banners as $value)
                <li>
                    <a href="{{ route('banner.large.show', $value->slug) }}">
                        <img src="{{ $value->cover }}" width="1920" height="476" 
                            alt="{{ Request::getHost() }}">
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
