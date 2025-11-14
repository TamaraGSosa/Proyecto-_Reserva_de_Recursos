$(function () {

   let hoy = new Date();
let dia = String(hoy.getDate()).padStart(2, '0');
let mes = String(hoy.getMonth() + 1).padStart(2, '0'); // Los meses empiezan en 0
let anio = hoy.getFullYear();

// Formato YYYY-MM-DD para input type="date"
let fechaHoy = `${anio}-${mes}-${dia}`;

$('#filtro-date').val(fechaHoy);


    // --- Rol por defecto ---
    $('#filtro-createby').val('administrador');

    let tabla = $("#tabla-reservas").DataTable({
        responsive: true,
        autoWidth: false,
        order: [[0, "desc"]],
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron resultados",
            sEmptyTable: "Ningún dato disponible en esta tabla",
            sInfo: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            sInfoEmpty: "Mostrando 0 a 0 de 0 registros",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            sSearch: "Buscar:",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior",
            },
        },
        dom: "lrtip", // Oculta el buscador global
    });



    // Filtro por nombre (columna 1)
    $("#filtro-profile").on("keyup", function () {
        tabla.column(1).search(this.value).draw();
    });

    // Filtro por creador/rol (columna 2)
    $("#filtro-createby").on("change", function () {
        tabla.column(2).search(this.value).draw();
    });

    // Filtro por estado
    $("#filtro-status").on("change", function () {
        var estado = $(this).val(); // valor seleccionado
        tabla
            .column(4) // columna “Estado” (0-based)
            .search(estado ? "^" + estado + "$" : "", true, false)
            .draw();
    });

 $("#filtro-date").on("change", function () {
        filtrarFecha($(this).val());
    });

    // --- Filtrado inicial por fecha hoy ---
    filtrarFecha(fechaHoy);

    // --- Función para filtrar fecha ---
    function filtrarFecha(fecha) {
        if (!fecha) {
            tabla.column(5).search('').draw();
            return;
        }
        let parts = fecha.split('-'); // YYYY-MM-DD
        let fechaFormateada = parts[2] + '/' + parts[1] + '/' + parts[0]; // dd/mm/yyyy
        tabla.column(5).search(fechaFormateada, true, false).draw();
    }



});
