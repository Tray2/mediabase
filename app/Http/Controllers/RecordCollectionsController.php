<?php

namespace App\Http\Controllers;

use App\Models\RecordCollectionView;
use App\Models\User;
use Illuminate\Http\Request;

class RecordCollectionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index($id)
    {
        if (is_numeric($id)) {
            $user = User::findOrFail($id);
        } else {
            $user = User::where('slug', $id)->firstOrFail();
        }

        return view('record_collection.index')->with(['records' => RecordCollectionView::whereUserId($user->id)
            ->orderBy('artist_name')
            ->orderBy('released')
            ->orderBy('title')
            ->get(),
            'user' => $user,
            'type' => 'records'
        ]);
    }

}
