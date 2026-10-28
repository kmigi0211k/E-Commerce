// funcion de js para traer el id
function changeStatus(id){
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
                    url: url + "userController/changeStatus",
                    data: {'id': id,}
                }).done(function(result){
                    window.location.href = url + "userController/viewUsers"
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
function deleteUser(id){
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
                    window.location.href = url + "userController/viewUsers"
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
function dataUser(userData){
    //Asigna cada dato del usuario a su respectivo campo en el modal
    document.getElementById('txtIdUser').value = userData.idUser;
    document.getElementById('txtDocument').value = userData.Document;
    document.getElementById('txtNames').value = userData.Names;
    document.getElementById('txtLastNames').value = userData.Lastname;
    document.getElementById('txtUsername').value = userData.userName;
    document.getElementById('txtEmail').value = userData.Email;
    document.getElementById('txtPhone').value = userData.Phone;
    document.getElementById('txtAddress').value = userData.Address;
    document.getElementById('txtPassword').value = userData.PASSWORD;

}
