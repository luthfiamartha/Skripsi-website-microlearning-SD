<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MonitorsProgress;
use App\Http\Controllers\Concerns\ResolvesPembelajaran;
use App\Models\Materi;
use Illuminate\Http\Request;

class GuruProgressController extends Controller
{
    use MonitorsProgress, ResolvesPembelajaran;

    public function index(Request $request)
    {
        $guru = $request->user();

        $daftarPembelajaran = $guru->pembelajaranDibuat()
            ->withCount('siswa')
            ->orderByDesc('id')
            ->get();

        $kosong = [
            'prefix' => 'guru',
            'judul' => 'Progress Siswa',
            'subjudul' => 'Pantau progress siswa yang bergabung ke pembelajaranmu.',
            'pilihan' => collect(),
            'pembelajaranAktif' => null,
            'labelKonteks' => null,
            'ringkasan' => collect(),
            'siswaAktif' => null,
            'detailProgress' => collect(),
            'hasilSiswa' => collect(),
            'totalModul' => 0,
            'pesanKosong' => 'Belum ada pembelajaran. Buat pembelajaran terlebih dahulu, lalu bagikan kode-nya kepada siswa.',
        ];

        if ($daftarPembelajaran->isEmpty()) {
            return view('guru.progress.index', $kosong);
        }

        $id = $request->query('pembelajaran');
        $pembelajaranAktif = null;

        if ($id && ctype_digit((string) $id)) {
            // Hanya pembelajaran milik guru ini yang boleh dipilih
            $pembelajaranAktif = $daftarPembelajaran->firstWhere('id', (int) $id);
        }

        $pembelajaranAktif = $pembelajaranAktif ?: $daftarPembelajaran->first();

        $totalModul = (int) Materi::withCount('modul')->get()->sum('modul_count');

        $anggota = $pembelajaranAktif->siswa()->orderBy('name')->get();

        $ringkasan = $this->ringkasanSiswa($anggota, $pembelajaranAktif, $totalModul);

        $siswaAktif = null;
        $detailProgress = collect();
        $hasilSiswa = collect();

        $siswaId = $request->query('siswa');

        if ($siswaId && ctype_digit((string) $siswaId)) {
            $siswaAktif = $anggota->firstWhere('id', (int) $siswaId);

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

        $pilihan = $daftarPembelajaran->map(fn ($p) => [
            'label' => $p->nama,
            'emoji' => '👩‍🏫',
            'params' => ['pembelajaran' => $p->id],
            'aktif' => $p->id === $pembelajaranAktif->id,
        ]);

        return view('guru.progress.index', [
            'prefix' => 'guru',
            'judul' => 'Progress Siswa',
            'subjudul' => 'Hanya siswa yang bergabung ke pembelajaranmu yang tampil di sini.',
            'pilihan' => $pilihan,
            'pembelajaranAktif' => $pembelajaranAktif,
            'labelKonteks' => $pembelajaranAktif->nama,
            'ringkasan' => $ringkasan,
            'siswaAktif' => $siswaAktif,
            'detailProgress' => $detailProgress,
            'hasilSiswa' => $hasilSiswa,
            'totalModul' => $totalModul,
            'pesanKosong' => $ringkasan->isEmpty() ? 'Belum ada siswa yang bergabung ke pembelajaran ini. Bagikan kode ' . $pembelajaranAktif->kode . ' kepada siswa.' : null,
        ]);
    }
}
