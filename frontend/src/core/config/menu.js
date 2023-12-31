const menu = [
  {
    target: '/dashboard',
    label: 'Dashboard',
    icon: 'menu-icon flaticon2-architecture-and-city',
    component: import('@/pages/Dashboard.vue')
  },
  // Keanggotaan
  {
    target: '/keanggotaan',
    label: 'Keanggotaan',
    icon: 'menu-icon far fa-user',
    component: import('@/pages/Keanggotaan'),
    children: [
      {
        target: 'registrasi-anggota',
        label: 'Registrasi Anggota',
        component: import('@/pages/Keanggotaan/RegistrasiAnggotaV2.vue'),
      },
      {
        target: 'regis-anggota-keluar',
        label: 'Regis Anggota Keluar',
        component: import('@/pages/Dummy.vue'),
      },
      // {
      //   target: 'registrasi-anggota-rembug',
      //   label: 'Registrasi Anggota Majelis',
      //   component: import('@/pages/Keanggotaan/RegistrasiAnggotaRembug.vue'),
      // },
      {
        target: 'approval-registrasi-keluar',
        label: 'Approval Registrasi Anggota Keluar',
        component: import('@/pages/Keanggotaan/ApprovalRegistrasiAnggotaKeluar.vue'),
      },
      {
        target: 'laporan',
        label: 'Laporan Keanggotaan',
        component: import('@/pages/Keanggotaan'),
        children: [
          {
            target: 'registrasi-anggota',
            label: 'Lap Regis Anggota',
            component: import('@/pages/Keanggotaan/Laporan/RegistrasiAnggota.vue'),
          },
          {
            target: 'saldo-anggota',
            label: 'Lap Saldo Anggota',
            component: import('@/pages/Keanggotaan/Laporan/SaldoAnggota.vue'),
          },
          {
            target: 'profil-anggota',
            label: 'Lap Profil Anggota',
            component: import('@/pages/Keanggotaan/Laporan/ProfilAnggota.vue'),
          },
          {
            target: 'rekap-regis-anggota',
            label: 'Lap Regis Regis Anggota',
            component: import('@/pages/Dummy.vue'),
          },
          {
            target: 'rekap-saldo-anggota',
            label: 'Lap Rekap Saldo Anggota',
            component: import('@/pages/Keanggotaan/Laporan/RekapSaldoAnggota.vue'),
          },
          {
            target: 'anggota-keluar',
            label: 'Lap Anggota Keluar',
            component: import('@/pages/Keanggotaan/Laporan/AnggotaKeluar.vue'),
          },
          {
            target: 'rekap-anggota-keluar',
            label: 'Lap Rekap Anggota Keluar',
            component: import('@/pages/Dummy.vue'),
          },
        ],
      },
    ]
  },
  // Simpanan
  {
    target: '/simpanan',
    label: 'Simpanan',
    icon: 'menu-icon fas fa-money-check-alt',
    component: import('@/pages/Tabungan'),
    children: [
      {
        target: 'regis-simpanan-anggota',
        label: 'Regis Simpanan Anggota',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'setoran-simpok-simwa',
        label: 'Setoran Simpok Simwa',
        component: import('@/pages/Simpanan/SetoranSimpokSimwa.vue'),
      },
      {
        target: 'setoran-simpanan-anggota',
        label: 'Setoran Simpanan Anggota',
        component: import('@/pages/Simpanan/SetoranSimpananAnggota.vue'),
      },
      {
        target: 'penarikan-simpanan-anggota',
        label: 'Penarikan Simpanan Anggota',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'pinbuk-saldo-tabungan-sukarela',
        label: 'Verifikasi Transaksi Simpanan',
        component: import('@/pages/Tabungan/TabunganPinBukSaldoTabunganSukarela.vue'),
      },
      {
        target: 'laporan',
        label: 'Laporan Simpanan',
        component: import('@/pages/Tabungan'),
        children: [
          {
            target: 'laporan-regis-simpana',
            label: 'Lap Regis Simpanan',
            component: import('@/pages/Dummy.vue'),
          },
          {
            target: 'laporan-transaksi-simpanan-anggota',
            label: 'Lap Transaksi Simp Anggota ',
            component: import('@/pages/Dummy.vue'),
          },
          {
            target: 'laporan-statement-simpanan-anggota',
            label: 'Lap Statement Simp Anggota',
            component: import('@/pages/Dummy.vue'),
          },
          {
            target: 'lap-rekap-saldo-simpanan-anggota',
            label: 'Lap Rekap Saldo Simp Anggota',
            component: import('@/pages/Dummy.vue'),
          },
          {
            target: 'lap-rekap-transaksi-simpanan-anggota ',
            label: 'Lap Rekap Transaksi Simp Anggota',
            component: import('@/pages/Dummy.vue'),
          },
        ],
      },
    ]
  },
  // Tabungan
  // {
  //   target: '/tabungan',
  //   label: 'Tabungan',
  //   icon: 'menu-icon fas fa-money-check-alt',
  //   component: import('@/pages/Tabungan'),
  //   children: [
  //     // {
  //     //   target: 'pembukaan-rekening-tabungan',
  //     //   label: 'Pembukaan Rekening Tabungan',
  //     //   component: import('@/pages/Tabungan/PembukaanRekeningTabungan.vue'),
  //     // },
  //     // {
  //     //   target: 'penutupan-rekening-tabungan',
  //     //   label: 'Penutupan Rekening Tabungan',
  //     //   component: import('@/pages/Dummy.vue'),
  //     // },
  //     {
  //       target: 'pinbuk-saldo-tabungan-sukarela',
  //       label: 'Pinbuk Saldo Tabungan ke Sukarela',
  //       component: import('@/pages/Tabungan/TabunganPinBukSaldoTabunganSukarela.vue'),
  //     },
  //     {
  //       target: 'verifikasi-pencairan-tabungan',
  //       label: 'Verifikasi Pencairan Tabungan',
  //       component: import('@/pages/Tabungan/VerifikasiPencairanTabungan.vue'),
  //     },
  //     {
  //       target: 'laporan',
  //       label: 'Laporan Tabungan',
  //       component: import('@/pages/Tabungan'),
  //       children: [
  //         {
  //           target: 'pembukaan-rekening-tabungan',
  //           label: 'Pembukaan Rekening Tabungan',
  //           component: import('@/pages/Tabungan/Laporan/PembukaanTabungan.vue'),
  //         },
  //         {
  //           target: 'saldo-tabungan',
  //           label: 'Saldo Tabungan',
  //           component: import('@/pages/Tabungan/Laporan/SaldoTabungan.vue'),
  //         },
  //         {
  //           target: 'statement-tabungan',
  //           label: 'Statement Tabungan',
  //           component: import('@/pages/Tabungan/StatementTabungan.vue'),
  //         },
  //         {
  //           target: 'penutupan-rekening-tabungan',
  //           label: 'Penutupan Rekening Tabungan',
  //           component: import('@/pages/Dummy.vue'),
  //         },
  //       ],
  //     },
  //   ]
  // },
  // Pembiayaan
  {
    target: '/pembiayaan',
    label: 'Pembiayaan',
    icon: 'menu-icon fas fa-wallet',
    component: import('@/pages/Pembiayaan'),
    children: [
      // {
      //   target: 'pengajuan-pembiayaan-old',
      //   label: 'Pengajuan Pembiayaan Old',
      //   component: import('@/pages/Pembiayaan/PengajuanPembiayaan/index.vue'),
      // },
      {
        target: 'pengajuan-pembiayaan',
        label: 'Pengajuan Pembiayaan',
        component: import('@/pages/Pembiayaan/PengajuanPembiayaan.vue'),
      },
      {
        target: 'pembiayaan-aproval-pengajuan-plussp3',
        label: 'Aproval Pengajuan (+SP3)',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'registrasi-akad',
        label: 'Registrasi Akad',
        component: import('@/pages/Pembiayaan/RegistrasiAkad.vue'),
      },
      {
        target: 'pembiayaan-cetak-akad',
        label: 'Cetak Akad',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'pembiayaan-aproval-pengajuan-plussp3',
        label: 'Pencairan',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'pembiayaan-setoran-angsuran',
        label: 'Setoran Angsuran ',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'verifikasi-akad',
        label: 'Verifikasi Setoran Angsuran',
        component: import('@/pages/Pembiayaan/VerifikasiAkad.vue'),
      },
      {
        target: 'pembiayaan-debet-dr-simpanan',
        label: 'Debet Angsuran dr Simpanan',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'pembiayaan-verifikasi-debet-debet-angsuran',
        label: 'Verifikasi Debet Angsuran',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'pembiayaan-regis-pelunasan',
        label: 'Regis Pelunasan',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'pembiayaan-approval-pelunasan',
        label: 'Approval Pelunasan',
        component: import('@/pages/Dummy.vue'),
      },
      // {
      //   target: 'update-status-komite',
      //   label: 'Update Status Komite',
      //   component: import('@/pages/Pembiayaan/UpdateStatusPengajuan.vue'),
      // },
      // {
      //   target: 'registrasi-akad-old',
      //   label: 'Registrasi Akad Old',
      //   component: import('@/pages/Pembiayaan/RegistrasiAkad/index.vue'),
      // },
      // {
      //   target: 'hitung-kolektibilitas',
      //   label: 'Hitung Kolektibilitas',
      //   component: import('@/pages/Pembiayaan/HitungKolektibilitas.vue'),
      // },
      /*
      {
        target: 'pencairan',
        label: 'Pencairan',
        component: import('@/pages/Dummy.vue'),
      },
      */
      {
        target: 'laporan',
        label: 'Laporan',
        component: import('@/pages/Pembiayaan'),
        children: [
          {
            target: 'pengajuan-pembiayaan',
            label: 'Pengajuan Pembiayaan',
            component: import('@/pages/Pembiayaan/Laporan/PengajuanPembiayaan.vue'),
          },
          {
            target: 'rekap-pengajuan-pembiayaan',
            label: 'Rekap Pengajuan Pembiayaan',
            component: import('@/pages/Pembiayaan/Laporan/LaporanRekapPengajuanPembiayaan.vue'),
          },
          {
            target: 'registrasi-akad',
            label: 'Registrasi Akad',
            component: import('@/pages/Pembiayaan/Laporan/LaporanRegistrasiAkad.vue'),
          },
          {
            target: 'pencairan',
            label: 'List Pencairan Pembiayaan',
            component: import('@/pages/Pembiayaan/Laporan/LaporanPencairanPembiayaan.vue'),
          },
          {
            target: 'rekap-pencairan-pembiayaan',
            label: 'Rekap Pencairan Pembiayaan',
            component: import('@/pages/Pembiayaan/Laporan/RekapPencairanPembiayaan.vue'),
          },
          {
            target: 'kartu-angsuran',
            label: 'Kartu Angsuran',
            component: import('@/pages/Pembiayaan/Laporan/LaporanKartuAngsuran.vue'),
          },
          {
            target: 'list-outstanding',
            label: 'List Outstanding',
            component: import('@/pages/Pembiayaan/Laporan/LaporanOutstanding.vue'),
          },
          {
            target: 'rekap-outstanding-piutang',
            label: 'Rekap Outstanding Piutang',
            component: import('@/pages/Pembiayaan/Laporan/RekapOutstandingPiutang.vue'),
          },
          {
            target: 'list-penerimaan-angsuran',
            label: 'Penerimaaan Angsuran',
            component: import('@/pages/Pembiayaan/Laporan/LaporanPenerimaanAngsuran.vue'),
          },
          {
            target: 'list-pelunasan',
            label: 'List Pelunasan',
            component: import('@/pages/Keuangan/LaporanPelunasanPembiayaan.vue'),
          },
          {
            target: 'par-kolektibilitas',
            label: 'PAR/ Kolektibilitas',
            component: import('@/pages/Pembiayaan/Laporan/LaporanKolektibilitas.vue'),
          },
          {
            target: 'rekapitulasi-par',
            label: 'Rekap PAR/NPL',
            component: import('@/pages/Pembiayaan/Laporan/LaporanRekapPar.vue'),
          },
        ],
      },
    ]
  },
  // General Ledger 
  {
    target: '/eneral-ledger',
    label: 'General Ledger',
    icon: 'menu-icon fas fa-wallet',
    component: import('@/pages/Pembiayaan'),
    children: [
      {
        target: 'transaksi-jurnal',
        label: 'Transaksi Jurnal Umum',
        component: import('@/pages/Transaksi/TransaksiJurnalUmum.vue'),
      },
      {
        target: 'jurnal-transaksi',
        label: 'Laporan Jurnal Transaksi',
        component: import('@/pages/Transaksi/Laporan/LaporanTransaksiJurnal.vue'),
      },
      {
        target: 'laporan-buku-besar',
        label: 'Laporan Buku Besar',
        component: import('@/pages/Keuangan/LaporanBukuBesar.vue'),
      },
      {
        target: 'laporan-neraca-saldo',
        label: 'Laporan Neraca Saldo',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'laporan-neraca',
        label: 'Laporan Neraca',
        component: import('@/pages/Keuangan/LaporanNeraca.vue'),
      },
      {
        target: 'laporan-labarugi',
        label: 'Laporan Laba Rugi',
        component: import('@/pages/Keuangan/LaporanLabaRugi.vue'),
      },
    ]
  },
  // Transaksi
  // {
  //   target: '/transaksi',
  //   label: 'Transaksi',
  //   icon: 'menu-icon fas fa-random',
  //   component: import('@/pages/Transaksi'),
  //   children: [
  //     // {
  //     //   target: 'transaksi-rembug',
  //     //   label: 'Transaksi Majelis',
  //     //   component: import('@/pages/Dummy.vue'),
  //     // },
  //     {
  //       target: 'verifikasi-transaksi-rembug',
  //       label: 'Verifikasi Transaksi Majelis',
  //       component: import('@/pages/Transaksi/VerifikasiTransaksiRembug.vue'),
  //     },
  //     {
  //       target: 'transaksi-kas-petugas',
  //       label: 'Transaksi Kas Petugas',
  //       component: import('@/pages/Transaksi/TransaksiKasPetugas.vue'),
  //     },
  //     {
  //       target: 'verifikasi-transaksi-kas-petugas',
  //       label: 'Verifikasi Transaksi Kas Petugas',
  //       component: import('@/pages/Dummy.vue'),
  //     },
  //     {
  //       target: 'verifikasi-transaksi-jurnal',
  //       label: 'Verifikasi Transaksi Jurnal',
  //       component: import('@/pages/Dummy.vue'),
  //     },
  //     {
  //       target: 'posting-jurnal',
  //       label: 'Posting Jurnal',
  //       component: import('@/pages/Transaksi/PostingJurnal.vue'),
  //     },
  //     {
  //       target: 'tutup-buku',
  //       label: 'Tutup Buku',
  //       component: import('@/pages/Transaksi/TutupBuku.vue'),
  //     },
  //     {
  //       target: 'hitung-kolektabilitas',
  //       label: 'Hitung Kolektabilitas',
  //       component: import('@/pages/Transaksi/HitungKolektabilitas.vue'),
  //     },
  //     {
  //       target: 'laporan',
  //       label: 'Laporan Transaksi',
  //       component: import('@/pages/Transaksi'),
  //       children: [
  //         {
  //           target: 'list-transaksi-rembug',
  //           label: 'List Transaksi Majelis',
  //           component: import('@/pages/Transaksi/Laporan/LaporanListTransaksiMajelis'),
  //         },
  //         {
  //           target: 'transaksi-kas-petugas',
  //           label: 'Transaksi Kas Petugas',
  //           component: import('@/pages/Transaksi/Laporan/LaporanTransaksiKasPetugas'),
  //         },
  //         {
  //           target: 'saldo-kas-petugas',
  //           label: ' Saldo Kas Petugas',
  //           component: import('@/pages/Transaksi/Laporan/LaporanSaldoKasPetugas.vue'),
  //         },
  //       ]
  //     },
  //   ]
  // },
  // Keuangan
  // {
  //   target: '/laporan-keuangan',
  //   label: 'Laporan Keuangan',
  //   icon: 'menu-icon fas fa-coins',
  //   component: import('@/pages/Keuangan'),
  //   children: [
  //     {
  //       target: 'laporan-cash-flow',
  //       label: 'Cash Flow',
  //       component: import('@/pages/Dummy.vue'),
  //     },
  //     {
  //       target: 'laporan-jurnal-transaksi',
  //       label: 'Jurnal Transaksi',
  //       component: import('@/pages/Transaksi/Laporan/LaporanTransaksiJurnal.vue'), //import('@/pages/Dummy.vue'),
  //     },
  //     {
  //       target: 'laporan-keuangan-bulan-lalu',
  //       label: 'Keuangan Bulan Lalu',
  //       component: import('@/pages/Keuangan/LaporanKeuanganBulanLalu.vue'),
  //     },
  //     {
  //       target: 'laporan-keuangan-bulan-berjalan',
  //       label: 'Keuangan Bulan Berjalan',
  //       component: import('@/pages/Keuangan/LaporanKeuanganBulanBerjalan.vue'),
  //     },
  //   ]
  // },
  // Proses Akhir Bulan
  // {
  //   target: '/proses-akhir-bulan',
  //   label: 'Proses Akhir Bulan',
  //   icon: 'menu-icon fas fa-wrench',
  //   component: import('@/pages/Pengaturan'),
  //   children: [
  //     {
  //       target: 'tutup-buku',
  //       label: 'Tutup Buku',
  //       component: import('@/pages/Dummy.vue'),
  //     },
  //   ]
  // },
  // Pengaturan
  {
    target: '/pengaturan',
    label: 'Pengaturan',
    icon: 'menu-icon fas fa-cogs',
    component: import('@/pages/Pengaturan'),
    children: [
      {
        target: 'pengguna',
        label: 'Pengguna',
        component: import('@/pages/Pengaturan/Pengguna'),
      },
      {
        target: 'grup-pengguna',
        label: 'Grup Pengguna',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'menu',
        label: 'Menu',
        component: import('@/pages/Dummy.vue'),
      },
      {
        target: 'master-data',
        label: 'Master Data',
        component: import('@/pages/Pengaturan/MasterData'),
        children: [
          {
            target: 'gl',
            label: 'Gl',
            component: import('@/pages/Pengaturan/MasterData/Gl'),
          },
          {
            target: 'kategori-par',
            label: 'Kategori PAR',
            component: import('@/pages/Pengaturan/MasterData/KategoriPar'),
          },
          {
            target: 'list-kode',
            label: 'List Kode',
            component: import('@/pages/Pengaturan/MasterData/ListKode'),
          },
          {
            target: 'produk-pembiayaan',
            label: 'Produk Pembiayaan',
            component: import('@/pages/Pengaturan/MasterData/ProdukPembiayaan'),
          },
          {
            target: 'produk-tabungan',
            label: 'Produk Tabungan',
            component: import('@/pages/Pengaturan/MasterData/ProdukTabungan'),
          },
          {
            target: 'produk-deposito',
            label: 'Produk Deposito',
            component: import('@/pages/Pengaturan/MasterData/ProdukDeposito'),
          },
          {
            target: 'pegawai',
            label: 'Pegawai',
            component: import('@/pages/Pengaturan/MasterData/Pegawai'),
          },
          {
            target: 'rembug',
            label: 'Majelis',
            component: import('@/pages/Pengaturan/MasterData/Rembug'),
          },
          {
            target: 'lembaga',
            label: 'Lembaga',
            component: import('@/pages/Pengaturan/MasterData/Lembaga'),
          },
          {
            target: 'cabang',
            label: 'Cabang',
            component: import('@/pages/Pengaturan/MasterData/Cabang'),
          },
          {
            target: 'desa',
            label: 'Desa',
            component: import('@/pages/Pengaturan/MasterData/Desa'),
          },
          {
            target: 'kecamatan',
            label: 'Kecamatan',
            component: import('@/pages/Pengaturan/MasterData/Kecamatan'),
          },
          {
            target: 'kota-kabupaten',
            label: 'Kota Kabupaten',
            component: import('@/pages/Pengaturan/MasterData/KotaKabupaten'),
          },
        ]
      }
    ]
  },
]
export default menu