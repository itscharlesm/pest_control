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
                            SA-{{ str_pad($display->svc_id, 6, '0', STR_PAD_LEFT) }}
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
                                        <td style="font-weight: bold;">INITIAL PRICE</td>
                                        <td>₱{{ number_format($display->svc_initial_price, 2) }}</td>

                                        <td style="font-weight: bold;">FINAL PRICE</td>
                                        <td>₱{{ number_format($display->svc_balance, 2) }}</td>

                                        <td style="font-weight: bold;">SERVICE PRICE</td>
                                        <td>₱{{ number_format($display->svc_service_price, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <td style="font-weight: bold;">IS TERMITE</td>
                                        <td>{{ $display->svc_is_termite ? 'YES' : 'NO' }}</td>
                                        <td style="font-weight: bold;">IS PACKAGE</td>
                                        <td>{{ $display->svc_is_package ? 'YES' : 'NO' }}</td>
                                        <td style="font-weight: bold;">INFESTATION</td>
                                        <td>{{ $display->svc_infestation }}</td>
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
                                        <th colspan="6" class="text-center table-light">SCHEDULE</th>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">CLIENT DATE</td>
                                        <td>{{ \Carbon\Carbon::parse($display->svca_client_date)->format('m/d/Y') }}</td>
                                        <td style="font-weight: bold;">CLIENT TIME</td>
                                        <td colspan="3">
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
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end flex-wrap mb-3 no-print">
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
                                        <input type="checkbox" class="print-toggle mr-1" data-target="sectionA"> Appointment
                                        Information
                                    </label>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="print-toggle mr-1" data-target="sectionB"> Service
                                        Orders
                                    </label>
                                    @if ($appointmentImages->count() > 0)
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="print-toggle mr-1" data-target="sectionC"> Client
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
                                                        ₱{{ number_format($area->svcpat_costs, 2) }}</td>
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
@endsection