@extends('home::layouts.master')

@section('app-content')
<section class="page-contents">
   <div class="page-header">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-sm-12">
               <h2>{{ $result->title }}</h2>
            </div>
         </div>
      </div>
   </div>

   <nav aria-label="breadcrumb">
      <div class="container">
         @if(isset($breadcrumb)) {!! breadcrumb($breadcrumb) !!} @endif
      </div>
   </nav>

   <div class="page-details">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-12">
               <div class="py-5">
                  {!! $result->detail !!}
               </div>
            </div>
         </div>

         <div class="row justify-content-center">
            <div class="col-md-12 pb-5">
               <div id="lightgallery"
                    class="row">
                  @if(!is_null($result->slider))
                  @foreach ($result->slider as $item)
                  @if(!empty($item))
                  <div class="col-md-3 mb-3 text-center"
                       data-src="{{ _fileExists('gallery/'.gen_folder($result->id), $item['name_uploaded']) ? Storage::url('gallery/'.gen_folder($result->id).'/' . $item['name_uploaded']) : __via_placeholder(683, 573) }}"
                       {{--
                       data-sub-html="<h4>Fading Light</h4><p>Classic view from Rigwood Jetty on Coniston Water an old archive shot similar to an old post but a little later on.</p>"
                       --}}
                       {{--
                       data-pinterest-text="Pin it"
                       --}}
                       {{--
                       data-tweet-text="share on twitter "
                       --}}>
                     <div>
                        <a href="#">
                           <img src="{{ _fileExists('gallery/'.gen_folder($result->id).'/crop', $item['name_uploaded']) ? Storage::url('gallery/'.gen_folder($result->id).'/crop/' . $item['name_uploaded']) : __via_placeholder(683, 573) }}"
                                class="img-fluid">
                        </a>
                     </div>
                  </div>
                  @endif
                  @endforeach
                  @endif
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection

@section('script-content')
<script type="text/javascript">
   $(document).ready(() => {
        $("#lightgallery").lightGallery({
            pager: true
        });
    });
</script>
@endsection