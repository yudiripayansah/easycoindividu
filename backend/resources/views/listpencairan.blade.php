<table>
    <thead>
        @if($format == 'excel')
        <tr>
            <th style="font-weight: bold; text-align: center; font-size: 12px;" colspan="10">KSPPS MITRA SEJAHTERA RAYA INDONESIA</th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center; font-size: 12px;" colspan="10">{{ $cabang }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center; font-size: 12px;" colspan="10">LAPORAN LIST PENCAIRAN</th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center; font-size: 12px;" colspan="10">PERIODE : {{ $tanggal1 }} s.d {{ $tanggal2 }}</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        @endif
        <tr>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000;">No</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000;">Tgl. Akad</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000;">Nama</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000;">Rembug</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000;">No. Rekening</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000;">Produk</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000;">Plafon</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000;">Margin</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000;">Jangka Waktu</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000;">Petugas</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1 @endphp
        @foreach($pencairan as $cair)

        @if($cair->periode_jangka_waktu == 0)
        {{ $periode = 'Harian' }}
        @elseif($cair->periode_jangka_waktu == 1)
        {{ $periode = 'Mingguan' }}
        @elseif($cair->periode_jangka_waktu == 2)
        {{ $periode = 'Bulanan' }}
        @else
        {{ $periode = 'Tempo' }}
        @endif
        <tr>
            <td style="border: 1px solid #000;">{{ $no++ }}</td>
            <td style="border: 1px solid #000;">{{ $cair->tanggal_akad }}</td>
            <td style="border: 1px solid #000;">{{ $cair->nama_anggota }}</td>
            <td style="border: 1px solid #000;">{{ $cair->nama_rembug }}</td>
            <td style="border: 1px solid #000;">'{{ $cair->no_rekening }}</td>
            <td style="border: 1px solid #000;">{{ $cair->nama_produk }}</td>
            <td style="border: 1px solid #000;">{{ $cair->pokok }}</td>
            <td style="border: 1px solid #000;">{{ $cair->margin }}</td>
            <td style="border: 1px solid #000;">{{ $cair->jangka_waktu }} {{ $periode }}</td>
            <td style="border: 1px solid #000;">{{ $cair->nama_petugas }}</td>
        </tr>
        @endforeach
    </tbody>
</table>