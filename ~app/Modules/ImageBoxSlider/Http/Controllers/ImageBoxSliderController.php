<?php

namespace Modules\ImageBoxSlider\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ImageBoxSlider\Entities\ImageBoxSliderModel;

class ImageBoxSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ImageBoxSliderModel::query();
        
        // Handle search
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }
        
        // Only active items
        $query->where('is_active', true);
        
        // Order by created_at in descending order
        $query->orderBy('created_at', 'desc');
        
        // Paginate the results
        $image_sliders = $query->paginate(16);
        
        return view('imageboxslider::index', compact('image_sliders'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('imageboxslider::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $slider = ImageBoxSliderModel::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        return view('imageboxslider::show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('imageboxslider::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
