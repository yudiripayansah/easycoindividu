<?php

namespace App\Http\Controllers;

use App\Models\KopRembug;
use App\Models\KopUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RembugController extends Controller
{
    function create(Request $request)
    {
        $data = $request->all();

        $data['nama_rembug'] = strtoupper($request->nama_rembug);

        $validate = KopRembug::validateAdd($data);

        DB::beginTransaction();

        if ($validate['status'] === TRUE) {
            try {
                $create = KopRembug::create($data);
                $find = KopRembug::find($create->id);

                $res = array(
                    'status' => TRUE,
                    'data' => $find,
                    'msg' => 'Berhasil!'
                );

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();

                $res = array(
                    'status' => FALSE,
                    'data' => $data,
                    'msg' => $e->getMessage()
                );
            }
        } else {
            $res = array(
                'status' => FALSE,
                'data' => $data,
                'msg' => $validate['msg'],
                'error' => $validate['errors']
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }

    public function generate_kode_rembug(Request $request)
    {
        $kode_cabang = $request->kode_cabang;

        $maximum = KopRembug::generateKodeRembug($kode_cabang);

        $format = '0000';

        if ($maximum->count() == 0) {
            $val = 1;
        } else {
            $val = $maximum['kode_rembug'] + 1;
        }

        $kode = $format . $val;
        $kode = substr($kode, -4);

        $data = array('kode_rembug' => $kode_cabang . $kode);

        $res = array(
            'status' => TRUE,
            'data' => $data,
            'msg' => 'Berhasil!'
        );

        $response = response()->json($res, 200);

        return $response;
    }

    public function read(Request $request)
    {
        $offset = 0;
        $page = 1;
        $perPage = '~';
        $sortDir = 'ASC';
        $sortBy = 'kode_rembug';
        $search = NULL;
        $total = 0;
        $totalPage = 1;

        $token = $request->header('token');
        $param = array('token' => $token);
        $get = KopUser::where($param)->first();
        $kode_cabang = $get->kode_cabang;

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

        if ($request->kode_cabang) {
            $kode_cabang = $request->kode_cabang;
        }

        if ($request->search) {
            $search = strtoupper($request->search);
        }

        if ($page > 1) {
            $offset = ($page - 1) * $perPage;
        }

        $read = KopRembug::read($search, $sortBy, $sortDir, $offset, $perPage, $kode_cabang);

        $data = array();

        foreach ($read as $rd) {
            $data[] = array(
                'id' => $rd->id,
                'id_rembug' => $rd->id_rembug,
                'kode_rembug' => $rd->kode_rembug,
                'kode_cabang' => $rd->kode_cabang,
                'kode_desa' => $rd->kode_desa,
                'kode_petugas' => $rd->kode_petugas,
                'nama_rembug' => $rd->nama_rembug,
                'tgl_pembentukan' => $rd->tgl_pembentukan,
                'hari_transaksi' => $rd->hari_transaksi,
                'jam_transaksi' => $rd->jam_transaksi
            );
        }

        $total = $read->count();

        if ($perPage != '~') {
            $totalPage = ceil($total / $perPage);
        }

        $res = array(
            'status' => TRUE,
            'data' => $data,
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

        if ($id) {
            $get = KopRembug::find($id);

            if ($get) {
                $res = array(
                    'status' => TRUE,
                    'data' => $get,
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
                'msg' => 'Maaf! Rembug tidak bisa ditampilkan'
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }

    public function update(Request $request)
    {
        $get = KopRembug::find($request->id);
        $validate = KopRembug::validateUpdate($request->all());

        $get->kode_cabang = $request->kode_cabang;
        $get->kode_desa = $request->kode_desa;
        $get->kode_petugas = $request->kode_petugas;
        $get->nama_rembug = strtoupper($request->nama_rembug);
        $get->tgl_pembentukan = $request->tgl_pembentukan;
        $get->hari_transaksi = $request->hari_transaksi;
        $get->jam_transaksi = $request->jam_transaksi;
        $get->status_aktif = $request->status_aktif;

        DB::beginTransaction();

        if ($validate['status'] === TRUE) {
            try {
                $get->save();

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
                'data' => $request->all(),
                'msg' => $validate['msg'],
                'error' => $validate['errors']
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        if ($id) {
            $data = KopRembug::find($id);

            DB::beginTransaction();

            try {
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
                'msg' => 'Maaf! Rembug tidak ditemukan'
            );
        }

        $response = response()->json($res, 200);

        return $response;
    }
}
