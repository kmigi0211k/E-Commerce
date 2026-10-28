// funcion de js para traer el id
function changeStatusP(id){
    //alert(id);
    //Swal es la abreviatura de sweetalert Siempre la S en mayuscula
    // fire es lo mismo que ready, o Start 
    Swal.fire({
        title:"Would you like to change Status?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, change It"

    }).then((result)=>{
        if(result.isConfirmed){
            Swal.fire({
                position: "Center",
                icon: "success",
                showCancelButton: true,
                title: "Status Changed",
                confirmButtonText: "OK",
        
            }).then((result)=> {
            if(result){
                $.ajax({
                    type: "post",
                    //lamar la constante creada en header con el enrutamiento
                    url: url + "ProductosController/changeStatusP",
                    data: {'id': id,}
                }).done(function(result){
                    window.location.href = url + "ProductosController/viewProducts"
                    window.reload()
                }).fail(function(error){
                    Swal.fire("Wrong to change Status", " ", "Error")
                })
            }
            })
        }
    })

}
//metodo de eleminar
function deleteProduct(id){
    //alert(id);
    //Swal es la abreviatura de sweetalert Siempre la S en mayuscula
    // fire es lo mismo que ready, o Start 
    Swal.fire({
        title:"Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Delete"

    }).then((result)=>{
        if(result.isConfirmed){
            Swal.fire({
                position: "Center",
                icon: "success",
                title: "User Delete",
                confirmButtonText: "OK",
        
            }).then((result)=> {
            if(result){
                $.ajax({
                    type: "post",
                    //lamar la constante creada en header con el enrutamiento
                    url: url + "userController/deleteUser",
                    data: {'id': id,}
                }).done(function(result){
                    window.location.href = url + "ProductosController/viewProducts"
                    window.reload()
                }).fail(function(error){
                    Swal.fire("Wrong to delete user", " ", "Error")
                })
            }
            })
        }
    })

}

//metodo para el editar
function dataProduct(userProduct){
    alert(userProduct.idProducto)
    //Asigna cada dato del usuario a su respectivo campo en el modal
    document.getElementById('txtidProducto').value = userProduct.idProducto;
    document.getElementById('txtNombre').value = userProduct.Nombre;
    document.getElementById('txtDescripcion').value = userProduct.Descripcion;
    document.getElementById('txtPrecio').value = userProduct.Precio;
    document.getElementById('txtStock').value = userProduct.Stock;
    document.getElementById('txtFechaCreacion').value = userProduct.FechaCreacion;
 

}
