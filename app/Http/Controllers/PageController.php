<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Wink\WinkPage;
use App\Http\Resources\WinkPage as WinkPageResource;

class PageController extends Controller
{
    public function index()
    {
        $pages = WinkPage::get();

        return WinkPageResource::collection($pages);
    }

    public function page($slug)
    {
        $page = WinkPage::where('slug', $slug)->get();

        return WinkPageResource::make($page);
    }
}