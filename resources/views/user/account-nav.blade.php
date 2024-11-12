<ul class="account-nav">
    <li><a href="{{ route('user.index') }}" class="menu-link menu-link_us-s">Dashboard</a></li>
    <li><a href="account-orders.html" class="menu-link menu-link_us-s">Đơn hàng</a></li>
    <li><a href="account-address.html" class="menu-link menu-link_us-s">Địa chỉ</a></li>
    <li><a href="account-details.html" class="menu-link menu-link_us-s">Chi tiết tài khoản</a></li>
    <li><a href="{{ route('wishlist.index') }}" class="menu-link menu-link_us-s">Danh sách mong muốn</a></li>
    <form action="{{ route('logout') }}" method="post" id="logout-form">
        @csrf
        <li>
            <a href="{{ route('logout') }}" class="menu-link menu-link_us-s"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
        </li>
    </form>
</ul>
