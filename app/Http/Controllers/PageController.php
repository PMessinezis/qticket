<?php

namespace App\Http\Controllers;

class PageController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function homePage()
    {
        return $this->page('home');
    }

    public function page($requestedPage = 'home')
    {
        if (!$requestedPage) {
            $requestedPage = 'home';
        }

        $getPage = $requestedPage;
        if (!\View::exists('pages.' . $requestedPage)) {
            $getPage = 'stub';
        }

        if (request()->ajax()) {
            $v = view('pages.' . $getPage)->with(['page_to_load' => $getPage, 'requested_page' => $requestedPage]);
        } else {
            $v = view('layouts.main')->with(['page_to_load' => $getPage, 'requested_page' => $requestedPage]);
        }
        $r = response($v->render());
        return ($r->header('Cache-Control', 'no-store, no-cache, must-revalidate')
                ->header('Pragma', 'no-store')
                ->header('Expires', -1));
    }
}
