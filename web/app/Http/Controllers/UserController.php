<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class UserController extends Controller
{
    public function account(Request $request)
    {
        $user = DB::table('users')
            ->where('usr_id', session('usr_id'))
            ->first();

        $regions = DB::table('location_regions')
            ->where('reg_active', 1)
            ->orderBy('reg_name')
            ->get(['reg_id', 'reg_name']);

        return view('account', compact('user', 'regions'));
    }

    public function account_update(Request $request)
    {
        $updateData = [
            'usr_first_name' => strtoupper($request->usr_first_name),
            'usr_middle_name' => strtoupper($request->usr_middle_name),
            'usr_last_name' => strtoupper($request->usr_last_name),
            'usr_birth_date' => $request->usr_birth_date,
        ];

        // AVATAR UPLOAD
        if ($request->hasFile('avatar')) {

            $request->validate([
                'avatar' => 'mimes:jpeg,jpg,png,webp|max:8192'
            ]);

            $file = $request->file('avatar');
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $path = public_path('images/users/' . $fileName);

            $ext = strtolower($file->getClientOriginalExtension());

            if (in_array($ext, ['jpg', 'jpeg'])) {
                $source = imagecreatefromjpeg($file->getPathname());
                imagejpeg($source, $path, 75);
                imagedestroy($source);

            } elseif ($ext === 'png') {
                $source = imagecreatefrompng($file->getPathname());

                if ($source && imageistruecolor($source) === false) {
                    imagepalettetotruecolor($source);
                }

                if ($source) {
                    imagepng($source, $path, 7);
                    imagedestroy($source);
                }

            } elseif ($ext === 'webp') {
                $source = imagecreatefromwebp($file->getPathname());
                imagewebp($source, $path, 75);
                imagedestroy($source);
            }

            // SAVE TO DB
            $updateData['usr_image_path'] = $fileName;
        }

        // ADDRESS LOGIC
        if (
            $request->street ||
            $request->barangay ||
            $request->municipality ||
            $request->province ||
            $request->region
        ) {
            $updateData['usr_address'] = collect([
                $request->street,
                $request->barangay,
                $request->municipality,
                $request->province,
                $request->region,
            ])->filter()->implode(', ');
        }

        DB::table('users')
            ->where('usr_id', session('usr_id'))
            ->update($updateData);

        return back()->with('successMessage', 'Account updated successfully.');
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