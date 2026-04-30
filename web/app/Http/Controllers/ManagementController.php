<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ManagementController extends Controller
{
    // START BRANCHES
    public function branches_active(Request $request)
    {
        $search = $request->search ?? '';

        $query = DB::table('branches')
            ->leftJoin('users', 'branches.branch_created_by', '=', 'users.usr_id')
            ->where('branches.branch_active', '=', '1');

        $query->select(
            'branches.branch_id',
            'branches.branch_name',
            'branches.branch_date_created',
            'branches.branch_date_modified',
            'branches.branch_active',

            'users.usr_first_name',
            'users.usr_middle_name',
            'users.usr_last_name'
        );

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('branches.branch_name', 'LIKE', "%$search%")
                ->orWhere('users.usr_first_name', 'LIKE', "%$search%")
                ->orWhere('users.usr_last_name', 'LIKE', "%$search%");
            });
        }

        $query->orderBy('branches.branch_name', 'asc');

        $branches = $query->paginate(500);

        return view('management.branches.active', compact('branches', 'search'));
    }

    public function branches_deleted(Request $request)
    {
        $search = $request->search ?? '';

        $sessionBranchId = session('branch_id');

        // Base query
        $query = DB::table('users')
            ->leftJoin('branches', 'users.branch_id', '=', 'branches.branch_id')
            ->where('users.utyp_id', '=', '3')
            ->where('users.usr_active', '=', '0');

        // Branch filter (unless super admin)
        if ($sessionBranchId != 1) {
            $query->where('users.branch_id', $sessionBranchId);
        }

        $query->select(
            'users.usr_id',
            'users.usr_uuid',
            'branches.branch_name',
            'users.usr_last_name',
            'users.usr_first_name',
            'users.usr_middle_name',
            'users.usr_email',
            'users.usr_mobile',
            'users.usr_birth_date',
            'users.usr_active',
        )
            ->groupBy(
                'users.usr_id',
                'users.usr_uuid',
                'branches.branch_name',
                'users.usr_last_name',
                'users.usr_first_name',
                'users.usr_middle_name',
                'users.usr_email',
                'users.usr_mobile',
                'users.usr_birth_date',
                'users.usr_active'
            )
            ->orderBy('users.usr_last_name')
            ->orderBy('users.usr_first_name');

        // Search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.usr_last_name', 'LIKE', "%$search%")
                    ->orWhere('users.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('users.usr_email', 'LIKE', "%$search%")
                    ->orWhere('users.usr_mobile', 'LIKE', "%$search%")
                    ->orWhere('branches.branch_name', 'LIKE', "%$search%");
            });
        }

        $clients = $query->paginate(500);

        // Get all addresses for listed clients
        $clientIds = collect($clients->items())->pluck('usr_id');

        $addresses = DB::table('user_addresses')
            ->leftJoin('addresses', 'user_addresses.add_id', '=', 'addresses.add_id')
            ->whereIn('user_addresses.usr_id', $clientIds)
            ->select(
                'user_addresses.*',
                'addresses.add_name'
            )
            ->get()
            ->groupBy('usr_id'); // group per client

        $branches = DB::table('branches')
            ->select('branch_id', 'branch_name')
            ->where('branch_active', 1)
            ->get();

        return view('profiling.clients.deleted', compact('clients', 'search', 'branches', 'addresses'));
    }

    public function branches_delete(Request $request, $usr_id)
    {
        $client = DB::table('users')
            ->where('usr_id', '=', $usr_id)
            ->first();

        if (!$client) {
            alert()->error('Client not found.');
            return redirect()->back();
        }

        DB::table('users')
            ->where('usr_id', '=', $usr_id)
            ->update([
                'usr_active' => 0
            ]);

        logUserActivity('Manage Clients', 'Deleted client ' . $client->usr_last_name . ', ' . $client->usr_first_name);

        session()->flash('successMessage', 'Client has been Deleted.');
        return redirect()->back();
    }

    public function branches_restore(Request $request, $usr_id)
    {
        $client = DB::table('users')
            ->where('usr_id', '=', $usr_id)
            ->first();

        if (!$client) {
            alert()->error('Client not found.');
            return redirect()->back();
        }

        DB::table('users')
            ->where('usr_id', '=', $usr_id)
            ->update([
                'usr_active' => 1
            ]);

        logUserActivity('Manage Clients', 'Restored client ' . $client->usr_last_name . ', ' . $client->usr_first_name);

        session()->flash('successMessage', 'Client has been restored.');
        return redirect()->back();
    }
    // END BRANCHES
}
