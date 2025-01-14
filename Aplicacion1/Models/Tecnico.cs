using System;
using System.Collections.Generic;

namespace Aplicacion1.Models;

public partial class Tecnico
{
    public int IdTecnico { get; set; }

    public string Nombre { get; set; } = null!;

    public virtual Usuario IdTecnicoNavigation { get; set; } = null!;

    public virtual ICollection<Registroclasesemanal> Registroclasesemanals { get; set; } = new List<Registroclasesemanal>();
}
