<?php

namespace App\Http\Traits;

use App\User;
use App\Models\m_saldo;
use App\Models\transaksi;

trait Saldo
{
    //
    public function saldoMasuk($request, $getsaldo)
    {
        //
        $t = json_decode($request);
        if (!isset($getsaldo->total_saldo)) {
            $total_saldo = json_decode($t->jml_masuk);

            $saldo = new m_saldo();
            $saldo->id_user = auth()->user()->id;
            $saldo->total_saldo = $total_saldo;
            if ($saldo->save()) {
                $response = array(
                    "status" => 200,
                    "messages" => "Berhasil top up",
                );
                return response()->json($response, 200);
            }
        } else {
            $total_saldo = $getsaldo->total_saldo + json_decode($t->jml_masuk);
            $saldo = m_saldo::where('id_user', auth()->user()->id)->first();
            $saldo->id_user = auth()->user()->id;
            $saldo->total_saldo = $total_saldo;
            if ($saldo->save()) {
                $response = array(
                    "status" => 200,
                    "messages" => "Berhasil top up",
                );
                return response()->json($response, 200);
            }
        }
    }

    public function saldoKeluar($request, $getsaldo)
    {
        $out = json_decode($request);
        if (!isset($getsaldo->total_saldo)) {
            $total_saldo = $out->jml_keluar;

            $saldo = new m_saldo();
            $saldo->id_user = auth()->user()->id;
            $saldo->total_saldo = $total_saldo;
            if ($saldo->save()) {
                $response = array(
                    "status" => 200,
                    "messages" => "Berhasil withdraw",
                );
                return response()->json($response, 200);
            }
        } else {
            if ($getsaldo->total_saldo < $out->jml_keluar) {
                $response = array(
                    "status" => 400,
                    "messages" => "Saldo tidak cukup",
                );
                return response()->json($response, 400);
            }
            $total_saldo = $getsaldo->total_saldo - $out->jml_keluar;
            $saldo = m_saldo::where('id_user', auth()->user()->id)->first();
            $saldo->id_user = auth()->user()->id;
            $saldo->total_saldo = $total_saldo;
            if ($saldo->save()) {
                $response = array(
                    "status" => 200,
                    "messages" => "Berhasil withdraw",
                );
                return response()->json($response, 200);
            }
        }
    }

    public function saldoTransfer($request, $getsaldo, $getsaldopenerima)
    {
        $out = json_decode($request);
        if (!isset($getsaldo->total_saldo)) {
            $total_saldo = $out->jml_keluar;

            $saldo = new m_saldo();
            $saldo->id_user = auth()->user()->id;
            $saldo->total_saldo = $total_saldo;
            $saldo->save();
        } else {
            if ($getsaldo->total_saldo < $out->jml_keluar) {
                DB::rollBack();
                $response = array(
                    "status" => 400,
                    "messages" => "Saldo tidak cukup",
                );
                return response()->json($response, 400);
            }
            $total_saldo = $getsaldo->total_saldo - $out->jml_keluar;
            $saldo = m_saldo::where('id_user', auth()->user()->id)->first();
            $saldo->id_user = auth()->user()->id;
            $saldo->total_saldo = $total_saldo;
            $saldo->save();
        }

        $data = new transaksi();
        $data->id_user = $out->id_penerima;
        $data->jml_masuk = $out->jml_keluar;
        $data->tgl_keluar = date("Y-m-d H:i:s");
        $data->keterangan = 'Debit';
        $data->save();

        if (!isset($getsaldopenerima->total_saldo)) {
            $total_saldo = $out->jml_keluar;

            $saldo = new m_saldo();
            $saldo->id_user = $out->id_penerima;
            $saldo->total_saldo = $total_saldo;
            if ($saldo->save()) {
                DB::commit();
                $response = array(
                    "status" => 200,
                    "messages" => "Berhasil transfer",
                );
                return response()->json($response, 200);
            }
        } else {
            if ($getsaldopenerima->total_saldo < $out->jml_keluar) {
                DB::rollBack();
                $response = array(
                    "status" => 400,
                    "messages" => "Saldo tidak cukup",
                );
                return response()->json($response, 400);
            }
            $total_saldo = $getsaldopenerima->total_saldo + $out->jml_keluar;
            $saldo = m_saldo::where('id_user', $out->id_penerima)->first();
            $saldo->id_user = $out->id_penerima;
            $saldo->total_saldo = $total_saldo;
            if ($saldo->save()) {
                DB::commit();
                $response = array(
                    "status" => 200,
                    "messages" => "Berhasil transfer",
                );
                return response()->json($response, 200);
            }
        }
    }
}
