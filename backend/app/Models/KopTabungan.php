<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KopTabungan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'kop_tabungan';
    protected $fillable = ['kode_produk', 'no_anggota', 'no_rekening', 'saldo', 'flag_taber', 'jangka_waktu', 'periode_setoran', 'setoran', 'counter_setoran', 'status_rekening', 'tanggal_buka', 'tanggal_tutup', 'created_by'];

    public function validateAdd($validate)
    {
        $rule = [
            'kode_produk' => 'required|numeric',
            'no_anggota' => 'required|numeric',
            'no_rekening' => 'required',
            'tanggal_buka' => 'required',
            'created_by' => 'required'
        ];

        $validator = Validator::make($validate, $rule);

        if ($validator->fails()) {
            $errors =  $validator->errors()->all();

            $res = [
                'status' => FALSE,
                'errors' => $errors,
                'msg' => 'Validation Error'
            ];
        } else {
            $res = [
                'status' => TRUE,
                'errors' => NULL,
                'msg' => 'Validation OK'
            ];
        }

        return $res;
    }

    public function validateUpdate($validate)
    {
        $rule = [
            'id' => 'required|numeric',
            'kode_produk' => 'required|numeric',
            'no_anggota' => 'required|numeric',
            'no_rekening' => 'required',
            'tanggal_buka' => 'required'
        ];

        $validator = Validator::make($validate, $rule);

        if ($validator->fails()) {
            $errors =  $validator->errors()->all();

            $res = [
                'status' => FALSE,
                'errors' => $errors,
                'msg' => 'Validation Error'
            ];
        } else {
            $res = [
                'status' => TRUE,
                'errors' => NULL,
                'msg' => 'Validation OK'
            ];
        }

        return $res;
    }

    function get_seq_rekening($no_anggota, $kode_produk)
    {
        $show = KopTabungan::select(DB::raw('COUNT(*) AS jumlah'))
            ->where('no_anggota', $no_anggota)
            ->where('kode_produk', $kode_produk)
            ->first();

        return $show;
    }

    function get_rekening_tabungan($no_anggota, $kode_produk)
    {
        $show = KopTabungan::select('*')
            ->where('no_anggota', $no_anggota)
            ->where('kode_produk', $kode_produk)
            ->first();

        return $show;
    }

    function tpl_saving($no_anggota)
    {
        $show = KopTabungan::select('kpt.nama_produk', 'kpt.nama_singkat', 'kop_tabungan.no_rekening', 'kop_tabungan.setoran', 'kop_tabungan.jangka_waktu', 'kop_tabungan.counter_setoran', 'kop_tabungan.saldo', 'kop_tabungan.kode_produk')
            ->join('kop_prd_tabungan AS kpt', 'kpt.kode_produk', '=', 'kop_tabungan.kode_produk')
            ->where('kop_tabungan.status_rekening', 1)
            ->where('kpt.jenis_tabungan', 1)
            ->where('kop_tabungan.no_anggota', $no_anggota)
            ->get();

        return $show;
    }

    function get_profile($no_anggota)
    {
        $show = KopTabungan::select('kop_tabungan.*', 'kpt.nama_produk', DB::raw('kop_tabungan.tanggal_tutup AS jatuh_tempo'), 'kop_tabungan.saldo', DB::raw('(CASE WHEN kop_tabungan.status_rekening = 1 THEN \'Aktif\' WHEN kop_tabungan.status_rekening = 2 THEN \'Blokir\' WHEN kop_tabungan.status_rekening = 3 THEN \'Verifikasi Anggota Keluar\' ELSE \'Tutup\' END) AS status_rekening'))
            ->join('kop_prd_tabungan AS kpt', 'kpt.kode_produk', 'kop_tabungan.kode_produk')
            ->where('kop_tabungan.no_anggota', $no_anggota)
            ->get();

        return $show;
    }
}
