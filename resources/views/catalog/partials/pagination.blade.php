@if ($products->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $products->links() }}
    </div>
@endif
