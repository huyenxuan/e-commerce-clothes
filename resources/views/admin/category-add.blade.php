@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Thông tin danh mục</h3>
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
                        <a href="{{ route('admin.categories') }}">
                            <div class="text-tiny">Danh sách danh mục</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Thêm danh mục</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{ route('admin.category.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">Tên danh mục <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Tên danh mục" name="name" tabindex="0"
                            value="{{ old('name') }}" aria-required="true" required="">
                        @error('name')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Đường dẫn <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Đường dẫn" name="slug" tabindex="0"
                            value="{{ old('slug') }}" aria-required="true" required="" disabled>
                        @error('slug')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset>
                        <div class="body-title">Ảnh upload<span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="upload-1.html" class="effect8" alt="">
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

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Lưu</button>
                    </div>
                </form>
            </div>
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
ưư
