$(function () {
    let tabla = $("#tabla-dt").DataTable({
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
    $("#filtro-nombre").on("keyup", function () {
        tabla.column(1).search(this.value).draw();
    });

    // Filtro por marca (columna 2)
    $("#filtro-marca").on("keyup", function () {
        tabla.column(2).search(this.value).draw();
    });
    // Filtro por estado
    $("#filtro-estado").on("change", function () {
        var estado = $(this).val(); // valor seleccionado
        tabla
            .column(3) // columna “Estado” (0-based)
            .search(estado ? "^" + estado + "$" : "", true, false)
            .draw();
    });

    // Filtro por categoría (columna 5)
    $("#filtro-categoria").on("change", function () {
        tabla.column(4).search(this.value).draw();
    });
});
