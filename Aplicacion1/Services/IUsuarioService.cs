using Aplicacion1.Models;

namespace Aplicacion1.Services
{
    public interface IUsuarioService
    {
        // Obtener todos los usuarios
        Task<List<Usuario>> GetAllUsers();

        // Crear un usuario
        Task CreateUser(Usuario usuario);

        // Editar un usuario
        Task EditUser(Usuario usuario);

        // Borrar un usuario
        Task DeleteUser(int id);
    }
}
