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
</style>

@extends('layouts.themes.main')

@section('content')
    {{-- Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Requested Appointment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Appointment</li>
                        <li class="breadcrumb-item">Requested</li>
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
                        <div class="table-responsive" id="sectionA">
                            <table class="table table-bordered text-left align-middle">
                                <tbody>
                                    <tr>
                                        <td style="font-weight: bold;">CLIENT NAME</td>
                                        <td>{{ $display->usr_first_name }} {{ $display->usr_last_name }}</td>
                                        <td style="font-weight: bold;">PAYMENT STATUS</td>
                                        <td>{{ $display->svc_payment_status }}</td>
                                        <td style="font-weight: bold;">INITIAL PRICE</td>
                                        <td>₱{{ number_format($display->svc_initial_price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">EMAIL</td>
                                        <td>{{ $display->usr_email }}</td>
                                        <td style="font-weight: bold;">IS TERMITE</td>
                                        <td>{{ $display->svc_is_termite ? 'YES' : 'NO' }}</td>
                                        <td style="font-weight: bold;">BALANCE</td>
                                        <td>₱{{ number_format($display->svc_balance, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">MOBILE NUMBER</td>
                                        <td>{{ $display->usr_mobile }}</td>
                                        <td colspan="1" style="font-weight: bold;">BRANCH</td>
                                        <td colspan="3">{{ $display->branch_name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">ADDRESS</td>
                                        <td>{{ implode(
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
                                        <td style="font-weight: bold;">STATUS</td>
                                        <td>{{ $display->svc_status }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-center table-light">
                                            SCHEDULE
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">DATE REQUESTED</td>
                                        <td colspan="2">
                                            {{ \Carbon\Carbon::parse($display->svca_client_date)->format('m/d/Y') }}</td>
                                        <td style="font-weight: bold;">TIME REQUESTED</td>
                                        <td colspan="2">
                                            {{ \Carbon\Carbon::parse($display->svca_client_time)->format('h:i A') }}</td>
                                    </tr>
                                    @if ($display->svc_is_termite)
                                        <tr>
                                            <th colspan="6" class="text-center table-light">
                                                TERMITE CASE
                                            </th>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">INITIAL SQM</td>
                                            <td colspan="2">{{ $display->svc_sqm_initial }}</td>
                                            <td style="font-weight: bold;">WITH DEVICE</td>
                                            <td colspan="2">{{ $display->svc_with_device ? 'YES' : 'NO' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">TREATMENT TYPE</td>
                                            <td colspan="2">{{ $display->svc_type_treatment }}</td>
                                            <td style="font-weight: bold;">DEVICE COUNT</td>
                                            <td colspan="2">{{ $display->svc_device_count ?? 'N/A' }}</td>
                                        </tr>
                                    @endif
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

                            <a class="btn btn-danger mr-2 mb-2"
                                href="">
                                <span class="fa fa-user-shield"></span> Override
                            </a>
                        </div>
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
                'sectionA'
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