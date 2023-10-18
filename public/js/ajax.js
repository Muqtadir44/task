//Add Product
$("#add_product_form").submit(function(e){
    e.preventDefault();
    var form_data = new FormData(this);
    console.log(form_data);
    
    var route = "{{route('add_product')}}";
    $.ajax({
        url: route, // Note the double curly braces for Blade templating
        method: 'POST', // Use uppercase for the HTTP method
        data: form_data,
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
        }
    });
    // var obj;
    // if (window.ActiveXObject) {
    //     obj = new ActiveXObject('Microsoft.XMLHTTP');
    // } else {
    //     obj = new XMLHttpRequest();
    // }
    // obj.onreadystatechange = function(){
    //     if (obj.status == 200 && obj.readyState == 4) {
    //         console.log(obj.responseText);
    //     }
    // }
    // obj.open('POST',route);
    // obj.send(form_data);
})