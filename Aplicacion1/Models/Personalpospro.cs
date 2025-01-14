using System;
using System.Collections.Generic;

namespace Aplicacion1.Models;

public partial class Personalpospro
{
    public int IdPersonal { get; set; }

    public string Nombre { get; set; } = null!;

    public virtual Usuario IdPersonalNavigation { get; set; } = null!;

    public virtual ICollection<Registroclasesemanal> RegistroclasesemanalEditorNavigations { get; set; } = new List<Registroclasesemanal>();

    public virtual ICollection<Registroclasesemanal> RegistroclasesemanalTrimadorNavigations { get; set; } = new List<Registroclasesemanal>();
}
