@extends('layouts.app')
@section('title')
    Về chúng tôi
@endsection
@section('content')
    @push('style')
        <style>
            .text-align_justify {
                text-align: justify;
            }
        </style>
    @endpush
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="contact-us container">
            <div class="mw-930">
                <h2 class="page-title">Về chúng tôi</h2>
            </div>
            <div class="about-us__content pb-5 mb-5">
                <p class="mb-5">
                    <img loading="lazy" class="w-100 h-auto d-block" src="assets/images/about/about-1.jpg" width="1410"
                        height="550" alt="" />
                </p>
                <div class="mw-930">
                    <h3 class="mb-3 text-uppercase">câu chuyện của chúng tôi</h3>
                    <p class="fs-6 fw-medium mb-5 text-align_justify">Từ những bước đi đầu tiên, NXHuyenClothes được sinh ra
                        từ niềm đam mê với thời trang và khát khao mang đến cho người mặc những sản phẩm chất lượng, phong
                        cách và độc đáo. Với sứ mệnh tạo ra những bộ trang phục không chỉ đẹp mà còn thể hiện cá tính và giá
                        trị của người dùng, NXHuyenClothes đã trải qua một hành trình đáng nhớ, vượt qua mọi thử thách để
                        trở thành thương hiệu đáng tin cậy.</p>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="mb-3 text-uppercase">Sứ mệnh</h5>
                            <p class="mb-3 text-align_justify">Sứ mệnh của NXHuyenClothes là mang đến những sản phẩm thời
                                trang phù hợp cho mọi lứa tuổi và phong cách, giúp khách hàng thể hiện bản thân một cách tự
                                tin và thoải mái. Chúng tôi cam kết chất lượng trong từng chi tiết và luôn nỗ lực để đáp ứng
                                tốt nhất nhu cầu của khách hàng, đồng thời góp phần vào sự phát triển bền vững của ngành
                                thời trang.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3 text-uppercase">tầm nhìn</h5>
                            <p class="mb-3 text-align_justify">NXHuyenClothes hướng đến việc trở thành thương hiệu thời
                                trang hàng đầu trong nước, không chỉ cung cấp các sản phẩm thời trang mà còn mang lại giá
                                trị và cảm hứng cho khách hàng. Chúng tôi không ngừng đổi mới và phát triển, hy vọng có thể
                                góp phần thay đổi tích cực cho ngành thời trang Việt Nam và tạo dựng niềm tin yêu bền vững
                                từ phía khách hàng.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mw-930 d-lg-flex align-items-lg-center mt-5">
                    <div class="image-wrapper col-lg-6">
                        <img class="h-auto" loading="lazy" src="assets/images/about/about-1.jpg" width="450"
                            height="500" alt="">
                    </div>
                    <div class="content-wrapper col-lg-6 px-lg-4">
                        <h5 class="mb-3 text-uppercase">công ty</h5>
                        <p class="text-align_justify">NXHuyenClothes là công ty thời trang tập trung vào việc mang đến trải
                            nghiệm mua sắm hoàn hảo cho khách hàng, từ chất lượng sản phẩm đến dịch vụ tận tâm. Đội ngũ của
                            chúng tôi bao gồm những con người tài năng, đam mê và tận tụy, luôn nỗ lực hết mình để đảm bảo
                            rằng mỗi sản phẩm đến tay khách hàng đều đạt chuẩn cao nhất về chất lượng và thẩm mỹ.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
