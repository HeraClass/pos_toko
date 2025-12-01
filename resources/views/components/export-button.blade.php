<div class="export-buttons">
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
            <i class="fas fa-download"></i> {{ $title }}
        </button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="{{ route($route . '.export.pdf') }}?{{ http_build_query($filters) }}" target="_blank">
                    <i class="fas fa-file-pdf text-danger"></i> Export PDF
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route($route . '.export.csv') }}?{{ http_build_query($filters) }}" target="_blank">
                    <i class="fas fa-file-csv text-success"></i> Export CSV
                </a>
            </li>
        </ul>
    </div>
</div>