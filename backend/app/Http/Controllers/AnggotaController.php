<?php

namespace App\Http\Controllers;

use App\Models\KopAnggota;
use App\Models\KopAnggotaUk;
use App\Models\KopLembaga;
use App\Models\KopPar;
use App\Models\KopPembiayaan;
use App\Models\KopTabungan;
use App\Models\KopUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    function saldosaldo(Request $request)
    {
        $token = $request->header('token');
        $param = array('token' => $token);
        $get = KopUser::where($param)->first();
        $cabang = $get->kode_cabang;

        $anggota = KopAnggota::get_jumlah_anggota($cabang);
        $outstanding = KopPembiayaan::get_saldo_outstanding($cabang);
        $tabungan = KopTabungan::get_saldo_tabungan($cabang);

        if ($cabang <> '00000') {
            $tanggal = KopPar::select(DB::raw('MAX(tanggal_hitung) AS tanggal_hitung'))->where('kode_cabang', $cabang)->first();
        } else {
            $tanggal = KopPar::select(DB::raw('MAX(tanggal_hitung) AS tanggal_hitung'))->first();
        }

        $par = KopPar::get_par($cabang, $tanggal->tanggal_hitung, 0);
        $par_all = KopPar::get_par($cabang, $tanggal->tanggal_hitung, 1);
        $saldo_par = ($par->saldo / $par_all->saldo) * 100;

        $data = array(
            'jumlah_anggota' => $anggota->jumlah_anggota,
            'saldo_outstanding' => (int) $outstanding->saldo_outstanding,
            'saldo_tabungan' => (int) $tabungan->saldo_tabungan,
            'persentase_par' => number_format($saldo_par, 2, '.', ',')
        );

        $res = array(
            'status' => TRUE,
            'data' => $data,
            'msg' => 'Berhasil!'
        );

        $response = response()->json($res, 200);

        return $response;
    }

    function rembug(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
        $kode_petugas = $request->kode_petugas;

        $show = KopAnggota::rembug($kode_cabang, $kode_petugas);

        $data = array();

        foreach ($show as $sh) {
            $kode_rembug = $sh->kode_rembug;
            $nama_rembug = $sh->nama_rembug;

            $data[] = array(
                'kode_rembug' => $kode_rembug,
                'nama_rembug' => $nama_rembug
            );
        }

        $res = array(
            'status' => TRUE,
            'data' => $data,
            'msg' => 'Berhasil!'
        );

        $response = response()->json($res, 200);

        return $response;
    }

    function simpanan_anggota()
    {
        $show = KopLembaga::first();

        $data = array(
            'simpok' => $show->simpok,
            'simwa' => $show->simwa
        );

        $res = array(
            'status' => TRUE,
            'data' => $data,
            'msg' => 'Berhasil!'
        );

        $response = response()->json($res, 200);

        return $response;
    }

    function create(Request $request)
    {
        $token = $request->header('token');
        $param = array('token' => $token);
        $get = KopUser::where($param)->first();

        if ($token) {
            $kode_cabang = $get->kode_cabang;
            $created_by = $get->id;
        } else {
            $kode_cabang = $request->kode_cabang;
            $created_by = 'SYS';
        }

        $nama_anggota = $request->nama_anggota;
        $jenis_kelamin = $request->jenis_kelamin;
        $ibu_kandung = $request->ibu_kandung;
        $tempat_lahir = $request->tempat_lahir;
        $tgl_lahir = $request->tgl_lahir;
        $no_ktp = $request->no_ktp;
        $fileKtp = $request->doc_ktp;
        $no_npwp = $request->no_npwp;
        $no_telp = $request->no_telp;
        $alamat = $request->alamat;
        $desa = $request->desa;
        $kecamatan = $request->kecamatan;
        $kabupaten = $request->kabupaten;
        $kodepos = $request->kodepos;
        $pendidikan = $request->pendidikan;
        $pekerjaan = $request->pekerjaan;
        $ket_pekerjaan = $request->ket_pekerjaan;
        $pendapatan_perbulan = $request->pendapatan_perbulan;
        $status_perkawinan = $request->status_perkawinan;
        $nama_pasangan = $request->nama_pasangan;
        $fileTtdAnggota = $request->ttd_anggota;

        $nama_anggota = strtoupper($nama_anggota);
        $ibu_kandung = strtoupper($ibu_kandung);
        $tempat_lahir = strtoupper($tempat_lahir);
        $tgl_lahir = date('Y-m-d', strtotime(str_replace('/', '-', $tgl_lahir)));
        $alamat = strtoupper($alamat);
        $desa = strtoupper($desa);
        $kecamatan = strtoupper($kecamatan);
        $kabupaten = strtoupper($kabupaten);
        $ket_pekerjaan = strtoupper($ket_pekerjaan);
        $nama_pasangan = strtoupper($nama_pasangan);

        $tgl_gabung = date('Y-m-d');

        try {
            if ($fileKtp == null or $fileKtp == 'null' or $fileKtp == 'undefined' or $fileKtp == '') {
                $doc_ktp = null;
            } else {
                $name_ktp = 'ktp_' . $request->no_ktp . '.png';
                $path_ktp = 'ktp/' . $name_ktp;

                Storage::disk('public')->put($path_ktp, file_get_contents($fileKtp));

                $doc_ktp = $name_ktp;
            }

            if ($fileTtdAnggota == null or $fileTtdAnggota == 'null' or $fileTtdAnggota == 'undefined' or $fileTtdAnggota == '') {
                $ttd_anggota = NULL;
            } else {
                $name_ttd_anggota = 'ttd_anggota_' . $request->no_ktp . '.png';
                $path_ttd_anggota = 'document/' . $name_ttd_anggota;

                Storage::disk('public')->put($path_ttd_anggota, file_get_contents($fileTtdAnggota));

                $ttd_anggota = $name_ttd_anggota;
            }

            $data = array(
                'kode_cabang' => $kode_cabang,
                'nama_anggota' => $nama_anggota,
                'jenis_kelamin' => $jenis_kelamin,
                'ibu_kandung' => $ibu_kandung,
                'tempat_lahir' => $tempat_lahir,
                'tgl_lahir' => $tgl_lahir,
                'no_ktp' => $no_ktp,
                'doc_ktp' => $doc_ktp,
                'no_npwp' => $no_npwp,
                'no_telp' => $no_telp,
                'alamat' => $alamat,
                'desa' => $desa,
                'kecamatan' => $kecamatan,
                'kabupaten' => $kabupaten,
                'kodepos' => $kodepos,
                'no_telp' => $no_telp,
                'pendidikan' => $pendidikan,
                'pekerjaan' => $pekerjaan,
                'ket_pekerjaan' => $ket_pekerjaan,
                'pendapatan_perbulan' => $pendapatan_perbulan,
                'status_perkawinan' => $status_perkawinan,
                'nama_pasangan' => $nama_pasangan,
                'ttd_anggota' => $ttd_anggota,
                'tgl_gabung' => $tgl_gabung,
                'created_by' => $created_by
            );

            $validate = KopAnggota::validateAdd($data);

            DB::beginTransaction();

            if ($validate['status'] === true) {
                KopAnggota::create($data);

                $res = array(
                    'status' => true,
                    'data' => null,
                    'msg' => 'Berhasil!'
                );

                DB::commit();
            } else {
                DB::rollBack();

                $res = array(
                    'status' => false,
                    'data' => $data,
                    'msg' => $validate['msg']
                );
            }
        } catch (Exception $e) {
            $res = array(
                'status' => false,
                'data' => null,
                'msg' => $e->getMessage()
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }

    public function read(Request $request)
    {
        $offset = 0;
        $page = 1;
        $perPage = '~';
        $sortDir = 'ASC';
        $sortBy = 'kop_anggota.no_anggota';
        $search = null;
        $total = 0;
        $totalPage = 1;
        $rembug = '~';
        $petugas = 0;
        $status = '~';
        $from = null;
        $to = null;

        if (isset($request->kode_cabang)) {
            $cabang = $request->kode_cabang;
        } else {
            $token = $request->header('token');
            $param = array('token' => $token);
            $get = KopUser::where($param)->first();
            $cabang = $get->kode_cabang;
        }

        if (isset($request->page)) {
            $page = $request->page;
        }

        if (isset($request->perPage)) {
            $perPage = $request->perPage;
        }

        if (isset($request->sortDir)) {
            $sortDir = $request->sortDir;
        }

        if (isset($request->sortBy)) {
            $sortBy = $request->sortBy;
        }

        if (isset($request->search)) {
            $search = strtoupper($request->search);
        }

        if (isset($request->rembug)) {
            $rembug = $request->rembug;
        }

        if (isset($request->petugas)) {
            $petugas = $request->petugas;
        }

        if (isset($request->status)) {
            $status = $request->status;
        }

        if (isset($request->from)) {
            $from = str_replace('/', '-', $request->from);
            $from = date('Y-m-d', strtotime($from));
        }

        if (isset($request->to)) {
            $to = str_replace('/', '-', $request->to);
            $to = date('Y-m-d', strtotime($to));
        }

        if ($page > 1) {
            $offset = ($page - 1) * $perPage;
        }

        $read = KopAnggota::select('kop_anggota.*', 'kop_cabang.nama_cabang', DB::raw('(CASE WHEN kop_anggota.kode_rembug IS NULL THEN \'INDIVIDU\' ELSE kop_rembug.nama_rembug END) AS nama_rembug'), DB::raw('COALESCE(kop_pembiayaan.saldo_pokok+kop_pembiayaan.saldo_margin,0) AS saldo_outstanding'))
            ->join('kop_cabang', 'kop_cabang.kode_cabang', 'kop_anggota.kode_cabang')
            ->leftjoin('kop_rembug', 'kop_rembug.kode_rembug', 'kop_anggota.kode_rembug')
            ->leftjoin('kop_pengajuan', function ($join) {
                $join->on('kop_pengajuan.no_anggota', 'kop_anggota.no_anggota')->where('kop_pengajuan.status_pengajuan', 1);
            })
            ->leftjoin('kop_pembiayaan', function ($join) {
                $join->on('kop_pembiayaan.no_pengajuan', 'kop_pengajuan.no_pengajuan')->where('kop_pembiayaan.status_rekening', 1);
            });

        if ($perPage != '~') {
            $read->skip($offset)->take($perPage);
        }

        if ($cabang != '00000') {
            $read->where('kop_anggota.kode_cabang', $cabang);
        }

        if ($rembug != '~') {
            $read->where('kop_anggota.kode_rembug', $rembug);
        }

        if ($petugas != 0) {
            $read->where('kop_rembug.kode_petugas', $petugas);
        }

        if ($status != '~') {
            $read->where('kop_anggota.status', $status);
        }

        if ($search <> null) {
            $read->where('kop_anggota.no_anggota', 'LIKE', '%' . $search . '%')
                ->orWhere('kop_anggota.nama_anggota', 'LIKE', '%' . $search . '%');
        }

        if ($from <> null and $to <> null) {
            $read->whereBetween('kop_anggota.tgl_gabung', [$from, $to]);
        }

        $read = $read->orderBy($sortBy, $sortDir)->get();

        foreach ($read as $rd) {
            $useCount = 'used count diubah datanya disini';
            $rd->used_count = $useCount;
        }

        if ($search <> null || ($from <> null and $to <> null)) {
            $total = KopAnggota::join('kop_cabang', 'kop_cabang.kode_cabang', 'kop_anggota.kode_cabang')
                ->leftjoin('kop_rembug', 'kop_rembug.kode_rembug', 'kop_anggota.kode_rembug')
                ->leftjoin('kop_pengajuan', function ($join) {
                    $join->on('kop_pengajuan.no_anggota', 'kop_anggota.no_anggota')->where('kop_pengajuan.status_pengajuan', 1);
                })
                ->leftjoin('kop_pembiayaan', function ($join) {
                    $join->on('kop_pembiayaan.no_pengajuan', 'kop_pengajuan.no_pengajuan')->where('kop_pembiayaan.status_rekening', 1);
                })
                ->orderBy($sortBy, $sortDir);

            if ($cabang != '00000') {
                $total->where('kop_anggota.kode_cabang', $cabang);
            }

            if ($rembug != '~') {
                $total->where('kop_anggota.kode_rembug', $rembug);
            }

            if ($petugas != 0) {
                $total->where('kop_rembug.kode_petugas', $petugas);
            }

            if ($status != '~') {
                $total->where('kop_anggota.status', $status);
            }

            if ($search) {
                $total->where('kop_anggota.no_anggota', 'LIKE', '%' . $search . '%')
                    ->orWhere('kop_anggota.nama_anggota', 'LIKE', '%' . $search . '%');
            }

            if ($from <> null && $to <> null) {
                $total->whereBetween('kop_anggota.tgl_gabung', [$from, $to]);
            }

            $total = $total->count();
        } else {
            $total = KopAnggota::all()->count();
        }

        if ($perPage != '~') {
            $totalPage = ceil($total / $perPage);
        }

        foreach ($read as $row) {
            $row->tgl_gabung = date('d-F-Y', strtotime($row->tgl_gabung));
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

    public function detail(Request $request)
    {
        $id = $request->id;

        if (isset($request->id)) {
            $get = KopAnggota::find($id);

            if ($get->doc_ktp == null or $get->doc_ktp == 'null' or $get->doc_ktp == 'undefined' or $get->doc_ktp == '') {
                $base64_ktp = null;
            } else {
                $path2 = Storage::disk('public')->url('ktp/' . $get->doc_ktp);
                $type2 = pathinfo($path2, PATHINFO_EXTENSION);
                $data2 = @file_get_contents($path2);
                $base64_ktp = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);
            }

            if ($get->ttd_anggota == null or $get->ttd_anggota == 'null' or $get->ttd_anggota == 'undefined' or $get->ttd_anggota == '') {
                $base64_ttd = null;
            } else {
                $path1 = Storage::disk('public')->url('document/' . $get->ttd_anggota);
                $type1 = pathinfo($path1, PATHINFO_EXTENSION);
                $data1 = @file_get_contents($path1);
                $base64_ttd = 'data:image/' . $type1 . ';base64,' . base64_encode($data1);
            }

            $data = array(
                'id' => $get->id,
                'kode_cabang' => $get->kode_cabang,
                'no_anggota' => $get->no_anggota,
                'nama_anggota' => $get->nama_anggota,
                'jenis_kelamin' => $get->jenis_kelamin,
                'ibu_kandung' => $get->ibu_kandung,
                'tempat_lahir' => $get->tempat_lahir,
                'tgl_lahir' => date('d/m/Y', strtotime(str_replace('-', '/', $get->tgl_lahir))),
                'alamat' => $get->alamat,
                'desa' => $get->desa,
                'kecamatan' => $get->kecamatan,
                'kabupaten' => $get->kabupaten,
                'kodepos' => $get->kodepos,
                'no_ktp' => $get->no_ktp,
                'doc_ktp' => $base64_ktp,
                'no_npwp' => $get->no_npwp,
                'no_telp' => $get->no_telp,
                'pendidikan' => $get->pendidikan,
                'status_perkawinan' => $get->status_perkawinan,
                'nama_pasangan' => $get->nama_pasangan,
                'pekerjaan' => $get->pekerjaan,
                'ket_pekerjaan' => $get->ket_pekerjaan,
                'pendapatan_perbulan' => (int) $get->pendapatan_perbulan,
                'simpok' => (int) $get->simpok,
                'simwa' => (int) $get->simwa,
                'simsuk' => (int) $get->simsuk,
                'tgl_gabung' => date('d/m/Y', strtotime(str_replace('-', '/', $get->tgl_gabung))),
                'ttd_anggota' => $base64_ttd
            );

            if ($data) {
                $res = array(
                    'status' => TRUE,
                    'data' => $data,
                    'msg' => 'Berhasil!'
                );
            } else {
                $res = array(
                    'status' => FALSE,
                    'msg' => 'Maaf! Data tidak ditemukan'
                );
            }
        } else {
            $res = array(
                'status' => FALSE,
                'msg' => 'Maaf! Anggota tidak bisa ditampilkan'
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }

    public function update(Request $request)
    {
        $token = $request->header('token');
        $param = array('token' => $token);
        $get = KopUser::where($param)->first();

        if ($token) {
            $updated_by = $get->id;
        } else {
            $updated_by = 'SYS';
        }

        $find = KopAnggota::find($request->id);

        $find->nama_anggota = strtoupper($request->nama_anggota);
        $find->jenis_kelamin = $request->jenis_kelamin;
        $find->ibu_kandung = strtoupper($request->ibu_kandung);
        $find->tempat_lahir = strtoupper($request->tempat_lahir);
        $find->tgl_lahir = date('Y-m-d', strtotime(str_replace('/', '-', $request->tgl_lahir)));
        $find->no_ktp = $request->no_ktp;
        $find->no_npwp = $request->no_npwp;
        $find->no_telp = $request->no_telp;
        $find->alamat = strtoupper($request->alamat);
        $find->desa = strtoupper($request->desa);
        $find->kecamatan = strtoupper($request->kecamatan);
        $find->kabupaten = strtoupper($request->kabupaten);
        $find->kodepos = $request->kodepos;
        $find->pendidikan = $request->pendidikan;
        $find->pekerjaan = $request->pekerjaan;
        $find->ket_pekerjaan = strtoupper($request->ket_pekerjaan);
        $find->pendapatan_perbulan = $request->pendapatan_perbulan;
        $find->status_perkawinan = $request->status_perkawinan;
        $find->nama_pasangan = strtoupper($request->nama_pasangan);
        $find->updated_by = $updated_by;
        $find->updated_at = date('Y-m-d H:i:s');

        try {
            if ($request->doc_ktp == null or $request->doc_ktp == 'null' or $request->doc_ktp == 'undefined' or $request->doc_ktp == '') {
                $find->doc_ktp = $find->doc_ktp;
            } else {
                $name_ktp = 'ktp_' . $request->no_ktp . '.png';
                $path_ktp = 'ktp/' . $name_ktp;

                Storage::disk('public')->put($path_ktp, file_get_contents($request->doc_ktp));

                $find->doc_ktp = $name_ktp;
            }

            if ($request->ttd_anggota == null or $request->ttd_anggota == 'null' or $request->ttd_anggota == 'undefined' or $request->ttd_anggota == '') {
                $find->ttd_anggota = $find->ttd_anggota;
            } else {
                $name_ttd_anggota = 'ttd_anggota_' . $request->no_ktp . '.png';
                $path_ttd_anggota = 'document/' . $name_ttd_anggota;

                Storage::disk('public')->put($path_ttd_anggota, file_get_contents($request->ttd_anggota));

                $find->ttd_anggota = $name_ttd_anggota;
            }

            $validate = KopAnggota::validateUpdate($request->all());

            DB::beginTransaction();

            if ($validate['status'] === true) {
                $find->save();

                $res = array(
                    'status' => true,
                    'data' => null,
                    'msg' => 'Berhasil!'
                );

                DB::commit();
            } else {
                DB::rollBack();

                $res = array(
                    'status' => false,
                    'data' => $request->all(),
                    'msg' => $validate['msg']
                );
            }
        } catch (Exception $e) {
            $res = array(
                'status' => false,
                'data' => null,
                'msg' => $e->getMessage()
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }

    public function approved(Request $request)
    {
        $token = $request->header('token');
        $param = array('token' => $token);
        $get = KopUser::where($param)->first();

        if ($token) {
            $verified_by = $get->id;
        } else {
            $verified_by = 'SYS';
        }

        $find = KopAnggota::find($request->id);

        $find->status = 1;
        $find->verified_by = $verified_by;
        $find->verified_at = date('Y-m-d H:i:s');

        try {
            $find->save();

            $res = array(
                'status' => true,
                'data' => null,
                'msg' => 'Approved!'
            );
        } catch (Exception $e) {
            $res = array(
                'status' => false,
                'data' => null,
                'msg' => $e->getMessage()
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }

    public function rejected(Request $request)
    {
        $token = $request->header('token');
        $param = array('token' => $token);
        $get = KopUser::where($param)->first();

        if ($token) {
            $verified_by = $get->id;
        } else {
            $verified_by = 'SYS';
        }

        $find = KopAnggota::find($request->id);

        $find->status = 99;
        $find->verified_by = $verified_by;
        $find->verified_at = date('Y-m-d H:i:s');

        try {
            $find->save();

            $res = array(
                'status' => true,
                'data' => null,
                'msg' => 'Rejected!'
            );
        } catch (Exception $e) {
            $res = array(
                'status' => false,
                'data' => null,
                'msg' => $e->getMessage()
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        if ($id) {
            $data = KopAnggota::find($id);

            $param = array('no_anggota' => $data->no_anggota);

            $data2 = KopAnggotaUk::where($param)->first();

            DB::beginTransaction();

            try {
                $data2->delete();
                $data->delete();

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
        } else {
            $res = array(
                'status' => FALSE,
                'msg' => 'Maaf! Anggota tidak ditemukan'
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }
}
