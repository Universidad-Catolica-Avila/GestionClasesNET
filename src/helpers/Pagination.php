<?php
class Paginacion
{
    private $total_registros;
    private $n_registros;
    private $total_paginas;
    private $pagina_actual;
    private $inicio;

    // Constructor que inicializa los valores
    public function __construct($total_registros, $n_registros, $pagina_actual = 1)
    {
        $this->total_registros = $total_registros;
        $this->n_registros = $n_registros;
        $this->pagina_actual = max(1, min($pagina_actual, $this->getTotalPaginas())); // Asegura que la página actual esté en el rango
        $this->total_paginas = ceil($this->total_registros / $this->n_registros); // Calcula el total de páginas
        $this->inicio = ($this->pagina_actual - 1) * $this->n_registros; // Calcula el inicio de la página
    }

    // Método para obtener la página actual
    public function getPaginaActual()
    {
        return $this->pagina_actual;
    }

    // Método para obtener el total de páginas
    public function getTotalPaginas()
    {
        return $this->total_paginas;
    }

    // Método para obtener el inicio de los registros
    public function getInicio()
    {
        return $this->inicio;
    }

    // Método para obtener el número de registros por página
    public function getRegistrosPorPagina()
    {
        return $this->n_registros;
    }

    // Método para generar los enlaces de la paginación
    public function generarEnlaces($url_base)
    {
        $enlaces = [];
        for ($i = 1; $i <= $this->total_paginas; $i++) {
            $enlaces[] = '<a href="' . $url_base . '&n=' . $i . '">' . $i . '</a>';
        }
        return implode(' | ', $enlaces);
    }

    // Método para verificar si hay paginación
    public function hayPaginacion()
    {
        return $this->total_paginas > 1;
    }

    // Método para obtener los registros en la página actual
    public function obtenerRegistros($model)
    {
        return $model->getRegistros($this->inicio, $this->n_registros);
    }
}
