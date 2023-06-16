<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use Auth;

class AppSettingsController extends Controller
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
        $appSetting = AppSetting::where('user_id', Auth::user()->id)->first();
        return view('admin.settings.show')->with('setting',$appSetting);
    }

    public function showUpdateMeasurementSettingsForm()
    {
        //$appSetting = AppSetting::where('user_id', Auth::user()->id)->first();
        return view('admin.settings.measurements.show');
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
        if (AppSetting::where('user_id', Auth::user()->id)->exists()) {
            $appSetting = AppSetting::where('user_id', Auth::user()->id)->first();
            return view('admin.settings.setup.business-info')->with('setting',$appSetting);
        }
        $appSetting = new AppSetting;
        
        $appSetting->measurement_details = $encoded_M_details;
        $appSetting->user_id = Auth::user()->id;
        $appSetting->save();
        return view('admin.settings.setup.business-info')->with('setting',$appSetting);
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
        
        $appSetting = AppSetting::where('user_id', Auth::user()->id)->first();
        
        $appSetting->measurement_details = $encoded_M_details;
        //$appSetting->user_id = Auth::user()->id;
        $appSetting->save();
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
        $setting = AppSetting::find($id);
        return view('admin.settings.show')->with('setting',$setting);
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
        $setting = AppSetting::find($id);
        
        $setting->business_name = $request->business_name;
        $setting->business_address = $request->business_address;
        $setting->business_phone = $request->business_phone;
        $setting->business_payment_advice = $request->business_payment_advice;
        $setting->sms_api_key = $request->sms_api_key;
        $setting->sms_api_username = $request->sms_api_username;
        $setting->sms_sender = $request->sms_sender;

        if($request->hasFile('business_logo'))
            {
                $allowedfileExtension=['jpeg','jpg','png'];
            
                $name = $request->file('business_logo')->getClientOriginalName();
                $extension = $request->business_logo->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);
                if($check){
                    $path = $request->file('business_logo')->store('public/images/logo_images');
                    
                    $setting->business_logo = $path;
                }
                
            }

        $setting->save();
        if($request->origin == 'setup'){
            return redirect('/home')->with('status','Setings updated. Welcome to the Tailor App');
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
        
        $setting->email_for_notifications = $request->email;

    }
}
