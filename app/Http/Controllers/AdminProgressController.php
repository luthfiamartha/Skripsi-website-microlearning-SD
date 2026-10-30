<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MonitorsProgress;
use App\Http\Controllers\Concerns\ResolvesPembelajaran;
use App\Models\Materi;
use App\Models\Pembelajaran;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProgressController extends Controller
{
    use MonitorsProgress, ResolvesPembelajaran;

    public function index(Request $request)
    {
        $totalModul = (int) Materi::withCount('modul')->get()->sum('modul_count');

        // Konteks aktif: tanpa parameter = Belajar Mandiri (semua siswa)
        $pembelajaranAktif = null;

        $id = $request->query('pembelajaran');

        if ($id && ctype_digit((string) $id)) {
            $pembelajaranAktif = Pembelajaran::with('guru')->find((int) $id);
        }

        if ($pembelajaranAktif) {
            $siswaList = $pembelajaranAktif->siswa()->orderBy('name')->get();
            $labelKonteks = 'Pembelajaran ' . $pembelajaranAktif->nama;
            $pesanKosong = 'Belum ada siswa yang bergabung ke pembelajaran ini.';
        } else {
            $siswaList = User::where('role', 'siswa')->orderBy('name')->get();
            $labelKonteks = 'Belajar Mandiri';
            $pesanKosong = null;
        }

        $ringkasan = $this->ringkasanSiswa($siswaList, $pembelajaranAktif, $totalModul);

        $siswaAktif = null;
        $detailProgress = collect();
        $hasilSiswa = collect();

        $siswaId = $request->query('siswa');

        if ($siswaId && ctype_digit((string) $siswaId)) {
            $siswaAktif = $siswaList->firstWhere('id', (int) $siswaId);

            if ($siswaAktif) {
                $detail = $this->detailSiswa(
                    $siswaAktif,
                    Materi::withCount('modul')->orderBy('id')->get(),
                    $pembelajaranAktif
                );
                $detailProgress = $detail['detailProgress'];
                $hasilSiswa = $detail['hasilSiswa'];
            }
        }

        $pilihan = collect([
            [
                'label' => 'Belajar Mandiri',
                'emoji' => '🌱',
                'params' => [],
                'aktif' => $pembelajaranAktif === null,
            ],
        ])->concat(
            Pembelajaran::with('guru')->orderBy('nama')->get()->map(fn ($p) => [
                'label' => $p->nama . ' (' . ($p->guru?->name ?? '-') . ')',
                'emoji' => '👩‍🏫',
                'params' => ['pembelajaran' => $p->id],
                'aktif' => $pembelajaranAktif !== null && $p->id === $pembelajaranAktif->id,
            ])
        );

        return view('admin.progress.index', [
            'prefix' => 'admin',
            'judul' => 'Progress Siswa',
            'subjudul' => 'Pantau progress belajar seluruh siswa, mandiri maupun pembelajaran guru.',
            'pilihan' => $pilihan,
            'pembelajaranAktif' => $pembelajaranAktif,
            'labelKonteks' => $labelKonteks,
            'ringkasan' => $ringkasan,
            'siswaAktif' => $siswaAktif,
            'detailProgress' => $detailProgress,
            'hasilSiswa' => $hasilSiswa,
            'totalModul' => $totalModul,
            'pesanKosong' => $pesanKosong,
        ]);
    }
}
