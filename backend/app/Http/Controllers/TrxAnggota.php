<?php

namespace App\Http\Controllers;

use App\Models\KopTrxAnggota;
use App\Http\Controllers\Controller;
use App\Models\KopUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrxAnggota extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    function check(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
        $voucher_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->voucher_date)));

        $get = KopTrxAnggota::check_transaction($kode_cabang, $voucher_date);

        $data = array();

        $total_debet = 0;
        $total_credit = 0;

        foreach ($get as $gt) {
            if ($gt['d_c'] == 'D') {
                $total_credit += $gt['amount'];
            } else {
                $total_debet += $gt['amount'];
            }

            $data[] = array(
                'kode_cabang' => $gt['kode_cabang'],
                'trx_date' => $gt['trx_date'],
                'trx_type' => $gt['trx_type'],
                'nama_trx' => $gt['nama_trx'],
                'flag_dc' => $gt['d_c'],
                'gl_debit' => $gt['gl_debit'],
                'nama_gl_debit' => $gt['nama_gl_debit'],
                'gl_credit' => $gt['gl_credit'],
                'nama_gl_credit' => $gt['nama_gl_credit'],
                'amount' => (int)$gt['amount']
            );
        }

        $res = array(
            'status' => true,
            'data' => $data,
            'total_kas_debit' => $total_debet,
            'total_kas_kredit' => $total_credit,
            'saldo_kas' => $total_debet - $total_credit
        );

        $response = response()->json($res, 200);

        return $response;
    }

    function posting(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
        $voucher_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->voucher_date)));

        DB::beginTransaction();

        try {
            KopTrxAnggota::buat_jurnal($kode_cabang, $voucher_date);

            $res = array(
                'status' => TRUE,
                'data' => NULL,
                'msg' => 'Berhasil!'
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            $res = array(
                'status' => FALSE,
                'data' => $request->all(),
                'msg' => $e->getMessage()
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }

    function transaksi_setoran_pokwa(Request $request)
    {
        $no_anggota = $request->no_anggota;
        $tanggal_transaksi = date('Y-m-d', strtotime(str_replace('/', '-', $request->tanggal_transaksi)));
        $jenis_setoran = $request->jenis_setoran;
        $jumlah_setoran = $request->jumlah_setoran;
        $no_referensi = $request->no_referensi;
        $keterangan = $request->keterangan;

        $token = $request->header('token');
        $param = array('token' => $token);
        $get = KopUser::where($param)->first();
        $created_by = $get->id;

        if ($jenis_setoran == 1) {
            $trx_type = 11;
        } else {
            $trx_type = 12;
        }

        $data = array(
            'no_anggota' => $no_anggota,
            'no_rekening' => $no_referensi,
            'trx_date' => $tanggal_transaksi,
            'amount' => $jumlah_setoran,
            'flag_debet_credit' => 'C',
            'trx_type' => $trx_type,
            'description' => $keterangan,
            'created_by' => $created_by
        );

        DB::beginTransaction();

        try {
            KopTrxAnggota::create($data);

            $res = array(
                'status' => TRUE,
                'data' => NULL,
                'msg' => 'Berhasil!'
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            $res = array(
                'status' => FALSE,
                'data' => $request->all(),
                'msg' => $e->getMessage()
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }

    function read_setoran_pokwa(Request $request)
    {
        $offset = 0;
        $page = 1;
        $perPage = '~';
        $sortDir = 'ASC';
        $sortBy = 'kop_trx_anggota.trx_date';
        $search = NULL;
        $total = 0;
        $totalPage = 1;
        $from = NULL;
        $to = NULL;

        $token = $request->header('token');
        $param = array('token' => $token);
        $get = KopUser::where($param)->first();
        $cabang = $get->kode_cabang;

        if ($request->page) {
            $page = $request->page;
        }

        if ($request->perPage) {
            $perPage = $request->perPage;
        }

        if ($request->sortDir) {
            $sortDir = $request->sortDir;
        }

        if ($request->sortBy) {
            $sortBy = $request->sortBy;
        }

        if ($request->search) {
            $search = strtoupper($request->search);
        }

        if ($request->cabang) {
            $cabang = $request->cabang;
        }

        if ($request->from) {
            $from = str_replace('/', '-', $request->from);
            $from = date('Y-m-d', strtotime($from));
        }

        if ($request->to) {
            $to = str_replace('/', '-', $request->to);
            $to = date('Y-m-d', strtotime($to));
        }

        if ($page > 1) {
            $offset = ($page - 1) * $perPage;
        }

        $read = KopTrxAnggota::select('kop_trx_anggota.*', 'ka.nama_anggota')
            ->join('kop_anggota AS ka', 'ka.no_anggota', 'kop_trx_anggota.no_anggota')
            ->join('kop_cabang AS kc', 'kc.kode_cabang', 'ka.kode_cabang')
            ->where('kop_trx_anggota.verified_by', null)
            ->where('ka.kode_rembug', null);

        if ($perPage != '~') {
            $read->skip($offset)->take($perPage);
        }

        if ($cabang != '00000') {
            $read->where('kc.kode_cabang', $cabang);
        }

        if ($search) {
            $read->where('ka.no_anggota', 'LIKE', '%' . $search . '%')
                ->orWhere('ka.nama_anggota', 'LIKE', '%' . $search . '%');
        }

        if ($from && $to) {
            $read->whereBetween('kop_trx_anggota.trx_date', [$from, $to]);
        }

        $read = $read->orderBy($sortBy, $sortDir)->get();

        foreach ($read as $rd) {
            $useCount = 'used count diubah datanya disini';
            $rd->used_count = $useCount;
        }

        if ($search || $cabang || ($from && $to)) {
            $total = KopTrxAnggota::orderBy($sortBy, $sortDir)
                ->join('kop_anggota AS ka', 'ka.no_anggota', 'kop_trx_anggota.no_anggota')
                ->join('kop_cabang AS kc', 'kc.kode_cabang', 'ka.kode_cabang')
                ->where('kop_trx_anggota.verified_by', null)
                ->where('ka.kode_rembug', null);

            if ($cabang != '00000') {
                $total->where('kc.kode_cabang', $cabang);
            }

            if ($search) {
                $total->where('ka.no_anggota', 'LIKE', '%' . $search . '%')
                    ->orWhere('ka.nama_anggota', 'LIKE', '%' . $search . '%');
            }

            if ($from && $to) {
                $total->whereBetween('kop_trx_anggota.trx_date', [$from, $to]);
            }

            $total = $total->count();
        } else {
            $total = KopTrxAnggota::all()->count();
        }

        if ($perPage != '~') {
            $totalPage = ceil($total / $perPage);
        }

        foreach ($read as $row) {
            $row->trx_date = date('d-F-Y', strtotime($row->trx_date));
        }

        $res = array(
            'status' => TRUE,
            'data' => $read,
            'page' => $page,
            'perPage' => $perPage,
            'sortDir' => $sortDir,
            'sortBy' => $sortBy,
            'search' => $search,
            'total' => $total,
            'totalPage' => $totalPage,
            'msg' => 'List data available'
        );

        $response = response()->json($res, 200);

        return $response;
    }
}
