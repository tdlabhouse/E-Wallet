<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LambungKapalController extends Controller
{
    //
    public function lambungKapal(Request $request)
    {
        //
        if (strlen($request->nomor_kontainer) < 7) {
            return "Nomor Kontainer kurang dari 7 angka";
        } else if (strlen($request->nomor_kontainer) > 7) {
            return "Nomor Kontainer dari 7 angka";
        }

        function cekprima($num)
        {
            $dibagi = 0;
            for ($i = 1; $i <= $num; $i++) {
                if ($num % $i == 0) {
                    $dibagi++;
                }
            }

            if ($dibagi == 2) {
                return "Bilangan Prima";
            } else {
                return "Bukan Bilangan Prima";
            }
        }

        function cekAdaNol($num)
        {
            if (strpos($num, '0')) {
                return "Ada Nol";
            } else {
                return "Tidak ada Nol";
            }
        }

        function cekTengah($num)
        {
            $numEdit = substr($num, 3);
            if (cekprima($numEdit)) {
                return "Cek Tengah Benar";
            } else {
                return "Cek Tengah Salah";
            }
        }

        function cekKanan($num)
        {
            $numEdit = strval(substr($num, 4));
            $numEdit2 = strval(substr($num, -1, 1));
            for ($j = 0; $j <= 2; $j++) {
                if ($numEdit[$j] != $numEdit2) {
                    return "3 digit terahir tidak sama";
                }
            }
            return "3 digit akhir sama";
        }

        function cekKiri($num)
        {
            $numEdit1 = strval(substr($num, -1));
            $numEdit2 = strval(substr($num, -2, 1));
            $lastNumber = (int)$numEdit1 - 1;
            if ($numEdit2 != strval($lastNumber)) {
                return "2 Angka akhir tidak berurutan";
            } else {
                return "2 Angka akhir berurutan";
            }
        }
        $input = $request->nomor_kontainer;

        $result = "";
        if (cekprima($input) == "Bilangan Prima" && cekAdaNol($input) == "Tidak ada Nol") {
            if (cekKanan($input) == "3 digit akhir sama") {
                $result = "Right";
            } else if (cekKiri($input) == "2 Angka akhir berurutan") {
                $result = "Left";
            } else if (cekTengah($input) == "Cek Tengah Benar") {
                $result = "Central";
            }
        } else if (cekprima($input) == "Bukan Bilangan Prima" && cekAdaNol($input) == "Ada Nol") {
            $result = "Dead";
        } else {
            if (strlen($input) < 7) {
                $result = "Inputan kurang dari 7 angka";
            } else if (strlen($input) > 7) {
                $result = "Inputan lebih dari 7 angka";
            } else {
                $result = "Dead - Tapi tidak mengandung angka 0";
            }
        }

        $data = array(
            'Input' => $input,
            'Output' => $result,
        );

        return $data;
    }
}
