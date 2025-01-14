using System;
using System.Collections.Generic;

namespace Aplicacion1.Models;

public partial class Horarioclase
{
    public int IdHorario { get; set; }

    public string TipoTitulacion { get; set; } = null!;

    public string Dia { get; set; } = null!;

    public TimeOnly Hora { get; set; }

    public string TipoPad { get; set; } = null!;

    public string CodigoPad { get; set; } = null!;

    public string Asignatura { get; set; } = null!;

    public string Profesor { get; set; } = null!;

    public string Aula { get; set; } = null!;

    public string Titulacion { get; set; } = null!;

    public string Semestre { get; set; } = null!;

    public string Curso { get; set; } = null!;

    public DateTime FechaDeInserccion { get; set; }

    public bool EstadoHorario { get; set; }

    public virtual ICollection<Registroclasesemanal> Registroclasesemanals { get; set; } = new List<Registroclasesemanal>();
}
