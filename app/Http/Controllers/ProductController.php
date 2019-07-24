<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function shopView(){

            $category_1 = Product::join('categories', 'categories.id', '=', 'products.category_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->select('products.*', 'categories.name as category_name', 'brands.name as brand_name')
                ->where('category_id', 1)
                ->get();

             $category_2 = Product::join('categories', 'categories.id', '=', 'products.category_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->select('products.*', 'categories.name as category_name', 'brands.name as brand_name')
                ->where('category_id', 2)
                ->get();
       
            $category_3 = Product::join('categories', 'categories.id', '=', 'products.category_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->select('products.*', 'categories.name as category_name', 'brands.name as brand_name')
                ->where('category_id', 3)
                ->get();

            $category_4 = Product::join('categories', 'categories.id', '=', 'products.category_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->select('products.*', 'categories.name as category_name', 'brands.name as brand_name')
                ->where('category_id', 4)
                ->get();

        //return $product;
        return response()->json([
            "category_1" => $category_1,
            "category_2" => $category_2,
            "category_3" => $category_3,
            "category_4" => $category_4,
        ], 200);
    }

    public function index()
    {
        $order_by = Input::get('order_by');
        $sort_by = Input::get('sort_by');
        $rowsPerPage = Input::get('rowsPerPage');
        $page = Input::get('page');


            
            $product = Product::join('categories', 'categories.id', '=', 'products.category_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->select('products.*', 'categories.name as category_name', 'brands.name as brand_name')
                ->orderBy($order_by, $sort_by)->paginate($rowsPerPage);
 
            
        return $product;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->hasFile('full_image')){
            //Get filename with the extension
            //$filenameWithExt = $request->file('template')->getClientOriginalName();
            $filenameWithExt = $request->name;
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //Get just ext
            $extension = $request->file('full_image')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension; 
            //full_image link
            $filenameURL = url('storage/images', $filename.'_'.time().'.'.$extension);
            //Upload Image
            $path = $request->file('full_image')->storeAs('public/images', $fileNameToStore);
            //$path = Storage::putFileAs('template', new File('storage/documents/templates/'), $fileNameToStore);
        } else {
            $filenameURL = null;
        }

        $product = new Product;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->full_image = $filenameURL;
        $product->image = $fileNameToStore;
        $product->save();

        $returnProduct = Product::join('categories', 'categories.id', '=', 'products.category_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->select('products.*', 'categories.name as category_name', 'brands.name as brand_name')
                ->where('products.id', $product->id)->first();

        return $returnProduct;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        if($request->hasFile('full_image')){
            //DELETE OLD FILE
            $imageDelete = Product::find($id)->image;
            \File::delete('storage/images/'. $imageDelete);
            //Get filename with the extension
            //$filenameWithExt = $request->file('template')->getClientOriginalName();
            $filenameWithExt = $request->name;
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //Get just ext
            $extension = $request->file('full_image')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension; 
            //full_image link
            $filenameURL = url('storage/images', $filename.'_'.time().'.'.$extension);
            //Upload Image
            $path = $request->file('full_image')->storeAs('public/images', $fileNameToStore);
            //$path = Storage::putFileAs('template', new File('storage/documents/templates/'), $fileNameToStore);

            $product = Product::find($id);
            $product->brand_id = $request->brand_id;
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->full_image = $filenameURL;
            $product->image = $fileNameToStore;
            $product->save();
        } else {
            $product = Product::find($id);
            $product->brand_id = $request->brand_id;
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->save();
        }



        $returnProduct = Product::join('categories', 'categories.id', '=', 'products.category_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->select('products.*', 'categories.name as category_name', 'brands.name as brand_name')
                ->where('products.id', $product->id)->first();

        return $returnProduct;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $imageDelete = Product::find($id)->image;
        \File::delete('storage/images/'. $imageDelete);

        $product = Product::find($id);
        $product->delete();
    }
}
