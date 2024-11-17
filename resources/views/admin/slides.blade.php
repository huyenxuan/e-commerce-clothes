@extends('layouts.admin')
@section('title')
    Slides
@endsection
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Slider</h3>
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
                        <div class="text-tiny">Slider</div>
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
                    <a class="tf-button style-1 w208" href="{{ route('admin.slide.add') }}"><i class="icon-plus"></i>Thêm
                        slide</a>
                </div>
                <div class="wg-table table-all-user">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ảnh</th>
                                <th>Khẩu hiệu</th>
                                <th>Tiêu đề</th>
                                <th>Phụ đề</th>
                                <th>Đường dẫn</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($slides as $index => $slide)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{ asset('uploads/slides') }}/{{ $slide->image }}"
                                                alt="{{ $slide->title }}" class="image">
                                        </div>
                                    </td>
                                    <td>{{ $slide->tagline }}</td>
                                    <td>{{ $slide->title }}</td>
                                    <td>{{ $slide->subtitle }}</td>
                                    <td>{{ $slide->links }}</td>
                                    <td>
                                        <div class="list-icon-function">
                                            <a href="{{ route('admin.slide.edit', ['id' => $slide->id]) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{ route('admin.slide.delete', ['id' => $slide->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                {{-- <input type="hidden" name="id" value="{{ $slide->id }}"> --}}
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
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $slides->links('pagination::bootstrap-5') }}
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
                    text: "Bạn có chắc chắn muốn xóa slide này!",
                    icon: "warning",
                    buttons: ["Không", "Có"],
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
