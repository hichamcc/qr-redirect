<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function handleRedirect($code)
    {
        $link = Link::where('code', $code)->first();

        if (!$link) {
            return view('redirect.invalid-code');
        }

        if ($link->redirect_url) {
            return redirect($link->redirect_url);
        }

        return view('redirect.no-url', ['code' => $code]);
    }
}
