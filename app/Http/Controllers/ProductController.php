<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;

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


    public function all_products(){
        $all_products = product::all();
        // print_r($all_products);
        $output = '';
        if ($all_products->count() > 0) {
            $output .= "<table class='table table-striped table-sm text-center align-middle'>
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
                                        <a href='' id='".$product->id."' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#edit_product'>Edit</a>
                                    </td>
                                    </tr>";
                    }
                    $output .= "</tbody></table>";
                    echo $output;
        }else{
            echo '<h1 class="text-danger text-center">No Record Found</h1>';
        }
    }
}
