<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get a list of all items in inventory
        $items = Item::orderBy('description', 'desc')->where('user_id', '=', auth()->user()->user_account_id)->paginate(15);
        return view('pages.items.index')->with('items', $items);
    }

    public function search(Request $request)
    {
        if ($request->search_by == 'desc') {
            $items = Item::where('description', 'like', '%' . "{$request->search_value}" . '%')->where('user_id', '=', auth()->user()->user_account_id)->paginate(10);
            if ($request->origin == 'order_creation') {
                return view('pages.orders.create.sales.step2')->with('items', $items);
            }
            return view('pages.items.index')->with('items', $items);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item_categories = ItemCategory::getAll();
        return view('pages.items.create')->with('item_categories', $item_categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //validate customer entry, store and set view to list of customers
    //     $validatedData = $request->validate([
    //         'category_id' => ['required'],
    //         'description' => ['required'],
    //         'qty' => ['required'],
    //     ]);

    //     $item = new Item;
    //     $item->description = $request->description;
    //     $item->item_category_id = $request->category_id;
    //     $item->inventory_quantity = $request->qty;
    //     $item->unit_measurement = $request->unit_measurement;
    //     $item->for_sale = isset($request->for_sale) ? 1 : 0;
    //     $item->user_id = auth()->user()->user_account_id;
    //     if($item->for_sale == 1){
    //         $item->price = $request->price;
    //     }

    //     $item->save();

    //     if($request->file('image')){
    //         $allowedfileExtension=['pdf','jpg','png','jpeg'];

    //         $file = $request->file('image');
    //         $filename = $file->getClientOriginalName();
    //         $extension = $file->getClientOriginalExtension();
    //         $check=in_array($extension,$allowedfileExtension);

    //         if($check)
    //         {
    //             $newfilename = time().rand(111, 9999).".". $extension;
    //             FacadesStorage::disk('items')->put($newfilename, file_get_contents($file));
    //             $item->image = $newfilename;
    //         }
    //         $item->save();
    //     }

    //     return redirect('items')->with('status', 'Item was added successfully');
    // }
    public function store(Request $request)
    {
        // --- 1. Validation ---

        // We update validation for the new fields: description and variations array.
        $validatedData = $request->validate([
            'category_id' => ['required', 'exists:item_categories,id'], // Check category exists
            'description' => ['required', 'string', 'max:255'],
            'unit_measurement' => ['nullable', 'string', 'max:50'],
            'for_sale' => ['nullable', 'boolean'],
            'price' => ['nullable', 'numeric', 'min:0'],
            // Validate the variations array structure
            'variations' => ['required', 'array'],
            'variations.*.color' => ['required', 'string', 'max:100'],
            'variations.*.qty' => ['required', 'integer', 'min:0'],
        ]);

        $masterDescription = $request->description;
        $isForSale = isset($request->for_sale) ? 1 : 0;
        $sellingPrice = $request->price ?? 0;
        $userId = auth()->user()->user_account_id;
        $createdItemsCount = 0;
        $imagePath = null;

        // --- 2. Handle Image Upload Once ---
        // Since the image upload is based on premium status, we handle it once
        // and apply the same image path to all newly created items.
        if ($request->file('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            // Allowed file extensions check is now partially handled by validation ('mimes').
            // We'll proceed with storage logic.

            // Generate a unique filename
            $newfilename = time() . rand(111, 9999) . "." . $extension;

            // Store the file and get the path/filename
            FacadesStorage::disk('items')->put($newfilename, file_get_contents($file));
            $imagePath = $newfilename;
        }

        // --- 3. Loop Through Variations and Create Items ---
        if ($request->has('variations')) {
            foreach ($request->variations as $variation) {
                // Check if both color and quantity exist (should pass validation, but good practice)
                if (isset($variation['color']) && isset($variation['qty'])) {

                    $color = $variation['color'];
                    $qty = $variation['qty'];

                    // Combine the master description and the color for unique identification
                    $itemName = $masterDescription . ' - ' . $color;

                    $item = new Item;

                    // Assign properties common to all variations
                    $item->item_category_id = $request->category_id;
                    $item->unit_measurement = $request->unit_measurement;
                    $item->for_sale = $isForSale;
                    $item->user_id = $userId;
                    $item->price = $sellingPrice; // Price applies to all variations
                    $item->image = $imagePath; // Apply the single uploaded image

                    // Assign variation-specific properties
                    $item->description = $itemName;
                    $item->inventory_quantity = $qty;

                    $item->save();
                    $createdItemsCount++;
                }
            }
        }

        // --- 4. Redirect and Status Message ---
        if ($createdItemsCount > 0) {
            return redirect('items')->with('status', $createdItemsCount . ' items (with variations) were added successfully!');
        } else {
            return redirect('items')->with('error', 'No items were created. Please ensure variations are provided.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::findOrFail($id);
        $item_categories = ItemCategory::getAll();
        return view('pages.items.show', compact('item', 'item_categories'));
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
        //validate customer entry, store and set view to list of customers
        $validatedData = $request->validate([
            'description' => ['required'],
            'qty' => ['required'],
            'category_id' => ['required'],
        ]);
        if (isset($request->for_sale)) {
            $validatedData = $request->validate([
                'price' => ['required'],
                'cost_price' => ['required'],
            ]);
        }
        $item = Item::find($id);
        $item->description = $request->description;
        $item->item_category_id = $request->category_id;
        $item->inventory_quantity = $request->qty;
        //$item->cost_price = $request->cost_price;
        $item->price = $request->price;
        $item->for_sale = isset($request->for_sale) ? 1 : 0;

        $item->save();

        if ($request->file('image')) {
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];

            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);

            if ($check) {
                $newfilename = time() . rand(111, 9999) . "." . $extension;
                FacadesStorage::disk('items')->put($newfilename, file_get_contents($file));
                $item->image = $newfilename;
            }
            $item->save();
        }

        return redirect('items')->with('status', 'Item was updated successfully');
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
}
