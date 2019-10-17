<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderProduct;
use Carbon;

class OrderController extends Controller
{
    public function store(Request $request)
    {
    	$totalAmmount = 0;
        foreach ($request->products as $key => $value) {
            $totalAmmount = $value['quantity'] * $value['price'] + $totalAmmount;

        }

    	$order = new Order;
    	$order->first_name = $request->first_name;
    	$order->last_name = $request->last_name;
    	$order->street = $request->street;
    	$order->email = $request->email;
    	$order->mobile = $request->mobile;
    	$order->city_mun = $request->city_mun;
    	$order->state_prov = $request->state_prov;
    	$order->country = $request->country;
    	$order->zip_code = $request->zip_code;
    	$order->total = $totalAmmount;
    	$order->save();

    	$products = [];
        foreach ($request->products as $key => $value) {
            $products[] = [
                'order_id' => $order->id, 
                'product_id' => $value['product_id'],
                'quantity' => $value['quantity'],
                'price' => $value['price'],
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
            ];
        }
        
        OrderProduct::insert($products);
        
        return Order::where('id', $order->id)->with('products')->first();
    }
}
