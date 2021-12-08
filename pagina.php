<?php
    $servername = "localhost";
    $dbname = "id17697788_usuarios_test";
    
    $conStr = "mysql:host=$servername;dbname=$dbname";
    $pdo = new PDO($conStr, "id17697788_root","D~1cuX||EvK5{Sz9");

    if($_POST["OPCION"] == "LOGIN")
    {
        try
        {
            $sentencia = $pdo -> prepare('SELECT * FROM usuarios WHERE CORREO = :correo AND CLAVE = :clave');

            $sentencia->execute(array(':correo' => $_POST["CORREO"],':clave' => $_POST["CLAVE"]));

            $arrayUsuarios = $sentencia -> fetchAll();

            if(sizeof($arrayUsuarios)==0)
            {
                echo "No se encontro a nadie con ese correo y contraseña.";
            }
            else
            {
                foreach($arrayUsuarios as $miUsuario)
                {
                    $sentenciaPerfil = $pdo -> prepare('SELECT * FROM perfiles WHERE ID = '.$miUsuario[4].'');

                    $sentenciaPerfil -> execute();

                    $perfil = $sentenciaPerfil -> fetchAll();

                    echo $miUsuario[3]." ".$perfil[0]["DESCRIPCION"]."<br>";
                }
            }
        }
        catch(PDOException $e)
        {
            echo "Error al conectarse con el servidor: " . $e->getMessage() . "<br/>";
        }
    }
    else if($_POST["OPCION"] == "MOSTRAR")
    {
        try
        {
            $sentencia = $pdo -> prepare('SELECT * FROM usuarios');

            $sentencia -> execute();

            $arrayUsuarios = $sentencia -> fetchAll();

            foreach($arrayUsuarios as $miUsuario)
            {
                $sentenciaPerfil = $pdo->prepare('SELECT * FROM perfiles WHERE ID = '.$miUsuario[4].'');

                $sentenciaPerfil -> execute();

                $perfil = $sentenciaPerfil -> fetchAll();
                
                echo $miUsuario[0]." ".$miUsuario[1]." ".$miUsuario[2]." ".$miUsuario[3]." ".$miUsuario[4]." ".$perfil[0]["DESCRIPCION"]."<br>";
            }
        }
        catch(PDOException $e)
        {
            echo "Error al conectarse con el servidor: " . $e->getMessage() . "<br/>";
        }
    }
    else if($_POST["OPCION"] == "ALTA" && isset($_POST["OBJ_JSON"]))
    {
        $json = json_decode($_POST["OBJ_JSON"],true);

        try
        {
            $sentencia = $pdo -> prepare('INSERT INTO usuarios(CORREO,CLAVE,NOMBRE,PERFIL) VALUES (:correo,:clave,:nombre,:perfil)');

            if($sentencia->execute(array(':correo'=>$json["CORREO"],':clave'=>$json["CLAVE"],':nombre'=>$json["NOMBRE"],':perfil'=>$json["PERFIL"])))
            {
                echo "Exito al dar de alta el usuario";
            }
            else
            {
                echo "Error al dar de alta el usuario";
            } 
        }
        catch(PDOException $e)
        {
            echo "Error al conectarse con el servidor: " . $e->getMessage() . "<br/>";
        }
    }
    else if($_POST["OPCION"] == "MODIFICACION" && isset($_POST["OBJ_JSON"]) && (isset($_POST["ID"])))
    {
        $json = json_decode($_POST["OBJ_JSON"],true);

        try
        {
            $sentencia = $pdo->prepare('UPDATE usuarios SET CORREO = :nuevoCorreo, CLAVE = :nuevaClave, NOMBRE = :nuevoNombre, PERFIL = :nuevoPerfil WHERE ID = :id');

            if($sentencia->execute(array(':nuevoCorreo'=>$json["CORREO"],':nuevaClave'=>$json["CLAVE"],':nuevoNombre'=>$json["NOMBRE"],':nuevoPerfil'=>$json["PERFIL"],':id'=>$_POST["ID"])))
            {
                echo "Exito al modificar el usuario";
            }
            else
            {
                echo "Error al modificar el usuario";
            }
        }
        catch(PDOException $e)
        {
            echo "Error al conectarse con el servidor: " . $e->getMessage() . "<br/>";
        }
    }
    else if($_POST["OPCION"] == "BAJA" && isset($_POST["ID"]))
    {
        try
        {
            if( $pdo->exec('DELETE FROM usuarios WHERE ID = ' . $_POST["ID"]) > 0)
            {
                echo "Exito al eliminar el usuario";
            }
            else
            {
                echo "Error al eliminar el usuario";
            }
        }
        catch(PDOException $e)
        {
            echo "Error al conectarse con el servidor: " . $e->getMessage() . "<br/>";
        }
    }
?>