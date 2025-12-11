<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::query()
            ->where('is_active', true)
            ->orderByPromo()
            ->orderByDesc('is_featured')
            ->orderBy('price')
            ->take(6)
            ->get();

        $stats = [
            ['label' => 'Pengguna Aktif', 'value' => '35.000+'],
            ['label' => 'Proyek Selesai', 'value' => '100.000+'],
            ['label' => 'Rating', 'value' => '4.9/5'],
        ];

        $testimonials = [
            ['name' => 'Ahmad Rizki', 'role' => 'Mahasiswa', 'text' => 'Pesanan selesai tepat waktu dan komunikasinya nyaman.'],
            ['name' => 'Siti Nurhaliza', 'role' => 'Pengusaha', 'text' => 'Kualitas penulisan rapi, tinggal fokus sidang saja.'],
            ['name' => 'Budi Santoso', 'role' => 'Karyawan', 'text' => 'Respon cepat dan hasilnya sesuai brief.'],
        ];

        return view('pages.home', [
            'services' => $services,
            'stats' => $stats,
            'testimonials' => $testimonials,
            'title' => 'Deadlineku — Portal Layanan Akademik',
        ]);
    }
}
