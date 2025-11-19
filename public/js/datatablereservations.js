$(function () {
  $('#filtro-date').datetimepicker({
        locale: 'es',        // español
        format: 'DD/MM/YYYY', // opcional, igual que tu config de Blade
    });

    // --- Inicializar DataTable ---
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
        dom: "lrtip",
    });

    // --- Fecha inicial (hoy) ---
    let hoy = moment().format('DD/MM/YYYY'); // usa moment.js que trae AdminLTE
    $('#filtro-date').val(hoy); // setea input
    filtrarFecha(hoy);

    // --- Eventos filtros ---
    $('#filtro-date').on('change.datetimepicker', function(e){
        filtrarFecha($(this).val());
    });

    $('#filtro-profile').on("keyup", function () {
        tabla.column(1).search(this.value).draw();
    });

    $('#filtro-createby').on("change", function () {
        tabla.column(2).search(this.value).draw();
    });

    $('#filtro-status').on("change", function () {
        var estado = $(this).val();
        tabla.column(4).search(estado ? "^" + estado + "$" : "", true, false).draw();
    });

    // --- Función filtrar fecha ---
    function filtrarFecha(fecha) {
        if(!fecha){
            tabla.column(5).search('').draw();
            return;
        }

        // La fecha ya viene en DD/MM/YYYY
        tabla.column(5).search(fecha, true, false).draw();
    }
});
