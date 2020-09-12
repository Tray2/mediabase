<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class StaticPagesController extends Controller
{
    public function about()
    {
        return view('static_pages.about');
    }

    public function contact()
    {
        return view('static_pages.contact');
    }

    public function start()
    {
        if (Auth::check()) {
            return redirect(route('home'));
        }
        return view('static_pages.start')->with('bookCounter', Book::count());
    }
}
