using System.Diagnostics;
using Aplicacion1.Data;
//using Aplicacion1.Entidades;
using Aplicacion1.Models;
using Aplicacion1.Services;
using Microsoft.AspNetCore.Mvc;

namespace Aplicacion1.Controllers
{
    public class HomeController : Controller
    {
        private readonly ILogger<HomeController> _logger;
        private readonly Aplicacion1DbContext _context;
        private readonly IUsuarioService _usuarioService;



        ////////// CONSTRUCTOR //////////
        public HomeController(ILogger<HomeController> logger, Aplicacion1DbContext context, IUsuarioService usuarioService)
        {
            this._logger = logger;
            this._context = context;
            this._usuarioService = usuarioService;
        }



        ////////// INDEX + LOGIN //////////
        public IActionResult Index()
        {
            return View();
        }

        [HttpGet]
        public IActionResult Login()
        {
            return View();
        }

        [HttpPost]
        public IActionResult Login(string Username, string Password)
        {
            Console.WriteLine($"\n\n\n\n=== DATOS FORMULARIO ===\nUsername: {Username} / Password: {Password}");

            TempData["Username"] = Username;
            TempData["Password"] = Password;
            
            // Buscar usuario en la base de datos
            var usuario = _context.Usuarios.FirstOrDefault(u => u.nombre == Username && u.password == Password);

            if (usuario == null)
            {
                ViewBag.ErrorMessage = "Estas credenciales no existen en base de datos";
                return View();
            }
            else {
                Console.WriteLine("\n\nCredenciales encontradas en base de datos");
                Console.WriteLine($"Id: {usuario.idUsuario}, Nombre: {usuario.nombre}, Password: {usuario.password}");
                return RedirectToAction("PagUsuario");
            }
        }



        ////////// PAG USUARIO + LISTA USUARIOS //////////
        public IActionResult PagUsuario()
        {
            // Recuperar valores de TempData
            var username = TempData["Username"]?.ToString();
            var password = TempData["Password"]?.ToString();

            if (string.IsNullOrEmpty(username) || string.IsNullOrEmpty(password))
            {
                ViewBag.ErrorMessage = "No se proporcionaron credenciales.";
                return View(new Usuario()); // Pasar un objeto vacío en caso de error
            }

            try
            {
                var us = _context.Usuarios.FirstOrDefault(u => u.nombre == username && u.password == password);
                if (us != null)
                {
                    return View(us); // Pasar el usuario encontrado a la vista
                }
                else
                {
                    ViewBag.ErrorMessage = "Usuario no encontrado o credenciales incorrectas.";
                    return View(new Usuario()); // Pasar un objeto vacío si no se encuentra
                }
            }
            catch (Exception ex)
            {
                ViewBag.ErrorMessage = $"Error al conectar con la base de datos: {ex.Message}";
                return View(new Usuario()); // Pasar un objeto vacío en caso de error
            }
        }

        public async Task<IActionResult> ListaUsuarios()
        {
            try
            {
                // Usar IUsuarioService para recuperar todos los usuarios de la base de datos
                var usuarios = await this._usuarioService.GetAllUsers();

                return View(usuarios); // Pasar la lista de usuarios a la vista
            }
            catch (Exception ex)
            {
                ViewBag.ErrorMessage = $"Error al recuperar los usuarios: {ex.Message}";
                return View(new List<Usuario>()); // Devolver una lista vacía si hay un error
            }
        }



        ////////// CREATE + EDIT + DELETE USER //////////
        public IActionResult CreateUser()
        {
            Console.WriteLine("\n\n\n\n\nPasa por HomeController.CreateUser[GET]\n\n\n\n\n");
            return View();
        }

        [HttpPost]
        public async Task<IActionResult> CreateUser(Usuario nuevoUsuario)
        {
            Console.WriteLine("\n\n\n\n\nPasa por HomeController.CreateUser[POST]\n\n\n\n\n");
            try
                {
                    // Llamar al servicio para crear el nuevo usuario
                    await this._usuarioService.CreateUser(nuevoUsuario);

                    // Redirigir a la lista de usuarios o a otra página
                    return RedirectToAction("ListaUsuarios");
                }
                catch (Exception ex)
                {
                    // Manejo de errores en caso de que falle la creación
                    ViewBag.ErrorMessage = $"Error al crear el usuario: {ex.Message}";
                    return View(); // Devolver la vista con el mensaje de error
                }
        }

        public IActionResult EditUser(int id)
        {
            Console.WriteLine("\n\n\n\n\nPasa por HomeController.EditUser[POST(id)]\n\n\n\n\n");
            // Buscar el usuario por ID en la base de datos
            var usuario = _context.Usuarios.FirstOrDefault(u => u.idUsuario == id);

            if (usuario == null)
            {
                // Manejo del caso en que no se encuentra el usuario
                ViewBag.ErrorMessage = "Usuario no encontrado.";
                return RedirectToAction("ListaUsuarios");
            }

            // Devolver el usuario a la vista para editar
            return View(usuario);
        }

        [HttpPost]
        public async Task<IActionResult> EditUser(Usuario usuario)
        {
            Console.WriteLine("\n\n\n\n\nPasa por HomeController.EditUser[POST(usuario)]\n\n\n\n\n");
            if (ModelState.IsValid)
            {
                try
                {
                    if (usuario == null)
                    {
                        return NotFound();
                    }
                    await _usuarioService.EditUser(usuario);
                    return RedirectToAction("ListaUsuarios");
                }
                catch (Exception ex)
                {
                    ModelState.AddModelError("", $"Error al editar el usuario: {ex.Message}");
                    return View(usuario);
                }
            }
            return View(usuario);
        }

        //[HttpPost]
        public async Task<IActionResult> DeleteUser(int id)
        {
            Console.WriteLine("\n\n\n\n\nPasa por HomeController.DeleteUser[POST(id)]\n\n\n\n\n");
            try
            {
                // Llamar al servicio para eliminar el usuario por ID
                await this._usuarioService.DeleteUser(id);

                // Redirigir a la lista de usuarios después de la eliminación
                return RedirectToAction("ListaUsuarios");
            }
            catch (Exception ex)
            {
                // Manejo de errores en caso de que falle la eliminación
                ViewBag.ErrorMessage = $"Error al eliminar el usuario: {ex.Message}";
                return RedirectToAction("ListaUsuarios"); // Redirigir con el mensaje de error
            }
        }



        ////////// ERROR //////////
        [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
        public IActionResult Error()
        {
            return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
        }


        public IActionResult VerifyHash()
        {
            return View();
        }

        [HttpPost]
        public IActionResult ComprobarHash(string Password, string Hash)
        {
            try
            {
                // Usar BCrypt para verificar si la contraseña corresponde al hash
                bool isMatch = BCrypt.Net.BCrypt.Verify(Password, Hash);

                if (isMatch)
                {
                    ViewBag.SuccessMessage = "La contraseña coincide con el hash.";
                }
                else
                {
                    ViewBag.ErrorMessage = "La contraseña NO coincide con el hash.";
                }
            }
            catch (Exception ex)
            {
                ViewBag.ErrorMessage = $"Error al comprobar el hash: {ex.Message}";
            }

            return View();
        }

    }
}
