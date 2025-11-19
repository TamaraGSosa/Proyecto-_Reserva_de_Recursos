@extends('adminlte::page')

@section('title', 'Reservas')

@section('plugins.Datatables', true)
@section('plugins.DataTablesResponsive', true)
@section('plugins.TempusDominusBs4', true)

@php
$config = ['format' => 'DD/MM/YYYY',
"locale" => "es",];
@endphp


@section('content_header')
@stop

@section('content')
<div class="container-fluid">

    {{-- Botón Nueva Reserva --}}
    <div class="row mb-3 align-items-center py-4 ">
        <div class="col d-flex justify-content-start">
            <button id="btnNuevaReserva" class="btn btn-primary " data-toggle="modal" data-target="#modalNuevaReserva">
                <i class="fas fa-plus"></i> Crear Reserva
            </button>
        </div>
        <div class="col d-flex justify-content-end">
            <form method="POST" action="{{ route('reservations.update') }}" style="display: inline;">
                @csrf
                <button id="btnActualizarEstados" type="submit" class="btn btn-secondary" title="Actualizar estados">
                    <i class="fas fa-sync-alt"></i> Actualizar Estados
                </button>
            </form>
        </div>
    </div>


    {{-- Alertas de sesión --}}
    @if(session('success'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- FILTROS --}}
    <div class="row mb-3">

        <div class="col-12 col-md-3 mb-2">
            <label for="filtro-profile">Nombre</label>
            <input type="text" id="filtro-profile" class="form-control" placeholder="Buscar por nombre">
        </div>

        <div class="col-12 col-md-3 mb-2">
            <label for="filtro-createby">Rol</label>
            <select id="filtro-createby" class="form-control">
                <option value="">Todos los roles</option>
                <option value="administrador">Administrador</option>
                <option value="personal">Personal</option>
            </select>
        </div>

        <div class="col-12 col-md-3 mb-2">
            <label for="filtro-status">Estado</label>
            <select id="filtro-status" class="form-control">
                <option value="">Todos los estados</option>
                @foreach ($status_reservations as $est)
                <option value="{{ $est->name ?? $est }}">{{ $est->name ?? $est }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-3 mb-2">
            <x-adminlte-input-date
                id="filtro-date"
                name="filtro-date"
                :config="$config"
                label="Fecha" />
        </div>

    </div>


    {{-- Tabla de reservas --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($reservations->isEmpty())
                    <p class="alert alert-danger text-center">No hay reservas cargadas</p>
                    @else
                    <div class="table-responsive">
                        <table id="tabla-reservas" class="table table-striped table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Persona</th>
                                    <th>Creado por</th>
                                    <th>Recursos</th>
                                    <th>Estado</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->id }}</td>
                                    <td>{{ Str::limit(optional(optional($reservation->profile)->person)->first_name ?? 'Sin perfil', 15) }}
                                        {{ Str::limit(optional(optional($reservation->profile)->person)->last_name ?? '', 15) }}
                                    </td>
                                    <td>{{ $reservation->creator->getRoleNames()->first() ?? 'Sin rol' }}</td>
                                    <td>
                                        @foreach ($reservation->resources as $resource)
                                        <span class="badge bg-secondary">{{ Str::limit($resource->name, 10) }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $reservation->status->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y - H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y - H:i') }}</td>

                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                                <i class="fas fa-cogs"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item btnVerReserva" href="#" data-id="{{ $reservation->id }}">
                                                    <i class="fas fa-eye text-info"></i> Ver detalles
                                                </a>
                                                @if(in_array($reservation->status->name, ['En curso', 'No entregado']))
                                                <form method="POST" action="{{ route('reservations.change-status', $reservation) }}" style="display: inline;" id="formEntregada{{ $reservation->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_id" value="4">
                                                    <button type="button" class="dropdown-item btnConfirmarEntregada" data-form="formEntregada{{ $reservation->id }}">
                                                        <i class="fas fa-check text-success"></i> Marcar entregada
                                                    </button>
                                                </form>
                                                @endif
                                                @if($reservation->status->name === 'Entregado')
                                                <form method="POST" action="{{ route('reservations.change-status', $reservation) }}" style="display: inline;" id="formNoEntregada{{ $reservation->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_id" value="5">
                                                    <button type="button" class="dropdown-item btnConfirmarNoEntregada" data-form="formNoEntregada{{ $reservation->id }}">
                                                        <i class="fas fa-undo text-warning"></i> Marcar no entregado
                                                    </button>
                                                </form>
                                                @endif
                                                @if($reservation->status->name !== 'Cancelada' && $reservation->status->name !== 'Entregado' && $reservation->status->name !== 'No entregado')
                                                <a class="dropdown-item btnEditarReserva" href="#" data-id="{{ $reservation->id }}">
                                                    <i class="fas fa-edit text-primary"></i> Editar
                                                </a>
                                                @if($reservation->status->name === 'Pendiente')
                                                <form method="POST" action="{{ route('reservations.change-status', $reservation) }}" style="display: inline;" id="formIniciar{{ $reservation->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_id" value="2">
                                                    <button type="button" class="dropdown-item btnConfirmarIniciar" data-form="formIniciar{{ $reservation->id }}">
                                                        <i class="fas fa-play text-info"></i> Iniciar reserva
                                                    </button>
                                                </form>
                                                @endif
                                                <form method="POST" action="{{ route('reservations.change-status', $reservation) }}" style="display: inline;" id="formCancelar{{ $reservation->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_id" value="3">
                                                    <button type="button" class="dropdown-item btnConfirmarCancelar" data-form="formCancelar{{ $reservation->id }}">
                                                        <i class="fas fa-ban text-warning"></i> Cancelar
                                                    </button>
                                                </form>
                                                @endif
                                                @if($reservation->status->name !== 'Entregado' && $reservation->status->name !== 'No entregado')
                                                <div class="dropdown-divider"></div>
                                                <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Estás seguro de eliminar permanentemente esta reserva? Esta acción no se puede deshacer.')">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>


                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Crear/Editar Reserva --}}
    <div class="modal fade" id="modalNuevaReserva" tabindex="-1" role="dialog" aria-labelledby="modalNuevaReservaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevaReservaLabel">Nueva Reserva</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formReserva" method="POST" action="{{ route('reservations.store') }}" class="p-3">
                    @csrf
                    <input type="hidden" name="_method" id="metodoReserva" value="POST">
                    <input type="hidden" name="reservation_id" id="reservation_id" value="">
                    <input type="hidden" name="profile_id" id="profile_id" value="">

                    {{-- Primera fila: Fechas --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="start_time">Hora de inicio</label>
                            <input type="datetime-local" class="form-control" name="start_time" id="start_time" value="{{ old('start_time') }}" min="{{ date('Y-m-d') }}T00:00">
                            <span class="text-danger" id="error-start_time">{{ $errors->first('start_time') }}</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="end_time">Hora final</label>
                            <input type="datetime-local" class="form-control" name="end_time" id="end_time" value="{{ old('end_time') }}" min="{{ date('Y-m-d') }}T00:00">
                            <span class="text-danger" id="error-end_time">{{ $errors->first('end_time') }}</span>
                        </div>
                    </div>

                    {{-- Segunda fila: Recurso y Estado --}}
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="resource_search">Buscar Recurso</label>
                            <div class="input-group">
                                <input list="resources_list" id="resource_search" class="form-control" placeholder="Buscar recurso...">
                                <datalist id="resources_list"></datalist>
                                <div class="input-group-append">
                                    <button type="button" id="btnAddResource" class="btn btn-secondary">Agregar</button>
                                </div>
                            </div>
                            <span class="text-danger" id="error-resource_id">{{ $errors->first('resource_id') }}</span>
                            <div id="selectedResources" class="mt-2"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="status_id">Estado inicial</label>
                            <select name="status_id" id="status_id" class="form-control" required>
                                <option value="2" {{ old('status_id') == '2' ? 'selected' : '' }}>En curso</option>
                                <option value="1" {{ old('status_id') == '1' ? 'selected' : '' }}>Pendiente</option>
                            </select>
                            <span class="text-danger" id="error-status_id">{{ $errors->first('status_id') }}</span>
                        </div>
                    </div>

                    {{-- Tercera fila: DNI, Nombre y Apellido --}}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="dni">DNI</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="dni" id="dni" placeholder="Ingrese DNI" value="{{ old('dni') }}">
                                <div class="input-group-append">
                                    <button type="button" id="btnVerificarDNI" class="btn btn-success">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <span class="text-danger" id="error-dni">{{ $errors->first('dni') }}</span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="first_name">Nombre</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Nombre" readonly value="{{ old('first_name') }}">
                            <span class="text-danger" id="error-first_name">{{ $errors->first('first_name') }}</span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="last_name">Apellido</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Apellido" readonly value="{{ old('last_name') }}">
                            <span class="text-danger" id="error-last_name">{{ $errors->first('last_name') }}</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Modal Ver Detalles Reserva --}}
    <x-adminlte-modal id="modalVerReserva" size="lg" icon="fas fa-eye" v-centered static-backdrop>
        <div id="detallesReserva">
            <!-- Los detalles se cargarán aquí vía AJAX -->
        </div>
        <x-slot name="footerSlot">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </x-slot>
    </x-adminlte-modal>

    {{-- Modal Confirmación --}}
    <x-adminlte-modal id="modalConfirmacion" size="md" icon="fas fa-question-circle" v-centered static-backdrop>
        <div id="mensajeConfirmacion"></div>
        <x-slot name="footerSlot">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="btnConfirmarAccion">Confirmar</button>
        </x-slot>
    </x-adminlte-modal>

