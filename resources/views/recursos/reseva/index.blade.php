<form action="{{ route('pdf.reservas-dia') }}" method="GET" class="d-flex align-items-center">
    <input type="date" name="fecha" class="form-control me-2" required>
    <button type="submit" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Descargar PDF del Día
    </button>
</form>