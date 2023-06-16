<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Storage;

class SettingsController extends Controller
{
    public function __construct()
    {
       
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appSetting = Setting::where('user_id', auth()->user()->id)->first();
        return view('pages.settings.show')->with('setting',$appSetting);
    }

    public function showUpdateMeasurementSettingsForm()
    {
        //$appSetting = AppSetting::where('user_id', Auth::user()->id)->first();
        return view('pages.settings.measurements.show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }
    function createUrlSlug($urlString){
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $urlString);
        return $slug;
     }
     function slugify($urlString) {
        $search = array('Ș', 'Ț', 'ş', 'ţ', 'Ş', 'Ţ', 'ș', 'ț', 'î', 'â', 'ă', 'Î', ' ', 'Ă', 'ë', 'Ë');
        $replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', '_', 'a', 'e', 'E');
        $str = str_ireplace($search, $replace, strtolower(trim($urlString)));
        $str = preg_replace('/[^\w\d\-\ ]/', '', $str);
        $str = str_replace(' ', '_', $str);
        return preg_replace('/[^A-Za-z0-9-]+/', '_', $str);
    }
     
    public function saveMeasurement(Request $request)
    {
        $validatedData = $request->validate([
            'measurement_details' => ['required'],
        ]);
        $m_details = array();
        foreach($request->measurement_details as $key=>$measurement_detail)
        {
            //slug each detail
            $slugged_detail = $this->slugify($measurement_detail);
            //add all to json string
            //save json string
            $m_details[$slugged_detail] = $measurement_detail;
            
        }
        $encoded_M_details = json_encode($m_details);
        // if (AppSetting::where('user_id', Auth::user()->id)->exists()) {
        //     $appSetting = AppSetting::where('user_id', Auth::user()->id)->first();
        //     return view('pages.settings.setup.business-info')->with('setting',$appSetting);
        // }
        $appSetting = Setting::where('user_id', auth()->user()->id)->first();
        
        $appSetting->measurement_details = $encoded_M_details;
        $appSetting->measurement_set = 1;
        $appSetting->save();
        if($request->has('origin') && $request->has('customer_id')){
            $customer = Customer::findOrFail($request->customer_id);
            
            $view = 'pages.orders.create.tailoring.step2';
            return view($view)->with('customer', $customer);
        }
        return redirect('/settings')->with('status','Settings saved successfully');
    }

    public function updateMeasurement(Request $request)
    {
        $validatedData = $request->validate([
            'measurement_details' => ['required'],
        ]);
        $m_details = array();
        foreach($request->measurement_details as $key=>$measurement_detail)
        {
            //slug each detail
            $slugged_detail = $this->slugify($measurement_detail);
            //add all to json string
            //save json string
            $m_details[$slugged_detail] = $measurement_detail;
            
        }
        $encoded_M_details = json_encode($m_details);
        
        $appSetting = Setting::where('user_id', auth()->user()->id)->first();
        
        $appSetting->measurement_details = $encoded_M_details;
        //$appSetting->user_id = Auth::user()->id;
        $appSetting->measurement_set = 1;
        $appSetting->save();
        if($request->has('origin') && $request->has('customer_id')){
            $customer = Customer::findOrFail($request->customer_id);
            
            $view = 'pages.orders.create.tailoring.step2';
            return view($view)->with('customer', $customer);
        }
        return redirect('/settings')->with('status','Measurement Details Updated');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = Setting::find($id);
        return view('pages.settings.show')->with('setting',$setting);
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
        $validatedData = $request->validate([
        'business_name' => ['required'],
        ]);
        $setting = Setting::find($id);
        $this->authorize(auth()->user(),  $setting);
        $setting->business_name = $request->business_name;
        $setting->business_address = $request->business_address;
        $setting->business_phone = $request->business_phone;
        $setting->business_payment_advice = $request->business_payment_advice;
        $setting->business_currency = $request->business_currency;
        $setting->business_focus = $request->business_focus;
        $setting->vat = $request->business_vat;
        if($setting->vat == null){
            $setting->vat = 0;
        }
        $setting->sms_sender = substr($request->business_name, 0, 11);
        if($request->origin == 'setup' || $setting->measurement_details == ''){
            
        }
        if($request->hasFile('business_logo'))
            {
                $allowedfileExtension=['jpeg','jpg','png'];
            
                $name = $request->file('business_logo')->getClientOriginalName();
                $extension = $request->business_logo->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);
                if($check){
                    $newfilename = time().rand(111, 9999).".". $extension;
                    Storage::disk('logo_images')->put($newfilename, file_get_contents($request->file('business_logo')));
                    
                    $setting->business_logo = $newfilename;
                }
                
            }

        $setting->save();
        if($request->origin == 'setup'){
            return redirect('/home')->with('status','Setings updated. Welcome to the HandySeam');
        }

        return redirect('/settings')->with('status','Settings Updated');
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
    public function loadSettingsInSession(){
        $setting = Setting::find(1);
        
        //$setting->email_for_notifications = $request->email;

    }
}
