<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Measurement;
use App\Models\Customer;
use Auth;

class MeasurementsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id);
        $view = Auth::user()->user_type . '.measurements.create';
        return view($view)->with('customer',$customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate customer entry, store and set view to list of customers
        $validatedData = $request->validate([
            'customer_id' => ['required'],
        ]);
        
        $measurement = new Measurement;
        $measurement->bust = $request->bust;
        $measurement->waist1 = $request->waist1;
        $measurement->waist2 = $request->waist2;
        $measurement->hips = $request->hips;
        $measurement->shoulder_length = $request->shoulder_length;
        $measurement->chest_width = $request->chest_width;
        $measurement->back_width = $request->back_width;
        $measurement->armhole = $request->armhole;
        $measurement->upper_arm = $request->upper_arm;
        $measurement->front_bodice_length = $request->front_bodice_length;
        $measurement->front_length = $request->front_length;
        $measurement->sleeve_length = $request->sleeve_length;
        $measurement->back_bodice = $request->back_bodice;
        $measurement->side_dart = $request->side_dart;
        $measurement->front_skirt_length = $request->front_skirt_length;
        $measurement->full_length = $request->full_length;
        $measurement->around_knee = $request->around_knee;
        $measurement->waist_to_hip = $request->waist_to_hip;
        $measurement->shoulder_to_hip = $request->shoulder_to_hip;
        $measurement->around_chest = $request->around_chest;
        $measurement->empire_length = $request->empire_length;
        $measurement->empire_width = $request->empire_width;
        $measurement->bust_point = $request->bust_point;
        $measurement->customer_id = $request->customer_id;

        $measurement->save();

        if($request->origin == 'order_creation'){
            $view = Auth::user()->user_type . '.orders.create.tailoring.step3';
            return view($view);
        }
        
        return redirect('customers')->with('status', 'Customer measurement was saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        //if admin has not set measurement, take customer to set measurement
        //set origin to create order if it was from create order origin
        // if(auth()->user()->app_settings->measurement_set == 0){
        //     //redirect to settings page
        //     return view('pages.settings.setup.measurements')->with('status','Please setup your measurement parameters first');
        // }
        // if
        // // {"bust":"Bust","waist_1":"Waist 1","waist_2":"Waist 2","hips":"Hips","shoulder_length":"Shoulder Length",
        // //     "chest_width":"Chest Width","arm_hole":"Arm Hole","front_bodice":"Front Bodice","back_width":"Back Width",
        // //     "front_length":"Front Length","sleeve_length":"Sleeve Length","back_bodice":"Back Bodice","side_dart":"Side Dart",
        // //     "front_skirt_length":"Front Skirt Length","full_length":"Full Length","around_knee":"Around Knee",
        // //     "waist_to_hip":"Waist To Hip","shoulder_to_hip":"Shoulder To Hip","empire_length":"Empire Length",
        // //     "empire_width":"Empire Width","around_chest":"Around Chest","upper_arm":"Upper Arm"}
        if($customer->measurement_details == null){
            $array = json_decode(auth()->user()->user_account->app_settings->measurement_details, true);
            // Fetch corresponding data from the database using Eloquent
            $data = $customer->measurement;

            // Update the value in the array with the database value
            if ($data) {
                foreach ($array as $key => &$item) {
                    $array[$key] = $data->$key;
                }
            }
        // Encode the updated array back to a JSON string
        $updatedJsonString = json_encode($array);
        $customer->measurement_details = $updatedJsonString;
        $customer->save();
        }
        $view = 'pages.measurements.show';
        return view($view)->with('customer',$customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $measurement = Measurement::find($id);

        $measurement->bust = $request->bust;
        $measurement->waist1 = $request->waist1;
        $measurement->waist2 = $request->waist2;
        $measurement->hips = $request->hips;
        $measurement->shoulder_length = $request->shoulder_length;
        $measurement->chest_width = $request->chest_width;
        $measurement->back_width = $request->back_width;
        $measurement->armhole = $request->armhole;
        $measurement->upper_arm = $request->upper_arm;
        $measurement->front_bodice_length = $request->front_bodice_length;
        $measurement->front_length = $request->front_length;
        $measurement->sleeve_length = $request->sleeve_length;
        $measurement->back_bodice = $request->back_bodice;
        $measurement->side_dart = $request->side_dart;
        $measurement->front_skirt_length = $request->front_skirt_length;
        $measurement->full_length = $request->full_length;
        $measurement->around_knee = $request->around_knee;
        $measurement->waist_to_hip = $request->waist_to_hip;
        $measurement->shoulder_to_hip = $request->shoulder_to_hip;
        $measurement->around_chest = $request->around_chest;
        $measurement->empire_length = $request->empire_length;
        $measurement->empire_width = $request->empire_width;
        $measurement->bust_point = $request->bust_point;

        $measurement->save();

        if($request->origin == 'order_creation'){
            
            $view = Auth::user()->user_type . '.orders.create.tailoring.step3';
            return view($view);
        }
        return redirect('customers/'.$measurement->customer_id)->with('status', 'Customer Measurement was updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function printMeasurement($customer_id){
        // $measurement = Measurement::findOrFail($measurement_id);
        // $customer = Customer::findOrFail($measurement->customer_id);

        // $printController = new PrintController();
        // return $printController->printMeasurement($measurement, $customer);
        // // $pdf = App::make('dompdf.wrapper');
        // // $pdf->loadHTML($this->a4html);
        // // return $pdf->stream();
        $customer = Customer::findOrFail($customer_id);
		return view('pages.measurements.print-measurement')->with(compact('customer'));
    }
}
