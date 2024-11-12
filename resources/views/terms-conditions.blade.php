@extends('layouts.app')
@section('title')
    Điều Khoản và Điều Kiện
@endsection
@section('content')
    @push('style')
        <style>
            .fs-5 {
                text-align: justify;
            }
        </style>
    @endpush
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="contact-us container">
            <div class="mw-930">
                <h2 class="page-title mb-1">Điều Khoản và Điều Kiện Sử Dụng của NXHuyenClothes</h2>
                <p class="mb-1 fs-4">Ngày hiệu lực: 10/07/2003</p>
            </div>
        </section>

        <div class="mb-5 pb-4"></div>
        <section class="container mw-930 lh-30">
            <div class="mb-4">
                <h3>1. Chấp thuận điều khoản</h3>
                <p class="fs-5">Khi truy cập và sử dụng trang web NXHuyenClothes, bạn đồng ý tuân thủ các điều khoản và
                    điều kiện dưới đây. Nếu bạn không đồng ý với bất kỳ điều khoản nào, vui lòng không tiếp tục sử dụng
                    trang web.</p>
            </div>
            <div class="mb-4">
                <h3>2. Sử Dụng Trang Web</h3>
                <p class="fs-5">rang web này được cung cấp để hỗ trợ bạn tham khảo, mua sắm và sử dụng sản phẩm của chúng
                    tôi. Bạn đồng ý sử dụng trang web chỉ cho các mục đích hợp pháp và không xâm phạm quyền của bất kỳ bên
                    thứ ba nào.</p>
            </div>
            <div class="mb-4">
                <h3>3. Tài Khoản Người Dùng</h3>
                <p class="fs-5">Khi tạo tài khoản trên NXHuyenClothes, bạn cần cung cấp thông tin chính xác và đầy đủ. Bạn
                    có trách nhiệm bảo mật thông tin tài khoản và mật khẩu của mình. Mọi hành động xảy ra dưới tài khoản của
                    bạn đều thuộc trách nhiệm của bạn.</p>
            </div>
            <div class="mb-4">
                <h3>4. Đặt Hàng và Thanh Toán</h3>
                <ul>
                    <li class="fs-5">Đặt Hàng: Sau khi bạn đặt hàng, chúng tôi sẽ gửi email xác nhận và bắt đầu xử lý đơn
                        hàng của bạn. Đơn hàng có thể bị hủy nếu sản phẩm không còn sẵn có hoặc có vấn đề về thanh toán.
                    </li>
                    <li class="fs-5">Thanh Toán: Chúng tôi chấp nhận các phương thức thanh toán được liệt kê trên trang
                        web. Bạn phải thanh toán đầy đủ trước khi chúng tôi vận chuyển hàng.</li>
                </ul>
            </div>
            <div class="mb-4">
                <h3>5. Giá Cả và Khuyến Mãi</h3>
                <p class="fs-5">Giá sản phẩm và chương trình khuyến mãi có thể thay đổi mà không cần thông báo trước. Giá
                    tại thời điểm mua hàng sẽ được áp dụng. Các chương trình khuyến mãi có thể có điều kiện và thời hạn sử
                    dụng.</p>
            </div>
            <div class="mb-4">
                <h3>6. Chính Sách Vận Chuyển</h3>
                <ul>
                    <li class="fs-5">Vận Chuyển: Chúng tôi cố gắng giao hàng đúng thời gian quy định, nhưng không chịu
                        trách nhiệm đối với các chậm trễ do đơn vị vận chuyển.</li>
                </ul>
            </div>
            <div class="mb-4">
                <h3>7. Quyền Sở Hữu Trí Tuệ</h3>
                <p class="fs-5">Tất cả nội dung, hình ảnh, logo, và tài sản trí tuệ khác trên NXHuyenClothes là tài sản
                    của chúng tôi hoặc các bên cấp phép của chúng tôi. Bạn không được sao chép, phân phối hoặc sử dụng bất
                    kỳ nội dung nào khi chưa được phép.</p>
            </div>
            <div class="mb-4">
                <h3>8. Giới Hạn Trách Nhiệm</h3>
                <p class="fs-5">NXHuyenClothes sẽ không chịu trách nhiệm cho bất kỳ thiệt hại trực tiếp, gián tiếp hoặc
                    hậu quả nào phát sinh từ việc sử dụng trang web, kể cả khi chúng tôi đã được thông báo về khả năng xảy
                    ra các thiệt hại đó.</p>
            </div>
            <div class="mb-4">
                <h3>9. Sửa Đổi Điều Khoản</h3>
                <p class="fs-5">Chúng tôi có quyền sửa đổi các điều khoản và điều kiện này bất kỳ lúc nào. Bản sửa đổi sẽ
                    có hiệu lực ngay khi được đăng lên trang web...</p>
            </div>
            <div class="mb-4">
                <h3>10. Luật Áp Dụng</h3>
                <p class="fs-5">Các điều khoản này được điều chỉnh bởi pháp luật Việt Nam. Mọi tranh chấp phát sinh sẽ
                    được giải quyết theo quy định của pháp luật Việt Nam.</p>
            </div>
            <div class="mb-4">
                <h3>11. Liên Hệ</h3>
                <p class="fs-5">Nếu bạn có câu hỏi hoặc ý kiến về các điều khoản này, vui lòng liên hệ với chúng tôi qua
                    email <a href="mailto:huyen107203@gmail.com" style="color: #767676">huyen107203@gmail.com</a>.</p>
            </div>
        </section>
    </main>
@endsection
