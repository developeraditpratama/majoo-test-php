<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class TransactionController extends Controller
{
    public function getAuthUser() {
        try {

            $user_id = auth()->guard('api')->user()->id;

        } catch (\Exception $e) {
            echo "Maaf : ".$e->getMessage()." <br/>"."Harap Login Kembali dan mengganti bearer Token";
        }

        return $user_id;


//        if ($user_id != null) {
//            return $user_id;
//        }
//        return response()->json([
//            'code' => '408',
//            'message' => 'Token Timeout',
//        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Transaction::all();
    }

    public function laporanAllMerchant () {

        $merchant_id = DB::table('merchants')->where('user_id', '=', $this->getAuthUser())->value('id');

        $begin = new DateTime( "2021-11-1" );
        $end   = new DateTime( "2021-11-30" );

        for($i = $begin; $i <= $end; $i->modify('+1 day')){

            $query = Transaction::where('merchant_id', '=', $merchant_id)
                ->select(['merchant_name', DB::raw('SUM(bill_total) as total_bill')])
                ->join('merchants', 'transactions.merchant_id', '=', 'merchants.id')
                ->groupBy('transactions.merchant_id')
                ->whereDate('transactions.created_at', '=', $i->format("Y-m-d"))->get()->toArray();

            if ($query == null) {
                $array[] = array(
                    "date"=> $i->format("Y-m-d"),
                    "merchant_name"=> "Tidak ada transaksi",
                    "total_bill"=> 0,
                );
            } else {
                $array[] = array(
                    "date"=> $i->format("Y-m-d"),
                    "merchant_name"=> $query[0]['merchant_name'],
                    "total_bill"=> $query[0]['total_bill'],
                );
            }
        }
        return response()->json([
            'code' => '200',
            'message' => 'Data Berhasil Ditampilkan',
            'data' => $array,
        ]);
    }

    public function laporanAllOutlet () {
        $merchant_id = DB::table('merchants')->where('user_id', '=', $this->getAuthUser())->value('id');

        $begin = new DateTime( "2021-11-1" );
        $end   = new DateTime( "2021-11-30" );

        for($i = $begin; $i <= $end; $i->modify('+1 day')){

            $query = Transaction::where('transactions.merchant_id', '=', $merchant_id)
                ->select(['merchant_name', 'outlet_name', DB::raw('SUM(bill_total) as total_bill')])
                ->join('merchants', 'transactions.merchant_id', '=', 'merchants.id')
                ->join('outlets', 'transactions.outlet_id', '=', 'outlets.id')
                ->groupBy('transactions.outlet_id')
                ->whereDate('transactions.created_at', '=', $i->format("Y-m-d"))->get()->toArray();

            if ($query == null) {
                $array[] = array(
                    "date"=> $i->format("Y-m-d"),
                    "merchant_name"=> "Tidak ada transaksi",
                    "outlate_name"=> "Tidak ada transaksi",
                    "total_bill"=> 0,
                );
            } else {
                $array[] = array(
                    "date"=> $i->format("Y-m-d"),
                    "merchant_name"=> $query[0]['merchant_name'],
                    "outlet_name"=> $query[0]['outlet_name'],
                    "total_bill"=> $query[0]['total_bill'],
                );
            }
        }
        return response()->json([
            'code' => '200',
            'message' => 'Data Berhasil Ditampilkan',
            'data' => $array,
        ]);
    }
}
