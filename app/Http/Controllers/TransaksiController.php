<?php

namespace App\Http\Controllers;

use App\Models\m_saldo;
use App\Models\t_transfer;
use App\Models\t_withdraw;
use App\Models\transaksi;
use Illuminate\Http\Request;
use DB;
use App\Http\Traits\Saldo;
use App\Models\User;
use Exception;

class TransaksiController extends Controller
{
    //
    use Saldo;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function topup(Request $request)
    {
        //
        try {
            $data = new transaksi();
            $data->id_user = auth()->user()->id;
            $data->jml_masuk = $request->jml_masuk;
            $data->tgl_masuk = date("Y-m-d H:i:s");
            $data->keterangan = 'topup';
            $data->save();

            $getsaldo = m_saldo::select('total_saldo')
                ->where('id_user',  auth()->user()->id)
                ->first();

            return $this->saldoMasuk(json_encode($request->all()), $getsaldo);
        } catch (Exception $e) {
            DB::rollBack();
            $response = array(
                "status" => 400,
                "messages" => "Gagal top up saldo",
            );
            return response()->json($response, 400);
        }
    }
    public function withdraw(Request $request)
    {
        //
        try {
            $data = new transaksi();
            $data->id_user = auth()->user()->id;
            $data->jml_keluar = $request->jml_keluar;
            $data->tgl_keluar = date("Y-m-d H:i:s");
            $data->keterangan = 'withdraw';
            $data->save();

            $withdraw = new t_withdraw();
            $withdraw->id_user = auth()->user()->id;
            $withdraw->id_rekening = $request->id_rekening;
            $withdraw->id_transaksi = $data->id;
            $withdraw->save();

            $getsaldo = m_saldo::select('total_saldo')
                ->where('id_user',  auth()->user()->id)
                ->first();
            return $this->saldoKeluar(json_encode($request->all()), $getsaldo);
        } catch (Exception $e) {
            DB::rollBack();
            $response = array(
                "status" => 400,
                "messages" => "Gagal withdraw",
            );
            return response()->json($response, 400);
        }
    }

    public function transfer(Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $data = new transaksi();
            $data->id_user = auth()->user()->id;
            $data->jml_keluar = $request->jml_keluar;
            $data->tgl_keluar = date("Y-m-d H:i:s");
            $data->keterangan = 'kredit';
            $data->save();

            $withdraw = new t_transfer();
            $withdraw->id_pengirim = auth()->user()->id;
            $withdraw->id_penerima = $request->id_penerima;
            $withdraw->id_transaksi = $data->id;
            $withdraw->save();

            $getsaldo = m_saldo::select('total_saldo')
                ->where('id_user',  auth()->user()->id)
                ->first();

            $getsaldopenerima = m_saldo::select('total_saldo')
                ->where('id_user',  $request->id_penerima)
                ->first();

            return $this->saldoTransfer(json_encode($request->all()), $getsaldo, $getsaldopenerima);
        } catch (Exception $e) {
            DB::rollBack();
            $response = array(
                "status" => 400,
                "messages" => "Gagal transfer",
            );
            return response()->json($response, 400);
        }
    }

    public function report(Request $request)
    {
        //
        $user = User::with(['transaksi'])->where('id', auth()->user()->id)->first();
        foreach ($user->transaksi as $us) {
            $transaksi[] = array(
                'tanggal_debit' => $us->tgl_masuk,
                'tanggal_kredit' => $us->tgl_keluar,
                'jumlah_debit' => $us->jml_masuk,
                'jumlah_kredit' => $us->jml_keluar,
                'keterangan' => $us->keterangan,
            );
        }
        $data = array(
            'nama' => $user->name,
            'email' => $user->email,
            'mutasi' => $transaksi,
        );

        $response = array(
            "status" => 200,
            "data" => $data,
        );
        return response()->json($response, 400);
    }
}
