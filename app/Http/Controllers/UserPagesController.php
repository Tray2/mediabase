<?php

namespace App\Http\Controllers;

use App\Models\BookCollection;
use Illuminate\Http\Request;
use App\Models\BookRead;

class UserPagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        ;
        return view('user_pages.index')->with(
            [
                'user' => $request->user(),
                'bookCount' => BookCollection::where('user_id', $request->user()->id)->count(),
                'readCount' => BookRead::where('user_id', $request->user()->id)->count()
            ]
        );
    }
}
