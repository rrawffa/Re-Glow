<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Glow - Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #e8f5f3 0%, #fff 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
        }

        /* Header */
        .header {
            background: #20413A;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-img {
            width: 50px;
            height: 50px;
            filter: brightness(0) invert(1);
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            letter-spacing: -1px;
        }

        .btn-logout {
            padding: 12px 30px;
            background: #F9B6C7;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Bricolage Grotesque', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #f7a3b8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(249, 182, 199, 0.4);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .welcome-card {
            background: white;
            padding: 60px 80px;
            border-radius: 30px;
            box-shadow: 0 10px 40px rgba(32, 65, 58, 0.15);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .user-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #20413A 0%, #2d5a4d 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 3rem;
        }

        .welcome-text {
            font-size: 1.5rem;
            color: #666;
            margin-bottom: 15px;
        }

        .username-text {
            font-size: 3rem;
            font-weight: 700;
            color: #20413A;
            margin-bottom: 20px;
        }

        .role-badge {
            display: inline-block;
            padding: 10px 25px;
            background: #20413A;
            color: white;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .description {
            margin-top: 30px;
            color: #888;
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .logo-text {
                font-size: 1.4rem;
            }

            .welcome-card {
                padding: 40px 30px;
            }

            .username-text {
                font-size: 2rem;
            }

            .user-icon {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-container">
            <img src="{{ asset('assets/re-glow.svg') }}" alt="Re-Glow" class="logo-img">
            <h1 class="logo-text">Re-Glow</h1>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="welcome-card">
            <div class="user-icon">👨‍💼</div>
            <p class="welcome-text">Halo,</p>
            <h2 class="username-text">{{ Session::get('username') }}!</h2>
            <span class="role-badge">ADMINISTRATOR</span>
            <p class="description">
                Selamat datang di Dashboard Administrator Re-Glow. Anda memiliki akses penuh untuk mengelola sistem, pengguna, dan semua fitur aplikasi.
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Re-Glow. Administrator Panel. 🌸♻️</p>
    </div>
</body>
</html>