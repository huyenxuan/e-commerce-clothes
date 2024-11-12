@extends('layouts.app')
@section('title')
    Chính sách bảo mật
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
                <h2 class="page-title mb-1">Chính Sách Bảo Mật của NXHuyenClothes</h2>
                <p class="mb-1 fs-4">Ngày hiệu lực: 10/07/2003</p>
            </div>
        </section>

        <div class="mb-5 pb-4"></div>
        <section class="container mw-930 lh-30">
            <div class="mb-4">
                <h3>1. Giới thiệu</h3>
                <p class="fs-5"> Quyền riêng tư của bạn rất quan trọng với chúng tôi. Chính sách bảo mật này giải thích
                    cách NXHuyenClothes thu thập, sử dụng và bảo vệ thông tin của bạn khi bạn truy cập trang web của chúng
                    tôi, sử dụng dịch vụ, hoặc thực hiện giao dịch mua hàng.</p>
            </div>
            <div class="mb-4">
                <h3>2. Thông Tin Chúng Tôi Thu Thập</h3>
                <p class="fs-5"> Chúng tôi thu thập các loại thông tin khác nhau để cung cấp dịch vụ tốt hơn cho tất cả
                    người dùng, bao gồm:</p>
                <ul>
                    <li class="fs-5">Thông Tin Cá Nhân: Tên, địa chỉ email, số điện thoại và địa chỉ của bạn khi bạn tạo
                        tài khoản, thực
                        hiện mua hàng hoặc đăng ký nhận bản tin.</li>
                    <li class="fs-5">Thông Tin Thanh Toán: Chi tiết thẻ tín dụng và thông tin thanh toán để xử lý giao
                        dịch an toàn (<strong>Lưu ý</strong> rằng chúng tôi không lưu trữ toàn bộ chi tiết thanh toán).</li>
                    <li class="fs-5">Dữ Liệu Sử Dụng: Thông tin về thiết bị của bạn, địa chỉ IP, và cách bạn tương tác với
                        trang web của chúng tôi.</li>
                </ul>
            </div>
            <div class="mb-4">
                <h3>3. Cách Chúng Tôi Sử Dụng Thông Tin Của Bạn</h3>
                <p class="fs-5">Chúng tôi sử dụng thông tin của bạn cho các mục đích như:</p>
                <ul>
                    <li class="fs-5">Xử lý và hoàn thành đơn hàng, cung cấp hỗ trợ khách hàng.</li>
                    <li class="fs-5">Gửi cập nhật, ưu đãi khuyến mãi và thông tin liên quan đến giao dịch của bạn (bạn có
                        thể hủy nhận thông báo bất cứ lúc nào).</li>
                    <li class="fs-5">Cải thiện dịch vụ, trang web và các sản phẩm của chúng tôi thông qua phân tích hành
                        vi người dùng</li>
                </ul>
            </div>
            <div class="mb-4">
                <h3>4. Chia Sẻ Thông Tin Của Bạn</h3>
                <p class="fs-5">Chúng tôi không bán hoặc cho thuê thông tin cá nhân của bạn cho bên thứ ba. Tuy nhiên,
                    chúng tôi có thể chia sẻ thông tin với:</p>
                <ul>
                    <li class="fs-5">Đối Tác Dịch Vụ: Các đối tác giúp chúng tôi xử lý thanh toán, giao hàng và tiếp thị.
                    </li>
                    <li class="fs-5">Yêu Cầu Pháp Lý: Nếu được yêu cầu bởi pháp luật hoặc để bảo vệ quyền lợi hợp pháp của
                        chúng tôi.</li>
                </ul>
            </div>
            <div class="mb-4">
                <h3>5. Bảo Mật Thông Tin Của Bạn</h3>
                <p class="fs-5">Chúng tôi nghiêm túc trong việc bảo mật và sử dụng các biện pháp tiêu chuẩn ngành để bảo
                    vệ dữ liệu của bạn. Tuy nhiên, không nền tảng nào trên mạng là hoàn toàn bảo mật.</p>
            </div>
            <div class="mb-4">
                <h3>6. Quyền Lựa Chọn Của Bạn</h3>
                <ul>
                    <li class="fs-5">Truy Cập và Cập Nhật: Bạn có thể cập nhật thông tin cá nhân trong cài đặt tài khoản
                        của mình.</li>
                    <li class="fs-5">Từ Chối Nhận Thông Báo: Bạn có thể hủy đăng ký nhận email tiếp thị của chúng tôi bằng
                        cách làm theo hướng dẫn trong email.</li>
                </ul>
            </div>
            <div class="mb-4">
                <h3>7. Thay Đổi Chính Sách Này</h3>
                <p class="fs-5">Chúng tôi có thể cập nhật Chính sách Bảo mật của mình theo thời gian. Vui lòng xem lại
                    trang này định kỳ.</p>
            </div>
            <div class="mb-4">
                <h3>8. Liên Hệ Với Chúng Tôi</h3>
                <p class="fs-5">Mọi thắc mắc hoặc quan ngại về chính sách này, vui lòng liên hệ chúng tôi qua
                    <a href="{{ route('contact.index') }}">LIÊN HỆ</a> hoặc qua email:
                    <a href="mailto:huyen107203@gmail.com" style="color: #767676">huyen107203@gmail.com</a>.
                </p>
            </div>
        </section>
    </main>
@endsection
