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
            ->leftJoin('users as creator', 'branches.branch_created_by', '=', 'creator.usr_id')
            ->leftJoin('users as modifier', 'branches.branch_modified_by', '=', 'modifier.usr_id')
            ->where('branches.branch_active', '=', '1');

        $query->select(
            'branches.branch_id',
            'branches.branch_name',
            'branches.branch_date_created',
            'branches.branch_date_modified',
            'branches.branch_active',

            // Created by
            'creator.usr_first_name as created_first_name',
            'creator.usr_last_name as created_last_name',

            // Modified by
            'modifier.usr_first_name as modified_first_name',
            'modifier.usr_last_name as modified_last_name'
        );

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('branches.branch_name', 'LIKE', "%$search%")
                    ->orWhere('creator.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('creator.usr_last_name', 'LIKE', "%$search%")
                    ->orWhere('modifier.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('modifier.usr_last_name', 'LIKE', "%$search%");
            });
        }

        $query->orderBy('branches.branch_name', 'asc');

        $branches = $query->paginate(500);

        return view('management.branches.active', compact('branches', 'search'));
    }

    public function branches_deleted(Request $request)
    {
        $search = $request->search ?? '';

        $query = DB::table('branches')
            ->leftJoin('users as creator', 'branches.branch_created_by', '=', 'creator.usr_id')
            ->leftJoin('users as modifier', 'branches.branch_modified_by', '=', 'modifier.usr_id')
            ->where('branches.branch_active', '=', '0');

        $query->select(
            'branches.branch_id',
            'branches.branch_name',
            'branches.branch_date_created',
            'branches.branch_date_modified',
            'branches.branch_active',

            // Created by
            'creator.usr_first_name as created_first_name',
            'creator.usr_last_name as created_last_name',

            // Modified by
            'modifier.usr_first_name as modified_first_name',
            'modifier.usr_last_name as modified_last_name'
        );

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('branches.branch_name', 'LIKE', "%$search%")
                    ->orWhere('creator.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('creator.usr_last_name', 'LIKE', "%$search%")
                    ->orWhere('modifier.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('modifier.usr_last_name', 'LIKE', "%$search%");
            });
        }

        $query->orderBy('branches.branch_name', 'asc');

        $branches = $query->paginate(500);

        return view('management.branches.deleted', compact('branches', 'search'));
    }

    public function branches_add(Request $request)
    {
        DB::table('branches')->insert([
            'branch_name' => $request->branch_name,
            'branch_date_created' => Carbon::now(),
            'branch_created_by' => session('usr_id'),
            'branch_active' => 1
        ]);

        logUserActivity('Manage Branches', 'Added new branch ' . $request->branch_name);

        session()->flash('successMessage', 'Branch has been added.');
        return redirect()->back();
    }

    public function branches_update(Request $request, $branch_id)
    {
        DB::table('branches')
            ->where('branch_id', $branch_id)
            ->update([
                'branch_name' =>  strtoupper($request->branch_name),
                'branch_date_modified' => Carbon::now(),
                'branch_modified_by' => session('usr_id'),
            ]);

        logUserActivity('Manage Branches', 'Updated branch ' . $request->branch_name);

        session()->flash('successMessage', 'Branch has been updated.');
        return redirect()->back();
    }

    public function branches_delete(Request $request, $branch_id)
    {
        $branch = DB::table('branches')
            ->where('branch_id', '=', $branch_id)
            ->first();

        if (!$branch) {
            alert()->error('Client not found.');
            return redirect()->back();
        }

        DB::table('branches')
            ->where('branch_id', '=', $branch_id)
            ->update([
                'branch_date_modified' => Carbon::now(),
                'branch_modified_by' => session('usr_id'),
                'branch_active' => 0
            ]);

        logUserActivity('Manage Branches', 'Deleted branch ' . $branch->branch_name);

        session()->flash('successMessage', 'Branch has been Deleted.');
        return redirect()->back();
    }

    public function branches_restore(Request $request, $branch_id)
    {
        $branch = DB::table('branches')
            ->where('branch_id', '=', $branch_id)
            ->first();

        if (!$branch) {
            alert()->error('Client not found.');
            return redirect()->back();
        }

        DB::table('branches')
            ->where('branch_id', '=', $branch_id)
            ->update([
                'branch_date_modified' => Carbon::now(),
                'branch_modified_by' => session('usr_id'),
                'branch_active' => 1
            ]);

        logUserActivity('Manage Branches', 'Restored branch ' . $branch->branch_name);

        session()->flash('successMessage', 'Branch has been Restored.');
        return redirect()->back();
    }
    // END BRANCHES

    // START ADDRESSES
    public function addresses_active(Request $request)
    {
        $search = $request->search ?? '';

        $query = DB::table('addresses')
            ->leftJoin('users as creator', 'addresses.add_created_by', '=', 'creator.usr_id')
            ->leftJoin('users as modifier', 'addresses.add_modified_by', '=', 'modifier.usr_id')
            ->where('addresses.add_active', '=', '1');

        $query->select(
            'addresses.add_id',
            'addresses.add_name',
            'addresses.add_date_created',
            'addresses.add_date_modified',
            'addresses.add_active',

            // Created by
            'creator.usr_first_name as created_first_name',
            'creator.usr_last_name as created_last_name',

            // Modified by
            'modifier.usr_first_name as modified_first_name',
            'modifier.usr_last_name as modified_last_name'
        );

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('addresses.add_name', 'LIKE', "%$search%")
                    ->orWhere('creator.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('creator.usr_last_name', 'LIKE', "%$search%")
                    ->orWhere('modifier.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('modifier.usr_last_name', 'LIKE', "%$search%");
            });
        }

        $query->orderBy('addresses.add_name', 'asc');

        $addresses = $query->paginate(500);

        return view('management.addresses.active', compact('addresses', 'search'));
    }

    public function addresses_deleted(Request $request)
    {
        $search = $request->search ?? '';

        $query = DB::table('addresses')
            ->leftJoin('users as creator', 'addresses.add_created_by', '=', 'creator.usr_id')
            ->leftJoin('users as modifier', 'addresses.add_modified_by', '=', 'modifier.usr_id')
            ->where('addresses.add_active', '=', '0');

        $query->select(
            'addresses.add_id',
            'addresses.add_name',
            'addresses.add_date_created',
            'addresses.add_date_modified',
            'addresses.add_active',

            // Created by
            'creator.usr_first_name as created_first_name',
            'creator.usr_last_name as created_last_name',

            // Modified by
            'modifier.usr_first_name as modified_first_name',
            'modifier.usr_last_name as modified_last_name'
        );

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('addresses.add_name', 'LIKE', "%$search%")
                    ->orWhere('creator.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('creator.usr_last_name', 'LIKE', "%$search%")
                    ->orWhere('modifier.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('modifier.usr_last_name', 'LIKE', "%$search%");
            });
        }

        $query->orderBy('addresses.add_name', 'asc');

        $addresses = $query->paginate(500);

        return view('management.addresses.deleted', compact('addresses', 'search'));
    }

    public function addresses_add(Request $request)
    {
        DB::table('addresses')->insert([
            'add_name' => $request->add_name,
            'add_date_created' => Carbon::now(),
            'add_created_by' => session('usr_id'),
            'add_active' => 1
        ]);

        logUserActivity('Manage Addresses', 'Added new address ' . $request->add_name);

        session()->flash('successMessage', 'Address has been added.');
        return redirect()->back();
    }

    public function addresses_update(Request $request, $add_id)
    {
        DB::table('addresses')
            ->where('add_id', $add_id)
            ->update([
                'add_name' => strtoupper($request->add_name),
                'add_date_modified' => Carbon::now(),
                'add_modified_by' => session('usr_id'),
            ]);

        logUserActivity('Manage Addresses', 'Updated address ' . $request->add_name);

        session()->flash('successMessage', 'Address has been updated.');
        return redirect()->back();
    }

    public function addresses_delete(Request $request, $add_id)
    {
        $address = DB::table('addresses')
            ->where('add_id', '=', $add_id)
            ->first();

        if (!$address) {
            alert()->error('Client not found.');
            return redirect()->back();
        }

        DB::table('addresses')
            ->where('add_id', '=', $add_id)
            ->update([
                'add_date_modified' => Carbon::now(),
                'add_modified_by' => session('usr_id'),
                'add_active' => 0
            ]);

        logUserActivity('Manage Addresses', 'Deleted address ' . $address->add_name);

        session()->flash('successMessage', 'Address has been Deleted.');
        return redirect()->back();
    }

    public function addresses_restore(Request $request, $add_id)
    {
        $address = DB::table('addresses')
            ->where('add_id', '=', $add_id)
            ->first();

        if (!$address) {
            alert()->error('Client not found.');
            return redirect()->back();
        }

        DB::table('addresses')
            ->where('add_id', '=', $add_id)
            ->update([
                'add_date_modified' => Carbon::now(),
                'add_modified_by' => session('usr_id'),
                'add_active' => 1
            ]);

        logUserActivity('Manage Addresses', 'Restored address ' . $address->add_name);

        session()->flash('successMessage', 'Address has been Restored.');
        return redirect()->back();
    }
    // END ADDRESSES
}