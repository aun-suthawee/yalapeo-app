<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomClass\LineNotify;
use App\Http\Controllers\BaseViewController;

class LeaveMessageController extends BaseViewController
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        // dump($request->all());
        $line = new LineNotify();
        $line->notifyMessage($request->all());

        return response()->json("success", 200);
    }
}
