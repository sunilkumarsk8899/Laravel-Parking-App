<?php

namespace App\Http\Controllers;

use App\Models\ParkingPay;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function index(){

        return view('super_admin.index');

    }

    public function store_vehical_record(Request $request){

        $insertData = [
            'vehicle_number' => $request->vehical,
            'start_date_time' => $request->date_time,
            'end_date_time' => 'no',
            'status' => 1,
        ];

        $id = '';
        $check = Vehicle::where('vehicle_number',$request->vehical)->where('status',1)->get()->toArray();
        if($check){
            $status = 2;
            $response = 200;
            $id = $check[0]['id'];
        }else{
            $id = Vehicle::create($insertData)->id; //insert data in vehicle
            $status = ($id) ? 1 : 0;
            $response = ($id) ? 200 : 500;
            ParkingPay::create([ // vehicle relation in parking_pay
                'vehicle_id' => $id,
                'paid'       => 'no',
                'status'     => 0,
            ]);
        }
        return response()->json(['status' => $status,'response' => $response, 'id' => $id ]);

    }

    public function delete_vehical_record(Request $request){

        $vehicle = Vehicle::find($request->input('id'));
        $res = $vehicle->delete();

        $status = ($res) ? 1 : 0 ;
        $response = ($res) ? 200 : 400 ;
        return response()->json(['status' => $status,'response' => $response]);

    }

    public function pay_vehical_record(Request $request){

        $vehicle = Vehicle::find($request->input('id'));
        $vehicle->end_date_time = date('m/d/Y h:i:s a', time());
        $vehicle->status = 0;
        $res = $vehicle->save();
        if($res){
            // DB::enableQueryLog();
            $vehicle_paid = ParkingPay::where('vehicle_id',$request->input('id'))->first();
            $vehicle_paid->paid = $request->input('hour_paid');
            $vehicle_paid->message = $request->input('msg');
            $vehicle_paid->pay_status = $request->input('paid_status');
            $result = $vehicle_paid->save();
            $status = ($result) ? 1 : 0;
            $response = ($result) ? 200 : 500;
            // print_r(DB::getQueryLog());
        }
        return response()->json(['status' => $status,'reponse' => $response]);

    }

}
