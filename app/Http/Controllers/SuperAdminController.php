<?php

namespace App\Http\Controllers;

use App\Models\ParkingPay;
use App\Models\Vehicle;
use DateTime;
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

    public function exit_parking(){

        $data = Vehicle::where('status',1)->get();
        return view('super_admin.exit_parking',compact('data'));

    }

    public function exit_parking_paid(Request $request){

        $data = Vehicle::find($request->input('id'));
        $timestamp1 = strtotime($request->input('date_time'));
        $timestamp2 = strtotime( $data->start_date_time);

        // Create DateTime objects
        $date1 = new DateTime();
        $date2 = new DateTime();

        // Set the timestamps for the DateTime objects
        $date1->setTimestamp($timestamp1);
        $date2->setTimestamp($timestamp2);

        // Calculate the difference
        $interval = $date1->diff($date2);

        $hour = $interval->h*50;
        $min = $interval->i;
        $final_amount = $hour+$min;


        if($request->input('action') == 'get_info'){ // get info vehicle
            $status = 1;
            $view = view('super_admin.components.get_info_vehicle',compact('data','hour','min','final_amount'))->render();
            return response()->json([ 'h' => $hour, 'm' => $min, 'amount' => $final_amount, 'status' => $status, 'action' => 'getInfo', 'html' => $view  ]);
        }else{ // parking paid
            $data->end_date_time = date('m/d/Y h:i:s a', time());
            $data->status = 0;
            $res = $data->save();
            if($res){
                $vehicle_paid = ParkingPay::where('vehicle_id',$request->input('id'))->first();
                $vehicle_paid->paid = $final_amount;
                $vehicle_paid->message = 'on time paid';
                $vehicle_paid->pay_status = 1;
                $result = $vehicle_paid->save();
                $status = ($result) ? 1 : 0;
            }
            return response()->json([ 'status' => $status, 'action' => 'pay' ]);
        }
        // Output the difference
        // echo "Difference: " . $interval->format('%d days, %h hours, %i minutes');


    }

}
