<!DOCTYPE html>
<html>

<?php

    $r = $_POST["rut"];
    $r = '"'.$r.'"';
    $c = $_POST["codigo"];

    $servername = "sql10.freesqldatabase.com";
    $username = "sql10330892";
    $dbname = "sql10330892";
    $password = "aLZXIsAywv";
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";

    $sql = "SELECT confCode FROM EstudiantesElos WHERE RUT = $r;";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
  
    if(strcasecmp($row["confCode"],$c)==0){
        echo "Voto Registrado";
        $d = date("d M Y H:i:s");
        $d = '"'.$d.'"';
        echo $d;
        $sql = "UPDATE EstudiantesElos SET votoEmitido=2, ultimaAct=$d WHERE RUT=$r;";
        if ($conn->multi_query($sql) === TRUE) {
                echo "New records created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

    }
    else{
        echo $row["confCode"];
        echo $c;
        echo "Error en validacion";
    }
    $conn->close();
?>
</html>
