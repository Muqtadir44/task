@extends('layouts.layout')
@section('page_title')
    Dashboard
@endsection
@section('content')
<h3 class="text-center display-1 text-primary animate__animated animate__bounceInDown">Dashboard</h3>
<div class="container">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Product</h3>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add Product <i class="fa-solid fa-circle-plus"></i>
                    </button>
                    
                    <!-- Add Product Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="show_all_products">
                                <p id="msg"></p>
                                <form action="{{route('add_product')}}" method="POST" id="add_product_form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="formGroupExampleInput" class="form-label">Product Image</label>
                                        <input type="file" name="product_image" class="form-control" id="formGroupExampleInput" placeholder="Image">
                                      </div>
                                      <div class="mb-3">
                                        <label for="formGroupExampleInput2" class="form-label">Product Title</label>
                                        <input type="text" name="product_title" class="form-control" id="formGroupExampleInput2" placeholder="product title">
                                      </div>
                                      <div class="mb-3">
                                        <label for="formGroupExampleInput2" class="form-label">Product Quantity</label>
                                        <input type="text" name="product_quantity" class="form-control" id="formGroupExampleInput2" placeholder="product quantity">
                                      </div>
                                      <div class="text-center">
                                        <button type="submit" id="add_product_btn" class="btn btn-primary px-5">Add Product</button>
                                      </div>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="records"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Product Modal -->
<div class="modal fade" id="edit_product" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="show_all_products">
            <p id="msg"></p>
            <form action="{{route('update_product')}}" method="POST" id="update_product_form" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="hidden" name="product_id" id="product_id">
                    <input type="hidden" name="product_image" id="product_image">
                    <label class="form-label">Product Image</label>
                    <input type="file" name="product_image" class="form-control" id="formGroupExampleInput" placeholder="Image">
                    <div id="img">

                    </div>
                </div>
                  <div class="mb-3">
                    <label  class="form-label">Product Title</label>
                    <input type="text" name="product_title" class="form-control" id="product_title" placeholder="product title">
                  </div>
                  <div class="mb-3">
                    <label  class="form-label">Product Quantity</label>
                    <input type="text" name="product_quantity" class="form-control" id="product_quantity" placeholder="product quantity">
                  </div>
                  <div class="text-center">
                    <button type="submit" id="update_product_btn" class="btn btn-primary px-5">Update Product</button>
                  </div>
            </form>
        </div>
    </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.js" defer></script>
<script>
    //----------fetching records-------
    all_products();

    function all_products(){
        $.ajax({
            url: '{{route('all_products')}}',
            method: 'GET',
            success: function(data){
                // console.log(data);
                $('#records').html(data);
                $('table').DataTable({
                    order: [0 , 'desc']
                })
            }
        })
    }

    $('#update_product_form').on('submit',function(e){
        e.preventDefault();
        var formdata = new FormData(this);
        // console.log(formdata);
        $.ajax({
            url: '{{route('update_product')}}',
            type: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            success:function(data){
                console.log(data);
                if (data.status == true) {
                    all_products();
                }
            }
            
        })
    })

    //----------adding product---------
    $('#add_product_form').on('submit',function(e){
        e.preventDefault();
        // alert('working');
        var formdata = new FormData(this);
        console.log(formdata);
        $.ajax({
            url: "{{route('add_product')}}",
            type: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            success:function(data){
                console.log(data);
                all_products();
            }
        })
    });

    //-------single product ---
    $(document).on('click','.update-btn',function(e){
        e.preventDefault();
        var product_id = $(this).attr('id');
        // console.log(product_id);
        $.ajax({
            url: '{{route('single_product')}}',
            type: 'GET',
            data:{
                id:product_id,
                _token: '{{ csrf_token() }}'
            },
            success: function(data){
                console.log(data);
                $('#product_title').val(data.product_title);
                $('#product_quantity').val(data.product_quantity);
                $('#img').html(`<img src='{{asset('storage/pictures/${data.product_image}')}}' width='100px' class='mt-2 img-fluid rounded'>`)
                $('#product_id').val(data.id);
                $('#product_image').val(data.product_image);
            }
        })
    });

    //----- updating product
    
</script>
@endsection