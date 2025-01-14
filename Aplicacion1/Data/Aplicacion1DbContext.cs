using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.EntityFrameworkCore;
using Aplicacion1.Models;

namespace Aplicacion1.Data
{
    public class Aplicacion1DbContext : DbContext
    {
        public Aplicacion1DbContext (DbContextOptions<Aplicacion1DbContext> options)
            : base(options)
        {
        }

        public DbSet<Aplicacion1.Models.Usuario> Usuarios { get; set; }
    }
}
