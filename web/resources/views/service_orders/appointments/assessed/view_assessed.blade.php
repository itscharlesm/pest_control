<style>
    @media print {
        body {
            zoom: 100%;
        }

        .no-print {
            display: none !important;
        }

        .print-only {
            display: block !important;
        }

        .print-hide {
            display: none !important;
        }
    }

    .table-responsive {
        overflow: visible !important;
    }

    #sectionB {
        overflow: visible !important;
    }

    .content-wrapper {
        overflow-x: hidden;
    }
</style>

@extends('layouts.themes.main')

@section('content')
    {{-- Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Assessed Appointment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Appointment</li>
                        <li class="breadcrumb-item">Assessed</li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <section class="content">
        @include('layouts.partials.onclick')
        @include('layouts.partials.alerts')
        @include('layouts.partials.modal_style')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body overflow-auto">
                    <div class="text-center mb-2">
                        <h5 style="margin: 0; font-weight: bold;">GO FORWARD PEST CONTROL</h5>
                        <p style="margin: 0;">{{ $display->branch_name }}</p>
                    </div>

                    <div class="mb-4 position-relative text-center">
                        <strong style="color: red;">SERVICE ORDER</strong>

                        <strong style="position: absolute; right: 0; top: 0;">
                            SA-{{ str_pad($display->svc_sa_number, 6, '0', STR_PAD_LEFT) }}
                        </strong>
                    </div>
                    <div class="row">

                        {{-- Appointment Information Display --}}
                        <div class="table-responsive overflow-auto" id="sectionA">
                            <table class="table table-bordered text-left align-middle">
                                <tbody>
                                    <tr>
                                        <th colspan="6" class="text-center table-light">CLIENT INFORMATION</th>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">CLIENT NAME</td>
                                        <td>{{ $display->usr_first_name }} {{ $display->usr_last_name }}</td>
                                        <td style="font-weight: bold;">EMAIL</td>
                                        <td>{{ $display->usr_email }}</td>
                                        <td style="font-weight: bold;">MOBILE</td>
                                        <td>{{ $display->usr_mobile }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">ADDRESS</td>
                                        <td colspan="3">
                                            {{ implode(
                                                ', ',
                                                array_filter([
                                                    $display->uadd_street,
                                                    $display->uadd_barangay,
                                                    $display->uadd_city,
                                                    $display->uadd_province,
                                                    $display->uadd_region,
                                                ]),
                                            ) }}
                                        </td>
                                        <td style="font-weight: bold;">ADDRESS TYPE</td>
                                        <td>{{ $display->add_name }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="6" class="text-center table-light">SERVICE INFORMATION</th>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">BRANCH</td>
                                        <td>{{ $display->branch_name }}</td>
                                        <td style="font-weight: bold;">STATUS</td>
                                        <td>{{ $display->svc_status }}</td>
                                        <td style="font-weight: bold;">PAYMENT STATUS</td>
                                        <td>{{ $display->svc_payment_status }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">PROPERTY TYPE</td>
                                        <td>{{ $display->svc_property_type }}</td>
                                        <td style="font-weight: bold;">INFESTATION</td>
                                        <td>{{ $display->svc_infestation }}</td>
                                        <td style="font-weight: bold;">DISTANCE</td>
                                        <td>{{ $display->svc_km_distance }} KM</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">IS TERMITE</td>
                                        <td>{{ $display->svc_is_termite ? 'YES' : 'NO' }}</td>
                                        <td style="font-weight: bold;">IS PACKAGE</td>
                                        <td colspan="3">{{ $display->svc_is_package ? 'YES' : 'NO' }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="6" class="text-center table-light">PRICING</th>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">INITIAL PRICE</td>
                                        <td>₱{{ number_format($display->svc_initial_price, 2) }}</td>
                                        <td style="font-weight: bold;">LOCATION PRICE</td>
                                        <td>₱{{ number_format($display->svc_location_price, 2) }}</td>
                                        <td style="font-weight: bold;">FIXED PRICE</td>
                                        <td>₱{{ number_format($display->svc_fixed_price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">FINAL PRICE</td>
                                        <td>₱{{ number_format($display->svc_final_price, 2) }}</td>
                                        <td style="font-weight: bold;">BALANCE</td>
                                        <td colspan="3">₱{{ number_format($display->svc_balance, 2) }}</td>
                                    </tr>

                                    @if ($display->svc_is_termite)
                                        <tr>
                                            <th colspan="6" class="text-center table-light">TERMITE CASE</th>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">TREATMENT TYPE</td>
                                            <td>{{ $display->svc_type_treatment }}</td>
                                            <td style="font-weight: bold;">INITIAL SQM</td>
                                            <td>{{ $display->svc_sqm_initial }}</td>
                                            <td style="font-weight: bold;">FINAL SQM</td>
                                            <td>{{ $display->svc_sqm_final }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">WITH DEVICE</td>
                                            <td>{{ $display->svc_with_device ? 'YES' : 'NO' }}</td>
                                            <td style="font-weight: bold;">DEVICE COUNT</td>
                                            <td colspan="3">{{ $display->svc_device_count ?? 'N/A' }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th colspan="6" class="text-center table-light">CHEMICAL</th>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">CHEMICAL QUANTITY</td>
                                        <td>{{ $display->svc_chemical_quantity }}</td>
                                        <td style="font-weight: bold;">CHEMICAL METRIC</td>
                                        <td colspan="3">{{ $display->svc_chemical_metric }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="6" class="text-center table-light">SCHEDULE</th>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">APPROVED BY</td>
                                        <td>{{ $display->approved_first_name }}
                                            {{ $display->approved_last_name }}</td>
                                        <td style="font-weight: bold;">CLIENT DATE</td>
                                        <td>{{ \Carbon\Carbon::parse($display->svca_client_date)->format('m/d/Y') }}</td>
                                        <td style="font-weight: bold;">CLIENT TIME</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($display->svca_client_time)->format('h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">DATE APPROVED</td>
                                        <td>{{ \Carbon\Carbon::parse($display->svca_date_approved)->format('m/d/Y') }}</td>
                                        <td style="font-weight: bold;">TIME FROM</td>
                                        <td>{{ \Carbon\Carbon::parse($display->svca_approved_time_from)->format('h:i A') }}
                                        </td>
                                        <td style="font-weight: bold;">TIME TO</td>
                                        <td>{{ \Carbon\Carbon::parse($display->svca_approved_time_to)->format('h:i A') }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th colspan="6" class="text-center table-light">PROBLEM DESCRIPTION</th>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="text-align: justify">
                                            {{ $display->svc_problem_description }}
                                        </td>
                                    </tr>

                                    @if (!empty($display->svc_assessment_recommendation))
                                        <tr>
                                            <th colspan="6" class="text-center table-light">ASSESSMENT RECOMMENDATION
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="text-align: justify">
                                                {{ $display->svc_assessment_recommendation }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end flex-wrap mb-3 no-print">
                            <button type="button" class="btn btn-success mr-2 mb-2" data-toggle="modal"
                                data-target="#assignTechnicianModal">
                                <span class="fa fa-user"></span> Assign Technician
                            </button>
                            <button type="button" class="btn btn-primary mr-2 mb-2" onclick="printDefault()">
                                <span class="fa fa-print"></span> Print
                            </button>
                            <div class="btn-group mr-2 mb-2">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span class="fa fa-print"></span> Print Options
                                </button>
                                <div class="dropdown-menu p-3" style="min-width: 200px;">
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="print-toggle mr-1" data-target="sectionA">
                                        Appointment
                                        Information
                                    </label>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="print-toggle mr-1" data-target="sectionB"> Service
                                        Orders
                                    </label>
                                    @if ($appointmentImages->count() > 0)
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="print-toggle mr-1" data-target="sectionC">
                                            Client
                                            Appointment Images
                                        </label>
                                    @endif
                                    <div class="dropdown-item text-center">
                                        <button class="btn btn-primary btn-sm mt-2" onclick="handlePrint()">Confirm &
                                            Print</button>
                                    </div>
                                    <div class="dropdown-item">
                                        <p id="warning" style="color:red; display:none;" class="mt-1">Select at least
                                            one
                                            section.</p>
                                    </div>
                                </div>
                            </div>

                            <a class="btn btn-danger mr-2 mb-2" href="">
                                <span class="fa fa-user-shield"></span> Override
                            </a>
                        </div>

                        {{-- Service Order Display --}}
                        <div class="table-responsive" id="sectionB">
                            <hr class="no-print">
                            @if ($display->svc_is_termite == 0)
                                {{-- NON-TERMITE: Two columns - Pest Type & Service Order --}}
                                <div class="row">
                                    {{-- PEST TYPE TABLE --}}
                                    <div class="col-md-6">
                                        <table class="table table-bordered text-center mb-2">
                                            <thead>
                                                <tr style="background-color: #f5f5f5;">
                                                    <th colspan="3"><strong>PEST TYPE</strong></th>
                                                </tr>
                                                <tr style="background-color: #f5f5f5;">
                                                    <th style="width: 50px;">No.</th>
                                                    <th>Pest</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pestTypes as $index => $type)
                                                    <tr>
                                                        <td style="vertical-align: middle; text-align: center">
                                                            {{ $index + 1 }}</td>
                                                        <td style="vertical-align: middle; text-align: center">
                                                            {{ $type->svcp_pest_type }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- SERVICE ORDER TABLE (non-termite areas) --}}
                                    <div class="col-md-6">
                                        <table class="table table-bordered text-center mb-2">
                                            <thead>
                                                <tr style="background-color: #f5f5f5;">
                                                    <th colspan="4"><strong>SERVICE ORDER</strong></th>
                                                </tr>
                                                <tr style="background-color: #f5f5f5;">
                                                    <th style="width: 50px;">No.</th>
                                                    <th>Service</th>
                                                    <th>Cost</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($serviceAreas as $index => $area)
                                                    <tr>
                                                        <td style="vertical-align: middle; text-align: center">
                                                            {{ $index + 1 }}</td>
                                                        <td style="vertical-align: middle; text-align: center">
                                                            {{ $area->svcpa_area }}</td>
                                                        <td style="vertical-align: middle; text-align: center">
                                                            ₱{{ number_format($area->svcpa_cost, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif ($display->svc_is_termite == 1)
                                {{-- TERMITE: Full width - Termite Details --}}
                                <div class="col-md-12">
                                    <table class="table table-bordered text-center mb-2">
                                        <thead>
                                            <tr style="background-color: #f5f5f5;">
                                                <th colspan="5"><strong>TERMITE DETAILS</strong></th>
                                            </tr>
                                            <tr style="background-color: #f5f5f5;">
                                                <th style="width: 50px;">No.</th>
                                                <th>SQM Details</th>
                                                <th>Cost</th>
                                                <th>Computation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($termiteAreas as $index => $area)
                                                <tr>
                                                    <td style="vertical-align: middle; text-align: center">
                                                        {{ $index + 1 }}</td>
                                                    <td style="vertical-align: middle; text-align: center">
                                                        {{ $area->svcpat_sqm_details }}</td>
                                                    <td style="vertical-align: middle; text-align: center">
                                                        ₱{{ number_format($area->svcpat_cost, 2) }}</td>
                                                    <td style="vertical-align: middle; text-align: center">
                                                        @if ($display->svc_type_treatment == 'STANDARD TREATMENT')
                                                            <p>STANDARD TREATMENT: sqm × cost</p>
                                                        @elseif ($display->svc_type_treatment == 'HYBRID TREATMENT')
                                                            <p>HYBRID TREATMENT: sqm × cost + device</p>
                                                        @else
                                                            <p>{{ $display->svc_type_treatment }}: sqm × cost</p>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No termite areas
                                                        added.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- Client Appointment Images --}}
                        @if ($appointmentImages->count() > 0)
                            <div class="table-responsive" id="sectionC">
                                <table class="table table-bordered text-center mb-2">
                                    <thead>
                                        <tr style="background-color: #f5f5f5;">
                                            <th colspan="5">
                                                <strong>CLIENT APPOINTMENT IMAGES</strong>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($appointmentImages->chunk(5) as $chunk)
                                            <tr>
                                                @foreach ($chunk as $img)
                                                    <td style="width:20%; padding:10px; vertical-align:middle;">
                                                        <a href="{{ asset('images/client_images/' . $img->svcap_image) }}"
                                                            target="_blank"
                                                            style="display:block; width:100%; aspect-ratio:1/1; overflow:hidden;">

                                                            <img src="{{ asset('images/client_images/' . $img->svcap_image) }}"
                                                                alt="Appointment Image"
                                                                style="width:100%; height:100%; object-fit:cover; display:block;">
                                                        </a>
                                                    </td>
                                                @endforeach

                                                {{-- Fill empty cells if less than 5 images --}}
                                                @for ($i = $chunk->count(); $i < 5; $i++)
                                                    <td style="vertical-align: middle;"></td>
                                                @endfor
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
    </section>

    {{-- Assign Technician Modal --}}
    <div class="modal fade" id="assignTechnicianModal" tabindex="-1" role="dialog"
        aria-labelledby="assignTechnicianModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ url('management/service-orders/assign-technician') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white" id="assignTechnicianModalLabel">
                            <span class="fa fa-user text-white"></span> Assign Technician
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="svc_id" value="{{ $display->svc_id }}">

                        <div class="form-group mb-3">
                            <label>Assign Technician <span class="text-danger">*</span></label>
                            <select class="form-control" name="svcas_assigned_to" id="technicianSelect" required>
                                <option value="" disabled selected>Select Technician</option>
                                @foreach ($technicians as $tech)
                                    @php
                                        $label = $tech->usr_last_name . ', ' . $tech->usr_first_name;
                                        $disabled = $tech->is_rest_day || $tech->is_busy;
                                        $suffix = $tech->is_rest_day
                                            ? ' — (Rest Day)'
                                            : ($tech->is_busy
                                                ? ' — (Not Available)'
                                                : '');
                                    @endphp
                                    <option value="{{ $tech->usr_id }}" data-rest="{{ $tech->is_rest_day ? 1 : 0 }}"
                                        data-busy="{{ $tech->is_busy ? 1 : 0 }}"
                                        @if ($disabled) disabled @endif>
                                        {{ $label }}{{ $suffix }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Timeline --}}
                        <div id="techTimelineWrap" style="display:none;">
                            <hr>
                            <p class="mb-2" style="font-size:13px; color:#666;">
                                Schedule for <strong>{{ \Carbon\Carbon::parse($approvedDate)->format('F d, Y') }}</strong>
                            </p>

                            <div class="d-flex mb-2" style="gap:12px; font-size:11px; color:#666;">
                                <span style="display:inline-flex;align-items:center;gap:4px;">
                                    <span
                                        style="width:12px;height:12px;border-radius:3px;background:#B5D4F4;border:0.5px solid #85B7EB;display:inline-block;"></span>
                                    Existing appointment
                                </span>
                                <span style="display:inline-flex;align-items:center;gap:4px;">
                                    <span
                                        style="width:12px;height:12px;border-radius:3px;background:#C0DD97;border:0.5px solid #97C459;display:inline-block;"></span>
                                    This appointment
                                </span>
                            </div>

                            <div style="overflow-x:auto;">
                                <div id="techTimeline" style="min-width:500px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <span class="fa fa-times"></span> Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <span class="fa fa-save"></span> Assign
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function printDefault() {
            window.print();
        }

        function handlePrint() {
            console.log("Print button clicked!");

            const checkboxes = document.querySelectorAll('.print-toggle');
            const warning = document.getElementById('warning');
            let anyChecked = false;

            // Reset all sections (show all initially) - Check if the section exists
            const sectionIds = [
                'sectionA', 'sectionB', 'sectionC'
            ];

            sectionIds.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (section) {
                    section.classList.remove('print-hide');
                }
            });

            // Hide unselected sections
            checkboxes.forEach(checkbox => {
                const targetId = checkbox.dataset.target;
                const targetDiv = document.getElementById(targetId);

                // Only modify the target if it exists
                if (targetDiv) {
                    if (checkbox.checked) {
                        anyChecked = true;
                        targetDiv.classList.remove('print-hide');
                    } else {
                        targetDiv.classList.add('print-hide');
                    }
                } else {
                    console.error(`Element with ID ${targetId} not found!`);
                }
            });

            if (!anyChecked) {
                warning.style.display = 'block';
                return;
            }

            warning.style.display = 'none';

            // Delay the print to ensure sections are properly hidden/shown
            setTimeout(() => {
                console.log("Triggering print dialog...");
                window.print();
            }, 500); // Allow time for the layout to update

            // Restore view after printing
            setTimeout(() => {
                sectionIds.forEach(sectionId => {
                    const section = document.getElementById(sectionId);
                    if (section) {
                        section.classList.remove('print-hide');
                    }
                });
            }, 1000);
        }
    </script>

    <script>
        (function() {
            const HOURS_START = 0;
            const HOURS_END = 24;
            const TOTAL_HRS = HOURS_END - HOURS_START;

            // Data from controller
            const NEW_FROM_STR = "{{ $approvedTimeFrom }}";
            const NEW_TO_STR = "{{ $approvedTimeTo }}";

            const DAY_SCHEDULES = @json($daySchedules);

            function parseTime(str) {
                if (!str) return null;
                const parts = str.split(':');
                return parseInt(parts[0]) + parseInt(parts[1]) / 60;
            }

            const NEW_FROM = parseTime(NEW_FROM_STR);
            const NEW_TO = parseTime(NEW_TO_STR);

            document.getElementById('technicianSelect').addEventListener('change', function() {
                const techId = this.value;
                const wrap = document.getElementById('techTimelineWrap');

                if (!techId) {
                    wrap.style.display = 'none';
                    return;
                }
                wrap.style.display = 'block';
                renderTimeline(techId);
            });

            function renderTimeline(techId) {
                const container = document.getElementById('techTimeline');
                container.innerHTML = '';

                const existingSlots = DAY_SCHEDULES[techId] || [];

                for (let h = HOURS_START; h < HOURS_END; h++) {
                    const row = document.createElement('div');
                    row.style.cssText = 'display:flex;height:44px;position:relative;border-bottom:0.5px solid #eee;';

                    // Hour label
                    const lbl = document.createElement('div');
                    lbl.style.cssText =
                        'flex:none;width:52px;font-size:11px;color:#999;display:flex;align-items:center;padding-right:6px;';
                    const h12 = h === 0 ? 12 : h > 12 ? h - 12 : h;
                    const ampm = h < 12 ? 'am' : 'pm';
                    lbl.textContent = h12 + ampm;
                    row.appendChild(lbl);

                    // Cells area
                    const cells = document.createElement('div');
                    cells.style.cssText = 'flex:1;position:relative;border-left:0.5px solid #eee;';

                    // Existing blocks
                    existingSlots.forEach(ev => {
                        const evFrom = parseTime(ev.svca_approved_time_from);
                        const evTo = parseTime(ev.svca_approved_time_to);
                        if (!(evFrom < h + 1 && evTo > h)) return;

                        const segFrom = Math.max(evFrom, h);
                        const segTo = Math.min(evTo, h + 1);

                        const contactParts = [
                            ev.usr_email,
                            '0' + ev.usr_mobile,
                        ].filter(p => p && p.trim() !== '');

                        const addressParts = [
                            ev.uadd_street,
                            ev.uadd_barangay,
                            ev.uadd_city,
                            ev.uadd_province,
                            ev.uadd_region,
                        ].filter(p => p && p.trim() !== '');

                        const distanceLine = ev.svc_km_distance ?
                            ev.svc_km_distance + 'KM from the office' :
                            null;

                        const addr = [...contactParts, ...addressParts, distanceLine].filter(Boolean).join(
                        ', ');

                        const blk = makeBlock(segFrom, segTo, h,
                            ev.usr_first_name + ' ' + ev.usr_last_name,
                            addr,
                            '#B5D4F4', '#0C447C', '#85B7EB');
                        cells.appendChild(blk);
                    });

                    // New appointment block
                    if (NEW_FROM !== null && NEW_TO !== null && NEW_FROM < h + 1 && NEW_TO > h) {
                        const segFrom = Math.max(NEW_FROM, h);
                        const segTo = Math.min(NEW_TO, h + 1);
                        const blk = makeBlock(segFrom, segTo, h,
                            'This appointment', '',
                            '#C0DD97', '#27500A', '#97C459');
                        cells.appendChild(blk);
                    }

                    row.appendChild(cells);
                    container.appendChild(row);
                }
            }

            function makeBlock(segFrom, segTo, hourBase, label, addr, bg, color, border) {
                const blk = document.createElement('div');
                const leftPct = ((segFrom - hourBase) * 100).toFixed(2) + '%';
                const widthPct = ((segTo - segFrom) * 100).toFixed(2) + '%';
                blk.style.cssText = [
                    'position:absolute;top:4px;bottom:4px;',
                    'left:' + leftPct + ';width:' + widthPct + ';',
                    'background:' + bg + ';color:' + color + ';border:0.5px solid ' + border + ';',
                    'border-radius:5px;display:flex;align-items:center;justify-content:center;',
                    'font-size:11px;font-weight:500;overflow:hidden;white-space:nowrap;',
                    'text-overflow:ellipsis;padding:0 4px;cursor:default;'
                ].join('');
                blk.textContent = label;

                // Tooltip
                blk.title = label + (addr ? '\n' + addr : '');

                blk.addEventListener('mouseenter', function(e) {
                    showTooltip(e, label, addr);
                });
                blk.addEventListener('mousemove', moveTooltip);
                blk.addEventListener('mouseleave', hideTooltip);
                return blk;
            }

            // Tooltip
            let tt = null;

            function ensureTT() {
                if (!tt) {
                    tt = document.createElement('div');
                    tt.style.cssText = [
                        'position:fixed;background:#fff;border:0.5px solid #ccc;',
                        'border-radius:8px;padding:8px 12px;font-size:12px;',
                        'pointer-events:none;z-index:9999;display:none;',
                        'box-shadow:0 4px 12px rgba(0,0,0,.08);max-width:200px;line-height:1.5;'
                    ].join('');
                    document.body.appendChild(tt);
                }
            }

            function showTooltip(e, label, addr) {
                ensureTT();
                tt.innerHTML = '<strong>' + label + '</strong>' + (addr ? '<br>' + addr : '');
                tt.style.display = 'block';
                moveTooltip(e);
            }

            function moveTooltip(e) {
                if (!tt) return;
                tt.style.left = (e.clientX + 14) + 'px';
                tt.style.top = (e.clientY + 14) + 'px';
            }

            function hideTooltip() {
                if (tt) tt.style.display = 'none';
            }
        })();
    </script>
@endsection