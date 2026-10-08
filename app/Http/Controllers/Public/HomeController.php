<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('public.home');
    }

    public function surah($id = 1)
    {
        $surahId = max(1, min(114, (int)$id));
        return view('public.surah', compact('surahId'));
    }

    public function bookmarks()
    {
        return view('public.bookmarks');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        return view('public.search', compact('query'));
    }

    public function juz($id = 1)
    {
        $juzId = max(1, min(30, (int)$id));
        return view('public.surah', ['surahId' => 1, 'juzId' => $juzId]);
    }

    public function about()
    {
        return view('public.about');
    }

    public function privacy()
    {
        return view('public.privacy');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Thank you for reaching out! We have received your message and will respond shortly.');
    }
}
