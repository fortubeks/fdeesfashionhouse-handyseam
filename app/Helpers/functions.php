<?php

use App\Models\Country;
use Magarrent\LaravelCurrencyFormatter\Facades\Currency;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

function formatCurrency($amount){
    $user = User::find(auth()->user()->user_account_id);
    $business_currency = $user->app_settings->business_currency ?: "NGN";
    return 'NGN '.number_format($amount,2,".",",");
    if($business_currency == 'GHS'){
        return 'GHc '.number_format($amount,2,".",",");
    }
    return Currency::currency($business_currency)->format($amount);
}
function ListofOrderStyleImagesWithFileUrl($data){
    $list = array();
    $style_images = explode(',',$data);
    if(count($style_images)>=1){
        foreach($style_images as $style_image){
            $titles = explode('=',$style_image);
            if(count($titles)>1){
                array_push($list, array($titles[0],$titles[1]));
            }    
        }
    }
    
    return $list;
}
function getModelList($model){
    $model_list = null;
    if($model == 'expense-categories'){
        $model_list = auth()->user()->user_account->expense_categories()->orWhere('user_id',0)->get();
    }
    if($model == 'item-categories'){
        $model_list = auth()->user()->user_account->item_categories()->orWhere('user_id',0)->get();
    }
    if($model == 'customers'){
        $model_list = auth()->user()->user_account->customers;
    }
    if($model == 'items'){
        $model_list = auth()->user()->user_account->items;
    }
    if($model == 'inventory'){
        $model_list = auth()->user()->user_account->items()->where('for_sale',0)->get();
    }
    if($model == 'countries'){
        $model_list = Country::all();
    }
    return $model_list;
}

if (! function_exists('divnum')) {

    function divnum($numerator, $denominator)
    {
        return $denominator == 0 ? 0 : ($numerator / $denominator);
    }

}

function paginate($items, $perPage = 5, $page = null, $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
}

function removeSpaces($inputString) {
    return str_replace(' ', '', $inputString);
}

function sendwhatsappnotification($type,$to,$template_name,$expected_delivery_date,$order_id) {
    try {
        $url = "https://graph.facebook.com/v17.0/108752848993090/messages";
    
        $client = new \GuzzleHttp\Client();
        $headers = ["Authorization" => "Bearer " . env('WHATSAPP_TOKEN'), "Content-Type" => "application/json" ];
        $params = ["messaging_product" =>  "whatsapp", "to" => $to, "type" => "template",
        "template" => ["name" => $template_name, "language" => ["code" => "en_US"]],];

        if($type == "new_order"){
            $arr = array(
                "type" => "text",
                "text" => $expected_delivery_date
            );
            $arr1 =array(
                "type" => "text",
                "text" => auth()->user()->user_account->app_settings->business_name
            );
            $arr2 = array(
                "type" => "text",
                "text" => "/".$order_id
            );
    
            $myJSON = json_encode($arr);
            $myJSON2 = json_encode($arr1);
            $myJSON3 = json_encode($arr2);
        
            $params = [
                "messaging_product" =>  "whatsapp",
                "to" => $to,
                "type" => "template",
                "template" => [
                    "namespace" => "46e87f57_c607_4e9f_b8f5_a8700ba35edb",
                    "language" => [
                        "code" => "en_US"
                    ],
                    "name" => $template_name,
                    "components" => [
                        
                        [
                            "type" => "body",
                            "parameters" => [
                                $myJSON,$myJSON2
                            ] 
                        # end body
                        ],
            
                        # The following part of this code example includes several possible button types, 
                        # not all are required for an interactive message template API call.
                        
                        [
                            "type" => "button",
                            "sub_type" => "url",
                            "index" => "0", 
                            "parameters" => [
                                $myJSON3
                            ]
                        ],
                        
                    ]
                ]
            ];
        }
        
    
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, ["headers" => $headers, "form_params" => $params]);
        
        $data = $response->getBody();
        return $data;
    } catch (Exception $ex) {
        //throw $th;
    }
   
}