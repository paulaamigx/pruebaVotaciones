<!DOCTYPE html>
<html>
    <body>
            
        <?php

        function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); 
        $alphaLength = strlen($alphabet) - 1; 
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

        ######Error report #####
        error_reporting(E_ALL);
        ini_set('error_log', 'phperror.log');
        ini_set('log_errors_max_len', 0);
        ini_set('log_errors', true);

        ###### Variables #####
        $m = $_POST["mail"];
        $d = $_POST["dominio"];
        $r = $_POST["rut"];
        
        $md0 = $m.$d;
        $md = '"'.$md0.'"';
        $r = '"'.$r.'"';
        

        #### Database ###
        $servername = "sql10.freesqldatabase.com";
        $username = "sql10330892";
        $dbname = "sql10330892";
        $password = "aLZXIsAywv";
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        echo "Connected successfully";

        
        ######
        $nameArray = explode(".",$m);
        $firstName =  $nameArray[0];
        $lastName =  $nameArray[1];

        $sql = "SELECT Nombre,Apellido FROM EstudiantesElos WHERE Rut = $r;";
        $result = $conn->query($sql);
        
        $row = $result->fetch_assoc();
        
        ### Name in list ####
        if(strcasecmp($row["Nombre"],$firstName) == 0 and 
        strncasecmp($row["Apellido"],$lastName,strlen($row["Apellido"])) == 0){
            $key = '"'.randomPassword().'"';
            $msg = "Gracias por votar. \r\n Tu codigo de verificacion es el siguiente: ".$key;
            echo mail($md0,"Codigo Verificacion Votacion",$msg);


            $sql = "UPDATE EstudiantesElos SET confCode=$key, votoEmitido=1, Correo=$md WHERE RUT = $r;";
            if ($conn->multi_query($sql) === TRUE) {
                echo "New records created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            echo " Registrando voto ";
            echo $_POST["respuesta"];
            echo "Hemos enviado un codigo de verificacion al cooreo ";
            echo $md;
            echo ". Favor ingresarlo a continuacion para terminar el proceso: ";

        }
        else{
            echo "No estas en la lista de votantes";

        }
        $conn->close();

        ?>
        <form action="/last.php" method="post" id>
            RUT: <input type="text" id="rut" name="rut" required><br>
            Codigo: <input type="text" id="codigo" name="codigo" required> <br>
            <input type="submit" value="Submit">
        </form>


    </body>
</html>
