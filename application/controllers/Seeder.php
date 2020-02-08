<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Seeder extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        $hak_akses = [
            ["id" => 1, "deskripsi" => "Admin(BPM)"],
            ["id" => 2, "deskripsi" => "Ketua Institusi"],
            ["id" => 3, "deskripsi" => "PK1"],
            ["id" => 4, "deskripsi" => "PK2"],
            ["id" => 5, "deskripsi" => "PK3"],
            ["id" => 6, "deskripsi" => "LPPM"],
            ["id" => 7, "deskripsi" => "Kaprodi"],
            ["id" => 8, "deskripsi" => "Ketua Unit"],
            ["id" => 9, "deskripsi" => "Dosen"],
            ["id" => 10, "deskripsi" => "Anggota Unit"]
        ];

        $institusi = [
            ["id" => 1, "nama_institusi" => "AMIK"],
            ["id" => 2, "nama_institusi" => "STMIK"],
            ["id" => 3, "nama_institusi" => "STIE"],
            ["id" => 4, "nama_institusi" => "SEMUA"]
        ];

        $unit = [
            ['id' => 1, 'nama_unit' => 'Ketua AMIK/STMIK/STIE','institusi_id' => 4],
            ["id" => 2, "nama_unit" => "PK1 AMIK", "institusi_id" => 1],
            ['id' => 3, 'nama_unit' => 'PK1 STMIK', 'institusi_id'=> 2],
            ['id' => 4, 'nama_unit' => 'PK1 STIE', 'institusi_id'=> 3],
            ['id' => 5, 'nama_unit' => 'PK2 AMIK', 'institusi_id'=> 1],
            ['id' => 6, 'nama_unit' => 'PK2 STMIK', 'institusi_id'=> 2],
            ['id' => 7, 'nama_unit' => 'PK2 STIE', 'institusi_id'=> 3],
            ['id' => 8, 'nama_unit' => 'PK3 AMIK', 'institusi_id'=> 1],
            ['id' => 9, 'nama_unit' => 'PK3 STMIK', 'institusi_id'=> 2],
            ['id' => 10, 'nama_unit' => 'PK3 STIE', 'institusi_id'=> 3],
            ['id' => 11, 'nama_unit' => 'Perpustakaan', 'institusi_id'=> 4],
            ['id' => 12, 'nama_unit' => 'Badan Penjaminan Mutu AMIK', 'institusi_id'=> 1],
            ['id' => 13, 'nama_unit' => 'Badan Penjaminan Mutu STMIK', 'institusi_id'=> 2],
            ['id' => 14, 'nama_unit' => 'Badan Penjaminan Mutu STIE', 'institusi_id'=> 3],
            ['id' => 15, 'nama_unit' => 'Unit Pelaksanaan Teknis', 'institusi_id'=> 4],
            ['id' => 16, 'nama_unit' => 'Program Studi Teknik Informatika', 'institusi_id'=> 2],
            ['id' => 17, 'nama_unit' => 'Program Studi Manajemen Informatika', 'institusi_id'=> 1],
            ['id' => 18, 'nama_unit' => 'Program Studi Komputerisasi Akutansi', 'institusi_id'=> 3],
            ['id' => 19, 'nama_unit' => 'Badan Komunikasi dan Pemasaran', 'institusi_id'=> 4],
            ['id' => 20, 'nama_unit' => 'Program Studi Teknik Komputer', 'institusi_id'=> 1],
            ['id' => 21, 'nama_unit' => 'Bagian Administrasi Keuangan', 'institusi_id'=> 4],
            ['id' => 22, 'nama_unit' => 'Bagian Administrasi Akademik', 'institusi_id'=> 4],
            ['id' => 23, 'nama_unit' => 'Badan Administrasi Umum', 'institusi_id'=> 4],
            ['id' => 24, 'nama_unit' => 'Program Studi Sistem Informasi', 'institusi_id'=> 2],
            ['id' => 25, 'nama_unit' => 'Lembaga Penelitian dan Pengabdian pada Masyarakat AMIK', 'institusi_id'=> 1],
            ['id' => 26, 'nama_unit' => 'Lembaga Penelitian dan Pengabdian pada Masyarakat STMIK', 'institusi_id'=> 2],
            ['id' => 27, 'nama_unit' => 'Lembaga Penelitian dan Pengabdian pada Masyarakat STIE', 'institusi_id'=> 3],
            ['id' => 28, 'nama_unit' => 'Unit Pelaksanaan Teknis Sistem Informasi', 'institusi_id'=> 4],
            ['id' => 29, 'nama_unit' => 'Multimedia', 'institusi_id'=> 4],
            ['id' => 30, 'nama_unit' => 'Laboratorium Robotika dan Jaringan', 'institusi_id'=> 4],
            ['id' => 31, 'nama_unit' => 'Laboratorium fisika dan Elektronika Dasar', 'institusi_id'=> 4],
            ['id' => 32, 'nama_unit' => 'Program Studi Akutansi', 'institusi_id'=> 3],
            ['id' => 33, 'nama_unit' => 'Program Studi Manajemen', 'institusi_id'=> 3]
        ];

        $user = [
            ['id' => 111111, 'nama_user' => 'Johannes Petrus', 'password_user'=> 111111, 'unit_id' => 1],
            ['id' => 222222, 'nama_user' => 'Desi Iba Ricoida, ST., M.T.I', 'password_user'=> 222222, 'unit_id' => 3],
            ['id' => 333333, 'nama_user' => 'Dr. Yulizar Kasih, SE, M.si', 'password_user'=> 333333, 'unit_id' => 4],
            ['id' => 444444, 'nama_user' => 'Yulistia, S.Kom., M.T.I', 'password_user'=> 444444,  'unit_id' => 6],
            ['id' => 555555, 'nama_user' => 'Megawati, S.E., M.Si', 'password_user'=> 555555,  'unit_id' => 7],
            ['id' => 666666, 'nama_user' => 'Antonius Wahyu Sudrajat, S.Kom., M.T.I', 'password_user'=> 666666, 'unit_id' => 9],
            ['id' => 777777, 'nama_user' => 'Idham Cholid, S.E, M.E., CFP, QWP', 'password_user'=> 777777, 'unit_id' => 10],
            ['id' => 888888, 'nama_user' => 'Desi Pibriana, S.SI, M.T.I', 'password_user'=> 888888, 'unit_id' => 12],
            ['id' => 999999, 'nama_user' => 'Muhammad Rizky Pribadi, M.Kom', 'password_user'=> 999999, 'unit_id' => 25],
            ['id' => 101010, 'nama_user' => 'Yoannita, M.Kom', 'password_user'=> 101010, 'unit_id' => 16],
            ['id' => 110011, 'nama_user' => 'Mardiani, S.Si, M.T.I', 'password_user'=> 110011, 'unit_id' => 24],
            ['id' => 120012, 'nama_user' => 'Lisa Amelia, S.E., M.T.I.', 'password_user'=> 120012, 'unit_id' => 18],
            ['id' => 130013, 'nama_user' => 'Inayatullah, S.Kom, M.Si', 'password_user'=> 130013, 'unit_id' => 17],
            ['id' => 140014, 'nama_user' => 'Abdul Rahman, S.Si., M.T.I., MTCNA, MTCRE', 'password_user'=> 140014, 'unit_id' => 20],
            ['id' => 150015, 'nama_user' => 'Dr. Anton Arisman, S.E., M.Si', 'password_user'=> 150015, 'unit_id' => 32],
            ['id' => 160016, 'nama_user' => 'Dr. Ratna Juwita, S.E., M.Si', 'password_user'=> 160016, 'unit_id' => 33],
            ['id' => 170017, 'nama_user' => 'Lisa Amelia, SE, M.T.I', 'password_user'=> 170017, 'unit_id' => 22],
            ['id' => 180018, 'nama_user' => 'Kathryn Sugara, S.E., M.Si., CFP, QWP', 'password_user'=> 180018, 'unit_id' => 21],
            ['id' => 190019, 'nama_user' => 'Mulyati, SE, M.T.I', 'password_user'=> 190019, 'unit_id' => 23],
            ['id' => 200020, 'nama_user' => 'Erwin Azhari Wijaya, M.Kom', 'password_user'=> 200020, 'unit_id' => 19],
            ['id' => 210021, 'nama_user' => 'Usniawati Keristin, SE, M.Si', 'password_user'=> 210021, 'unit_id' => 11],
            ['id' => 220022, 'nama_user' => 'Haiyunizar, MTCNA, MTCRE', 'password_user'=> 220022, 'unit_id' => 15],
            ['id' => 230023, 'nama_user' => 'Nur Rachmat, M.Kom', 'password_user'=> 230023, 'unit_id' => 28],
            ['id' => 240024, 'nama_user' => 'Anugerah Widi, M.Kom', 'password_user'=> 240024, 'unit_id' => 29],
            ['id' => 250025, 'nama_user' => 'Eka Puji Widiyanto, S.T., M.Kom., MTCNA, MTCRE', 'password_user'=> 250025, 'unit_id' => 31],
            ['id' => 260026, 'nama_user' => 'Dedy Hermanto, S.Kom, M.T.I., MTCNA, MTCRE', 'password_user'=> 260026, 'unit_id' => 31],
            ['id' => 270027, 'nama_user' => 'Dosen SI', 'password_user'=> 270027, 'unit_id' => 24],
            ['id' => 280028, 'nama_user' => 'Dosen TI', 'password_user'=> 280028, 'unit_id' => 16]
        ];

        $ketua_unit = [
            ['ketua_unit' => 111111, 'unit_id' => 1],
            ['ketua_unit' => 222222, 'unit_id' => 3],
            ['ketua_unit' => 333333, 'unit_id' => 4],
            ['ketua_unit' => 444444, 'unit_id' => 6],
            ['ketua_unit' => 555555, 'unit_id' => 7],
            ['ketua_unit' => 666666, 'unit_id' => 9],
            ['ketua_unit' => 777777, 'unit_id' => 10],
            ['ketua_unit' => 888888, 'unit_id' => 12],
            ['ketua_unit' => 999999, 'unit_id' => 25],
            ['ketua_unit' => 101010, 'unit_id' => 16],
            ['ketua_unit' => 110011, 'unit_id' => 24],
            ['ketua_unit' => 120012, 'unit_id' => 18],
            ['ketua_unit' => 130013, 'unit_id' => 17],
            ['ketua_unit' => 140014, 'unit_id' => 20],
            ['ketua_unit' => 150015, 'unit_id' => 32],
            ['ketua_unit' => 160016, 'unit_id' => 33],
            ['ketua_unit' => 170017, 'unit_id' => 22],
            ['ketua_unit' => 180018, 'unit_id' => 21],
            ['ketua_unit' => 190019, 'unit_id' => 23],
            ['ketua_unit' => 200020, 'unit_id' => 19],
            ['ketua_unit' => 210021, 'unit_id' => 11],
            ['ketua_unit' => 220022, 'unit_id' => 15],
            ['ketua_unit' => 230023, 'unit_id' => 28],
            ['ketua_unit' => 240024, 'unit_id' => 29],
            ['ketua_unit' => 250025, 'unit_id' => 31],
            ['ketua_unit' => 260026, 'unit_id' => 31]
        ];

        $periode = [
            ['id' => 1, 'tahun' => 2018, 'gazal' => 0],
            ['id' => 2, 'tahun' => 2018, 'gazal' => 1],
            ['id' => 3, 'tahun' => 2019, 'gazal' => 0],
            ['id' => 4, 'tahun' => 2019, 'gazal' => 1]
        ];

        $indikator = [
            [
                "id" => 1,
                "nama_indikator" => "Operasional dan evaluasi kegiatan belajar mengajar berjalan sesuai prosedur"
            ],
            [
                "id" => 2,
                "nama_indikator" => "Kinerja Dosen : keterlambatan (< 2 hari) pengumpulan Soal Ujian dan Nilai"
            ],
            [
                "id" => 3,
                "nama_indikator" => "Kinerja Dosen : jumlah pertemuan tatap muka"
            ],
            [
                "id" => 4,
                "nama_indikator" => "Penanganan PTPP Akademik tepat waktu (< 1 minggu)"
            ],
            [
                "id" => 5,
                "nama_indikator" => "Kecukupan jumlah dosen"
            ],
            [
                "id" => 6,
                "nama_indikator" => "Dosen kualifikasi s2 ( per tahun)"
            ],
            [
                "id" => 7,
                "nama_indikator" => "Kualifikasi Jenjang Akademik Dosen Meningkat ( per tahun)"
            ],
            [
                "id" => 8,
                "nama_indikator" => "Dosen berserifikasi ( per tahun)"
            ],
            [
                "id" => 9,
                "nama_indikator" => "indikator 1 : PK 2 STMIK "
            ],
            [
                "id" => 10,
                "nama_indikator" => "indikator 2 : PK 2 STMIK "
            ],
            [
                "id" => 11,
                "nama_indikator" => "indikator 3 : PK 2 STMIK "
            ],
            [
                "id" => 12,
                "nama_indikator" => "indikator 4 : PK 2 STMIK "
            ],
            [
                "id" => 13,
                "nama_indikator" => "indikator 5 : PK 2 STMIK "
            ],
            [
                "id" => 14,
                "nama_indikator" => "indikator 6 : PK 2 STMIK "
            ],
            [
                "id" => 15,
                "nama_indikator" => "indikator 7 : PK 2 STMIK "
            ],
            [
                "id" => 16,
                "nama_indikator" => "indikator 8 : PK 2 STMIK "
            ],
            [
                "id" => 17,
                "nama_indikator" => "indikator 1 : PK 3 STMIK "
            ],
            [
                "id" => 18,
                "nama_indikator" => "indikator 2 : PK 3 STMIK "
            ],
            [
                "id" => 19,
                "nama_indikator" => "indikator 3 : PK 3 STMIK "
            ],
            [
                "id" => 20,
                "nama_indikator" => "indikator 4 : PK 3 STMIK "
            ],
            [
                "id" => 21,
                "nama_indikator" => "indikator 5 : PK 3 STMIK "
            ],
            [
                "id" => 22,
                "nama_indikator" => "indikator 6 : PK 3 STMIK "
            ],
            [
                "id" => 23,
                "nama_indikator" => "indikator 7 : PK 3 STMIK "
            ],
            [
                "id" => 24,
                "nama_indikator" => "indikator 8 : PK 3 STMIK "
            ],
            [
                "id" => 25,
                "nama_indikator" => "indikator 1 : Sistem Informasi "
            ],
            [
                "id" => 26,
                "nama_indikator" => "indikator 2 : Sistem Informasi "
            ],
            [
                "id" => 27,
                "nama_indikator" => "indikator 3 : Sistem Informasi "
            ],
            [
                "id" => 28,
                "nama_indikator" => "indikator 4 : Sistem Informasi "
            ],
            [
                "id" => 29,
                "nama_indikator" => "indikator 5 : Sistem Informasi "
            ],
            [
                "id" => 30,
                "nama_indikator" => "indikator 6 : Sistem Informasi "
            ],
            [
                "id" => 31,
                "nama_indikator" => "indikator 7 : Sistem Informasi "
            ],
            [
                "id" => 32,
                "nama_indikator" => "indikator 8 : Sistem Informasi "
            ],
            [
                "id" => 33,
                "nama_indikator" => "indikator 1 : Teknik Informatika "
            ],
            [
                "id" => 34,
                "nama_indikator" => "indikator 2 : Teknik Informatika "
            ],
            [
                "id" => 35,
                "nama_indikator" => "indikator 3 : Teknik Informatika "
            ],
            [
                "id" => 36,
                "nama_indikator" => "indikator 4 : Teknik Informatika "
            ],
            [
                "id" => 37,
                "nama_indikator" => "indikator 5 : Teknik Informatika "
            ],
            [
                "id" => 38,
                "nama_indikator" => "indikator 6 : Teknik Informatika "
            ],
            [
                "id" => 39,
                "nama_indikator" => "indikator 7 : Teknik Informatika "
            ],
            [
                "id" => 40,
                "nama_indikator" => "indikator 8 : Teknik Informatika "
            ],
        ];

        $kpi = [
            [
                "id" => 1,
                "nama_kpi" => "Hasil audit internal bagian akademik tidak ada minor",
                "indikator_id" => 1
            ],
            [
                "id" => 2,
                "nama_kpi" => "Persentase dosen terlambat mengumpulkan soal dan ujian",
                "indikator_id" => 2
            ],
            [
                "id" => 3,
                "nama_kpi" => "Persentase jumlah pertemuan >= 12 (2 sks) atau >= 25 (4 sks)",
                "indikator_id" => 3
            ],
            [
                "id" => 4,
                "nama_kpi" => "Persentase penanggan PTPP akademik tepat",
                "indikator_id" => 4
            ],
            [
                "id" => 5,
                "nama_kpi" => "Rasio Kecukupan Mahasiswa : Dosen per prodi pada tahun 2020",
                "indikator_id" => 5
            ],
            [
                "id" => 6,
                "nama_kpi" => "Persentase Dosen Kualifikasi S2 pada tahun 2016",
                "indikator_id" => 6
            ],
            [
                "id" => 7,
                "nama_kpi" => "Persentase dosen minimal jenjang AA pada tahun 2016",
                "indikator_id" => 7
            ],
            [
                "id" => 8,
                "nama_kpi" => "Persentase dosen bersertifikasi tahun 2016",
                "indikator_id" => 8
            ],
            [
                "id" => 9,
                "nama_kpi" => "KPI no 1 unit PK 2 STMIK",
                "indikator_id" => 9
            ],
            [
                "id" => 10,
                "nama_kpi" => "KPI no 2 unit PK 2 STMIK",
                "indikator_id" => 10
            ],
            [
                "id" => 11,
                "nama_kpi" => "KPI no 3 unit PK 2 STMIK",
                "indikator_id" => 11
            ],
            [
                "id" => 12,
                "nama_kpi" => "KPI no 4 unit PK 2 STMIK",
                "indikator_id" => 12
            ],
            [
                "id" => 13,
                "nama_kpi" => "KPI no 5 unit PK 2 STMIK",
                "indikator_id" => 13
            ],
            [
                "id" => 14,
                "nama_kpi" => "KPI no 6 unit PK 2 STMIK",
                "indikator_id" => 14
            ],
            [
                "id" => 15,
                "nama_kpi" => "KPI no 7 unit PK 2 STMIK",
                "indikator_id" => 15
            ],
            [
                "id" => 16,
                "nama_kpi" => "KPI no 8 unit PK 2 STMIK",
                "indikator_id" => 16
            ],
            [
                "id" => 17,
                "nama_kpi" => "KPI no 1 unit PK 3 STMIK",
                "indikator_id" => 17
            ],
            [
                "id" => 18,
                "nama_kpi" => "KPI no 2 unit PK 3 STMIK",
                "indikator_id" => 18
            ],
            [
                "id" => 19,
                "nama_kpi" => "KPI no 3 unit PK 3 STMIK",
                "indikator_id" => 19
            ],
            [
                "id" => 20,
                "nama_kpi" => "KPI no 4 unit PK 3 STMIK",
                "indikator_id" => 20
            ],
            [
                "id" => 21,
                "nama_kpi" => "KPI no 5 unit PK 3 STMIK",
                "indikator_id" => 21
            ],
            [
                "id" => 22,
                "nama_kpi" => "KPI no 6 unit PK 3 STMIK",
                "indikator_id" => 22
            ],
            [
                "id" => 23,
                "nama_kpi" => "KPI no 7 unit PK 3 STMIK",
                "indikator_id" => 23
            ],
            [
                "id" => 24,
                "nama_kpi" => "KPI no 8 unit PK 3 STMIK",
                "indikator_id" => 24
            ],
            [
                "id" => 25,
                "nama_kpi" => "KPI no 1 unit Sistem Informasi",
                "indikator_id" => 25
            ],
            [
                "id" => 26,
                "nama_kpi" => "KPI no 2 unit Sistem Informasi",
                "indikator_id" => 26
            ],
            [
                "id" => 27,
                "nama_kpi" => "KPI no 3 unit Sistem Informasi",
                "indikator_id" => 27
            ],
            [
                "id" => 28,
                "nama_kpi" => "KPI no 4 unit Sistem Informasi",
                "indikator_id" => 28
            ],
            [
                "id" => 29,
                "nama_kpi" => "KPI no 5 unit Sistem Informasi",
                "indikator_id" => 29
            ],
            [
                "id" => 30,
                "nama_kpi" => "KPI no 6 unit Sistem Informasi",
                "indikator_id" => 30
            ],
            [
                "id" => 31,
                "nama_kpi" => "KPI no 7 unit Sistem Informasi",
                "indikator_id" => 31
            ],
            [
                "id" => 32,
                "nama_kpi" => "KPI no 8 unit Sistem Informasi",
                "indikator_id" => 32
            ],
            [
                "id" => 33,
                "nama_kpi" => "KPI no 1 unit Teknik Informatika",
                "indikator_id" => 33
            ],
            [
                "id" => 34,
                "nama_kpi" => "KPI no 2 unit Teknik Informatika",
                "indikator_id" => 34
            ],
            [
                "id" => 35,
                "nama_kpi" => "KPI no 3 unit Teknik Informatika",
                "indikator_id" => 35
            ],
            [
                "id" => 36,
                "nama_kpi" => "KPI no 4 unit Teknik Informatika",
                "indikator_id" => 36
            ],
            [
                "id" => 37,
                "nama_kpi" => "KPI no 5 unit Teknik Informatika",
                "indikator_id" => 37
            ],
            [
                "id" => 38,
                "nama_kpi" => "KPI no 6 unit Teknik Informatika",
                "indikator_id" => 38
            ],
            [
                "id" => 39,
                "nama_kpi" => "KPI no 7 unit Teknik Informatika",
                "indikator_id" => 39
            ],
            [
                "id" => 40,
                "nama_kpi" => "KPI no 4 unit Teknik Informatika",
                "indikator_id" => 40
            ]   
        ];

        $kamus_indikator = [
            [
                "unit_id" => 3,
                "indikator_id" => 1
            ],
            [
                "unit_id" => 3,
                "indikator_id" => 2
            ],
            [
                "unit_id" => 3,
                "indikator_id" => 3
            ],
            [
                "unit_id" => 3,
                "indikator_id" => 4
            ],
            [
                "unit_id" => 3,
                "indikator_id" => 5
            ],
            [
                "unit_id" => 3,
                "indikator_id" => 6
            ],
            [
                "unit_id" => 3,
                "indikator_id" => 7
            ],
            [
                "unit_id" => 3,
                "indikator_id" => 8
            ],
            [
                "unit_id" => 5,
                "indikator_id" => 9
            ],
            [
                "unit_id" => 5,
                "indikator_id" => 10
            ],
            [
                "unit_id" => 5,
                "indikator_id" => 11
            ],
            [
                "unit_id" => 5,
                "indikator_id" => 12
            ],
            [
                "unit_id" => 5,
                "indikator_id" => 13
            ],
            [
                "unit_id" => 5,
                "indikator_id" => 14
            ],
            [
                "unit_id" => 5,
                "indikator_id" => 15
            ],
            [
                "unit_id" => 5,
                "indikator_id" => 16
            ],
            [
                "unit_id" => 9,
                "indikator_id" => 17
            ],
            [
                "unit_id" => 9,
                "indikator_id" => 18
            ],
            [
                "unit_id" => 9,
                "indikator_id" => 19
            ],
            [
                "unit_id" => 9,
                "indikator_id" => 20
            ],
            [
                "unit_id" => 9,
                "indikator_id" => 21
            ],
            [
                "unit_id" => 9,
                "indikator_id" => 22
            ],
            [
                "unit_id" => 9,
                "indikator_id" => 23
            ],
            [
                "unit_id" => 9,
                "indikator_id" => 24
            ],
            [
                "unit_id" => 24,
                "indikator_id" => 25
            ],
            [
                "unit_id" => 24,
                "indikator_id" => 26
            ],
            [
                "unit_id" => 24,
                "indikator_id" => 27
            ],
            [
                "unit_id" => 24,
                "indikator_id" => 28
            ],
            [
                "unit_id" => 24,
                "indikator_id" => 29
            ],
            [
                "unit_id" => 24,
                "indikator_id" => 30
            ],
            [
                "unit_id" => 24,
                "indikator_id" => 31
            ],
            [
                "unit_id" => 24,
                "indikator_id" => 32
            ],
            [
                "unit_id" => 16,
                "indikator_id" => 33
            ],
            [
                "unit_id" => 16,
                "indikator_id" => 34
            ],
            [
                "unit_id" => 16,
                "indikator_id" => 35
            ],
            [
                "unit_id" => 16,
                "indikator_id" => 36
            ],
            [
                "unit_id" => 16,
                "indikator_id" => 37
            ],
            [
                "unit_id" => 16,
                "indikator_id" => 38
            ],
            [
                "unit_id" => 16,
                "indikator_id" => 39
            ],
            [
                "unit_id" => 16,
                "indikator_id" => 40
            ]
        ];

        $formulir_rencana_kerja = [
            [
                "id" => 1,
                "periode_id" => 1,
                "unit_id" => 3,
                "user_id" => 222222,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "1"
            ],
            [
                "id" => 2,
                "periode_id" => 1,
                "unit_id" => 6,
                "user_id" => 444444,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "1"
            ],
            [
                "id" => 3,
                "periode_id" => 1,
                "unit_id" => 9,
                "user_id" => 666666,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "1"
            ],
            [
                "id" => 4,
                "periode_id" => 1,
                "unit_id" => 24,
                "user_id" => 110011,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "1"
            ],
            [
                "id" => 5,
                "periode_id" => 1,
                "unit_id" => 16,
                "user_id" => 101010,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "1"
            ],
            [
                "id" => 6,
                "periode_id" => 1,
                "unit_id" => 3,
                "user_id" => 222222,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "0"
            ],
            [
                "id" => 7,
                "periode_id" => 1,
                "unit_id" => 6,
                "user_id" => 444444,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "0"
            ],
            [
                "id" => 8,
                "periode_id" => 1,
                "unit_id" => 9,
                "user_id" => 666666,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "0"
            ],
            [
                "id" => 9,
                "periode_id" => 1,
                "unit_id" => 24,
                "user_id" => 110011,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "0"
            ],
            [
                "id" => 10,
                "periode_id" => 1,
                "unit_id" => 16,
                "user_id" => 101010,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "0"
            ],
            [
                "id" => 11,
                "periode_id" => 1,
                "unit_id" => 16,
                "user_id" => 280028,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "0"
            ],
            [
                "id" => 12,
                "periode_id" => 1,
                "unit_id" => 24,
                "user_id" => 270027,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "0"
            ]
        ];


        $detil_formulir_rencana_kerja = [
            [
                "id" => 1,
                "kpi_id" => 1, 
                "formulir_rencana_kerja_id" => 1,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 2,
                "kpi_id" => 2, 
                "formulir_rencana_kerja_id" => 1,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 3,
                "kpi_id" => 3, 
                "formulir_rencana_kerja_id" => 1,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 4,
                "kpi_id" => 4, 
                "formulir_rencana_kerja_id" => 1,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 5,
                "kpi_id" => 5, 
                "formulir_rencana_kerja_id" => 1,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 6,
                "kpi_id" => 6, 
                "formulir_rencana_kerja_id" => 1,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 7,
                "kpi_id" => 7, 
                "formulir_rencana_kerja_id" => 1,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 8,
                "kpi_id" => 8, 
                "formulir_rencana_kerja_id" => 1,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 9,
                "kpi_id" => 9, 
                "formulir_rencana_kerja_id" => 2,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 10,
                "kpi_id" => 10, 
                "formulir_rencana_kerja_id" => 2,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 11,
                "kpi_id" => 11, 
                "formulir_rencana_kerja_id" => 2,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 12,
                "kpi_id" => 12, 
                "formulir_rencana_kerja_id" => 2,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 13,
                "kpi_id" => 17, 
                "formulir_rencana_kerja_id" => 3,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 14,
                "kpi_id" => 18, 
                "formulir_rencana_kerja_id" => 3,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 15,
                "kpi_id" => 19, 
                "formulir_rencana_kerja_id" => 3,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 16,
                "kpi_id" => 20, 
                "formulir_rencana_kerja_id" => 3,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 17,
                "kpi_id" => 25, 
                "formulir_rencana_kerja_id" => 4,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 18,
                "kpi_id" => 26, 
                "formulir_rencana_kerja_id" => 4,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 19,
                "kpi_id" => 27, 
                "formulir_rencana_kerja_id" => 4,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 20,
                "kpi_id" => 28, 
                "formulir_rencana_kerja_id" => 4,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 21,
                "kpi_id" => 33, 
                "formulir_rencana_kerja_id" => 5,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 22,
                "kpi_id" => 34, 
                "formulir_rencana_kerja_id" => 5,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 23,
                "kpi_id" => 35, 
                "formulir_rencana_kerja_id" => 5,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 24,
                "kpi_id" => 36, 
                "formulir_rencana_kerja_id" => 5,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 25,
                "kpi_id" => 29, 
                "formulir_rencana_kerja_id" => 6,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 26,
                "kpi_id" => 30, 
                "formulir_rencana_kerja_id" => 6,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 27,
                "kpi_id" => 31, 
                "formulir_rencana_kerja_id" => 6,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 28,
                "kpi_id" => 32, 
                "formulir_rencana_kerja_id" => 6,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 29,
                "kpi_id" => 29, 
                "formulir_rencana_kerja_id" => 7,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 30,
                "kpi_id" => 30, 
                "formulir_rencana_kerja_id" => 7,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 31,
                "kpi_id" => 31, 
                "formulir_rencana_kerja_id" => 7,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 32,
                "kpi_id" => 32, 
                "formulir_rencana_kerja_id" => 7,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 33,
                "kpi_id" => 29, 
                "formulir_rencana_kerja_id" => 8,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 34,
                "kpi_id" => 30, 
                "formulir_rencana_kerja_id" => 8,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 35,
                "kpi_id" => 31, 
                "formulir_rencana_kerja_id" => 8,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 36,
                "kpi_id" => 32, 
                "formulir_rencana_kerja_id" => 8,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 37,
                "kpi_id" => 29, 
                "formulir_rencana_kerja_id" => 9,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 38,
                "kpi_id" => 30, 
                "formulir_rencana_kerja_id" => 9,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 39,
                "kpi_id" => 31, 
                "formulir_rencana_kerja_id" => 9,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 40,
                "kpi_id" => 32, 
                "formulir_rencana_kerja_id" => 9,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 41,
                "kpi_id" => 37, 
                "formulir_rencana_kerja_id" => 10,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 42,
                "kpi_id" => 38, 
                "formulir_rencana_kerja_id" => 10,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 43,
                "kpi_id" => 39, 
                "formulir_rencana_kerja_id" => 10,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 44,
                "kpi_id" => 40, 
                "formulir_rencana_kerja_id" => 10,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 45,
                "kpi_id" => 37, 
                "formulir_rencana_kerja_id" => 11,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 46,
                "kpi_id" => 38, 
                "formulir_rencana_kerja_id" => 11,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 47,
                "kpi_id" => 39, 
                "formulir_rencana_kerja_id" => 11,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 48,
                "kpi_id" => 40, 
                "formulir_rencana_kerja_id" => 11,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 49,
                "kpi_id" => 29, 
                "formulir_rencana_kerja_id" => 12,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renop",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 50,
                "kpi_id" => 30, 
                "formulir_rencana_kerja_id" => 12,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "sasaran mutu",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 51,
                "kpi_id" => 31, 
                "formulir_rencana_kerja_id" => 12,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ],
            [
                "id" => 52,
                "kpi_id" => 32, 
                "formulir_rencana_kerja_id" => 12,
                "target_institusi" => 1,
                "target_individu" => 1,
                "sumber" => "renstra",
                "bukti" => "contoh.pdf", 
                "status" => 1,
                "nilai_aktual" => 1
            ]
        ];

        $this->db->trans_begin();
        $this->db->insert_batch('hak_akses', $hak_akses);
        $this->db->insert_batch('institusi', $institusi);
        $this->db->insert_batch('unit', $unit);
        $this->db->insert_batch('user', $user);
        $this->db->insert_batch('ketua_unit', $ketua_unit);
        $this->db->insert_batch('periode', $periode);
        $this->db->insert_batch('indikator', $indikator);
        $this->db->insert_batch('kpi', $kpi);
        $this->db->insert_batch('kamus_indikator', $kamus_indikator);
        $this->db->insert_batch('formulir_rencana_kerja', $formulir_rencana_kerja);
        $this->db->insert_batch('detil_formulir_rencana_kerja', $detil_formulir_rencana_kerja);
        $this->db->trans_complete();

        echo "success seed data!";

        
    }
}


?>