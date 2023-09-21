<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get products
        $products = Product::with('category')->when(request()->q, function($products) {
            $products = $products->where('title', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);
        
        //return with Api Resource
        return new ProductResource(true, 'List Data Products', $products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'title'         => 'required|unique:products',
            'category_id'   => 'required',
            'description'   => 'required',
            'weight'        => 'required',
            'price'         => 'required',
            'stock'         => 'required',
            'discount'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        if($request->file('image_1')){
            $image1 = $request->file('image_1');
            $image1->storeAs('public/products', $image1->hashName());
        }

        if($request->file('image_2')){
            $image2 = $request->file('image_2');
            $image2->storeAs('public/products', $image2->hashName());
        }

        if($request->file('image_3')){
            $image3 = $request->file('image_3');
            $image3->storeAs('public/products', $image3->hashName());
        }

        if($request->file('image_4')){
            $image4 = $request->file('image_4');
            $image4->storeAs('public/products', $image4->hashName());
        }



        //create product
        $product = Product::create([
            'image'         => $image->hashName(),
            'image_1'       => $image1->hashName() ?? NULL,
            'image_2'       => $image2->hashName() ?? NULL,
            'image_3'       => $image3->hashName() ?? NULL,
            'image_4'       => $image4->hashName() ?? NULL,
            'title'         => $request->title,
            'slug'          => Str::slug($request->title, '-'),
            'category_id'   => $request->category_id,
            'user_id'       => auth()->guard('api_admin')->user()->id,
            'description'   => $request->description,
            'weight'        => $request->weight,
            'price'         => $request->price,
            'stock'         => $request->stock,
            'discount'      => $request->discount
        ]);

        if($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Disimpan!', $product);
        }

        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Disimpan!', null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::whereId($id)->first();
        
        if($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Detail Data Product!', $product);
        }

        //return failed with Api Resource
        return new ProductResource(false, 'Detail Data Product Tidak Ditemukan!', null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|unique:products,title,'.$product->id,
            'category_id'   => 'required',
            'description'   => 'required',
            'weight'        => 'required',
            'price'         => 'required',
            'stock'         => 'required',
            'discount'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        //check image update
        if ($request->file('image')) {
            Storage::disk('local')->delete('public/products/'.basename($product->image));
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());
        }

        if ($request->file('image_1')) {
            Storage::disk('local')->delete('public/products/'.basename($product->image_1));
            //upload new image
            $image1 = $request->file('image_1');
            $image1->storeAs('public/products', $image1->hashName());
        }

        if ($request->file('image_2')) {
            Storage::disk('local')->delete('public/products/'.basename($product->image_2));
            //upload new image
            $image2 = $request->file('image_2');
            $image2->storeAs('public/products', $image2->hashName());
        }

        if ($request->file('image_3')) {
            Storage::disk('local')->delete('public/products/'.basename($product->image_3));
            //upload new image
            $image3 = $request->file('image_3');
            $image3->storeAs('public/products', $image3->hashName());
        }

        if ($request->file('image_4')) {
            Storage::disk('local')->delete('public/products/'.basename($product->image_4));
            //upload new image
            $image4 = $request->file('image_4');
            $image4->storeAs('public/products', $image4->hashName());
        }


        $product->update([
            'image'         => $image->hashName() ?? $product->image,
            'image_1'       => $image1->hashName() ?? $product->image_1,
            'image_2'       => $image2->hashName() ?? $product->image_2,
            'image_3'       => $image3->hashName() ?? $product->image_3,
            'image_4'       => $image4->hashName() ?? $product->image_4,
            'title'         => $request->title,
            'slug'          => Str::slug($request->title, '-'),
            'category_id'   => $request->category_id,
            'user_id'       => auth()->guard('api_admin')->user()->id,
            'description'   => $request->description,
            'weight'        => $request->weight,
            'price'         => $request->price,
            'stock'         => $request->stock,
            'discount'      => $request->discount
        ]);

       
     

        if($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Diupdate!', $product);
        }

        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //remove image
        Storage::disk('local')->delete('public/products/'.basename($product->image));
        Storage::disk('local')->delete('public/products/'.basename($product->image_1));
        Storage::disk('local')->delete('public/products/'.basename($product->image_2));
        Storage::disk('local')->delete('public/products/'.basename($product->image_3));
        Storage::disk('local')->delete('public/products/'.basename($product->image_4));

        if($product->delete()) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Dihapus!', null);
    }
}
