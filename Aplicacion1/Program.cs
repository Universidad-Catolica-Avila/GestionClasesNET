using Aplicacion1.Models; // Para que conozca 'UsuarioDBContext'
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.DependencyInjection;
using Aplicacion1.Data;
using Aplicacion1.Services;

var builder = WebApplication.CreateBuilder(args);

// Configurar la conexión con la base de datos
builder.Services.AddDbContext<Aplicacion1DbContext>(options =>
    options.UseSqlServer(builder.Configuration.GetConnectionString("Aplicacion1DbContext") ?? throw new InvalidOperationException("Connection string 'Aplicacion1DbContext' not found.")));

// Configurar el servicio de Entity Framework Core con SQL Server
builder.Services.AddDbContext<GestionclasesContext>(options =>
    options.UseSqlServer(builder.Configuration.GetConnectionString("DefaultConnection")));

// Habilitar Distributed Memory Cache para sesiones
builder.Services.AddDistributedMemoryCache();

// Configurar las sesiones
builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromMinutes(30); // Duración de la sesión
    options.Cookie.HttpOnly = true;
    options.Cookie.IsEssential = true;
});

builder.Services.AddScoped<IUsuarioService, UsuarioService>();

// Agregar servicios para controladores con vistas
builder.Services.AddControllersWithViews();

var app = builder.Build();

// Configurar el pipeline de manejo de solicitudes HTTP
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Home/Error");
    app.UseHsts();
}

app.UseHttpsRedirection();
app.UseStaticFiles();

app.UseRouting();

// Habilitar sesiones
app.UseSession();

app.UseAuthorization();

app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}");

app.Run();
