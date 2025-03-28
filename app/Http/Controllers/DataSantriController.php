<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use App\Models\Santri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

use function Pest\Laravel\json;

class DataSantriController extends Controller
{
    public function data(Request $request)
    {
        $query = Santri::query();

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                if (!empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where('nama_lengkap', 'LIKE', "%{$search}%")
                        ->orWhere('nis', 'LIKE', "%{$search}%");
                }
            })
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $status = 'aktif';
                if ($row->tgl_keluar && Carbon::parse($row->tgl_keluar)->lt(Carbon::today())) {
                    $status = "non-aktif";
                }
                return $status;
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function tambah()
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

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nis'           => 'required|numeric|unique:santri,nis',
                'nik'           => 'required|numeric|unique:santri,nik',
                'nama_lengkap'  => 'required|string|max:255',
                'tempat_lahir'  => 'required|string|max:100',
                'tgl_lahir'     => 'required|date',
                'gender'        => 'required|in:L,P',
                'email'         => 'nullable|email|unique:santri,email',
                'no_hp'         => 'required|string|max:255',
                'alamat'        => 'required|string',
                'foto'          => 'nullable|image|mimes:jpg,png,jpeg,webp,svg|max:400',
                'tgl_masuk'     => 'required|date',
                'tgl_keluar'    => 'nullable|date|after_or_equal:tgl_masuk',
            ]);

            DB::beginTransaction();

            if ($request->hasFile('foto')) {
                $nis = $validated['nis'];
                $extension = $request->file('foto')->getClientOriginalExtension();
                $filename = "{$nis}  - " . uniqid() . "." . "{$extension}"; // Format nama file

                $validated['foto'] = $filename;
            }

            $validated['status'] = 1;
            Santri::create($validated);

            DB::commit();
            if ($request->hasFile('foto')) {
                $request->file('foto')->storeAs('data-santri', $filename, 'public');
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $nis = $request->nis;
            $data = Santri::where('nis', $nis)->first();

            return Inertia::render('Data-Santri/Edit', [
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            // DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ], 500);
        }
    }

    public function patch(Request $request)
    {
        try {
            // dd($request->all());
            $nis = $request->nis;
            $santri = Santri::where('nis', $nis)->first();

            $request->merge(
                collect($request->all())->map(fn($value) => $value === "null" || $value === "" ? null : $value)->toArray()
            );

            $validated = $request->validate([
                'nis'           => 'required|numeric|unique:santri,nis,' . $santri->id . ',id',
                'nik'           => 'required|numeric|unique:santri,nik,' . $santri->id . ',id',
                'nama_lengkap'  => 'required|string|max:255',
                'tempat_lahir'  => 'required|string|max:100',
                'tgl_lahir'     => 'required|date',
                'gender'        => 'required|in:L,P',
                'email'         => 'nullable|email|unique:santri,email,' . $santri->id . ',id',
                'no_hp'         => 'required|string|max:255',
                'alamat'        => 'required|string',
                'foto'          => 'nullable|image|mimes:jpg,png,jpeg,webp|max:400',
                'tgl_masuk'     => 'required|date',
                'tgl_keluar'    => 'nullable|date|after_or_equal:tgl_masuk',
            ]);

            DB::beginTransaction();

            $changes = [];
            foreach ($validated as $key => $value) {
                if (!is_null($value) && $santri->$key != $value) {
                    $changes[$key] = $value;
                }
            }

            // Jika ada file foto baru diunggah
            if ($request->hasFile('foto')) {
                $filename = "{$santri->nis}-" . uniqid() . "." . $request->file('foto')->getClientOriginalExtension();
                
                $fotoPath = "data-santri/{$santri->foto}"; 
                if (Storage::disk('public')->exists($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
                $request->file('foto')->storeAs('data-santri', $filename, 'public');

                $changes['foto'] = $filename;
            }

            // Update hanya jika ada perubahan
            if (!empty($changes)) {
                $santri->update($changes);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Update Data Sukses!"
            ]);
        } catch (\Exception $e) {
            // DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ], 500);
        }
    }

    public function softDel(Request $request)
    {
        try {
            $nis = $request->nis;
            $santri = Santri::where('nis', $nis)->first();
            $nama = $santri->nama_lengkap;

            if (!$santri) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan!'
                ]);
            }

            DB::beginTransaction();

            $santri->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data santri dengan <br>Nama : <strong>' . $nama . '</strong><br>NIS : <strong>' . $nis . '</strong>'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ], 500);
        }
    }
}
