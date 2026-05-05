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

        $query = DB::table('services')
            ->leftJoin('users', 'services.usr_id', '=', 'users.usr_id')
            ->leftJoin('branches', 'services.branch_id', '=', 'branches.branch_id')
            ->where('services.svc_active', 1)
            ->where('services.svc_status', 'REQUESTED');

        $query->select(
            'services.svc_id',
            'services.svc_is_termite',
            'services.svc_status',
            'services.svc_payment_status',

            'users.usr_first_name',
            'users.usr_last_name',

            'branches.branch_name'
        );

        // Search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('users.usr_last_name', 'LIKE', "%$search%")
                    ->orWhere('branches.branch_name', 'LIKE', "%$search%");
            });
        }

        $query->orderBy('services.svc_id', 'desc');

        $appointments = $query->paginate(50);

        return view('service_orders.appointments.requested', compact('appointments', 'search'));
    }
    // END REQUESTED APPOINTMENTS
}