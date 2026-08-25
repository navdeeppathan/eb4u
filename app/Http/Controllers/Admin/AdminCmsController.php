<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsBanner;
use App\Models\CmsPage;
use App\Models\Faq;

class AdminCmsController extends Controller
{
    public function banners()
    {
        $banners = CmsBanner::orderBy('sort_order')->get();
        return view('admin.cms.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|string',
        ]);

        CmsBanner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'badge' => $request->badge,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'image' => $request->image ?? 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=1600&auto=format&fit=crop&q=80',
            'position' => 'home_hero',
            'is_active' => true,
        ]);

        return back()->with('success', 'Banner created.');
    }

    public function pages()
    {
        $pages = CmsPage::latest()->get();
        return view('admin.cms.pages', compact('pages'));
    }

    public function editPage(int $id)
    {
        $page = CmsPage::findOrFail($id);
        return view('admin.cms.edit_page', compact('page'));
    }

    public function updatePage(Request $request, int $id)
    {
        $page = CmsPage::findOrFail($id);
        $request->validate(['content' => 'required|string']);

        $page->update(['content' => $request->content]);
        return redirect()->route('admin.cms.pages')->with('success', 'Page content updated.');
    }

    public function faqs()
    {
        $faqs = Faq::orderBy('sort_order')->get();
        return view('admin.cms.faqs', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        Faq::create($request->all());
        return back()->with('success', 'FAQ added successfully.');
    }
}
