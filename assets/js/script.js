window.addEventListener('load', function () {

    if (document.getElementsByClassName('tabla').length > 0) {

        document.querySelectorAll('.fila2').forEach(function (element) {
            let colorFondo;

            switch (true) {
                case element.classList.contains('estado-no-editado'):
                    colorFondo = 'rgb(154, 233, 243)';
                    break;
                case element.classList.contains('estado-pendiente'):
                    colorFondo = 'rgb(255, 255, 255)';
                    break;
                case element.classList.contains('estado-tramitando'):
                    colorFondo = 'rgb(243, 247, 146)';
                    break;
                case element.classList.contains('estado-editado'):
                    colorFondo = 'rgb(147, 245, 159)';
                    break;
                default:
                    break;
            }

            element.querySelectorAll('.celda-fixed, .celda2-fixed').forEach(function (celda) {
                celda.style.backgroundColor = colorFondo;
            });
        });



        document.querySelectorAll('form[id^="estadoForm"]').forEach(function (form) {
            form.querySelector('select').addEventListener('change', function () {
                // Envía el formulario cuando el valor de un campo cambia
                form.submit();
            });
        });

        document.querySelectorAll('form[id^="editorForm"]').forEach(function (form) {
            form.querySelector('select').addEventListener('change', function () {
                form.submit();
            });
        });

        document.querySelectorAll('form[id^="trimadorForm"]').forEach(function (form) {
            form.querySelector('select').addEventListener('change', function () {
                form.submit();
            });
        });

        document.querySelectorAll('form[id^="seleccionarSemanaForm"]').forEach(function (form) {
            form.querySelector('select').addEventListener('change', function () {
                form.submit();
            });
        });
        
        if (document.getElementById('filtro-formulario')) {
            document.querySelectorAll('#filtro-formulario select, #filtro-formulario input').forEach(function (element) {
                element.addEventListener('change', function () {
                    document.getElementById('filtro-formulario').submit();
                });
            });

            document.getElementById('reset-filtro').addEventListener('click', function () {
                document.getElementById('filtro-formulario').reset();
            });

            function resetFiltro(campo) {
                if (campo.value === "") {
                    campo.value = "";  // Reinicia el valor a vacío si no se aplica filtro
                }
            }

            // Llama esta función cuando el filtro se aplica o se reinicia
            document.getElementById('filtro-codigo').addEventListener('input', function () {
                resetFiltro(this);  // Reinicia el valor de 'filtro-codigo' si no tiene valor
            });

            document.getElementById('reset-filtro').addEventListener('click', function () {
                // Limpiar los filtros en el formulario
                document.getElementById('filtro-codigo').value = '';
                document.getElementById('filtro-tipo').value = '';
                document.getElementById('filtro-dia').value = '';
                document.getElementById('filtro-tipoPAD').value = '';
                document.getElementById('filtro-Grabado').value = '';

                // Opcionalmente, recargar la página para limpiar los filtros desde la sesión
                window.location.href = "getSemanaActual?n=1"; // O usar otra URL si es necesario
            });

            document.getElementById('filtro-codigo').addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();  // Prevenir que el formulario se envíe de forma predeterminada
                    document.getElementById('filtro-formulario').submit();  // Enviar el formulario manualmente
                }
            });
        }
    };
    if (document.getElementById("subirExcelForm")) {
        // Función para mostrar/ocultar el botón submit del fichero excel
        function toggleSubmitButton() {
            const fileInput = document.getElementById('excel');
            const submitButton = document.getElementById('submitButton');
            // Si hay un archivo seleccionado mostrar el botón, de lo contrario, ocultarlo
            if (fileInput.files.length > 0) {
                submitButton.style.display = 'block';
            } else {
                submitButton.style.display = 'none';
            }
        }
    
        // Asignar el evento onchange al input de archivo
        const fileInput = document.getElementById('excel');
        fileInput.addEventListener('change', toggleSubmitButton);
    }
    

    // Función para ocultar mensajes
    function ocultarMensaje(elemento) {
        elemento.style.opacity = "0"; // Disminuir la opacidad
        setTimeout(() => {
            elemento.style.display = "none"; // Ocultar completamente después de la transición
        }, 500); // Tiempo de espera para la transición
    }

    // Mensaje temporal
    const mensajeTemporal = document.getElementById('mensaje-temporal');
    if (mensajeTemporal) {
        mensajeTemporal.addEventListener("click", function () {
            ocultarMensaje(mensajeTemporal);
        });

        // Ocultar automáticamente después de 3 segundos
        setTimeout(() => {
            ocultarMensaje(mensajeTemporal);
        }, 2000); // Tiempo antes de comenzar a ocultar
    }

    // Mensajes de error
    const errorElements = document.querySelectorAll('.error');
    errorElements.forEach(errorElement => {
        errorElement.addEventListener("click", function () {
            ocultarMensaje(errorElement);
        });

        // Ocultar automáticamente después de 3 segundos
        setTimeout(() => {
            ocultarMensaje(errorElement);
        }, 2000); // Tiempo antes de comenzar a ocultar
    });

    if (document.getElementById('formRegistrarClase')) {
        if (document.getElementById('idFechaInicio')) {
            const fechaInicioInput = document.getElementById('idFechaInicio');
            const fechaFinInput = document.getElementById('idFechaFin');

            const hoy = new Date();
            const diaSemana = hoy.getDay(); // 0 para domingo, 1 para lunes, etc.

            // Calcular el lunes de esta semana
            const lunes = new Date(hoy);
            lunes.setDate(hoy.getDate() - (diaSemana === 0 ? 6 : diaSemana - 1)); // Si es domingo, retrocede 6 días; de lo contrario, retrocede al lunes

            // Calcular el domingo de esta semana
            const domingo = new Date(lunes);
            domingo.setDate(lunes.getDate() + 6);

            // Establecer los valores en los inputs
            fechaInicioInput.value = lunes.toISOString().split('T')[0];
            fechaFinInput.value = domingo.toISOString().split('T')[0];

            const errorFechaInicio = document.getElementById("errorFechaInicio");
            const errorFechaFin = document.getElementById("errorFechaFin");
            const formRegistrarClase = document.getElementById("formRegistrarClase");

            // Obtener la fecha actual
            hoy.setHours(0, 0, 0, 0); // Resetear horas para comparar solo fechas

            // Calcular el lunes más reciente (hoy o anterior)
            const diferenciaALunes = diaSemana === 0 ? -6 : 1 - diaSemana; // Diferencia en días para llegar al lunes más cercano
            const lunesReciente = new Date(hoy);
            lunesReciente.setDate(hoy.getDate() + diferenciaALunes);

            // Validar que la fecha sea un lunes
            function validarLunes(fecha) {
                const dia = new Date(fecha).getDay();
                return dia === 1; // 1 = Lunes
            }

            // Validar que la fecha sea un domingo
            function validarDomingo(fecha) {
                const dia = new Date(fecha).getDay();
                return dia === 0; // 0 = Domingo
            }

            // Validar que una fecha no sea anterior al lunes más reciente
            function validarDesdeLunesReciente(fecha) {
                const fechaSeleccionada = new Date(fecha);
                fechaSeleccionada.setHours(0, 0, 0, 0); // Resetear hora
                return fechaSeleccionada >= lunesReciente; // La fecha debe ser igual o posterior al lunes reciente
            }

            // Validar fechas al enviar el formulario
            formRegistrarClase.addEventListener("submit", (e) => {
                let esValido = true;

                // Validar fecha de inicio (lunes desde el lunes más reciente)
                if (!validarLunes(fechaInicioInput.value)) {
                    errorFechaInicio.textContent = "La fecha de inicio debe ser un lunes.";
                    errorFechaInicio.style.display = "block";
                    fechaInicioInput.className = "error-register";
                    esValido = false;
                } else if (!validarDesdeLunesReciente(fechaInicioInput.value)) {
                    errorFechaInicio.textContent = `La fecha de inicio no puede ser anterior al lunes ${lunesReciente.toLocaleDateString()}.`;
                    errorFechaInicio.style.display = "block";
                    fechaInicioInput.className = "error-register";
                    esValido = false;
                } else {
                    errorFechaInicio.style.display = "none";
                    fechaInicioInput.className = "";
                }

                // Validar fecha de fin (domingo)
                if (!validarDomingo(fechaFinInput.value)) {
                    errorFechaFin.textContent = "La fecha de fin debe ser un domingo.";
                    errorFechaFin.style.display = "block";
                    fechaFinInput.className = "error-register";
                    esValido = false;
                } else {
                    errorFechaFin.style.display = "none";
                    fechaFinInput.className = "";
                }

                // Validar que la fecha de fin sea posterior a la fecha de inicio
                const fechaInicio = new Date(fechaInicioInput.value);
                const fechaFin = new Date(fechaFinInput.value);
                if (fechaInicio >= fechaFin) {
                    errorFechaFin.textContent = "La fecha de fin debe ser posterior a la fecha de inicio.";
                    errorFechaFin.style.display = "block";
                    fechaFinInput.className = "error-register";
                    esValido = false;
                }

                if (!esValido) {
                    e.preventDefault(); // Evitar el envío del formulario si hay errores
                }
            });
        }
    }
    if (this.document.getElementById("aBorrarRegistro")) {
        document.getElementById("aBorrarRegistro").addEventListener("click", function (event) {
            window.confirm("¿Estás seguro de borrar este registro?");

            if (!confirmation) {
                // Si el usuario cancela, evitamos que el enlace se ejecute
                document.getElementById("aBorrarRegistro").style.pointerEvents = null;
            }
        });
    }
    const parametsBaseUrls = "http://localhost/GestionDeClases/";

    // funcion general para enviar tecnico,editor al servidor con fetch
    function registrarGeneral(url, idRegistro, campo, valor) {
        let mensaje = document.createElement('div');
        mensaje.id = 'mensaje-temporal';
        let errores = document.createElement('div');
        errores.classList.add('error');
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                idRegistro: idRegistro,
                [campo]: valor
            })
        })
            .then(response => response.text()) // Cambiar a .text() para ver lo que llega como respuesta
            .then(data => {
                try {
                    let jsonData = JSON.parse(data); // Intentamos parsear la respuesta
                    console.log(jsonData.message);
                    if (jsonData.success) {
                        mostrarMensajeExito(jsonData.message); // Mostrar mensaje de éxito
                        window.location.reload();
                    } else {
                        mostrarMensajeError(jsonData.message); // Mostrar mensaje de error
                    }
                } catch (error) {
                    alert('Error al procesar la respuesta del servidor.', 'error');
                    mostrarMensajeError('Ocurrio un error inesperado'); // MostrOar mensaje de error
                }
            })
            .catch(error => {
                console.error('Error en la solicitud fetch:', error);
                mostrarMensajeError('Ocurrio un error inesperado'); // MostrOar mensaje de error
            });
    }

    //***************************************TECNICO******************* */
    document.querySelectorAll('.idTecnico').forEach(select => {
        select.addEventListener('change', function () {
            let idTecnico = this.value; // Obtener el idTecnico seleccionado
            let idRegistro = this.id.replace('idTecnico-', ''); // Obtener el idRegistro del select
            const url = parametsBaseUrls + 'RegistroClaseSemanal/registrarTecnicoEnClase';

            if (idTecnico === '0' || idTecnico === null || idTecnico === '') {
                idTecnico = null;
            }
            console.log("ingresar registro-> " + idRegistro + " el tecnico-> " + idTecnico);
            registrarGeneral(url, idRegistro, "idTecnico", idTecnico); // Llamar a la función con los valores seleccionados
        });
    });

    // Función para mostrar mensajes de éxito
    function mostrarMensajeExito(message) {
        // Eliminar mensaje de error si existe
        const mensajeExistente = document.querySelector('#mensaje-temporal');
        if (mensajeExistente) {
            mensajeExistente.remove();
        }

        // Crear el div de mensaje temporal
        const mensaje = document.createElement('div');
        mensaje.id = 'mensaje-temporal';  // Asignar id
        mensaje.classList.add('mensaje-exito');  // Clase para estilos de éxito

        // Asignar el texto del mensaje de éxito
        mensaje.textContent = message;

        // Agregar el mensaje al contenedor de contenido
        document.querySelector('.contenido').appendChild(mensaje);

        // Eliminar el mensaje después de 2 segundos
        setTimeout(() => {
            mensaje.remove();
        }, 2000);
    }

    // Función para mostrar mensajes de error
    function mostrarMensajeError(message) {
        // Eliminar mensaje de éxito si existe
        const errorExistente = document.querySelector('.error');
        if (errorExistente) {
            errorExistente.remove();
        }

        // Crear el div de errores
        const errores = document.createElement('div');
        errores.classList.add('errores-container');  // Clase para errores

        // Crear el párrafo para el mensaje de error
        const errorMessage = document.createElement('p');
        errorMessage.classList.add('error');  // Asignar id

        // Asignar el texto del mensaje de error
        errorMessage.textContent = message;

        // Agregar el mensaje de error al contenedor de contenido
        errores.appendChild(errorMessage);
        document.querySelector('.contenido').appendChild(errores);

        // Eliminar el mensaje después de 3 segundos
        setTimeout(() => {
            errores.remove();
        }, 2000);
    }

    // Función para actualizar campos genericos
    function actualizarCamposGenericos(url, idRegistro, campo, valor) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                idRegistro: idRegistro,
                [campo]: valor
            })
        })
            .then(response => response.text()) // Cambiar a .text() para ver lo que llega como respuesta
            .then(data => {
                try {
                    console.log('Respuesta del servidor:', data);
                    let jsonData = JSON.parse(data); // Intentamos parsear la respuesta
                    console.log(jsonData.message);

                    if (jsonData.success) {
                        mostrarMensajeExito(jsonData.message); // Mostrar mensaje de éxito
                    } else {
                        mostrarMensajeError(jsonData.message); // Mostrar mensaje de error
                    }

                } catch (error) {
                    console.error("Error al procesar la respuesta del servidor:", error);
                    mostrarMensajeError("Hubo un problema al procesar la respuesta del servidor.");
                }
            })
            .catch(error => {
                console.error("Error en la solicitud Fetch:", error);
                mostrarMensajeError("Hubo un problema con la conexión. Intente nuevamente.");
            });
    }

    // ------------------GRABADO---------------------------------- tecnico
    document.querySelectorAll('.grabado').forEach(select => {
        select.addEventListener('change', function () {
            let grabado = this.value; // Obtener el idTecnico seleccionado
            let idRegistro = this.id.replace('idGrabado-', ''); // Obtener el idRegistro del select
            const url = parametsBaseUrls + 'RegistroClaseSemanal/actualizarCampoGrabado';

            if (grabado) {
                console.log("actualizado el " + idRegistro + " a ->" + grabado);
                actualizarCamposGenericos(url, idRegistro, "grabado", grabado); // Llamar a la función con los valores seleccionados
            } else {

                alert("elije una opcion");
            }
        });
    });
    //   // ------------------BRUTO---------------------------------- tecnico
    document.querySelectorAll('.bruto').forEach(select => {
        select.addEventListener('change', function () {
            let bruto = this.value; // Obtener el bruto seleccionado
            let idRegistro = this.id.replace('idBruto-', ''); // Obtener el idRegistro del select
            const url = parametsBaseUrls + 'RegistroClaseSemanal/actualizarCampoBruto';

            if (bruto) {
                console.log("actualizado el " + idRegistro + " a ->" + bruto);
                actualizarCamposGenericos(url, idRegistro, "bruto", bruto); // Llamar a la función con los valores seleccionados
            } else {

                alert("elije una opcion");
            }
        });
    });
    // ------------------OBSERVACIONESTECNICO---------------------------------- tecnico
    document.querySelectorAll('.observacionesTecnico').forEach(select => {
        select.addEventListener('change', function () {
            let observaciones = this.value; // Obtener el bruto seleccionado
            let idRegistro = this.id.replace('idObservacionesTecnico-', ''); // Obtener el idRegistro del select
            const url = parametsBaseUrls + 'RegistroClaseSemanal/actualizarCampoObservaciones';
            if (observaciones == '') {
                observaciones = '-';
            }

            if (observaciones || observaciones == '') {
                console.log("actualizado el " + idRegistro + " a ->" + observaciones);
                actualizarCamposGenericos(url, idRegistro, "observaciones", observaciones); // Llamar a la función con los valores seleccionados
            } else {
                exit();
            }
        });
    });
    // ------------------editado---------------------------------- editor,trimador
    document.querySelectorAll('.editado').forEach(select => {
        select.addEventListener('change', function () {
            let editado = this.value; // Obtener el bruto seleccionado
            let idRegistro = this.id.replace('idEditado-', ''); // Obtener el idRegistro del select
            const url = parametsBaseUrls + 'RegistroClaseSemanal/actualizarCampoEditado';

            if (editado) {
                console.log("actualizado el " + idRegistro + " a ->" + editado);
                actualizarCamposGenericos(url, idRegistro, "editado", editado); // Llamar a la función con los valores seleccionados
            } else {
                exit();
            }
        });
    });
    // ------------------grabacion---------------------------------- editor,trimador
    document.querySelectorAll('.grabacion').forEach(select => {
        select.addEventListener('change', function () {
            let grabacion = this.value; // Obtener el bruto seleccionado
            let idRegistro = this.id.replace('idgrabacion-', ''); // Obtener el idRegistro del select
            const url = parametsBaseUrls + 'RegistroClaseSemanal/actualizarCampoGrabacionEditor';

            if (grabacion) {
                console.log("actualizado el " + idRegistro + " a ->" + grabacion);
                actualizarCamposGenericos(url, idRegistro, "grabacion", grabacion); // Llamar a la función con los valores seleccionados
            } else {
                exit();
            }
        });
    });
    // ------------------duracionBruto---------------------------------- editor,trimador
    document.querySelectorAll('.duracionBruto').forEach(select => {
        select.addEventListener('change', function () {
            let duracionBruto = this.value; // Obtener el bruto seleccionado
            let idRegistro = this.id.replace('IdduracionBruto-', ''); // Obtener el idRegistro del select
            const url = parametsBaseUrls + 'RegistroClaseSemanal/actualizarCampoDuracionBruto';

            if (duracionBruto) {
                console.log("actualizado el " + idRegistro + " a ->" + duracionBruto);
                actualizarCamposGenericos(url, idRegistro, "duracionBruto", duracionBruto); // Llamar a la función con los valores seleccionados
            } else {
                exit();
            }
        });
    });
    // ------------------observacionesEditor---------------------------------- editor,trimador
    document.querySelectorAll('.observacionesEditor').forEach(select => {
        select.addEventListener('change', function () {
            let observaciones = this.value; // Obtener el bruto seleccionado
            let idRegistro = this.id.replace('idObservacionesEditor-', ''); // Obtener el idRegistro del select
            const url = parametsBaseUrls + 'RegistroClaseSemanal/actualizarCampoObservacionesEditor';

            if (observaciones) {
                console.log("actualizado el " + idRegistro + " a ->" + observaciones);
                actualizarCamposGenericos(url, idRegistro, "observacionesEditor", observaciones); // Llamar a la función con los valores seleccionados
            } else {

                exit();
            }
        });
    });

    if (document.getElementById('tooltip')) {
        document.getElementById('tooltip-container').addEventListener('click', function(){
            const tooltip = document.getElementById('tooltip');

            if (tooltip.classList.contains('tooltip-clicked')) {
                tooltip.classList.remove('tooltip-clicked');
            } else {
                tooltip.classList.add('tooltip-clicked');
            }
        });
    }

});