using System;
using System.Collections.Generic;

namespace Aplicacion1.Models;

public partial class Registroclasesemanal
{
    public int IdRegistro { get; set; }

    public int IdHorario { get; set; }

    public DateOnly SemanaInicio { get; set; }

    public DateOnly SemanaFin { get; set; }

    public DateOnly? Fecha { get; set; }

    public int? Tecnico { get; set; }

    public bool? Grabado { get; set; }

    public bool? Editado { get; set; }

    public bool? Bruto { get; set; }

    public string? ObservacionesTecnico { get; set; }

    public bool? GrabacionBrutoBorrado { get; set; }

    public TimeOnly? DuracionBruto { get; set; }

    public int? Editor { get; set; }

    public int? Trimador { get; set; }

    public string? ObservacionesEditorTrimador { get; set; }

    public string? Estado { get; set; }

    public virtual Personalpospro? EditorNavigation { get; set; }

    public virtual Horarioclase IdHorarioNavigation { get; set; } = null!;

    public virtual Tecnico? TecnicoNavigation { get; set; }

    public virtual Personalpospro? TrimadorNavigation { get; set; }
}
