using Aplicacion1.Models;
using Microsoft.EntityFrameworkCore;

namespace Aplicacion1.Services
{
    public class UsuarioService : IUsuarioService
    {
        private readonly GestionclasesContext _context;

        public UsuarioService(GestionclasesContext context)
        {
            this._context = context;
        }

        public async Task<List<Usuario>> GetAllUsers()
        {
            var usuarios = await this._context.Usuarios
                .Select(u => new Usuario
                {
                    idUsuario = u.idUsuario,
                    nombre = u.nombre,
                    email = u.email,
                    rol = u.rol,
                    estado = u.estado,
                    fechaBaja = u.fechaBaja,
                    observaciones = u.observaciones
                })
                .ToListAsync();

            return usuarios;
        }

        public async Task CreateUser(Usuario usuario)
        {
            Console.WriteLine("Pasa por UsuarioService.CreateUser\n\n\n\n\n");
            if (usuario == null)
            {
                throw new ArgumentNullException(nameof(usuario), "El usuario no puede ser null");
            }
            else
            {
                try
                {
                    Console.WriteLine($"usuario.nombre = {usuario.nombre}\nusuario.email = {usuario.email}\nusuario.password = {usuario.password}\nusuario.rol  = {usuario.rol}\nusuario.estado = {usuario.estado}\nusuario.fechaBaja = {usuario.fechaBaja}\nusuario.observaciones = {usuario.observaciones}\n\n\n\n\n");
                    // Agregar el nuevo usuario al contexto de la base de datos
                    this._context.Usuarios.Add(usuario);

                    // Guardar los cambios en la base de datos
                    await this._context.SaveChangesAsync();
                }
                catch (Exception ex)
                {
                    // Manejo de errores si algo falla al guardar
                    throw new InvalidOperationException("Hubo un error al crear el usuario.", ex);
                }
            }
        }

        public async Task EditUser(Usuario usuario)
        {
            Console.WriteLine("Pasa por UsuarioService.EditUser\n\n\n\n\n");

            if (usuario == null)
            {
                throw new ArgumentNullException(nameof(usuario), "El usuario no puede ser null");
            }

            try
            {
                // Buscar el usuario en la base de datos
                var oldUsuario = await this._context.Usuarios.FindAsync(usuario.idUsuario);
                if (oldUsuario == null)
                {
                    throw new KeyNotFoundException("El usuario no existe.");
                }

                // Actualizar los campos del usuario con los valores nuevos
                oldUsuario.nombre = usuario.nombre;
                oldUsuario.email = usuario.email;
                oldUsuario.password = usuario.password;
                oldUsuario.rol = usuario.rol;
                oldUsuario.estado = usuario.estado;
                oldUsuario.fechaBaja = usuario.fechaBaja;
                oldUsuario.observaciones = usuario.observaciones;

                // Guardar los cambios en la base de datos
                await this._context.SaveChangesAsync();

                Console.WriteLine($"Usuario actualizado con éxito: {usuario.nombre} ({usuario.email})");
            }
            catch (KeyNotFoundException knfEx)
            {
                // El usuario no se encontró en la base de datos
                throw knfEx;
            }
            catch (DbUpdateException dbEx)
            {
                // Captura errores específicos de la base de datos
                throw new InvalidOperationException("Hubo un error al intentar actualizar el usuario en la base de datos.", dbEx);
            }
            catch (Exception ex)
            {
                // Captura cualquier otro tipo de error
                throw new InvalidOperationException("Hubo un error al actualizar el usuario.", ex);
            }
        }

        public async Task DeleteUser(int id)
        {
            Console.WriteLine("Pasa por UsuarioService.DeleteUser\n\n\n\n\n");

            if (id == 0)
            {
                throw new ArgumentNullException(nameof(id), "El id no puede ser cero");
            }

            try
            {
                // Buscar el usuario por su ID
                var usuario = await _context.Usuarios.FindAsync(id);

                // Comprobar que ha obtenido bien los datos
                if (usuario == null)
                {
                    throw new KeyNotFoundException($"No se encontró un usuario con el ID {id}");
                }

                // Eliminar el usuario del contexto
                _context.Usuarios.Remove(usuario);

                // Guardar los cambios en la base de datos
                await _context.SaveChangesAsync();
            }
            catch (Exception ex)
            {
                throw new InvalidOperationException("Error al eliminar el usuario.", ex);
            }
        }
    }
}
