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
            ->whereIn('services.svc_status', ['REQUESTED', 'CONFIRM ASSESSMENT']);

        // Branch filter (same logic as users_active)
        if ($sessionBranchId != 1) {
            $query->where('services.branch_id', $sessionBranchId);
        }

        $query->select(
            'services.svc_id',
            'services.svc_sa_number',
            'services.svc_is_termite',
            'services.svc_is_package',
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
                'services.svc_sa_number',
                'services.svc_km_distance',
                'services.svc_property_type',
                'services.svc_is_package',
                'services.svcpat_id',
                'services.svc_is_termite',
                'services.svc_type_treatment',
                'services.svc_sqm_initial',
                'services.svc_sqm_final',
                'services.svc_with_device',
                'services.svc_device_count',
                'services.svc_problem_description',
                'services.svc_status',
                'services.svc_infestation',
                'services.svc_initial_price',
                'services.svc_location_price',
                'services.svc_device_price',
                'services.svc_fixed_price',
                'services.svc_final_price',
                'services.svc_balance',
                'services.svc_payment_status',
                'services.svc_assessment_recommendation',
                'services.svc_chemical_quantity',
                'services.svc_chemical_metric',
                'users.usr_first_name',
                'users.usr_last_name',
                'users.usr_email',
                'users.usr_mobile',
                'branches.branch_id',
                'branches.branch_name',
                'service_appointments.svca_client_date',
                'service_appointments.svca_client_time',
                'service_appointments.svca_approved_date',
                'service_appointments.svca_approved_time_from',
                'service_appointments.svca_approved_time_to',
                'service_appointments.svca_date_approved',
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

        // Add Pest Type
        $existingPests = DB::table('service_order_pests')
            ->where('svc_id', $svc_id)
            ->where('svcop_active', 1)
            ->pluck('svcp_id')
            ->toArray();

        $servicePackages = DB::table('service_packages')
            ->where('svcp_id', '!=', 8)
            ->whereNotIn('svcp_id', $existingPests)
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

        // Add Service Area
        $existingAreas = DB::table('service_orders')
            ->where('svc_id', $svc_id)
            ->where('svco_active', 1)
            ->pluck('svcpa_id')
            ->toArray();

        $servicePackageAreas = DB::table('service_package_areas')
            ->whereNotIn('svcpa_id', $existingAreas)
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
                'service_package_area_termites.svcpat_cost'
            )
            ->get();

        // Device cost for this branch (termite only)
        $deviceCost = DB::table('service_package_area_devices')
            ->where('branch_id', $display->branch_id)
            ->where('svcpad_active', 1)
            ->select('svcpad_id', 'svcpad_cost')
            ->first();

        $locationRate = DB::table('service_package_area_locations')
            ->where('branch_id', $display->branch_id)
            ->where('svcpal_active', 1)
            ->select('svcpal_first_cost', 'svcpal_succeeding_cost')
            ->first();

        $termiteAreaOptions = DB::table('service_package_area_termites')
            ->where('branch_id', $display->branch_id)
            ->where('svcpat_active', 1)
            ->select('svcpat_id', 'svcpat_sqm_details', 'svcpat_cost')
            ->get();

        // Client Appointment Images
        $appointmentImages = DB::table('service_appointment_images')
            ->join('service_appointments', 'service_appointment_images.svca_id', '=', 'service_appointments.svca_id')
            ->where('service_appointments.svc_id', $svc_id)
            ->where('service_appointment_images.svcap_active', 1)
            ->select('service_appointment_images.*')
            ->get();

        return view('service_orders.appointments.requested.view_requested', compact('display', 'pestTypes', 'servicePackages', 'serviceAreas', 'servicePackageAreas', 'termiteAreas', 'deviceCost', 'locationRate', 'termiteAreaOptions', 'appointmentImages'));
    }

    public function requested_appointments_view_add_pest(Request $request)
    {
        $request->validate([
            'svc_id' => 'required',
            'svcp_id' => 'required'
        ]);

        $svc_id = $request->svc_id;
        $svcp_id = $request->svcp_id;

        // check if already exists active
        $exists = DB::table('service_order_pests')
            ->where('svc_id', $svc_id)
            ->where('svcp_id', $svcp_id)
            ->where('svcop_active', 1)
            ->first();

        if ($exists) {
            alert()->error('Pest type already added.');
            return redirect()->back();
        }

        DB::table('service_order_pests')->insert([
            'svcop_uuid' => generateuuid(),
            'svc_id' => $svc_id,
            'svcp_id' => $svcp_id,
            'svcop_date_created' => Carbon::now(),
            'svcop_created_by' => session('usr_id'),
            'svcop_active' => 1
        ]);

        $pest = DB::table('service_packages')
            ->where('svcp_id', $svcp_id)
            ->first();

        $serviceOrder = 'SA-' . str_pad($svc_id, 6, '0', STR_PAD_LEFT);

        logUserActivity(
            'Manage Appointments',
            'Added pest type ' . ($pest->svcp_pest_type ?? '') . ' to ' . $serviceOrder
        );

        session()->flash('successMessage', 'Pest type successfully added.');
        return redirect()->back();
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

    public function requested_appointments_view_add_service(Request $request)
    {
        $request->validate([
            'svc_id' => 'required',
            'svcpa_id' => 'required'
        ]);

        $svc_id = $request->svc_id;
        $svcpa_id = $request->svcpa_id;

        // check if already added
        $exists = DB::table('service_orders')
            ->where('svc_id', $svc_id)
            ->where('svcpa_id', $svcpa_id)
            ->where('svco_active', 1)
            ->first();

        if ($exists) {
            alert()->error('Service area already added.');
            return redirect()->back();
        }

        $area = DB::table('service_package_areas')
            ->where('svcpa_id', $svcpa_id)
            ->first();

        if (!$area) {
            alert()->error('Service area not found.');
            return redirect()->back();
        }

        $service = DB::table('services')
            ->where('svc_id', $svc_id)
            ->first();

        if (!$service) {
            alert()->error('Service not found.');
            return redirect()->back();
        }

        $newInitial = $service->svc_initial_price + $area->svcpa_cost;
        $newBalance = $service->svc_balance + $area->svcpa_cost;

        DB::beginTransaction();

        // insert service order
        DB::table('service_orders')->insert([
            'svco_uuid' => generateuuid(),
            'svc_id' => $svc_id,
            'svcpa_id' => $svcpa_id,
            'svco_date_created' => Carbon::now(),
            'svco_created_by' => session('usr_id'),
            'svco_active' => 1
        ]);

        // update service pricing
        DB::table('services')
            ->where('svc_id', $svc_id)
            ->update([
                'svc_initial_price' => $newInitial,
                'svc_balance' => $newBalance
            ]);

        DB::commit();

        $serviceOrder = 'SA-' . str_pad($svc_id, 6, '0', STR_PAD_LEFT);

        logUserActivity(
            'Manage Appointments',
            'Added service area "' . $area->svcpa_area . '" to ' . $serviceOrder .
            '. Added ' . number_format($area->svcpa_cost, 2) .
            '. New Initial: ' . number_format($newInitial, 2) .
            ', New Balance: ' . number_format($newBalance, 2)
        );

        session()->flash('successMessage', 'Service area successfully added.');
        return redirect()->back();
    }

    public function requested_appointments_view_delete_service(Request $request, $svcpa_id)
    {
        $service = DB::table('service_orders')
            ->leftJoin('service_package_areas', 'service_orders.svcpa_id', '=', 'service_package_areas.svcpa_id')
            ->leftJoin('services', 'service_orders.svc_id', '=', 'services.svc_id')
            ->where('service_orders.svcpa_id', $svcpa_id)
            ->where('service_orders.svco_active', 1)
            ->select(
                'service_orders.svco_id',
                'service_orders.svc_id',
                'service_orders.svcpa_id',
                'service_package_areas.svcpa_area',
                'service_package_areas.svcpa_cost',
                'services.svc_initial_price',
                'services.svc_balance'
            )
            ->first();

        if (!$service) {
            alert()->error('Service order not found.');
            return redirect()->back();
        }

        $newInitialPrice = $service->svc_initial_price - $service->svcpa_cost;
        $newBalance = $service->svc_balance - $service->svcpa_cost;

        DB::beginTransaction();

        // Soft delete service order
        DB::table('service_orders')
            ->where('svcpa_id', $svcpa_id)
            ->update([
                'svco_date_modified' => Carbon::now(),
                'svco_modified_by' => session('usr_id'),
                'svco_active' => 0
            ]);

        // Update service prices
        DB::table('services')
            ->where('svc_id', $service->svc_id)
            ->update([
                'svc_initial_price' => $newInitialPrice,
                'svc_balance' => $newBalance
            ]);

        DB::commit();

        $serviceOrder = 'SA-' . str_pad($service->svc_id, 6, '0', STR_PAD_LEFT);

        logUserActivity(
            'Manage Appointments',
            'Deleted service area "' . $service->svcpa_area .
            '" from ' . $serviceOrder .
            '. Deducted ' . number_format($service->svcpa_cost, 2) .
            ' from Initial Price and Balance. ' .
            'New Initial Price: ' . number_format($newInitialPrice, 2) .
            ', New Balance: ' . number_format($newBalance, 2)
        );

        session()->flash(
            'successMessage',
            'Appointment service order has been deleted and prices updated.'
        );

        return redirect()->back();
    }

    public function requested_appointments_view_assess(Request $request)
    {
        $request->validate([
            'svc_id' => 'required',
            'svc_sa_number' => 'required',
            'svc_infestation' => 'required',
            'svc_location_price' => 'required|numeric',
            'svc_final_price' => 'required|numeric',
            'svca_approved_date' => 'required',
            'svca_approved_time_from' => 'required',
            'svca_approved_time_to' => 'required',
        ]);

        $svc_id = $request->svc_id;

        if (
            DB::table('services')
                ->where('svc_sa_number', $request->svc_sa_number)
                ->where('svc_id', '!=', $svc_id)
                ->exists()
        ) {

            session()->flash('errorMessage', 'SA Number already exists.');
            return redirect()->back();
        }

        $svc_id = $request->svc_id;
        $isTermite = $request->svc_is_termite;
        $isPackage = $request->svc_is_package;
        $servicePrice = $request->svc_location_price;
        $finalPrice = $request->svc_final_price;

        // Fetch existing service record
        $service = DB::table('services')->where('svc_id', $svc_id)->first();

        // Shared fields across both paths
        $sharedFields = [
            'svc_sa_number' => $request->svc_sa_number,
            'svc_property_type' => $request->svc_property_type,
            'svc_km_distance' => $request->svc_km_distance,
            'svc_fixed_price' => $request->svc_fixed_price,
            'svc_chemical_quantity' => $request->svc_chemical_quantity,
            'svc_chemical_metric' => $request->svc_chemical_metric,
            'svc_assessment_recommendation' => $request->svc_assessment_recommendation,
        ];

        if ($isTermite == 1) {
            // TERMITE PATH
            $treatmentType = $request->svc_type_treatment;
            $withDevice = ($treatmentType === 'HYBRID TREATMENT') ? 1 : 0;
            $deviceCount = $withDevice ? (int) $request->svc_device_count : null;
            $sqmInitial = $request->svc_sqm_initial ?? $service->svc_sqm_initial;

            // Recompute device price server-side (mirrors JS: count × unit cost)
            $deviceCostRow = DB::table('service_package_area_devices')
                ->where('branch_id', $service->branch_id)
                ->where('svcpad_active', 1)
                ->first();

            $devicePrice = ($withDevice && $deviceCount && $deviceCostRow)
                ? $deviceCount * $deviceCostRow->svcpad_cost
                : 0;

            DB::table('services')
                ->where('svc_id', $svc_id)
                ->update(array_merge($sharedFields, [
                    'svc_is_termite' => 1,
                    'svcpat_id' => $request->svcpat_id,
                    'svc_type_treatment' => $treatmentType,
                    'svc_with_device' => $withDevice,
                    'svc_device_count' => $deviceCount,
                    'svc_device_price' => $devicePrice,
                    'svc_sqm_initial' => $sqmInitial,
                    'svc_sqm_final' => $sqmInitial,
                    'svc_status' => 'CONFIRM ASSESSMENT',
                    'svc_infestation' => $request->svc_infestation,
                    'svc_location_price' => $servicePrice,
                    'svc_final_price' => $finalPrice,
                    'svc_balance' => $finalPrice,
                    'svc_date_modified' => Carbon::now(),
                    'svc_modified_by' => session('usr_id'),
                ]));

            DB::table('service_orders')
                ->where('svc_id', $svc_id)
                ->update([
                    'svcpat_id' => $request->svcpat_id,
                    'svco_date_modified' => Carbon::now(),
                    'svco_modified_by' => session('usr_id'),
                ]);

        } else {
            // NON-TERMITE PATH
            $sqmInitial = $service->svc_sqm_initial;
            if ($isPackage == 0 && is_null($service->svc_sqm_initial)) {
                $sqmInitial = null;
            } elseif ($isPackage == 1) {
                $sqmInitial = $request->svc_sqm_initial ?? $service->svc_sqm_initial;
            }

            DB::table('services')
                ->where('svc_id', $svc_id)
                ->update(array_merge($sharedFields, [
                    'svc_is_package' => $isPackage,
                    'svc_sqm_initial' => $sqmInitial,
                    'svc_sqm_final' => $isPackage == 1 ? $sqmInitial : null,
                    'svc_status' => 'CONFIRM ASSESSMENT',
                    'svc_infestation' => $request->svc_infestation,
                    'svc_location_price' => $servicePrice,
                    'svc_initial_price' => $request->svc_initial_price ?? $service->svc_initial_price,
                    'svc_final_price' => $finalPrice,
                    'svc_balance' => $finalPrice,
                    'svc_date_modified' => Carbon::now(),
                    'svc_modified_by' => session('usr_id'),
                ]));
        }

        // APPOINTMENT (shared)
        DB::table('service_appointments')
            ->where('svc_id', $svc_id)
            ->update([
                'svca_approved_date' => $request->svca_approved_date,
                'svca_approved_time_from' => $request->svca_approved_time_from,
                'svca_approved_time_to' => $request->svca_approved_time_to,
                'svca_date_approved' => Carbon::now(),
                'svca_approved_by' => session('usr_id'),
                'svca_date_modified' => Carbon::now(),
                'svca_modified_by' => session('usr_id'),
            ]);

        $serviceOrder = 'SA-' . str_pad($svc_id, 6, '0', STR_PAD_LEFT);

        logUserActivity('Manage Appointments', 'Assessed appointment ' . $serviceOrder);

        session()->flash('successMessage', 'Appointment successfully assessed.');
        return redirect()->back();
    }

    public function requested_appointments_view_assess_confirmation(Request $request)
    {
        $request->validate([
            'svc_id' => 'required',
            'svc_sa_number' => 'required',
            'svc_infestation' => 'required',
            'svc_location_price' => 'required|numeric',
            'svc_final_price' => 'required|numeric',
            'svca_approved_date' => 'required',
            'svca_approved_time_from' => 'required',
            'svca_approved_time_to' => 'required',
        ]);

        $svc_id = $request->svc_id;

        if (
            DB::table('services')
                ->where('svc_sa_number', $request->svc_sa_number)
                ->where('svc_id', '!=', $svc_id)
                ->exists()
        ) {

            session()->flash('errorMessage', 'SA Number already exists.');
            return redirect()->back();
        }

        $svc_id = $request->svc_id;
        $isTermite = $request->svc_is_termite;
        $isPackage = $request->svc_is_package;
        $servicePrice = $request->svc_location_price;
        $finalPrice = $request->svc_final_price;

        $service = DB::table('services')->where('svc_id', $svc_id)->first();

        // ← NEW: shared fields (mirrors assess method)
        $sharedFields = [
            'svc_sa_number' => $request->svc_sa_number,
            'svc_property_type' => $request->svc_property_type,
            'svc_km_distance' => $request->svc_km_distance,
            'svc_fixed_price' => $request->svc_fixed_price,
            'svc_chemical_quantity' => $request->svc_chemical_quantity,
            'svc_chemical_metric' => $request->svc_chemical_metric,
            'svc_assessment_recommendation' => $request->svc_assessment_recommendation,
        ];

        if ($isTermite == 1) {
            $treatmentType = $request->svc_type_treatment;
            $withDevice = ($treatmentType === 'HYBRID TREATMENT') ? 1 : 0;
            $deviceCount = $withDevice ? (int) $request->svc_device_count : null;
            $sqmInitial = $request->svc_sqm_initial ?? $service->svc_sqm_initial;

            $deviceCostRow = DB::table('service_package_area_devices')
                ->where('branch_id', $service->branch_id)
                ->where('svcpad_active', 1)
                ->first();

            $devicePrice = ($withDevice && $deviceCount && $deviceCostRow)
                ? $deviceCount * $deviceCostRow->svcpad_cost
                : 0;

            DB::table('services')
                ->where('svc_id', $svc_id)
                ->update(array_merge($sharedFields, [   // ← merged
                    'svc_is_termite' => 1,
                    'svcpat_id' => $request->svcpat_id,
                    'svc_type_treatment' => $treatmentType,
                    'svc_with_device' => $withDevice,
                    'svc_device_count' => $deviceCount,
                    'svc_device_price' => $devicePrice,
                    'svc_sqm_initial' => $sqmInitial,
                    'svc_sqm_final' => $sqmInitial,
                    'svc_status' => 'ASSESSED',
                    'svc_infestation' => $request->svc_infestation,
                    'svc_location_price' => $servicePrice,
                    'svc_final_price' => $finalPrice,
                    'svc_balance' => $finalPrice,
                    'svc_date_modified' => Carbon::now(),
                    'svc_modified_by' => session('usr_id'),
                ]));

            DB::table('service_orders')
                ->where('svc_id', $svc_id)
                ->update([
                    'svcpat_id' => $request->svcpat_id,
                    'svco_date_modified' => Carbon::now(),
                    'svco_modified_by' => session('usr_id'),
                ]);

        } else {
            $sqmInitial = $service->svc_sqm_initial;
            if ($isPackage == 0 && is_null($service->svc_sqm_initial)) {
                $sqmInitial = null;
            } elseif ($isPackage == 1) {
                $sqmInitial = $request->svc_sqm_initial ?? $service->svc_sqm_initial;
            }

            DB::table('services')
                ->where('svc_id', $svc_id)
                ->update(array_merge($sharedFields, [   // ← merged
                    'svc_is_package' => $isPackage,
                    'svc_sqm_initial' => $sqmInitial,
                    'svc_sqm_final' => $isPackage == 1 ? $sqmInitial : null,
                    'svc_status' => 'ASSESSED',
                    'svc_infestation' => $request->svc_infestation,
                    'svc_location_price' => $servicePrice,
                    'svc_initial_price' => $request->svc_initial_price ?? $service->svc_initial_price,
                    'svc_final_price' => $finalPrice,
                    'svc_balance' => $finalPrice,
                    'svc_date_modified' => Carbon::now(),
                    'svc_modified_by' => session('usr_id'),
                ]));
        }

        DB::table('service_appointments')
            ->where('svc_id', $svc_id)
            ->update([
                'svca_approved_date' => $request->svca_approved_date,
                'svca_approved_time_from' => $request->svca_approved_time_from,
                'svca_approved_time_to' => $request->svca_approved_time_to,
                'svca_date_approved' => Carbon::now(),
                'svca_approved_by' => session('usr_id'),
                'svca_date_modified' => Carbon::now(),
                'svca_modified_by' => session('usr_id'),
            ]);

        $serviceOrder = 'SA-' . str_pad($svc_id, 6, '0', STR_PAD_LEFT);

        logUserActivity('Manage Appointments', 'Confirmed Assessment ' . $serviceOrder);

        session()->flash('successMessage', 'Appointment successfully assessed.');
        return redirect()->action(
            [AppointmentController::class, 'assessed_appointments_view'],
            ['svc_id' => $svc_id]
        );
    }
    // END REQUESTED APPOINTMENTS

    // START ASSESSED APPOINTMENTS
    public function assessed_appointments_view($svc_id)
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
                'services.svc_sa_number',
                'services.svc_km_distance',
                'services.svc_property_type',
                'services.svc_is_package',
                'services.svcpat_id',
                'services.svc_is_termite',
                'services.svc_type_treatment',
                'services.svc_sqm_initial',
                'services.svc_sqm_final',
                'services.svc_with_device',
                'services.svc_device_count',
                'services.svc_problem_description',
                'services.svc_status',
                'services.svc_infestation',
                'services.svc_initial_price',
                'services.svc_location_price',
                'services.svc_device_price',
                'services.svc_fixed_price',
                'services.svc_final_price',
                'services.svc_balance',
                'services.svc_payment_status',
                'services.svc_assessment_recommendation',
                'services.svc_chemical_quantity',
                'services.svc_chemical_metric',
                'users.usr_first_name',
                'users.usr_last_name',
                'users.usr_email',
                'users.usr_mobile',
                'branches.branch_id',
                'branches.branch_name',
                'service_appointments.svca_client_date',
                'service_appointments.svca_client_time',
                'service_appointments.svca_approved_date',
                'service_appointments.svca_approved_time_from',
                'service_appointments.svca_approved_time_to',
                'service_appointments.svca_date_approved',
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

        // Add Pest Type
        $existingPests = DB::table('service_order_pests')
            ->where('svc_id', $svc_id)
            ->where('svcop_active', 1)
            ->pluck('svcp_id')
            ->toArray();

        $servicePackages = DB::table('service_packages')
            ->where('svcp_id', '!=', 8)
            ->whereNotIn('svcp_id', $existingPests)
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

        // Add Service Area
        $existingAreas = DB::table('service_orders')
            ->where('svc_id', $svc_id)
            ->where('svco_active', 1)
            ->pluck('svcpa_id')
            ->toArray();

        $servicePackageAreas = DB::table('service_package_areas')
            ->whereNotIn('svcpa_id', $existingAreas)
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
                'service_package_area_termites.svcpat_cost'
            )
            ->get();

        // Client Appointment Images
        $appointmentImages = DB::table('service_appointment_images')
            ->join('service_appointments', 'service_appointment_images.svca_id', '=', 'service_appointments.svca_id')
            ->where('service_appointments.svc_id', $svc_id)
            ->where('service_appointment_images.svcap_active', 1)
            ->select('service_appointment_images.*')
            ->get();

        return view('service_orders.appointments.assessed.view_assessed', compact('display', 'pestTypes', 'servicePackages', 'serviceAreas', 'servicePackageAreas', 'termiteAreas', 'appointmentImages'));
    }
    // END ASSESSED APPOINTMENTS

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