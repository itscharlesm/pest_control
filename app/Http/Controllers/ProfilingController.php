<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ProfilingController extends Controller
{
    // START USERS
    public function users_active(Request $request)
    {
        $search = $request->search ?? '';

        // Query to get active users along with their roles
        $query = DB::table('users')
            ->leftJoin('user_roles', 'users.usr_id', '=', 'user_roles.usr_id')
            ->leftJoin('roles', 'user_roles.rol_id', '=', 'roles.rol_id')
            ->where('users.usr_active', '=', '1')
            ->select(
                'users.usr_id',
                'users.usr_uuid',
                'users.usr_last_name',
                'users.usr_first_name',
                'users.usr_middle_name',
                'users.usr_email',
                'users.usr_mobile',
                'users.usr_active',
                DB::raw('GROUP_CONCAT(CASE WHEN user_roles.url_active = 1 THEN roles.rol_name END ORDER BY roles.rol_name SEPARATOR ", ") as roles')
            )
            ->groupBy(
                'users.usr_id',
                'users.usr_uuid',
                'users.usr_last_name',
                'users.usr_first_name',
                'users.usr_middle_name',
                'users.usr_email',
                'users.usr_mobile',
                'users.usr_active'
            )
            ->orderBy('users.usr_last_name')
            ->orderBy('users.usr_first_name');

        // Apply search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.usr_last_name', 'LIKE', "%$search%")
                    ->orWhere('users.usr_first_name', 'LIKE', "%$search%")
                    ->orWhere('users.usr_email', 'LIKE', "%$search%")
                    ->orWhere('users.usr_mobile', 'LIKE', "%$search%");
            });
        }

        // Paginate results
        $users = $query->paginate(100);

        $roles = DB::table('roles')
            ->select('rol_id', 'rol_name', 'rol_description')
            ->where('rol_active', 1)
            ->where('rol_id', '!=', 1)
            ->get();

        return view('profiling.users.active', compact('users', 'search', 'roles'));
    }

    public function users_reset_password(Request $request, $usr_uuid)
    {
        $user = DB::table('users')
            ->where('usr_uuid', '=', $usr_uuid)
            ->first();

        if (!$user) {
            alert()->error('User not found.');
            return redirect()->back();
        }

        DB::table('users')
            ->where('usr_uuid', '=', $usr_uuid)
            ->update([
                'usr_password' => md5('123456')
            ]);

        logUserActivity('Manage Users', 'Reset password for user ' . $user->usr_last_name . ', ' . $user->usr_first_name);

        alert()->info('Password has been reset', 'Password has been reset to 123456.');
        return redirect()->action([UserController::class, 'employees_active']);
    }
    // END USERS
}