<?php

namespace App\Http\Controllers;

class PeminjamanController extends Controller
{
    private function destinasi(): array
    {
        return [
            ['nama' => 'alat laboratorium → ', 'harga' => 1250000],
            ['nama' => 'Medan → Bandung', 'harga' => 1400000],
            ['nama' => 'Medan → Yogyakarta', 'harga' => 1550000],
            ['nama' => 'Medan → Surabaya', 'harga' => 1650000],
            ['nama' => 'Medan → Denpasar (Bali)', 'harga' => 1950000],
            ['nama' => 'Medan → Kuala Lumpur', 'harga' => 850000],
        ];
    }

    public function index()
    {
        return view('booking', ['destinasi' => $this->destinasi()]);
    }

    public function daftar()
    {
        return view('daftar');
    }
}
