@extends('adminlte::page')

@section('title', 'Mis Reservas')

@section('plugins.Datatables', true)
@section('plugins.DataTablesResponsive', true)
@section('plugins.Datatables', true)
@section('plugins.DataTablesResponsive', true)



@section('content_header')
@stop

@section('content')
<div class="container-fluid">

    {{-- Botón Nueva Reserva --}}
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center mt-4">
            <h2 class="m-0 h5 h-md2">Mis Reservas</h2>
            <button id="btnNuevaReserva" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevaReserva">
                <i class="fas fa-plus"></i> Crear Reserva
            </button>
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

    {{-- Reservas en tarjetas --}}
    <div class="row">
        @if ($reservations->isEmpty())
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <h4>No tienes reservas creadas</h4>
                <p>Crea tu primera reserva haciendo click en "Crear Reserva"</p>
            </div>
        </div>
        @else
        @foreach ($reservations as $reservation)
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-{{ $reservation->status->name === 'En curso' ? 'success' : ($reservation->status->name === 'Pendiente' ? 'warning' : ($reservation->status->name === 'Entregado' ? 'info' : 'secondary')) }}">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-calendar-alt"></i> Reserva #{{ $loop->iteration }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <strong>Inicio:</strong><br>
                            {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-6">
                            <strong>Fin:</strong><br>
                            {{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <strong>Persona:</strong><br>
                        {{ Str::limit(optional(optional($reservation->profile)->person)->first_name ?? 'Sin perfil', 20) }}
                        {{ Str::limit(optional(optional($reservation->profile)->person)->last_name ?? '', 20) }}
                    </div>

                    <div class="mb-3">
                        <strong>Recursos:</strong><br>
                        @if($reservation->resources->isEmpty())
                        <span class="text-muted">Sin recursos</span>
                        @else
                        @foreach ($reservation->resources as $resource)
                        <span class="badge badge-secondary">{{ Str::limit($resource->name, 15) }}</span>
                        @endforeach
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Estado:</strong>
                        <span class="badge badge-{{ $reservation->status->name === 'En curso' ? 'success' : ($reservation->status->name === 'Pendiente' ? 'warning' : ($reservation->status->name === 'Entregado' ? 'info' : 'secondary')) }}">
                            {{ $reservation->status->name }}
                        </span>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-sm btn-info btnVerPersonal" data-id="{{ $reservation->id }}">
                            <i class="fas fa-eye"></i> Ver
                        </button>
                        @if($reservation->status->name === 'Pendiente')
                        <button class="btn btn-sm btn-warning btnEditarPersonal" data-id="{{ $reservation->id }}">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
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

                   {{-- Información del usuario --}}
                   <div class="alert alert-info mb-3">
                       <i class="fas fa-user"></i>
                       <strong>Reservando para:</strong> {{ auth()->user()->name }}
                       @if(auth()->user()->profile)
                           (DNI: {{ auth()->user()->profile->person->DNI ?? 'No registrado' }})
                       @endif
                   </div>

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

                   {{-- Segunda fila: Recurso --}}
                   <div class="form-row">
                       <div class="form-group col-12">
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
                   </div>

                   {{-- Campos ocultos --}}
                   <input type="hidden" name="dni" id="dni" value="{{ auth()->user()->profile->person->DNI ?? '' }}">
                   <input type="hidden" name="first_name" id="first_name" value="{{ auth()->user()->profile->person->first_name ?? '' }}">
                   <input type="hidden" name="last_name" id="last_name" value="{{ auth()->user()->profile->person->last_name ?? '' }}">

                   <div class="d-flex justify-content-end mt-3">
                       <button type="submit" class="btn btn-primary">Guardar Reserva</button>
                   </div>
               </form>

           </div>
       </div>
   </div>

    {{-- Modal Ver Detalles --}}
    <x-adminlte-modal id="modalVerReserva" size="lg" icon="fas fa-eye" v-centered static-backdrop>
        <div id="detallesReserva">
            <!-- Los detalles se cargarán aquí -->
        </div>
        <x-slot name="footerSlot">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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

            $('#dni').val('{{ auth()->user()->profile->person->DNI ?? "" }}');
            $('#first_name').val('{{ auth()->user()->profile->person->first_name ?? "" }}');
            $('#last_name').val('{{ auth()->user()->profile->person->last_name ?? "" }}');

            selectedResources = [];
            updateSelectedResources();
        }

        window.removeResource = function(index) {
            selectedResources.splice(index, 1);
            updateSelectedResources();
        }

        function loadAvailableResources(excludeReservationId = null) {
            const startTime = $('#start_time').val();
            const endTime = $('#end_time').val();
            if (!startTime || !endTime) return;

            const params = { start_time: startTime, end_time: endTime };
            if (excludeReservationId) params.reservation_id = excludeReservationId;

            $.get('{{ route("resources.available") }}', params, function(data) {
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

        // Actualizar min de end_time cuando cambia start_time
        $('#start_time').on('change', function() {
            const startVal = $(this).val();
            $('#end_time').attr('min', startVal);
            // Si end_time es anterior a start, resetearlo
            const endVal = $('#end_time').val();
            if (endVal && endVal < startVal) {
                $('#end_time').val('');
            }
        });

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

        // --- Ver Detalles Reserva ---
        $('.btnVerPersonal').click(function() {
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
        $('.btnEditarPersonal').click(function() {
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

                // Recursos seleccionados
                selectedResources = data.resources.map(r => ({ id: r.id, name: r.name }));
                updateSelectedResources();

                // Recursos disponibles
                loadAvailableResources();

                $('#modalNuevaReserva').modal('show');
            }).fail(function() {
                alert('Error al cargar los datos de la reserva');
            });
        });

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
    if (oldResources.length > 0) {
        // Obtener nombres de recursos
        $.get('{{ route("resources.available") }}', {start_time: '2000-01-01T00:00', end_time: '2000-01-01T00:01'}, function(data) {
            selectedResources = oldResources.map(id => {
                const res = data.find(r => r.id == id);
                return res ? {id: res.id, name: res.name} : {id: id, name: 'Desconocido'};
            });
            updateSelectedResources();
        });
    }
});
</script>
@endif
@endsection
