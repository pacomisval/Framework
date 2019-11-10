<?php
namespace app\views;

use core\MVC\Controller as Controller;
use app\models\ComentarioController;


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Portada</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../public/css/main.css" />
</head>
<body>

<?php
    include "menu.php";

?>

<div id="content">
<!-- <?php
    /* $display = "";
    if(isset($_POST['btnSub'])) {
        $display = "block";
    }
    else {
        $display = "none";
    }
    echo $display; */
?> -->

<h1>Jugador <?php echo $jugador->Nombre ?></h1>
    <?php
        

        echo "<table border='1'>";
        echo '<tr><th>Nombre</th><td>' . $jugador->Nombre . '</td></tr>';
        echo '<tr><th>Procedencia</th><td>' . $jugador->Procedencia . '</td></tr>';
        echo '<tr><th>Altura</th><td>' . $jugador->Altura . '</td></tr>';
        echo '<tr><th>Peso</th><td>' . $jugador->Peso . '</td></tr>';
        echo '<tr><th>Posición</th><td>' . $jugador->Posicion . '</td></tr>';
        echo "</table>"; 
        
        echo "<br><br><br><br>";

        echo "<form action='http://localhost:8088/cursoServidor/framework5/jugador/".$jugador->codigo."' method='POST'>";
        echo "<table border='1'>";
        echo "<tr><th>Ver Comentarios</th></tr>";
        foreach($com as $value){
            echo "<tr><td><textarea name='text' rows='6' cols='50'>".$value->comentario."</textarea></td></tr>";
        } 
        echo "<input type='hidden' name='idJugador' value='". $jugador->codigo."'>" ;       
        echo "</table>";
        echo "</form>";

        echo "<br<br><br><br><br><br><br>";

        echo "<form action='http://localhost:8088/cursoServidor/framework5/registroComentario' method='POST' name='formRegistro' id='formReg'>";
        echo "<table border='1'>";
        echo "<tr><th><label>Deja tu comentario</label></th><tr>";
        echo "<tr><td><textarea name='text' rows='6' cols='50'></textarea></td><tr>";
        echo "<input type='hidden' name='idJugador' value='". $jugador->codigo."'>" ;
        echo "<tr><td><input type='reset' name='limpiar' value='Limpiar'><input type='submit' name='enviar' value='Enviar'></td></tr>";
        echo "</table>";
        echo "</form>";
       

    ?>
</div>

<!-- <script>
    function ocultarForm() {
        document.getElementById("formReg").style.display="none";
    }
    function mostrarForm() {
        document.getElementById("content2").style.display="block";
    }
</script> -->   
</body>
</html>