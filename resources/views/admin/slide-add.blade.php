@extends('layouts.admin')
@section('title')
    Thêm slide
@endsection
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Slide</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="index.html">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.slides') }}">
                            <div class="text-tiny">Slider</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Thêm Slide</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.slide.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">Khẩu hiệu <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Khẩu hiệu" name="tagline" tabindex="0"
                            value="" aria-required="true" required="" value="{{ old('tagline') }}">
                    </fieldset>
                    @error('tagline')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Tiêu đề <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Tiêu đề" name="title" tabindex="0"
                            value="" aria-required="true" required="" value="{{ old('title') }}">
                    </fieldset>
                    @error('title')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Tiêu đề con <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Tiêu đề con" name="subtitle" tabindex="0"
                            value="" aria-required="true" required="" value="{{ old('subtitle') }}">
                    </fieldset>
                    @error('subtitle')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Đường dẫn <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Đường dẫn" name="links" tabindex="0"
                            value="" aria-required="true" required="" value="{{ old('links') }}">
                    </fieldset>
                    @error('links')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset>
                        <div class="body-title">Ảnh upload <span class="tf-color-1">*</span>
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
                    <fieldset class="category">
                        <div class="body-title">Trạng thái</div>
                        <div class="select flex-grow">
                            <select class="" name="status">
                                <option>Chọn trạng thái </option>
                                <option value="1" @if (old('status') == '1') selected @endif>Hoạt động</option>
                                <option value="0" @if (old('status') == '0') selected @endif>Không hoạt động
                                </option>
                            </select>
                        </div>
                    </fieldset>
                    @error('status')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Thêm slide</button>
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
        });
    </script>
@endpush
