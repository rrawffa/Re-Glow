@extends('layouts.app')

@section('title', 'Re-Glow - Dashboard Pengguna')

{{-- Bagian Styles digunakan untuk menyimpan semua CSS unik dari dashboard lama --}}
@section('styles')
    <style>
        /* Container untuk mengatur background dan tata letak tengah */
        .dashboard-container {
            /* Mengambil background lama dari body dashboard.blade.php */
            background: linear-gradient(135deg, #fef5f8 0%, #fff 100%);
            /* Mengatur tinggi agar mengisi sisa ruang antara navbar dan footer */
            min-height: calc(100vh - 150px); 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }

        /* Style untuk tombol Logout, dipindahkan dari Header lama */
        .btn-logout {
            padding: 12px 30px;
            background: #20413A; /* Menggunakan var(--green-dark) */
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Bricolage Grotesque', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 30px; /* Tambahkan margin agar tidak terlalu mepet */
        }

        .btn-logout:hover {
            background: #163026;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(32, 65, 58, 0.3);
        }

        .welcome-card {
            background: white;
            padding: 60px 80px;
            border-radius: 30px;
            box-shadow: 0 10px 40px rgba(249, 182, 199, 0.2);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .user-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #F9B6C7 0%, #ffc9d4 100%);
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
            color: #F9B6C7;
            margin-bottom: 20px;
        }

        .role-badge {
            display: inline-block;
            padding: 10px 25px;
            background: #F9B6C7;
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

        @media (max-width: 768px) {
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
@endsection

{{-- Bagian Content diisi dengan konten utama Dasbor --}}
@section('content')
    <div class="dashboard-container">
        {{-- Konten Utama Dashboard --}}
        <div class="welcome-card">
            <div class="user-icon">👤</div>
            <p class="welcome-text">Halo,</p>
            <h2 class="username-text">{{ Session::get('username') }}!</h2>
            <span class="role-badge">PENGGUNA</span>
            <p class="description">
                Selamat datang di Re-Glow! Mulai perjalanan Anda untuk memberikan dampak positif pada planet dengan mendaur ulang limbah kosmetik.
            </p>
            
            {{-- Tombol Logout dipindahkan dari Header lama ke dalam Welcome Card --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>
@endsection