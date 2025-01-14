<?php
	use admin\gestionDeClases\Config\Parameters;
	use admin\gestionDeClases\Helpers\ErrorHelpers;
    
    if (isset($_SESSION['errores'])) {
        echo "<div class='errores-container'>";
        foreach ($_SESSION['errores'] as $error) {
            echo "<p class='error'>$error</p>";
        }
        echo "</div>";
        unset($_SESSION['errores']);
    }
?>
    <div class="contenido form-container">
    <article id="header-contenido">
        <h1>Subir Tabla</h1>
    </article>
    <form id="subirExcelForm" action="<?=Parameters::$BASE_URL . "horarioClases/subirExcel" ?>" method="post" enctype="multipart/form-data">
        <p>Por favor, selecione un archivo excel para cargar</p>    
        <input type="file" accept=".xlsx" name="excelFile" id="excel">
        <button type="submit" id="submitButton" style="display: none">Enviar</button>
    </form>
</div> 
    
<?php
		ErrorHelpers::clearAll();
?>  