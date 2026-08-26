<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Notification;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'product'])->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review = Review::findOrFail($id);
        $review->update([
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        if ($request->status === 'approved' && $review->user_id) {
            Notification::send(
                $review->user_id,
                'review_approved',
                "Review Published! ⭐",
                "Your product review for " . ($review->product->name ?? 'E-Bike') . " has been approved and published.",
                route('products.show', $review->product->slug ?? ''),
                'fa-star'
            );
        }

        return back()->with('success', 'Review status updated.');
    }

    public function destroy(int $id)
    {
        Review::findOrFail($id)->delete();
        return back()->with('success', 'Review deleted.');
    }
}
