<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KopAnggotaMutasi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'kop_anggota_mutasi';
    protected $fillable = ['no_anggota', 'jenis_mutasi', 'alasan_mutasi', 'keterangan_mutasi', 'kode_rembug', 'kode_rembug_baru', 'tanggal_mutasi', 'saldo_pokok', 'saldo_margin', 'saldo_catab', 'saldo_minggon', 'saldo_sukarela', 'saldo_tab_berencana', 'saldo_deposito', 'saldo_simpok', 'saldo_simwa', 'bonus_bagihasil', 'setoran_tambahan', 'penarikan_sukarela', 'flag_saldo_margin', 'flag_saldo_catab', 'status_mutasi', 'kode_petugas', 'created_by'];

    public function validateAdd($validate)
    {
        $rule = [
            'no_anggota' => 'required|numeric',
            'jenis_mutasi' => 'required|numeric',
            'alasan_mutasi' => 'required|numeric',
            'keterangan_mutasi' => 'required',
            'kode_rembug' => 'required|numeric',
            'kode_rembug_baru' => 'numeric',
            'tanggal_mutasi' => 'required',
            'saldo_pokok' => 'numeric',
            'saldo_margin' => 'numeric',
            'saldo_catab' => 'numeric',
            'saldo_minggon' => 'numeric',
            'saldo_sukarela' => 'numeric',
            'saldo_tab_berencana' => 'numeric',
            'saldo_deposito' => 'numeric',
            'saldo_simpok' => 'numeric',
            'saldo_simwa' => 'numeric',
            'bonus_bagihasil' => 'numeric',
            'setoran_tambahan' => 'numeric',
            'penarikan_sukarela' => 'numeric',
            'flag_saldo_margin' => 'numeric',
            'flag_saldo_catab' => 'numeric',
            'status_mutasi' => 'numeric',
            'kode_petugas' => 'numeric',
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
            'jenis_mutasi' => 'required|numeric',
            'alasan_mutasi' => 'required|numeric',
            'keterangan_mutasi' => 'required',
            'kode_rembug' => 'required|numeric',
            'kode_rembug_baru' => 'numeric',
            'tanggal_mutasi' => 'required',
            'saldo_pokok' => 'numeric',
            'saldo_margin' => 'numeric',
            'saldo_catab' => 'numeric',
            'saldo_minggon' => 'numeric',
            'saldo_sukarela' => 'numeric',
            'saldo_tab_berencana' => 'numeric',
            'saldo_deposito' => 'numeric',
            'saldo_simpok' => 'numeric',
            'saldo_simwa' => 'numeric',
            'bonus_bagihasil' => 'numeric',
            'setoran_tambahan' => 'numeric',
            'penarikan_sukarela' => 'numeric',
            'flag_saldo_margin' => 'numeric',
            'flag_saldo_catab' => 'numeric',
            'status_mutasi' => 'numeric',
            'kode_petugas' => 'numeric'
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

    function get_saldo_keluar($no_anggota)
    {
        $statement = "SELECT
        COALESCE(a.saldo_pokok) AS saldo_pokok,
        COALESCE(a.saldo_margin) AS saldo_margin,
        COALESCE(a.saldo_catab) AS saldo_catab,
        COALESCE(ka.simwa,0) AS saldo_minggon,
        COALESCE(ka.simpok,0) AS simpok,
        COALESCE(ka.simsuk,0) AS simsuk,
        COALESCE(c.simwa) AS simwa,
        COALESCE(b.saldo_tabungan,0) AS saldo_tabungan,
        0 AS saldo_deposito,
        0 AS bonus_bagihasil
        FROM kop_anggota AS ka
        LEFT JOIN (
            SELECT
            kp.no_anggota,
            SUM(kpb.saldo_pokok) AS saldo_pokok,
            SUM(kpb.saldo_margin) AS saldo_margin,
            SUM(kpb.saldo_catab) AS saldo_catab
            FROM kop_pengajuan AS kp
            JOIN kop_pembiayaan AS kpb ON kpb.no_pengajuan = kp.no_pengajuan
            WHERE kpb.status_rekening = 1
            GROUP BY 1
        ) AS a ON a.no_anggota = ka.no_anggota
        LEFT JOIN (
            SELECT
            no_anggota,
            SUM(saldo) AS saldo_tabungan
            FROM kop_tabungan
            WHERE status_rekening = 1 AND kode_produk <> '099'
            GROUP BY 1
        ) AS b ON b.no_anggota = ka.no_anggota
        LEFT JOIN (
            SELECT
            no_anggota,
            SUM(saldo) AS simwa
            FROM kop_tabungan
            WHERE status_rekening = 1 AND kode_produk = '099'
            GROUP BY 1
        ) AS c ON c.no_anggota = ka.no_anggota
        WHERE ka.no_anggota = ?";

        $show = DB::select($statement, [$no_anggota]);

        return $show;
    }

    function get_anggota_keluar($kode_cabang, $kode_rembug, $from_date, $thru_date)
    {
        $show = KopAnggotaMutasi::select('kop_anggota_mutasi.*', 'ka.nama_anggota', 'kp.nama_pgw', 'kc.nama_cabang', 'kr.nama_rembug')
            ->join('kop_anggota AS ka', 'ka.no_anggota', 'kop_anggota_mutasi.no_anggota')
            ->join('kop_cabang AS kc', 'kc.kode_cabang', 'ka.kode_cabang')
            ->leftjoin('kop_rembug AS kr', 'kr.kode_rembug', 'ka.kode_rembug')
            ->join('kop_pegawai AS kp', 'kp.kode_pgw', 'kop_anggota_mutasi.kode_petugas')
            ->where('kop_anggota_mutasi.status_mutasi', 1);

        if ($kode_cabang <> '~' and $kode_cabang <> '00000' and !empty($kode_cabang) and $kode_cabang <> null) {
            $show->where('ka.kode_cabang', $kode_cabang);
        }

        if ($kode_rembug <> '~' and $kode_rembug <> '00000' and !empty($kode_rembug) and $kode_rembug <> null) {
            $show->where('ka.kode_rembug', $kode_rembug);
        }

        $show->whereBetween('kop_anggota_mutasi.tanggal_mutasi', [$from_date, $thru_date])
            ->orderBy('ka.kode_cabang', 'DESC')
            ->orderBy('ka.kode_rembug', 'DESC')
            ->orderBy('kop_anggota_mutasi.tanggal_mutasi', 'DESC');

        $show = $show->get();

        return $show;
    }
}
