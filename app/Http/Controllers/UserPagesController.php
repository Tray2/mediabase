<?php

namespace App\Http\Controllers;

use App\BookCollection;
use Illuminate\Support\Facades\Auth;
use App\BookRead;

class UserPagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('user_pages.index')->with(
            [
                'user' => $user,
                'bookCount' => BookCollection::where('user_id', $user->id)->count(),
                'readCount' => BookRead::where('user_id', $user->id)->count()
            ]
        );
    }
}
