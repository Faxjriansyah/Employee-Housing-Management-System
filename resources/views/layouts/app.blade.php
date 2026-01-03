<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Employee Housing System')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">


    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- DevExtreme --}}
    <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.5/css/dx.light.css">

    {{-- Font Awesome untuk icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --sidebar-width: 220px;
            --sidebar-collapsed-width: 60px;
            --transition-speed: 0.3s;
        }

        body {
            background: #f4f6f9;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            display: flex;
            min-height: 100vh;
            transition: all var(--transition-speed) ease;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
            color: #fff;
            transition: all var(--transition-speed) ease;
            position: relative;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 20px 15px;
            border-bottom: 1px solid #374151;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 80px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            overflow: hidden;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo i {
            font-size: 20px;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 600;
            white-space: nowrap;
            transition: opacity var(--transition-speed) ease;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 18px;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .toggle-btn:hover {
            color: #fff;
            background: #374151;
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.2s;
            margin: 0 5px;
            border-radius: 6px;
            white-space: nowrap;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .menu-item.active {
            background: #3b82f6;
            color: white;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }

        .menu-item i {
            font-size: 18px;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        .menu-text {
            transition: opacity var(--transition-speed) ease;
            overflow: hidden;
        }

        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
        }

        .content-area {
            flex: 1;
            padding: 20px;
            transition: all var(--transition-speed) ease;
            overflow-x: hidden;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding: 15px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            color: #6b7280;
            font-size: 20px;
            cursor: pointer;
        }

        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .content-body {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            min-height: calc(100vh - 180px);
        }

        /* Style untuk logout button di sidebar */
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: #fca5a5;
            text-decoration: none;
            transition: all 0.2s;
            margin: 0 5px;
            border-radius: 6px;
            white-space: nowrap;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100vh;
                z-index: 1000;
                transform: translateX(0);
            }

            .sidebar.collapsed {
                transform: translateX(calc(-100% + 60px));
            }

            .content-area {
                padding: 15px;
            }

            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .user-menu {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="main-container">
        {{-- Sidebar dengan DevExtreme styling --}}
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="logo-text">EHMS </div>
                </div>
                <button class="toggle-btn" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="sidebar-menu">
                <a href="/dashboard" class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-text">Dashboard</span>
                </a>

                <a href="/employees" class="menu-item {{ request()->is('employees*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="menu-text">Employees</span>
                </a>

                <a href="/rooms" class="menu-item {{ request()->is('rooms*') ? 'active' : '' }}">
                    <i class="fas fa-bed"></i>
                    <span class="menu-text">Rooms</span>
                </a>

                <a href="/occupancies" class="menu-item {{ request()->is('occupancies*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span class="menu-text">Room Occupancies</span>
                </a>

                <a href="/reports/occupancy" class="menu-item">
                    <i class="fas fa-file"></i>
                    <span class="menu-text">Occupancy Report</span>
                </a>

            </div>

            <div style="position: absolute; bottom: 20px; left: 0; right: 0; padding: 0 15px;">
                <!-- Logout Button di sini -->
                <button class="logout-btn" id="logoutButton">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="menu-text">Logout</span>
                </button>
            </div>
        </div>

        {{-- Content Area --}}
        <div class="content-area">
            <div class="content-header">
                <h1 class="page-title">
                    @yield('page-title', 'Dashboard')
                    <small class="text-muted" style="font-size: 14px; font-weight: normal;">
                        @yield('page-subtitle', 'Employee Housing Management System')
                    </small>
                </h1>

                <div class="user-menu">
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </button>

                    <div class="user-profile">
                        <div class="user-avatar">AD</div>
                        <div>
                            <div style="font-weight: 600; color: #1f2937;">Admin User</div>
                            <div style="font-size: 12px; color: #6b7280;">Administrator</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- DevExtreme --}}
    <script src="https://cdn3.devexpress.com/jslib/23.2.5/js/dx.all.js"></script>

    <script>
        $(document).ready(function() {
            // Sidebar toggle functionality
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('collapsed');

                // Change icon based on state
                const icon = $(this).find('i');
                if ($('#sidebar').hasClass('collapsed')) {
                    icon.removeClass('fa-bars').addClass('fa-chevron-right');
                } else {
                    icon.removeClass('fa-chevron-right').addClass('fa-bars');
                }

                // Store state in localStorage
                localStorage.setItem('sidebarCollapsed', $('#sidebar').hasClass('collapsed'));
            });

            // Check saved sidebar state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                $('#sidebar').addClass('collapsed');
                $('#sidebarToggle i').removeClass('fa-bars').addClass('fa-chevron-right');
            }

            // Mobile menu toggle
            $(document).on('click', function(e) {
                if ($(window).width() <= 768 &&
                    !$(e.target).closest('#sidebar').length &&
                    !$(e.target).closest('#sidebarToggle').length) {
                    $('#sidebar').addClass('collapsed');
                    $('#sidebarToggle i').removeClass('fa-bars').addClass('fa-chevron-right');
                    localStorage.setItem('sidebarCollapsed', 'true');
                }
            });

            // Initialize DevExtreme components
            DevExpress.localization.locale('id');

            // CSRF setup for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            // ======================
            // LOGOUT FUNCTIONALITY
            // ======================
            $('#logoutButton').click(function() {
                // Tampilkan konfirmasi menggunakan DevExtreme dialog
                DevExpress.ui.dialog.confirm(
                    "Are you sure you want to logout?",
                    "Confirm Logout"
                ).done(function(dialogResult) {
                    if (dialogResult) {
                        // Tampilkan loading indicator
                        const loadingToast = DevExpress.ui.notify({
                            message: "Logging out...",
                            type: "info",
                            displayTime: 0, // indefinite
                            closeOnClick: false
                        });

                        // Kirim request logout
                        $.ajax({
                            url: '/logout',
                            method: 'POST',
                            success: function() {
                                // Redirect ke halaman login
                                window.location.href = '/login';
                            },
                            error: function(xhr) {
                                loadingToast.hide();

                                // Jika AJAX gagal, coba redirect langsung
                                if (xhr.status === 401 || xhr.status === 419) {
                                    // Session expired, redirect ke login
                                    window.location.href = '/login';
                                } else {
                                    // Tampilkan error
                                    DevExpress.ui.notify(
                                        "Logout failed. Please try again.",
                                        "error",
                                        3000
                                    );
                                }
                            }
                        });
                    }
                });
            });

            // Juga tambahkan logout ke user profile jika diklik
            $('.user-profile').click(function() {
                // Buka popup menu untuk logout dari user profile
                const popupMenu = $('#userProfileMenu');
                if (!popupMenu.length) {
                    // Buat popup menu
                    $('body').append(`
                        <div id="userProfileMenu" style="position: fixed; z-index: 9999;"></div>
                    `);

                    $('#userProfileMenu').dxPopup({
                        contentTemplate: function() {
                            return `
                                <div style="padding: 10px;">
                                    <div style="padding: 8px 12px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                        Account Menu
                                    </div>
                                    <button id="profileLogoutBtn" style="width: 100%; padding: 10px; text-align: left; border: none; background: none; color: #ef4444; cursor: pointer; border-radius: 4px;">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </div>
                            `;
                        },
                        showTitle: false,
                        width: 200,
                        height: 'auto',
                        position: {
                            of: '.user-profile',
                            offset: {
                                y: 10,
                                x: -150
                            }
                        },
                        shading: false,
                        closeOnOutsideClick: true,
                        onHidden: function() {
                            $('#userProfileMenu').remove();
                        }
                    }).dxPopup('instance');

                    // Tampilkan popup
                    $('#userProfileMenu').dxPopup('show');

                    // Handle logout dari popup
                    $(document).on('click', '#profileLogoutBtn', function() {
                        $('#logoutButton').click();
                    });
                }
            });
        });

        // Make sidebar state available globally
        window.isSidebarCollapsed = function() {
            return $('#sidebar').hasClass('collapsed');
        };
    </script>

    @stack('scripts')
</body>

</html>
