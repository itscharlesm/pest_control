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