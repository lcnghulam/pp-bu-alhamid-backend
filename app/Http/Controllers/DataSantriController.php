<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use App\Models\Santri;

class DataSantriController extends Controller
{
    public function tambah(Request $request)
    {
        // - NIS Generator -
        $zone = 'Asia/Jakarta';
        $currentYear = Carbon::now($zone)->format('y'); // last 2 digits
        $prevYear = str_pad($currentYear - 1, 2, '0', STR_PAD_LEFT);
        $currentMonth = Carbon::now($zone)->format('m'); // last 2 digits

        // Thn Ajaran
        $tahunAjaran = ($currentMonth < 7) ? "{$prevYear}{$currentYear}" : "{$currentYear}" . str_pad($currentYear + 1, 2, '0', STR_PAD_LEFT);

        $lastReg = Santri::where('nis', 'LIKE', "{$tahunAjaran}{$currentMonth}%")
            ->latest('nis')
            ->first();

        if ($lastReg) {
            $lastNumb = intval(substr($lastReg->nis, -4)) + 1;
        } else {
            $lastNumb = 1; // RegNumb Restart
        }

        // Pastikan nomor urut tetap 4 digit (0001, 0002, dst.)
        $startReg = str_pad($lastNumb, 4, '0', STR_PAD_LEFT);

        $finalReg = $tahunAjaran . $currentMonth . $startReg;

        return Inertia::render('Data-Santri/Tambah', [
            'nis' => $finalReg
        ]);
    }
}
