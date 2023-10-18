<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
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

    //------ all products
    public function all_products(){
        $all_products = product::all();
        // print_r($all_products);
        $output = '';
        if ($all_products->count() > 0) {
            $output .= "<table class='table table-striped table-sm'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Product Quantity</th>
                            <th>Action</th>
                        </tr>    
                    </thead>
                    <tbody>";
                    foreach($all_products as $product){
                        $output .= "<tr>
                                    <td>".$product->id."</td>
                                    <td><img src='storage/pictures/".$product->product_image."' width='50px' class='image-fluid rounded'></td>
                                    <td>".$product->product_title."</td>
                                    <td>".$product->product_quantity."</td>
                                    <td>
                                        <a href='' id='".$product->id."' class='btn btn-success update-btn' data-bs-toggle='modal' data-bs-target='#edit_product'>Edit</a>
                                    </td>
                                    </tr>";
                    }
                    $output .= "</tbody></table>";
                    echo $output;
        }else{
            echo '<h1 class="text-danger text-center">No Record Found</h1>';
        }
    }


    public function single_product(Request $request){
        $id      = $request->id;
        $product = Product::find($id);
        return response()->json($product);
    }

    public function update_product(Request $request){
        $file_name = '';
        $product   = Product::find($request->product_id);
        if($request->hasFile('product_image')){

            $file = $request->file('product_image');
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
