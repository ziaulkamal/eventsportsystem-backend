<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function auth() {
        $option = [
            'title'         => 'Halaman Login',
            'h1'            => 'Auth',
            'description'   => 'Selamat datang di aplikasi management Pekan Olahraga 2026 Aceh Jaya'
        ];

        return view('page.auth.index', $option);
    }

    public function dashboard() {
        $option = [
            'title'         => 'Dashboard',
            'description'   => 'Selamat datang di dashboard',
            'section'       => true,
        ];

        return view('page.dashboard.index', $option);
    }

    public function atlet() {
        $option = [
            'title'         => 'Atlet',
            'description'   => 'Menampilkan data atlet secara keseluruhan',
            'section'       => true,
        ];

        return view('page.atlet.atlet', $option);
    }

    public function coach() {
        $option = [
            'title'         => 'Coach',
            'description'   => 'Menampilkan data coach secara keseluruhan',
            'section'       => true,
        ];

        return view('page.atlet.coach', $option);
    }

    public function official() {
        $option = [
            'title'         => 'Official',
            'description'   => 'Menampilkan data official secara keseluruhan',
            'section'       => true,
        ];

        return view('page.atlet.official', $option);
    }

    public function venue() {
        $option = [
            'title'         => 'Venue',
            'description'   => 'Menampilkan data Venue secara keseluruhan',
            'section'       => true,
        ];

        return view('page.venue.index', $option);
    }

    public function master_cabor() {
        $option = [
            'title'         => 'Cabor',
            'description'   => 'Menampilkan data Cabor secara keseluruhan',
            'section'       => true,
        ];

        return view('page.master-konfigurasi.cabor', $option);
    }

    public function master_kelas_cabor() {
        $option = [
            'title'         => 'Kelas Cabor',
            'description'   => 'Menampilkan data Kelas Cabor secara keseluruhan',
            'section'       => true,
        ];

        return view('page.master-konfigurasi.kelas-cabor', $option);
    }

    public function penginapan() {
        $option = [
            'title'         => 'Penginapan',
            'description'   => 'Menampilkan data Penginapan secara keseluruhan',
            'section'       => true,
        ];

        return view('page.penginapan.index', $option);
    }
}
