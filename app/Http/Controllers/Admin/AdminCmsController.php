<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsBanner;
use App\Models\CmsPage;
use App\Models\Faq;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdminCmsController extends Controller
{
    public function banners()
    {
        $banners = CmsBanner::orderBy('sort_order')->orderByDesc('id')->get();
        return view('admin.cms.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url',
            'position' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ], [
            'image_file.max' => 'Banner image file size must not exceed 2MB.',
            'image_file.mimes' => 'Banner image must be a valid image file of type: jpeg, png, jpg, webp.',
        ]);

        $imgPath = null;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $uploadDir = public_path('uploads/banners');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $filename = time() . '_banner_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $imgPath = 'uploads/banners/' . $filename;
        } elseif ($request->filled('image_url')) {
            $imgPath = $request->image_url;
        } else {
            $imgPath = 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=1600&auto=format&fit=crop&q=80';
        }

        CmsBanner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'badge' => $request->badge,
            'button_text' => $request->button_text ?: 'Shop Now',
            'button_url' => $request->button_url ?: route('catalog.index'),
            'image' => $imgPath,
            'position' => $request->position ?: 'home_hero',
            'is_active' => true,
            'sort_order' => $request->sort_order ?: 1,
        ]);

        return back()->with('success', 'Banner slide created successfully!');
    }

    public function editBanner(int $id)
    {
        $banner = CmsBanner::findOrFail($id);
        return view('admin.cms.edit_banner', compact('banner'));
    }

    public function updateBanner(Request $request, int $id)
    {
        $banner = CmsBanner::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'image_file.max' => 'Banner image file size must not exceed 2MB.',
        ]);

        $imgPath = $banner->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $uploadDir = public_path('uploads/banners');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $filename = time() . '_banner_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $imgPath = 'uploads/banners/' . $filename;
        } elseif ($request->filled('image_url')) {
            $imgPath = $request->image_url;
        }

        $banner->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'badge' => $request->badge,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'image' => $imgPath,
            'position' => $request->position ?: 'home_hero',
            'sort_order' => $request->sort_order ?: 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.cms.banners')->with('success', 'Banner updated successfully!');
    }

    public function toggleBanner(int $id)
    {
        $banner = CmsBanner::findOrFail($id);
        $banner->update(['is_active' => !$banner->is_active]);

        return back()->with('success', 'Banner status updated.');
    }

    public function destroyBanner(int $id)
    {
        $banner = CmsBanner::findOrFail($id);
        if ($banner->image && Str::startsWith($banner->image, 'uploads/banners/')) {
            $fullPath = public_path($banner->image);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
        $banner->delete();

        return back()->with('success', 'Banner slide deleted.');
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
