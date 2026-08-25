<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsPage;
use App\Models\Faq;

class CmsController extends Controller
{
    public function showPage(string $slug)
    {
        $page = CmsPage::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('cms.page', compact('page'));
    }

    public function faqs()
    {
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get()->groupBy('category');
        return view('cms.faqs', compact('faqs'));
    }

    public function contact()
    {
        return view('cms.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for reaching out to E-Bike 4 U! Our UK customer support team will reply within 2 hours.'
        ]);
    }
}
