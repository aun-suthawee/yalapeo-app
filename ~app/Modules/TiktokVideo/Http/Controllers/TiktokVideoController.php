<?php

namespace Modules\TiktokVideo\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TiktokVideo\Entities\TiktokVideoModel;

class TiktokVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = TiktokVideoModel::query();
        
        // Handle search
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }
        
        // Order by created_at in descending order
        $query->orderBy('created_at', 'desc');
        
        // Paginate the results
        $tiktok_videos = $query->paginate(16);
        
        return view('tiktokvideo::index', compact('tiktok_videos'));
    }
}
