<!DOCTYPE html>
<html lang="">
    <head>
        <title>CandyPet</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" >
        <link rel="stylesheet" href="css/style.css" >
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>
            function valida_datos() {
                const name = document.getElementById("name");
                const last_name = document.getElementById("last_name");
                const phone = document.getElementById("phone");
                const address = document.getElementById("address");
                const email = document.getElementById("email");
                const password = document.getElementById("password");
                const conf_password = document.getElementById("conf_password");

                const expresion = /[0-9]{10}/;
                const regex_email = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if(name.value==="") {
                    name.setCustomValidity("Campo nombre no puede venir vacio");
                }else{
                    name.setCustomValidity("");
                }

                if(last_name.value==="") {
                    last_name.setCustomValidity("Campo apellido no puede venir vacio");
                }else{
                    last_name.setCustomValidity("");
                }

                if(!expresion.test(phone.value) || phone.value.length > 10) {
                    phone.setCustomValidity("El numero de telefono debe de ser de 10 digitos");
                }else{
                    phone.setCustomValidity("");
                }

                if(address.value==="") {
                    address.setCustomValidity("Campo direccion no puede venir vacio");
                }else{
                    address.setCustomValidity("");
                }

                if(!regex_email.test(email.value)) {
                    email.setCustomValidity("Campo email debe de tener la estructura a@a.com");
                }else{
                    email.setCustomValidity("");
                }

                if(password.value==="") {
                    password.setCustomValidity("Campo contraseña no puede venir vacio");
                }else{
                    password.setCustomValidity("");
                }

                if(conf_password.value==="") {
                    conf_password.setCustomValidity("Campo confirma contraseña no puede venir vacio");
                }else{
                    conf_password.setCustomValidity("");
                }

                if(conf_password.value!==password.value) {
                    conf_password.setCustomValidity("Campo las contraseñas no coinciden");
                }else{
                    conf_password.setCustomValidity("");
                }
            }
        </script>
    </head>
    <body>
        <div style="display:flex; height: 100vh" class="container justify-content-center align-items-center">
            <form style="width: 40%" action="correo_validacion.php" method="POST" class="border shadow p-3 rounded">
                <div class="mb-3" style="text-align: center">
                    <h1 class="button_registro">Registro</h1>
                </div>
                <div class="mb-3">
                    <label class="form-label input_registro">Nombre:
                        <input class="form-control" id="name" name="name">
                    </label>
                </div>
                <div class="mb-3">
                    <label class="form-label input_registro">Apellidos:
                        <input class="form-control" id="last_name" name="last_name">
                    </label>
                </div>
                <div class="mb-3">
                    <label class="form-label input_registro">Telefono:
                        <input class="form-control" id="phone" name="phone">
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label input_registro">Direccion:
                        <input class="form-control" id="address" name="address">
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label input_registro">Email:
                        <input class="form-control" id="email" name="email">
                    </label>
                </div>
                <div class="mb-3">
                    <label class="form-label input_registro">Contraseña:
                        <input type="password" class="form-control" id="password" name="password">
                    </label>
                </div>
                <div class="mb-3">
                    <label class="form-label input_registro">Confirma contraseña:
                        <input type="password" class="form-control" id="conf_password" name="conf_password">
                    </label>
                </div>
                <button type="submit" onclick="valida_datos()" class="btn btn-primary button_registro">Enviar</button>
            </form>
        </div>
    </body>
</html>