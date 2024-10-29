@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Cập nhật sản phẩm</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.products') }}">
                            <div class="text-tiny">Sản phẩm</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Cập nhật sản phẩm</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.product.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $product->id }}">
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tên sản phẩm <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Tên sản phẩm" name="name" tabindex="0"
                            value="{{ $product->name }}" aria-required="true" required="">
                        <div class="text-tiny">Không vượt quá 100 ký tự khi nhập tên sản phẩm</div>
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Đường dẫn</div>
                        <input class="mb-10" type="text" name="slug" tabindex="0" value="{{ $product->slug }}"
                            aria-required="true" disabled placeholder="Đường dẫn">
                    </fieldset>
                    @error('slug')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Danh mục <span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="category_id">
                                    <option>Chọn danh mục</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $product->category_id === $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error('category_id')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="brand">
                            <div class="body-title mb-10">Thương hiệu <span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="brand_id">
                                    <option>Chọn thương hiệu</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ $product->brand_id === $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error('brand_id')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Mô tả ngắn gọn <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Mô tả ngắn gọn" tabindex="0" aria-required="true"
                            required="">{{ $product->short_description }}</textarea>
                        <div class="text-tiny">Không vượt quá 100 ký tự khi viết mô tả.</div>
                    </fieldset>
                    @error('short_description')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="description">
                        <div class="body-title mb-10">Mô tả <span class="tf-color-1">*</span>
                        </div>
                        <textarea class="mb-10" name="description" placeholder="Mô tả" tabindex="0" aria-required="true" required="">{{ $product->description }}</textarea>
                        <div class="text-tiny">Không vượt quá 100 ký tự khi viết mô tả.</div>
                    </fieldset>
                    @error('description')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </div>
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Ảnh sản phẩm <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview">
                                @if ($product->image)
                                    <img src="{{ asset('uploads/products') }}/{{ $product->image }}" class="effect8"
                                        alt="{{ $product->name }}">
                                @endif
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Thả hình ảnh của bạn ở đây hoặc chọn
                                        <span class="tf-color">Bấm để duyệt</span>
                                    </span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset>
                        <div class="body-title mb-10">Bộ ảnh sản phẩm</div>
                        <div class="upload-image mb-16">
                            @if ($product->images)
                                @foreach (explode(',', $product->images) as $img)
                                    <div class="item gitems">
                                        <img src="{{ asset('uploads/products') }}/{{ trim($img) }}" alt="">
                                    </div>
                                @endforeach
                            @endif
                            <div id="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Thả hình ảnh của bạn ở đây hoặc chọn
                                        <span class="tf-color">Bấm để duyệt</span>
                                    </span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*"
                                        multiple="">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('images')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Giá thường xuyên <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Giá thường xuyên" name="regular_price"
                                tabindex="0" value="{{ $product->regular_price }}" aria-required="true"
                                required="">
                        </fieldset>
                        @error('regular_price')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Giá bán <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Giá bán" name="sale_price" tabindex="0"
                                value="{{ $product->sale_price }}" aria-required="true" required="">
                        </fieldset>
                        @error('sale_price')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Mã sản phẩm <span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Mã sản phẩm" name="SKU"
                                tabindex="0" value="{{ $product->SKU }}" aria-required="true" required="">
                        </fieldset>
                        @error('SKU')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Số lượng <span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Số lượng" name="quantity" tabindex="0"
                                value="{{ $product->quantity }}" aria-required="true" required="">
                        </fieldset>
                        @error('quantity')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Tình trạng</div>
                            <div class="select mb-10">
                                <select class="" name="stock_status">
                                    <option value="Còn hàng"
                                        {{ $product->stock_status === 'Còn hàng' ? 'selected' : '' }}>
                                        Còn hàng</option>
                                    <option value="Hết hàng"
                                        {{ $product->stock_status === 'Hết hàng' ? 'selected' : '' }}>
                                        Hết hàng</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('stock_status')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Nổi bật</div>
                            <div class="select mb-10">
                                <select class="" name="featured">
                                    <option value="0" {{ $product->featured === '0' ? 'selected' : '' }}>Không
                                    </option>
                                    <option value="1" {{ $product->featured === '1' ? 'selected' : '' }}>Có</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('featured')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Thêm sản phẩm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#myFile').on('change', function(e) {
                const photoInp = $('#myFile');
                const [file] = this.files;
                if (file) {
                    $('#imgpreview img').attr('src', URL.createObjectURL(file));
                    $('#imgpreview').show();
                }
            });

            $('#gFile').on('change', function(e) {
                const photoInp = $('#gFile');
                const gPhotos = this.files;
                $.each(gPhotos, function(key, value) {
                    $('#galUpload').prepend(
                        `<div class="item gitems"><img src="${URL.createObjectURL(value)}"/></div>`
                    );
                });
            });

            $('input[name="name"]').on('change', function() {
                $('input[name="slug"]').val(StringToSlug($(this).val()));
            });
        });

        function StringToSlug(Text) {
            // Chuyển đổi ký tự có dấu thành không dấu
            Text = Text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            return Text.toLowerCase()
                .replace(/[^\w ]+/g, '') // Xóa các ký tự không phải là chữ cái và khoảng trắng
                .replace(/ +/g, '-'); // Thay khoảng trắng thành dấu gạch ngang
        }
    </script>
@endpush
