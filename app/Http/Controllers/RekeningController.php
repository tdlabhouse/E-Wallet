<?php

namespace App\Http\Controllers;

use App\Models\m_rekening;
use Illuminate\Http\Request;
use Validator;

class RekeningController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        //
        $getrekening = m_rekening::select('bank', 'no_rekening', 'kota', 'nama_pemilik')
            ->where('id_user',  auth()->user()->id)
            ->get();

        $response = array(
            "status" => 200,
            "data" => $getrekening
        );
        return response()->json($response, 200);
    }
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'bank' => 'required',
            'no_rekening' => 'required|numeric',
            'kota' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {
            $data = new m_rekening();
            $data->id_user = auth()->user()->id;
            $data->nama_pemilik = $request->nama_pemilik;
            $data->bank = $request->bank;
            $data->no_rekening = $request->no_rekening;
            $data->kota = $request->kota;
            if ($data->save()) {
                $response = array(
                    "status" => 200,
                    "messages" => "Berhasil tambah rekening",
                    "data" => $data,
                );
                return response()->json($response, 200);
            }
        } catch (Exception $e) {
            $response = array(
                "status" => 400,
                "messages" => "Gagal tambah rekening",
                "data" => []
            );
            return response()->json($response, 400);
        }
    }

    //fungsi untuk edit rekening
    public function edit(Request $request)
    {
        // return $request->all();
        // die;
        $validator = Validator::make($request->all(), [
            'bank' => 'required',
            'no_rekening' => 'required|numeric',
            'kota' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {
            $data = m_rekening::where('id', $request->input('id'))->first();
            $data->id_user = auth()->user()->id;
            $data->nama_pemilik = $request->nama_pemilik;
            $data->bank = $request->bank;
            $data->no_rekening = $request->no_rekening;
            $data->kota = $request->kota;
            if ($data->save()) {
                $response = array(
                    "status" => 200,
                    "messages" => "Berhasil edit rekening",
                    "data" => $data,
                );
                return response()->json($response, 200);
            }
        } catch (Exception $e) {
            $response = array(
                "status" => 400,
                "messages" => "Gagal edit rekening",
                "data" => []
            );
            return response()->json($response, 400);
        }
    }

    public function destroy(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'id_rekening' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {
            $data = m_rekening::find($request->id_rekening);
            if ($data->delete()) {
                $response = array(
                    "status" => 200,
                    "messages" => "Berhasil delete rekening",
                );
                return response()->json($response, 200);
            }
        } catch (Exception $e) {
            $response = array(
                "status" => 400,
                "messages" => "Gagal delete rekening",
            );
            return response()->json($response, 400);
        }
    }
}
