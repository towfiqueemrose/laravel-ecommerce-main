<div class="row g-3 pt-2 pb-2" style="background: white;">

    @forelse ($slugproducts as $categoryproduct)
    <div class="col-6 col-lg-2">
        @include('webview.partials.product-card', ['product' => $categoryproduct])
    </div>
    @empty
        <h2 class="p-4 text-center"><b>No Products found...</b></h2>
    @endforelse
</div>
