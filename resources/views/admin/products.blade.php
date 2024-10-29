@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Sản phẩm</h3>
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
                        <div class="text-tiny">Sản phẩm</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="name"
                                    tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.product.add') }}">
                        <i class="icon-plus"></i>Thêm sản phẩm</a>
                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        @if (Session::has('success'))
                            <p class="alert alert-success">{{ Session::get('success') }}</p>
                        @endif
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Tên sản phẩm</th>
                                    <th class="text-center">Giá thành</th>
                                    <th class="text-center">Giá sale</th>
                                    <th class="text-center">Mã<br> sản phẩm</th>
                                    <th class="text-center">Danh mục</th>
                                    <th class="text-center">Thương hiệu</th>
                                    <th class="text-center">Sản phẩm nổi bật</th>
                                    <th class="text-center">Tình trạng</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="pname">
                                            <div class="image">
                                                <img src="{{ asset('uploads/products/thumbnails/' . $product->image) }}"
                                                    alt="{{ $product->name }}" class="image">
                                            </div>
                                            <div class="name">
                                                <a href="#" class="body-title-2">{{ $product->name }}</a>
                                                <div class="text-tiny mt-3">{{ $product->slug }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $product->regular_price }}</td>
                                        <td>{{ $product->sale_price }}</td>
                                        <td>{{ $product->SKU }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                        <td>{{ $product->featured == 0 ? 'No' : 'Yes' }}</td>
                                        <td>{{ $product->stock_status }}</td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="#" target="_blank">
                                                    <div class="item eye">
                                                        <i class="icon-eye"></i>
                                                    </div>
                                                </a>
                                                <a href="{{ route('admin.product.edit', ['id' => $product->id]) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
                                                <form action="{{ route('admin.product.delete', ['id' => $product->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="item text-danger delete">
                                                        <i class="icon-trash-2"></i>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Bạn muốn xóa?",
                    text: "Bạn có chắc chắn muốn xóa sản phẩm này?",
                    icon: "warning",
                    buttons: ["No", "Yes"],
                    dangerMode: true,
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
