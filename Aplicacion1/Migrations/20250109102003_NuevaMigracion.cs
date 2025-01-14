using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace Aplicacion1.Migrations
{
    /// <inheritdoc />
    public partial class NuevaMigracion : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            //migrationBuilder.CreateTable(
            //    name: "Usuarios",
            //    columns: table => new
            //    {
            //        idUsuario = table.Column<int>(type: "int", nullable: false)
            //            .Annotation("SqlServer:Identity", "1, 1"),
            //        nombre = table.Column<string>(type: "nvarchar(max)", nullable: false),
            //        email = table.Column<string>(type: "nvarchar(max)", nullable: false),
            //        password = table.Column<string>(type: "nvarchar(max)", nullable: false),
            //        rol = table.Column<string>(type: "nvarchar(max)", nullable: false),
            //        estado = table.Column<bool>(type: "bit", nullable: false),
            //        fecha_baja = table.Column<DateTime>(type: "datetime2", nullable: false),
            //        observaciones = table.Column<string>(type: "nvarchar(max)", nullable: false)
            //    },
            //    constraints: table =>
            //    {
            //        table.PrimaryKey("PK_Usuarios", x => x.idUsuario);
            //    });
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            //migrationBuilder.DropTable(
            //    name: "Usuarios");
        }
    }
}
