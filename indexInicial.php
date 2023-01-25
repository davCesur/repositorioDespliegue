<!DOCTYPE html>
<html lang="es">
  <head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRUD</title>
  <link rel="stylesheet" type="text/css" href="hoja.css">
</head>

<body>

  <h1>CRUD<span class="subtitulo">Create Read Update Delete</span></h1>

  <?php

    $campos = array();

	  try{
      $base=new PDO("mysql:host=localhost; dbname=primerabbdd", "root", "");
		  $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  $base->exec("SET CHARACTER SET utf8");
		
		  $registros_por_pagina=5; /* CON ESTA VARIABLE INDICAREMOS EL NUMERO DE REGISTROS QUE QUEREMOS POR PAGINA*/
		  $estoy_en_pagina=1;/* CON ESTA VARIABLE INDICAREMOS la pagina en la que estamos*/
		
		  if (isset($_GET["pagina"])){
			  $estoy_en_pagina=$_GET["pagina"];				
		  }
		
		  $empezar_desde=($estoy_en_pagina-1)*$registros_por_pagina;
		
		  $sql_total="SELECT * FROM datos_usuarios";

      /* CON LIMIT 0,3 HACE LA SELECCION DE LOS 3 REGISTROS QUE HAY EMPEZANDO DESDE EL REGISTRO 0*/
		  $resultado=$base->prepare($sql_total);
		  $resultado->execute(array());
		
		  $num_filas=$resultado->rowCount(); /* nos dice el numero de registros del reusulset*/
		  $total_paginas=ceil($num_filas/$registros_por_pagina); /* FUNCION CEIL REDONDEA EL RESULTADO*/
		  echo "Número de Registros de la consulta: " . $num_filas . "<br>";
		  echo "Mostramos " . $registros_por_pagina . " Registros por página." . "<br>";
		  echo "Mostrando la página " . $estoy_en_pagina . " de " . $total_paginas . "<br>" . "<br>";

      /* ESTA PRIMERA CONSULTA ES PARA SABER NUMERO TOTAL DE REGISTROS Y MOSTRAR LAS PAGINAS Y REGISTROS QUE HAY*/
		
		$resultado->CloseCursor();
		$sql_limite="SELECT * FROM datos_usuarios LIMIT $empezar_desde,$registros_por_pagina";
		$resultado=$base->prepare($sql_limite);
		$resultado->execute(array());
		
	?>

		<table width="50%" border="0" align="center">
			<tr>
				<td class='primera_fila'>ID</td>
				<td class='primera_fila'>Nombre</td>
				<td class='primera_fila'>Apellido</td>
				<td class='primera_fila'>Dirección</td>
			</tr>


	<?php
		  while ($registro=$resultado->fetch(PDO::FETCH_ASSOC)){
			  echo "<tr>";
				  echo "<td>" . $registro['id'] . "</td>";
				  echo "<td>" . $registro['Nombre'] . "</td>";
				  echo "<td>" .  $registro['Apellido'] . "</td>";
				  echo "<td>" .  $registro['Direccion'] . "</td>";
		
	?>
 					<td class="bot"><a href="borrar.php?id=<?php echo $registro['id']?>"><img src="borrar.png"></a></td>
					<td class='bot'><a href="editarInicial.php?id=<?php echo $registro['id']?> & nom=<?php echo $registro['Nombre']?> & ape=<?php echo $registro['Apellido']?> & dir=<?php echo $registro['Direccion']?>"><img src="editar.png"></a></td>
	<?php
			echo "</tr>";
		  }
	?>
		
		  <tr>
    		<td></td>
        		<form method="get" action="insertar.php">
          		<td><input type='text' name='Nom' size='10' class='centrado'></td>
          		<td><input type='text' name='Ape' size='10' class='centrado'></td>
          		<td><input type='text' name=' Dir' size='10' class='centrado'></td>
          		<td class='bot'><input type='image' src="insertar.png" name='cr' id='cr'></td></tr>    
        	</form>
	<?php
		    echo "</table>";

	  }catch (Exception $e){
		  die ("Error: " . $e->getMessage());
	  }
	
  /*-------------------------PAGINACION-----------------*/
	  echo "<br>";
	
	  for ($i=1; $i<=$total_paginas; $i++){
		  echo "<a href='indexInicial.php?pagina=" . $i . "'>" . $i . "</a>  ";
	  }
  ?>

  <!-- PARA LA BÚSQUEDA POR CAMPO -->
  <br>

  <table width="50%" border="0" align="center">
      <tr >
        <td class="primera_fila">ID</td>
        <td class="primera_fila">Nombre</td>
        <td class="primera_fila">Apellido</td>
        <td class="primera_fila">Dirección</td>
        <td class="sin">&nbsp;</td>
        <td class="sin">&nbsp;</td>
        <td class="sin">&nbsp;</td>
      </tr> 

  <?php
    // Primero, incluimos el archivo de conexión a la base de datos
    include("conexion.php");

    // Se declara la variable campo
    $campo = '';

    // Si se ha enviado el formulario, recogemos el valor del campo de texto
    if (isset($_POST['texto'])) {
      $texto = $_POST['texto'];
   
      // Armamos la consulta SQL para buscar en la base de datos las filas que contengan el texto ingresado
      $consulta = "SELECT * FROM datos_usuarios WHERE id LIKE '%$texto%' or Nombre LIKE '%$texto%' or Apellido LIKE '%$texto%' or Direccion LIKE '%$texto%'";

      // Ejecutamos la consulta
      $resultado = $base->query($consulta);
      $campos=$resultado->fetchAll(PDO::FETCH_OBJ);
      
    }
      foreach($campos as $campo):
  ?>

      <tr>
        <td><?php echo $campo->id?> </td>
        <td><?php echo $campo->Nombre?></td>
        <td><?php echo $campo->Apellido?></td>
        <td><?php echo $campo->Direccion?></td>
      </tr>  

      <?php
        endforeach;
      ?>
    
    <form method="post">
      <input type="text" name="texto">
      <button type="submit"><img src="buscar.png"></button>
    </form>
    

  <p>&nbsp;</p>
</body>
</html>