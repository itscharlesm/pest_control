<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class AppointmentController extends Controller
{
    // START REQUESTED APPOINTMENTS
    public function requested_appointments(Request $request)
    {
        $search = $request->search ?? '';
        $sessionBranchId = session('branch_id');

        $query = DB::table('services')
            ->leftJoin('users', 'services.usr_id', '=', 'users.usr_id')
            ->leftJoin('branches', 'services.branch_id', '=', 'branches.branch_id')
            ->where('services.svc_active', 1)
            ->where('services.svc_status', 'REQUESTED');

        // Branch filter (same logic as users_active)
        if ($sessionBranchId != 1) {
            $query->where('services.branch_id', $sessionBranchId);
        }

        $query->select(
            'services.svc_id',
            'services.svc_is_termite',
            'services.svc_status',
            'services.svc_payment_status',
            'services.svc_date_created',
            'users.usr_first_name',
            'users.usr_last_name',
            'users.usr_email',
            'users.usr_mobile',
            'branches.branch_name'
        );

        // Search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('users.usr_last_name', 'LIKE', "%$search%")
                    ->orWhere('users.usr_email', 'LIKE', "%$search%")
                    ->orWhere('users.usr_mobile', 'LIKE', "%$search%")
                    ->orWhere('branches.branch_name', 'LIKE', "%$search%");
            });
        }

        $query->orderBy('services.svc_date_created', 'asc');

        $appointments = $query->paginate(50);

        return view('service_orders.appointments.requested.requested', compact('appointments', 'search'));
    }

    public function requested_appointments_view($svc_id)
    {
        $display = DB::table('services')
            ->leftJoin('users', 'services.usr_id', '=', 'users.usr_id')
            ->leftJoin('branches', 'services.branch_id', '=', 'branches.branch_id')
            ->leftJoin('service_appointments', 'services.svc_id', '=', 'service_appointments.svc_id')
            ->leftJoin('user_addresses', 'service_appointments.uadd_id', '=', 'user_addresses.uadd_id')
            ->leftJoin('addresses', 'user_addresses.add_id', '=', 'addresses.add_id')
            ->where('services.svc_id', $svc_id)
            ->select(
                'services.svc_id',
                'services.svc_is_package',
                'services.svcpat_id',
                'services.svc_is_termite',
                'services.svc_type_treatment',
                'services.svc_sqm_initial',
                'services.svc_with_device',
                'services.svc_device_count',
                'services.svc_status',
                'services.svc_initial_price',
                'services.svc_balance',
                'services.svc_payment_status',
                'users.usr_first_name',
                'users.usr_last_name',
                'users.usr_email',
                'users.usr_mobile',
                'branches.branch_name',
                'service_appointments.svca_client_date',
                'service_appointments.svca_client_time',
                'user_addresses.uadd_street',
                'user_addresses.uadd_barangay',
                'user_addresses.uadd_city',
                'user_addresses.uadd_province',
                'user_addresses.uadd_region',
                'addresses.add_name'
            )
            ->first();

        // Pest Types for this service
        $pestTypes = DB::table('service_order_pests')
            ->leftJoin('service_packages', 'service_order_pests.svcp_id', '=', 'service_packages.svcp_id')
            ->where('service_order_pests.svc_id', $svc_id)
            ->where('service_order_pests.svcop_active', 1)
            ->select(
                'service_order_pests.svcop_id',
                'service_packages.svcp_id',
                'service_packages.svcp_pest_type'
            )
            ->get();

        // Service Orders with Areas (non-termite: svcpat_id IS NULL)
        $serviceAreas = DB::table('service_orders')
            ->leftJoin('service_package_areas', 'service_orders.svcpa_id', '=', 'service_package_areas.svcpa_id')
            ->where('service_orders.svc_id', $svc_id)
            ->whereNull('service_orders.svcpat_id')
            ->where('service_orders.svco_active', 1)
            ->select(
                'service_orders.svco_id',
                'service_package_areas.svcpa_id',
                'service_package_areas.svcpa_area',
                'service_package_areas.svcpa_cost'
            )
            ->get();

        // Service Orders with Termite Areas (termite: svcpat_id IS NOT NULL)
        $termiteAreas = DB::table('service_orders')
            ->leftJoin('service_package_area_termites', 'service_orders.svcpat_id', '=', 'service_package_area_termites.svcpat_id')
            ->where('service_orders.svc_id', $svc_id)
            ->whereNotNull('service_orders.svcpat_id')
            ->where('service_orders.svco_active', 1)
            ->select(
                'service_orders.svco_id',
                'service_package_area_termites.svcpat_id',
                'service_package_area_termites.svcpat_sqm_details',
                'service_package_area_termites.svcpat_costs'
            )
            ->get();

        // Client Appointment Images
        $appointmentImages = DB::table('service_appointment_images')
            ->join('service_appointments', 'service_appointment_images.svca_id', '=', 'service_appointments.svca_id')
            ->where('service_appointments.svc_id', $svc_id)
            ->where('service_appointment_images.svcap_active', 1)
            ->select('service_appointment_images.*')
            ->get();

        return view('service_orders.appointments.requested.view_requested', compact('display', 'pestTypes', 'serviceAreas', 'termiteAreas', 'appointmentImages'));
    }

    public function requested_appointments_view_delete_pest(Request $request, $svcop_id)
    {
        $pest = DB::table('service_order_pests')
            ->leftJoin('service_packages', 'service_order_pests.svcp_id', '=', 'service_packages.svcp_id')
            ->where('service_order_pests.svcop_id', $svcop_id)
            ->select(
                'service_order_pests.svcop_id',
                'service_order_pests.svc_id',
                'service_packages.svcp_pest_type'
            )
            ->first();

        if (!$pest) {
            alert()->error('Pest type not found.');
            return redirect()->back();
        }

        DB::table('service_order_pests')
            ->where('svcop_id', $svcop_id)
            ->update([
                'svcop_date_modified' => Carbon::now(),
                'svcop_modified_by' => session('usr_id'),
                'svcop_active' => 0
            ]);

        $serviceOrder = 'SA-' . str_pad($pest->svc_id, 6, '0', STR_PAD_LEFT);

        logUserActivity(
            'Manage Appointments',
            'Deleted pest type ' . $pest->svcp_pest_type . ' from ' . $serviceOrder
        );

        session()->flash('successMessage', 'Appointment service pest type has been deleted.');
        return redirect()->back();
    }
    // END REQUESTED APPOINTMENTS

    // START DELETED APPOINTMENTS
    public function delete_appointment(Request $request, $svc_id)
    {
        $service = DB::table('services')
            ->leftJoin('users', 'services.usr_id', '=', 'users.usr_id')
            ->where('services.svc_id', '=', $svc_id)
            ->select(
                'services.svc_id',
                'users.usr_first_name',
                'users.usr_last_name'
            )
            ->first();

        if (!$service) {
            alert()->error('Service not found.');
            return redirect()->back();
        }

        DB::table('services')
            ->where('svc_id', '=', $svc_id)
            ->update([
                'svc_date_modified' => Carbon::now(),
                'svc_modified_by' => session('usr_id'),
                'svc_active' => 0
            ]);

        logUserActivity(
            'Manage Appointments',
            'Deleted appointment of ' . $service->usr_first_name . ' ' . $service->usr_last_name
        );

        session()->flash('successMessage', 'Appointment has been deleted.');
        return redirect()->back();
    }
    // END DELETED APPOINTMENTS
}