<fieldset>
    <form action="{{ (!empty($product)) ? route('admin.products.update',['product' => $product->id]) : route('admin.products.store') }}" method="POST" id="product-form">
        @csrf
        @if (!empty($product))
            @method('PUT')
        @endif
        <div class="row mb-3">
            <div class="col-md-12 form-group form-group">
                <label for="sku" class="col-form-label">{{ __('Product SKU') }}</label>
                <input id="sku" type="text" class="form-control" name="sku" value="{{ (!empty($product->sku)) ? $product->sku : '' }}" autofocus>
                <span class="text-danger errors" id="skuerror"></span>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 form-group form-group">
                <label for="product_name" class="col-form-label">{{ __('Product Name') }}</label>
                <input id="product_name" type="text" class="form-control" name="product_name" value="{{ (!empty($product->product_name)) ? $product->product_name : '' }}">
                <span class="text-danger errors" id="product_nameerror"></span>
            </div>
            <div class="col-md-6 form-group form-group">
                <label for="slug" class="col-form-label">{{ __('Product Slug') }}</label>
                <input id="slug" type="text" class="form-control" name="slug" value="{{ (!empty($product->slug)) ? $product->slug : '' }}">
                <span class="text-danger errors" id="slugerror"></span>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="price" class="col-form-label">{{ __('Price') }}</label>
                <input id="price" type="text" class="form-control numeric-dot-only" value="{{ (!empty($product->price)) ? $product->price : '' }}" name="price" >
                <span class="text-danger errors" id="priceerror"></span>
            </div>
            <div class="col-md-6 form-group">
                <label for="product_image" class="col-form-label">{{ __('Image') }}</label>
                <input id="product_image" type="file" accept=".jpg, .jpeg, .png, .webp" class="form-control numericonly"  value="" name="product_image">
                <span class="text-danger errors" id="product_imageerror"></span>
                @if(!empty($product))
                    <p class="text-secondary"><span class="text-dark">Note : </span>If replacing an image, select a new one; otherwise, leave the field blank to retain the existing image.</p>
                @endif
            </div>
        </div>

        <div class="row text-end">
            <div class="col-md-12 form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</fieldset>