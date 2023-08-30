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

function sendwhatsappmessage($to,$template_name) {
    try {
        $url = "https://graph.facebook.com/v17.0/108752848993090/messages";
    
        $client = new \GuzzleHttp\Client();
        $headers = ["Authorization" => "Bearer " . env('WHATSAPP_TOKEN'), "Content-Type" => "application/json" ];
        $params = ["messaging_product" =>  "whatsapp", "to" => $to, "type" => "template",
        "template" => ["name" => $template_name, "language" => ["code" => "en_US"]],];
    
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, ["headers" => $headers, "form_params" => $params]);
        
        $data = $response->getBody();
        return $data;
    } catch (Exception $ex) {
        //throw $th;
    }
   
}