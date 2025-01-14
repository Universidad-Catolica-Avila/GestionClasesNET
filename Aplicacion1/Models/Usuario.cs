using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Aplicacion1.Models;

public partial class Usuario
{
    [Key]
    [Column("IdUsuario")]
    public int idUsuario { get; set; }

    [Column("Nombre")]
    public string nombre { get; set; } = null!;

    [Column("Email")]
    public string email { get; set; } = null!;

    [Column("Password")]
    public string password { get; set; } = null!;

    [Column("Rol")]
    public string rol { get; set; } = null!;

    [Column("Estado")]
    public bool estado { get; set; }

    [Column("Fecha_baja")]
    public DateOnly? fechaBaja { get; set; }

    [Column("Observaciones")]
    public string? observaciones { get; set; }

    //public virtual Personalpospro? Personalpospro { get; set; }

    //public virtual Tecnico? Tecnico { get; set; }

    // Descomentar líneas 92 y 156 en GestionclasesContext.cs


}

