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
        if(request()->jumlahperpage){
            $perPage = request()->jumlahperpage;
        }else{
            $perPage =10;
        }
        $products = Product::with('category')->when(request()->pencarian, function($products) {
            $products = $products->where('title', 'like', '%'. request()->pencarian . '%');
        })->latest()->paginate($perPage);

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

        $image1 = NULL;
        if($request->file('image_1')){
            $image1 = $request->file('image_1');
            $image1->storeAs('public/products', $image1->hashName());
        }

        $image2 = NULL;
        if($request->file('image_2')){
            $image2 = $request->file('image_2');
            $image2->storeAs('public/products', $image2->hashName());
        }

        $image3 = NULL;
        if($request->file('image_3')){
            $image3 = $request->file('image_3');
            $image3->storeAs('public/products', $image3->hashName());
        }

        $image4 = NULL;
        if($request->file('image_4')){
            $image4 = $request->file('image_4');
            $image4->storeAs('public/products', $image4->hashName());
        }



        //create product
        $product = Product::create([
            'image'         => $image->hashName(),
            'image_1'       => $image1 != NULL ? $image1->hashName() : NULL,
            'image_2'       => $image2 != NULL ? $image2->hashName() : NULL,
            'image_3'       => $image3  != NULL ? $image3->hashName() : NULL,
            'image_4'       => $image4 != NULL ? $image4->hashName() : NULL,
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
        $product = Product::find($id);
        $image1Url = $product->image_1;



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
            $img = $image->hashName();
        }



        if ($request->file('image_1')) {
            Storage::disk('local')->delete('public/products/'.basename($product->image_1));
            //upload new image
            $image1 = $request->file('image_1');
            $image1->storeAs('public/products', $image1->hashName());
            $img1 = $image1->hashName();
        }


        if ($request->file('image_2')) {
            Storage::disk('local')->delete('public/products/'.basename($product->image_2));
            //upload new image
            $image2 = $request->file('image_2');
            $image2->storeAs('public/products', $image2->hashName());
            $img2 = $image2->hashName();
        }

        if ($request->file('image_3')) {
            Storage::disk('local')->delete('public/products/'.basename($product->image_3));
            //upload new image
            $image3 = $request->file('image_3');
            $image3->storeAs('public/products', $image3->hashName());
            $img3 = $image3->hashName();
        }


        if ($request->file('image_4')) {
            Storage::disk('local')->delete('public/products/'.basename($product->image_4));
            //upload new image
            $image4 = $request->file('image_4');
            $image4->storeAs('public/products', $image4->hashName());
            $img4 = $image4->hashName();
        }



        $productData = [
            'title'         => $request->title,
            'slug'          => Str::slug($request->title, '-'),
            'category_id'   => $request->category_id,
            'user_id'       => auth()->guard('api_admin')->user()->id,
            'description'   => $request->description,
            'weight'        => $request->weight,
            'price'         => $request->price,
            'stock'         => $request->stock,
            'discount'      => $request->discount
        ];

        if ($request->file('image')) {
            $productData['image'] = $img;
        }

        if ($request->file('image_1')) {
            $productData['image_1'] = $img1;
        }

        if ($request->file('image_2')) {
            $productData['image_2'] = $img2;
        }

        if ($request->file('image_3')) {
            $productData['image_3'] = $img3;
        }

        if ($request->file('image_4')) {
            $productData['image_4'] = $img4;
        }


        // Update data produk
        $product->update($productData);




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
