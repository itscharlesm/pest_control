<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $usr_email = $request->usr_email;
        $usr_first_name = strtoupper(trim($request->usr_first_name));
        $usr_middle_name = strtoupper(trim($request->usr_middle_name));
        $usr_last_name = strtoupper(trim($request->usr_last_name));
        $code = '123456'; // fixed temporary code

        // Normalize mobile number
        $usr_mobile = trim($request->usr_mobile);
        if (substr($usr_mobile, 0, 1) === '0') {
            $usr_mobile = substr($usr_mobile, 1); // remove leading 0
        }
        $usr_mobile = substr($usr_mobile, 0, 10); // cut to 10 digits
        $usr_mobile = str_pad($usr_mobile, 10, '0'); // pad end with 0 if less than 10

        // Check if email already exists
        $emailExists = DB::table('users')
            ->where('usr_email', $usr_email)
            ->exists();

        // Check if combination of first + last name already exists
        $nameExists = DB::table('users')
            ->where('usr_first_name', $usr_first_name)
            ->where('usr_last_name', $usr_last_name)
            ->exists();

        if ($emailExists) {
            alert()->error(
                'Account already exists',
                'A user with the email ' . $usr_email . ' already exists.'
            );
        } elseif ($nameExists) {
            alert()->error(
                'Account already exists',
                'A user with the name ' . $usr_first_name . ' ' . $usr_last_name . ' already exists.'
            );
        } else {
            // Insert into 'users' table
            DB::table('users')->insert([
                'usr_uuid' => generateuuid(),
                'usr_mobile' => $usr_mobile,
                'usr_email' => $usr_email,
                'usr_password' => md5($code),
                'usr_first_name' => $usr_first_name,
                'usr_middle_name' => $usr_middle_name,
                'usr_last_name' => $usr_last_name,
                'usr_date_created' => Carbon::now(),
                'usr_code' => $code
            ]);

            alert()->success(
                'User successfully registered.',
                'Your temporary password is 123456. Use this to login.'
            );
        }

        return redirect()->action([MainController::class, 'main']);
    }

    public function update_password(Request $request)
    {
        $current_password = $request->current_password;
        $new_password1 = $request->new_password1;
        $new_password2 = $request->new_password2;

        $user = DB::table('users')
            ->where('usr_id', '=', session('usr_id'))
            ->first();

        if (md5($current_password) == $user->usr_password) {
            if ($new_password1 == $new_password2) {

                DB::table('users')
                    ->where('usr_id', '=', session('usr_id'))
                    ->update([
                        'usr_password' => md5($new_password1)
                    ]);

                alert()->success('Success', 'User password has been changed.');
            } else {
                alert()->warning('Warning', 'Password did not matched.');
            }
        } else {
            alert()->warning('Warning', 'Incorrect user password.');
        }
        return redirect()->action([AdminController::class, 'home']);
    }
}