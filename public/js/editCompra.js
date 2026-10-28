// funcion de js para traer el id
function changeStatusC(id){
    alert(id);
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
                    url: url + "userController/changeStatus",
                    data: {'id': id,}
                }).done(function(result){
                    window.location.href = url + "ComprasController/viewCompras"
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
function deleteCompra(id){
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
                    url: url + "ComprasController/deleteCompra",
                    data: {'id': id,}
                }).done(function(result){
                    window.location.href = url + "ComprasController/viewCompras"
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
function dataCompra(CompraData){
    alert(CompraData.id)
    //Asigna cada dato del usuario a su respectivo campo en el modal
    document.getElementById('txtid').value = CompraData.id;
    document.getElementById('txtarticulo').value = CompraData.articulo;
    document.getElementById('txtcantidad').value = CompraData.cantidad;
    document.getElementById('txtprecio_unitario').value = CompraData.precio_unitario;
    document.getElementById('txttotal').value = CompraData.total;
   

}