</div>
@stop

@section('js')
@if ($errors->any())
<script>
    window.modalHasErrors = true;
    $('#modalNuevaReserva').modal('show');
</script>
@endif
<script>
    // --- Variables y funciones de recursos ---
    let selectedResources = [];

    function updateSelectedResources() {
        const container = $('#selectedResources');
        container.empty();
        selectedResources.forEach((r, i) => {
            container.append(`
            <div class="badge badge-secondary mr-2 mb-2" style="display: inline-block;">
                ${r.name}
                <button type="button" class="btn btn-sm btn-danger ml-1" onclick="removeResource(${i})">×</button>
            </div>
        `);
        });
        // inputs ocultos
        $('#selectedResources').nextAll('input[name="resource_id[]"]').remove();
        selectedResources.forEach(r => {
            $('#selectedResources').after(`<input type="hidden" name="resource_id[]" value="${r.id}">`);
        });
    }

    $(document).ready(function() {

        // Reset modal
        function resetModal() {
            window.modalHasErrors = false;
            $('#formReserva')[0].reset();
            $('#formReserva .is-invalid').removeClass('is-invalid');
            $('#formReserva .text-danger').text('');
            $('#modalNuevaReserva .modal-title').text('Nueva Reserva');
            $('#formReserva').attr('action', '{{ route("reservations.store") }}');
            $('#metodoReserva').val('POST');
            $('#reservation_id,#profile_id').val('');

            $('#status_id').val('2');

            $('#first_name,#last_name').prop('readonly', true);
            $('#dni').prop('readonly', false).removeClass('bg-light');

            $('#btnVerificarDNI')
                .prop('disabled', true)
                .removeClass()
                .addClass('btn btn-success')
                .html('<i class="fas fa-search"></i>');

            selectedResources = [];
            updateSelectedResources();

            personaEncontrada = false;
        }

        window.removeResource = function(index) {
            selectedResources.splice(index, 1);
            updateSelectedResources();
        }

        function loadAvailableResources(excludeReservationId = null) {
            const startTime = $('#start_time').val();
            const endTime = $('#end_time').val();
            if (!startTime || !endTime) return;

            const params = {
                start_time: startTime,
                end_time: endTime
            };
            if (excludeReservationId) {
                params.reservation_id = excludeReservationId;
            }

            $.get('{{ route("resources.available") }}', params, function(data) {
                console.log('Datos recibidos:', data); // <--- aquí ves lo que devuelve
                const datalist = $('#resources_list');
                datalist.empty();
                data.forEach(resource => {
                    datalist.append(`<option value="${resource.name}" data-id="${resource.id}"></option>`);
                });

                // Limpiar recursos seleccionados al cambiar fechas solo si no hay errores y no hay recursos seleccionados
                if (!window.modalHasErrors && selectedResources.length === 0) {
                    selectedResources = [];
                    updateSelectedResources();
                }
            }).fail(() => alert('Error al cargar recursos disponibles.'));
        }


        // --- Eventos ---
        $('#start_time,#end_time').on('input change', loadAvailableResources);

        // Si hay errores, cargar recursos disponibles si hay fechas
        if (window.modalHasErrors && $('#start_time').val() && $('#end_time').val()) {
            loadAvailableResources();
        }

        $('#btnAddResource').click(function() {
            const val = $('#resource_search').val().trim();
            if (!val) return;
            const option = $('#resources_list option').filter(function() {
                return $(this).val() === val;
            });
            const resourceId = option.data('id');
            const resourceName = val;
            if (!resourceId || selectedResources.some(r => r.id === resourceId)) return;
            selectedResources.push({
                id: resourceId,
                name: resourceName
            });
            updateSelectedResources();
            $('#resource_search').val('');
        });

        // --- Modal Nueva Reserva ---
        $('#btnNuevaReserva').click(function() {
            resetModal();
            $('#modalNuevaReserva').modal('show');
        });



        // --- Función para mostrar modal de confirmación ---
        function mostrarConfirmacion(mensaje, callback) {
            $('#mensajeConfirmacion').text(mensaje);
            $('#modalConfirmacion').modal('show');
            $('#btnConfirmarAccion').text('Confirmar').off('click').on('click', function() {
                $('#modalConfirmacion').modal('hide');
                callback();
            });
        }

        // --- Confirmaciones de acciones ---
        $('.btnConfirmarEntregada').click(function() {
            let formId = $(this).data('form');
            mostrarConfirmacion('¿Estás seguro de marcar esta reserva como entregada?', function() {
                $('#' + formId).submit();
            });
        });

        $('.btnConfirmarNoEntregada').click(function() {
            let formId = $(this).data('form');
            mostrarConfirmacion('¿Estás seguro de marcar esta reserva como no entregada?', function() {
                $('#' + formId).submit();
            });
        });

        $('.btnConfirmarCancelar').click(function() {
            let formId = $(this).data('form');
            mostrarConfirmacion('¿Estás seguro de cancelar esta reserva?', function() {
                $('#' + formId).submit();
            });
        });

        $('.btnConfirmarIniciar').click(function() {
            let formId = $(this).data('form');
            mostrarConfirmacion('¿Estás seguro de iniciar esta reserva?', function() {
                $('#' + formId).submit();
            });
        });

        // --- Botón Ver Detalles Reserva ---
        $('.btnVerReserva').click(function() {
            let reservationId = $(this).data('id');

            $.get('/panel/reservations/' + reservationId + '/json', function(data) {
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información de la Reserva</h5>
                            <p><strong>ID:</strong> ${data.id}</p>
                            <p><strong>Estado:</strong> <span class="badge badge-${data.status.name === 'En curso' ? 'success' : data.status.name === 'Pendiente' ? 'warning' : 'secondary'}">${data.status.name}</span></p>
                            <p><strong>Inicio:</strong> ${new Date(data.start_time).toLocaleString('es-ES')}</p>
                            <p><strong>Fin:</strong> ${new Date(data.end_time).toLocaleString('es-ES')}</p>
                            <p><strong>Creado por:</strong> ${data.creator ? data.creator.name : 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Información de la Persona</h5>
                            <p><strong>DNI:</strong> ${data.profile.person.DNI}</p>
                            <p><strong>Nombre:</strong> ${data.profile.person.first_name} ${data.profile.person.last_name}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Recursos Reservados</h5>
                            <div class="d-flex flex-wrap gap-2">
                `;

                data.resources.forEach(resource => {
                    html += `<span class="badge badge-primary">${resource.name}</span>`;
                });

                html += `
                            </div>
                        </div>
                    </div>
                `;

                $('#detallesReserva').html(html);
                $('#modalVerReserva').modal('show');
            }).fail(function() {
                alert('Error al cargar los detalles de la reserva');
            });
        });

        // --- Botón Editar Reserva ---
        $('.btnEditarReserva').click(function() {
            let reservationId = $(this).data('id');

            resetModal();

            // Cambiar título del modal y acción del formulario
            $('#modalNuevaReserva .modal-title').text('Editar Reserva');
            $('#formReserva').attr('action', '/panel/reservations/' + reservationId);
            $('#metodoReserva').val('PUT');
            $('#reservation_id').val(reservationId);

            // Llenar campos vía AJAX
            $.get('/panel/reservations/' + reservationId + '/json', function(data) {
                $('#start_time').val(data.start_time.replace(' ', 'T'));
                $('#end_time').val(data.end_time.replace(' ', 'T'));
                $('#dni').val(data.profile.person.DNI);
                $('#first_name').val(data.profile.person.first_name);
                $('#last_name').val(data.profile.person.last_name);
                $('#profile_id').val(data.profile_id);

                // Cargar recursos seleccionados
                selectedResources = data.resources.map(r => ({
                    id: r.id,
                    name: r.name
                }));
                updateSelectedResources();

                // Cargar recursos disponibles (solo los no ocupados por otras reservas)
                loadAvailableResources();

                // Marcar como persona encontrada
                personaEncontrada = true;
                $('#dni').prop('readonly', true).addClass('bg-light');
                $('#first_name,#last_name').prop('readonly', true);
                $('#btnVerificarDNI').removeClass().addClass('btn btn-danger').html('<i class="fas fa-trash"></i>');

                $('#modalNuevaReserva').modal('show');
            }).fail(function() {
                alert('Error al cargar los datos de la reserva');
            });
        });

        // --- Búsqueda persona por DNI ---
        let personaEncontrada = false;
        const $dni = $('#dni'),
            $firstName = $('#first_name'),
            $lastName = $('#last_name'),
            $btn = $('#btnVerificarDNI');

        function resetFormulario() {
            personaEncontrada = false;
            $dni.prop('readonly', false).val('');
            $firstName.prop('readonly', true).val('');
            $lastName.prop('readonly', true).val('');
            $('#profile_id').val('');
            $btn.prop('disabled', true).removeClass().addClass('btn btn-success').html('<i class="fas fa-search"></i>');
        }

        $dni.on('input', () => {
            $btn.prop('disabled', !$dni.val().trim());
        });
        $btn.on('click', () => {
            resetFormulario(); // Limpiar todo
        });
        $dni.on('blur', () => {
            if (!personaEncontrada && $dni.val().trim()) buscarPersona();
        });

        function buscarPersona() {
            const dni = $dni.val().trim();
            if (!dni) return;
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            $.get('/panel/persons/search/' + dni, function(data) {
                if (data && data.first_name) {
                    personaEncontrada = true;
                    $dni.prop('readonly', true).addClass('bg-light');
                    $firstName.val(data.first_name).prop('readonly', true);
                    $lastName.val(data.last_name).prop('readonly', true);
                    $('#profile_id').val(data.profile_id);
                    $btn.removeClass().addClass('btn btn-danger').html('<i class="fas fa-trash"></i>');
                } else {
                    personaEncontrada = false;
                    $dni.prop('readonly', true).removeClass('bg-light');
                    $firstName.val('').prop('readonly', false);
                    $lastName.val('').prop('readonly', false);
                    $('#profile_id').val('');
                    $btn.removeClass().addClass('btn btn-danger').html('<i class="fas fa-trash"></i>');
                }
            }).fail(() => alert('Error al buscar la persona.')).always(() => $btn.prop('disabled', false));
        }

        // --- Reset modal al cerrarlo ---
        $('#modalNuevaReserva').on('hidden.bs.modal', function() {
            if (window.modalHasErrors) {
                console.log("No se resetea porque hay errores del backend.");
                return;
            }
            resetModal();
        });

    });
</script>
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reconstruir recursos seleccionados
        const oldResources = @json(old('resource_id', []));
        console.log('oldResources:', oldResources);
        if (oldResources.length > 0) {
            // Obtener nombres de recursos
            $.get('{{ route("resources.available") }}', {
                start_time: '2000-01-01T00:00',
                end_time: '2000-01-01T00:01'
            }, function(data) {
                console.log('data recursos:', data);
                selectedResources = oldResources.map(id => {
                    const res = data.find(r => r.id == id);
                    return res ? {
                        id: res.id,
                        name: res.name
                    } : {
                        id: id,
                        name: 'Desconocido'
                    };
                });
                console.log('selectedResources reconstruidos:', selectedResources);
                updateSelectedResources();
            });
        }
    });
</script>
@endif
<script src="{{ asset('js/datatablereservations.js') }}"></script>
@stop