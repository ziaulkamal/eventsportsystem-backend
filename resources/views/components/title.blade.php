@if (isset($section))
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4>{{ $title }}</h4>
            <h6>{{ isset($description) ? $description : '' }}</h6>

        </div>
    </div>
    <ul class="table-top-head">
        <li>
            <a id="refreshPages" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
        </li>
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
        </li>
    </ul>
    @yield('button_action')
</div>
@endif

