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

   <div class="page-detail">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-sm-12 col-md-12">
               <div class="py-5">
                  <p class="text-center">
                     <img src="{{ Storage::url('banner/small/'.gen_folder($result->id).'/crop/' . $result->cover) }}"
                          class="img-fluid">
                  </p>
                  {!! $result->detail !!}
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection