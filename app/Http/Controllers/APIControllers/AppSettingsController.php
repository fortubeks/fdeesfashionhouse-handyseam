<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Customer;
use Storage;

class SettingsController extends Controller
{

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

        $appSetting = Setting::where('user_id', auth()->user()->id)->first();
        
        $appSetting->measurement_details = $encoded_M_details;
        $appSetting->measurement_set = 1;
        $appSetting->save();
        
        return response()->json($appSetting, 201);
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

        return response()->json($appSetting, 200);
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
        return response()->json($setting, 200);
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
        return response()->json($setting, 200);
    }

}
