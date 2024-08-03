<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalLinks = Link::where('user_id', $user->id)->count();
        $linksWithRedirect = Link::where('user_id', $user->id)->whereNotNull('redirect_url')->count();
        $linksWithoutRedirect = $totalLinks - $linksWithRedirect;
        $recentlyCreated = Link::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $recentLinks = Link::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalLinks',
            'linksWithRedirect',
            'linksWithoutRedirect',
            'recentlyCreated',
            'recentLinks'
        ));
    }
}
