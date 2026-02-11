<!-- Navigation -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="/">PAULINE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/#home">Ana Sayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/#products">Ürünler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/#flavours">Lezzetler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">Hakkımızda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">İletişim</a>
                </li>
            </ul>
            <div class="navbar-icons">
                <a href="#search"><i class="bi bi-search"></i></a>
                @auth
                    <div class="dropdown" style="position: relative; display: inline-block;">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" style="text-decoration: none; color: inherit;">
                            <i class="bi bi-person"></i>
                            <span style="margin-left: 0.25rem;">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu" style="position: absolute; right: 0; background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-width: 150px; padding: 0.5rem 0; margin-top: 0.5rem; list-style: none;">
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}" style="padding: 0.5rem 1rem; display: block; text-decoration: none; color: #333;">Siparişlerim</a></li>
                            <li><hr style="margin: 0.5rem 0;"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="width: 100%; text-align: left; padding: 0.5rem 1rem; border: none; background: none; color: #333; cursor: pointer;">
                                        Çıkış Yap
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" style="text-decoration: none; color: inherit; margin-right: 1rem;">
                        <i class="bi bi-person"></i> Giriş
                    </a>
                @endauth
                <a href="/cart" class="cart-icon-wrapper">
                    <i class="bi bi-bag"></i>
                    @if(session()->has('cart') && count(session()->get('cart')) > 0)
                        <span class="cart-badge">{{ count(session()->get('cart')) }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>
</nav>
