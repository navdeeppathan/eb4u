<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsPage;
use App\Models\Faq;

class CmsController extends Controller
{
    public function showPage(string $slug)
    {
        switch ($slug) {
            case 'privacy-policy':
            case 'privacy':
                return $this->privacyPolicy();
            
            case 'terms-and-conditions':
            case 'terms':
            case 'terms-of-service':
                return $this->termsAndConditions();

            case 'about-us':
            case 'about':
                return view('cms.about');
        }

        $page = CmsPage::where('slug', $slug)->where('is_active', true)->first();
        if ($page) {
            return view('cms.page', compact('page'));
        }

        return $this->termsAndConditions();
    }

    public function about()
    {
        return view('cms.about');
    }

    public function privacyPolicy()
    {
        $page = CmsPage::where('slug', 'privacy-policy')->first();
        return view('pages.privacy_policy', compact('page'));
    }

    public function termsAndConditions()
    {
        $page = CmsPage::where('slug', 'terms-and-conditions')->first();
        return view('pages.terms_and_conditions', compact('page'));
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
