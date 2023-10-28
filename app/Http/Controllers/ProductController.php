<?php

namespace App\Http\Controllers;

use App\DataTables\ProductsDataTable;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;



class ProductController extends Controller
{

    // public function records(ProductsDataTable $dataTable){
    //     return $dataTable->render('records');
    // }
    public function add_product(Request $request){
   
        $request->file('product_image');
        $file_name = time().".".$request->file('product_image')->getClientOriginalExtension();
        $request->file('product_image')->storeAs('public/pictures',$file_name);

        $product = [
            "product_image"    => $file_name,
            'product_title'    => $request->product_title,
            'product_quantity' => $request->product_quantity
        ];

        Product::create($product);
        return response()->json([
            'status' => true
        ]);
    }

    //------ all products ----
    public function all_products(){
        // return $dataTable->render('dashboard');
        $data = product::query()->get();
        return DataTables::of($data)->addIndexColumn()
            ->editColumn('product_image', function ($data) {
                $image = "<img src='storage/pictures/".$data->product_image."' width='50px' class='image-fluid rounded'>";
                return $image;
            })
            ->addColumn('action', function($data){
                $button = '<button type="button" name="edit" id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_product" class="update-btn btn btn-success btn-sm"> <i class="fa-solid fa-pen"></i></button>';
                return $button;
            })
            ->rawColumns(['action', 'product_image'])
            ->make(true);
    }

    // ----  Single product  ----
    public function single_product(Request $request){
        $id      = $request->id;
        $product = Product::find($id);
        return response()->json($product);
    }

    // ----  updating product  ----
    public function update_product(Request $request){
        $file_name = '';
        $product   = Product::find($request->product_id);
        if($request->hasFile('product_image')){

            $file      = $request->file('product_image');
            $file_name = time().'.'.$request->file('product_image')->getClientOriginalExtension();
            $request->file('product_image')->storeAs('public/pictures',$file_name);
            if ($product->product_image) {
                Storage::delete('app/public/pictures/'.$request->product_image);
            }
        }else{
            $file_name = $request->product_image;
        }
        $update_product = [
            'product_image'    => $file_name,
            'product_title'    => $request->product_title,
            'product_quantity' => $request->product_quantity,
        ];
        $product->update($update_product);
        return response()->json([
            'status' => true
        ]);
    }
    
}
