<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - PAULINE</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --admin-primary: #8b7355;
            --admin-secondary: #c9a882;
            --admin-dark: #2c2c2c;
            --admin-light: #f8f5f2;
        }
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: #f5f5f5;
        }
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 250px;
            background: var(--admin-dark);
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .admin-logo {
            padding: 0 2rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 2rem;
        }
        .admin-logo h2 {
            margin: 0;
            color: var(--admin-secondary);
            font-size: 1.5rem;
        }
        .admin-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .admin-menu li {
            margin: 0;
        }
        .admin-menu a {
            display: block;
            padding: 1rem 2rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .admin-menu a:hover,
        .admin-menu a.active {
            background: rgba(255,255,255,0.1);
            border-left-color: var(--admin-secondary);
            color: white;
        }
        .admin-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }
        .admin-header {
            background: white;
            padding: 1.5rem 2rem;
            margin: -2rem -2rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 {
            margin: 0;
            color: var(--admin-dark);
            font-size: 1.8rem;
        }
        .admin-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .btn-logout {
            padding: 0.5rem 1rem;
            background: var(--admin-primary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .admin-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h2>PAULINE</h2>
                <p style="margin: 0.5rem 0 0; font-size: 0.9rem; color: rgba(255,255,255,0.6);">Admin Panel</p>
            </div>
            <ul class="admin-menu">
                <li><a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a></li>
                <li><a href="/admin/products" class="{{ request()->is('admin/products*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam me-2"></i> Ürünler
                </a></li>
                <li><a href="/admin/flavours" class="{{ request()->is('admin/flavours*') ? 'active' : '' }}">
                    <i class="bi bi-stars me-2"></i> Lezzetler
                </a></li>
                <li><a href="/admin/pages" class="{{ request()->is('admin/pages*') ? 'active' : '' }}">
                    <i class="bi bi-file-text me-2"></i> Sayfa İçerikleri
                </a></li>
                <li><a href="/admin/orders" class="{{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check me-2"></i> Siparişler
                </a></li>
            </ul>
        </aside>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>@yield('title', 'Dashboard')</h1>
                <div class="admin-user">
                    <span id="adminUserName">Admin</span>
                    <a href="#" class="btn-logout" onclick="logout()">Çıkış</a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load user info
            const adminUser = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const userNameElement = document.getElementById('adminUserName');
            if (adminUser.name && userNameElement) {
                userNameElement.textContent = adminUser.name;
            }
            
            // Check authentication
            if (!localStorage.getItem('admin_token')) {
                window.location.href = '/admin/login';
            }
            
            // Add token to all fetch requests
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                const token = localStorage.getItem('admin_token');
                if (token && args[1]) {
                    args[1].headers = {
                        ...args[1].headers,
                        'Authorization': 'Bearer ' + token
                    };
                }
                return originalFetch.apply(this, args);
            };
        });
        
        // Logout function (global)
        async function logout() {
            if (typeof Swal === 'undefined') {
                if (confirm('Çıkış yapmak istediğinize emin misiniz?')) {
                    const token = localStorage.getItem('admin_token');
                    if (token) {
                        fetch('/api/auth/logout', {
                            method: 'POST',
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Content-Type': 'application/json'
                            }
                        });
                    }
                    localStorage.removeItem('admin_token');
                    localStorage.removeItem('admin_user');
                    window.location.href = '/admin/login';
                }
                return;
            }

            const result = await Swal.fire({
                title: 'Çıkış yapmak istediğinize emin misiniz?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8b7355',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Evet, Çıkış Yap',
                cancelButtonText: 'İptal'
            });

            if (result.isConfirmed) {
                const token = localStorage.getItem('admin_token');
                if (token) {
                    fetch('/api/auth/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        }
                    });
                }
                localStorage.removeItem('admin_token');
                localStorage.removeItem('admin_user');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Çıkış yapıldı!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '/admin/login';
                });
            }
        }
    </script>
</body>
</html>
