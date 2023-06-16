<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemsUsed;
use Illuminate\Http\Request;

class OutfitController extends Controller
{
    public function addItemsUsed(Request $request){
        $item_used = ItemsUsed::create([
            'outfits_orders_id' => $request->outfit_order_id,
            'item_id' => $request->item_id,
            'qty' => $request->qty,
            'unit_cost' => $request->unit_cost,
            'amount' => $request->amount,
        ]);
        //reduce in inventory
        $item = Item::find($item_used->item_id);
        $item->inventory_quantity -= $item_used->qty;
        $item->save();
        return redirect('orders/'.$item_used->outfit->order->id)->with('status', 'Added Successfully');
    }
}
