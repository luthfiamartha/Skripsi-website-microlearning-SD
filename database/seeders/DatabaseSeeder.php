<?php

namespace Database\Seeders;

use App\Models\HasilKuis;
use App\Models\JawabanSiswa;
use App\Models\Kuis;
use App\Models\Materi;
use App\Models\Modul;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        User::create([
            'name' => 'Admin Sistem',
            'username' => 'admin',
            'email' => 'admin@ipa.test',
            'password' => $password,
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Guru IPA Kelas III',
            'username' => 'guru',
            'email' => 'guru@ipa.test',
            'password' => $password,
            'role' => 'guru',
        ]);

        $siswa = [];
        foreach ([1, 2, 3, 4] as $i) {
            $siswa[] = User::create([
                'name' => 'Siswa '.$i,
                'username' => 'siswa'.$i,
                'email' => 'siswa'.$i.'@ipa.test',
                'password' => $password,
                'role' => 'siswa',
            ]);
        }

        $dataMateri = [
            [
                'judul' => 'Makhluk Hidup dan Lingkungan',
                'deskripsi' => 'Mengenal makhluk hidup, cirinya, dan lingkungan tempat tinggalnya.',
                'gambar' => 'makhluk-hidup.svg',
                'modul' => [
                    [
                        'judul' => 'Apa itu Makhluk Hidup?',
                        'konten' => "Makhluk hidup adalah semua yang tumbuh, bernapas, dan butuh makan.\nContoh makhluk hidup di sekitar kita adalah tumbuhan, hewan, dan manusia.\nBerbeda dengan batu atau kursi yang tidak bernapas dan tidak tumbuh.",
                    ],
                    [
                        'judul' => 'Ciri Makhluk Hidup',
                        'konten' => "Semua makhluk hidup memiliki ciri yang sama.\nCiri tersebut adalah bernapas, memerlukan makanan, tumbuh, bergerak, dan berkembang biak.\nCoba amati hewan peliharaanmu di rumah, pasti memiliki ciri-ciri tersebut.",
                    ],
                    [
                        'judul' => 'Lingkungan Makhluk Hidup',
                        'konten' => "Lingkungan adalah tempat makhluk hidup tinggal.\nAda lingkungan darat seperti hutan dan sawah.\nAda juga lingkungan air seperti sungai, kolam, dan laut.\nMakhluk hidup menyesuaikan diri dengan lingkungannya.",
                    ],
                ],
                'kuis' => [
                    'judul' => 'Kuis Makhluk Hidup',
                    'soal' => [
                        ['Berikut yang termasuk makhluk hidup adalah...', 'Batu', 'Pohon mangga', 'Meja', 'Gelas', 'b'],
                        ['Ciri makhluk hidup yang benar adalah...', 'Bisa bercahaya', 'Bernapas dan memerlukan makanan', 'Berwarna merah', 'Berasap', 'b'],
                        ['Lingkungan air contohnya adalah...', 'Sungai', 'Gurun', 'Bukit', 'Pegunungan', 'a'],
                        ['Makhluk hidup yang berkembang biak dengan biji adalah...', 'Ikan', 'Kucing', 'Mangga', 'Ayam', 'c'],
                    ],
                ],
            ],
            [
                'judul' => 'Bagian Tubuh Hewan dan Fungsinya',
                'deskripsi' => 'Mengenal bagian tubuh hewan dan fungsinya masing-masing.',
                'gambar' => 'hewan.svg',
                'modul' => [
                    [
                        'judul' => 'Tubuh Ikan',
                        'konten' => "Ikan hidup di air dan memiliki bagian tubuh khas.\nIkan bernapas menggunakan insang.\nIkan bergerak menggunakan sirip dan ekor.\nTubuhnya ditutupi sisik yang licin.",
                    ],
                    [
                        'judul' => 'Tubuh Katak',
                        'konten' => "Katak hidup di air dan darat.\nKatak bernapas menggunakan paru-paru dan kulitnya.\nKatak bergerak melompat menggunakan kaki belakang yang kuat.\nKatak berkembang biak dengan bertelur di air.",
                    ],
                    [
                        'judul' => 'Tubuh Ayam',
                        'konten' => "Ayam hidup di darat.\nAyam bernapas menggunakan paru-paru melalui lubang hidung.\nAyam bergerak menggunakan kaki dan sayapnya.\nAyam memiliki paruh untuk mencari makan.",
                    ],
                ],
                'kuis' => [
                    'judul' => 'Kuis Tubuh Hewan',
                    'soal' => [
                        ['Ikan bernapas menggunakan...', 'Paru-paru', 'Insang', 'Kulit', 'Sirip', 'b'],
                        ['Bagian tubuh yang digunakan katak untuk melompat adalah...', 'Kaki depan', 'Ekor', 'Kaki belakang', 'Sayap', 'c'],
                        ['Ayam mencari makanan menggunakan...', 'Sirip', 'Paruh', 'Insang', 'Cakar depan saja', 'b'],
                        ['Hewan yang hidup di air dan darat adalah...', 'Ayam', 'Kucing', 'Katak', 'Merpati', 'c'],
                    ],
                ],
            ],
            [
                'judul' => 'Tumbuhan di Sekitar Kita',
                'deskripsi' => 'Mengenal bagian tubuh tumbuhan dan manfaatnya bagi kehidupan.',
                'gambar' => 'tumbuhan.svg',
                'modul' => [
                    [
                        'judul' => 'Bagian Tubuh Tumbuhan',
                        'konten' => "Tumbuhan memiliki bagian utama yaitu akar, batang, daun, dan bunga.\nAkar berada di dalam tanah dan menopang tumbuhan.\nBatang berfungsi menyalurkan air dan nutrisi.\nDaun tempat terjadinya proses penting bagi tumbuhan.",
                    ],
                    [
                        'judul' => 'Fungsi Akar dan Daun',
                        'konten' => "Akar menyerap air dan gizi dari tanah.\nAkar juga membuat tumbuhan tidak mudah tumbang.\nDaun menangkap sinar matahari.\nDaun juga menjadi tempat pertukaran udara dan mengeluarkan uap air.",
                    ],
                    [
                        'judul' => 'Tumbuhan Berguna',
                        'konten' => "Tumbuhan bermanfaat bagi manusia dan hewan.\nTumbuhan menghasilkan buah dan sayur sebagai makanan.\nTumbuhan menghasilkan oksigen yang kita hirup.\nTumbuhan juga menjadi tempat tinggal hewan.",
                    ],
                ],
                'kuis' => [
                    'judul' => 'Kuis Tumbuhan',
                    'soal' => [
                        ['Bagian tumbuhan yang menyerap air dari tanah adalah...', 'Daun', 'Bunga', 'Akar', 'Buah', 'c'],
                        ['Fungsi daun adalah...', 'Menahan tanah', 'Menangkap sinar matahari', 'Menyimpan air hujan', 'Membuat bunga', 'b'],
                        ['Tumbuhan menghasilkan gas yang kita butuhkan...', 'Karbon dioksida', 'Nitrogen', 'Oksigen', 'Uap air', 'c'],
                        ['Bagian tumbuhan yang menjadi tempat pertukaran udara adalah...', 'Akar', 'Daun', 'Batang', 'Biji', 'b'],
                    ],
                ],
            ],
            [
                'judul' => 'Air dan Kegunaannya',
                'deskripsi' => 'Mengenal sifat air dan manfaat air bagi makhluk hidup.',
                'gambar' => 'air.svg',
                'modul' => [
                    [
                        'judul' => 'Mengenal Air',
                        'konten' => "Air adalah zat cair yang sangat kita butuhkan.\nAir bersih digunakan untuk minum, memasak, dan mencuci.\nKita harus menjaga sumber air agar tetap bersih.\nAir yang kotor dapat membuat kita sakit.",
                    ],
                    [
                        'judul' => 'Sifat Air',
                        'konten' => "Air bersifat mengalir menuju tempat yang lebih rendah.\nAir selalu mencari wadah sesuai bentuknya.\nAir dapat membeku menjadi es saat suhu sangat dingin.\nAir dapat menguap menjadi uap air saat dipanaskan.",
                    ],
                    [
                        'judul' => 'Kegunaan Air',
                        'konten' => "Makhluk hidup membutuhkan air untuk bertahan hidup.\nTumbuhan menyerap air melalui akarnya.\nHewan meminum air untuk minum dan menjaga tubuhnya.\nManusia menggunakan air untuk berbagai kegiatan sehari-hari.",
                    ],
                ],
                'kuis' => [
                    'judul' => 'Kuis Air',
                    'soal' => [
                        ['Air bersifat mengalir menuju tempat...', 'Lebih tinggi', 'Lebih rendah', 'Tempat datar saja', 'Langit', 'b'],
                        ['Air menjadi es saat suhu...', 'Sangat panas', 'Sangat dingin', 'Sedang', 'Tidak berubah', 'b'],
                        ['Air yang kotor dapat membuat kita...', 'Sehat', 'Kuat', 'Sakit', 'Tinggi', 'c'],
                        ['Tumbuhan menyerap air melalui...', 'Daun', 'Bunga', 'Akar', 'Buah', 'c'],
                    ],
                ],
            ],
        ];

        $materiModels = [];

        foreach ($dataMateri as $m) {
            $materi = Materi::create([
                'judul' => $m['judul'],
                'deskripsi' => $m['deskripsi'],
            ]);

            foreach ($m['modul'] as $i => $modul) {
                Modul::create([
                    'materi_id' => $materi->id,
                    'judul' => $modul['judul'],
                    'konten' => $modul['konten'],
                    'gambar' => $i === 0 ? $m['gambar'] : null,
                    'urutan' => $i + 1,
                ]);
            }

            $kuis = Kuis::create([
                'materi_id' => $materi->id,
                'judul' => $m['kuis']['judul'],
            ]);

            foreach ($m['kuis']['soal'] as $i => $soal) {
                $kuis->soal()->create([
                    'pertanyaan' => $soal[0],
                    'pilihan_a' => $soal[1],
                    'pilihan_b' => $soal[2],
                    'pilihan_c' => $soal[3],
                    'pilihan_d' => $soal[4],
                    'kunci_jawaban' => $soal[5],
                    'urutan' => $i + 1,
                ]);
            }

            $materiModels[] = ['materi' => $materi, 'kuis' => $kuis];
        }

        // Progress siswa1: materi pertama selesai, materi kedua setengah jalan.
        $m1 = $materiModels[0]['materi'];
        $m2 = $materiModels[1]['materi'];

        Progress::create([
            'user_id' => $siswa[0]->id,
            'materi_id' => $m1->id,
            'status' => 'selesai',
            'modul_selesai' => 3,
            'persentase' => 100,
        ]);

        Progress::create([
            'user_id' => $siswa[0]->id,
            'materi_id' => $m2->id,
            'status' => 'belum',
            'modul_selesai' => 2,
            'persentase' => 67,
        ]);

        // Progress siswa2: baru modul pertama materi pertama.
        Progress::create([
            'user_id' => $siswa[1]->id,
            'materi_id' => $m1->id,
            'status' => 'belum',
            'modul_selesai' => 1,
            'persentase' => 33,
        ]);

        // Hasil kuis siswa1 pada kuis pertama (3 benar dari 4 soal).
        $kuis1 = $materiModels[0]['kuis'];
        $soalList = $kuis1->soal()->get();

        $hasil = HasilKuis::create([
            'user_id' => $siswa[0]->id,
            'kuis_id' => $kuis1->id,
            'nilai' => 75,
            'jumlah_benar' => 3,
            'jumlah_salah' => 1,
            'jumlah_soal' => 4,
        ]);

        foreach ($soalList as $i => $soal) {
            $jawaban = $i < 3 ? $soal->kunci_jawaban : ($soal->kunci_jawaban === 'a' ? 'b' : 'a');

            JawabanSiswa::create([
                'hasil_kuis_id' => $hasil->id,
                'soal_id' => $soal->id,
                'jawaban' => $jawaban,
                'benar' => $i < 3,
            ]);
        }
    }
}
