<?php

namespace App\Http\Controllers;


class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('pages.message.index');
    }

}
