<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsPage;
use App\Models\Faq;

class CmsController extends Controller
{
    public function showPage(string $slug)
    {
        // Map common static legal & informational pages to static Blade views
        switch ($slug) {
            case 'privacy-policy':
            case 'privacy':
                return view('pages.privacy_policy');
            
            case 'terms-and-conditions':
            case 'terms':
            case 'terms-of-service':
                return view('pages.terms_and_conditions');

            case 'rental-policy':
                return view('pages.rental_policy');

            case 'refund-policy':
            case 'shipping-policy':
            case 'about-us':
            case 'about':
                // Fallback to static legal view
                return view('pages.terms_and_conditions');
        }

        // Database lookup fallback for dynamic pages
        $page = CmsPage::where('slug', $slug)->where('is_active', true)->first();
        if ($page) {
            return view('cms.page', compact('page'));
        }

        return view('pages.terms_and_conditions');
    }

    public function about()
    {
        return view('cms.about');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy_policy');
    }

    public function termsAndConditions()
    {
        return view('pages.terms_and_conditions');
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
            'message' => 'Thank you for reaching out to eb4u! Our UK customer support team will reply within 2 hours.'
        ]);
    }
}
