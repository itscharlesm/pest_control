<?php

namespace App\Http\Controllers\api\v1\mobile_controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MobileServiceAppointmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'uadd_id' => 'required',
            'client_date' => 'required|date',
            'client_time' => 'required',
            'initial_price' => 'nullable|numeric',
            'service_packages' => 'required',
            'service_areas' => 'nullable',
            'is_termite' => 'nullable|in:0,1',
            'termite_sqm_id' => 'nullable|integer',
            'images.*' => 'mimes:jpeg,jpg,png,webp|max:8192',
        ]);

        DB::beginTransaction();

        try {
            $user = DB::table('users')
                ->where('usr_email', $request->email)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            $servicePackages = json_decode($request->service_packages, true) ?? [];
            $serviceAreas = json_decode($request->service_areas, true) ?? [];
            $isTermite = $request->is_termite == 1;
            $termiteSqmId = $request->termite_sqm_id;
            $initialPrice = $request->filled('initial_price')
                ? $request->initial_price
                : 0;

            $serviceId = DB::table('services')->insertGetId([
                'svc_uuid' => Str::uuid(),
                'branch_id' => $user->branch_id,
                'usr_id' => $user->usr_id,
                'svc_is_package' => count($servicePackages) > 1 ? 1 : 0,
                'svcpat_id' => $isTermite ? $termiteSqmId : null,
                'svc_is_termite' => $isTermite ? 1 : 0,
                'svc_type_treatment' => null,
                'svc_sqm_initial' => null,
                'svc_sqm_final' => null,
                'svc_with_device' => null,
                'svc_device_count' => null,
                'svc_problem_description' => strtoupper($request->problem_description),
                'svc_status' => 'REQUESTED',
                'svc_infestation' => null,
                'svc_initial_price' => $initialPrice,
                // 'svc_location_price' => null,
                'svc_final_price' => null,
                'svc_balance' => $initialPrice,
                'svc_payment_status' => 'NO PAYMENT',
                'svc_attachment' => null,
                'svc_frequency_type' => null,
                'svc_frequency' => null,
                // 'svc_recommendation' => null,
                'svc_date_created' => now(),
                'svc_created_by' => $user->usr_id,
                'svc_date_modified' => null,
                'svc_modified_by' => null,
                'svc_active' => 1,
            ]);

            DB::table('services')
            ->where('svc_id', $serviceId)
            ->update([
                'svc_sa_number' => $serviceId,
            ]);

            foreach ($servicePackages as $package) {
                DB::table('service_order_pests')->insert([
                    'svcop_uuid' => Str::uuid(),
                    'svc_id' => $serviceId,
                    'svcp_id' => $package['id'],
                    'svcop_date_created' => now(),
                    'svcop_created_by' => $user->usr_id,
                    'svcop_date_modified' => null,
                    'svcop_modified_by' => null,
                    'svcop_active' => 1,
                ]);
            }

            if ($isTermite && $termiteSqmId) {
                DB::table('service_orders')->insert([
                    'svco_uuid' => Str::uuid(),
                    'svc_id' => $serviceId,
                    'svcpa_id' => null,
                    'svcpat_id' => $termiteSqmId,
                    'svco_date_created' => now(),
                    'svco_created_by' => $user->usr_id,
                    'svco_date_modified' => null,
                    'svco_modified_by' => null,
                    'svco_active' => 1,
                ]);
            } else {
                foreach ($serviceAreas as $area) {
                    DB::table('service_orders')->insert([
                        'svco_uuid' => Str::uuid(),
                        'svc_id' => $serviceId,
                        'svcpa_id' => $area['id'],
                        'svcpat_id' => null,
                        'svco_date_created' => now(),
                        'svco_created_by' => $user->usr_id,
                        'svco_date_modified' => null,
                        'svco_modified_by' => null,
                        'svco_active' => 1,
                    ]);
                }
            }

            $appointmentId = DB::table('service_appointments')->insertGetId([
                'svca_uuid' => Str::uuid(),
                'svc_id' => $serviceId,
                'uadd_id' => $request->uadd_id,
                'svca_client_date' => $request->client_date,
                'svca_client_time' => $request->client_time,
                'svca_status' => 'REQUESTED',
                'svca_approved_date' => null,
                'svca_approved_time_from' => null,
                'svca_approved_time_to' => null,
                'svca_date_approved' => null,
                'svca_approved_by' => null,
                'svca_date_created' => now(),
                'svca_created_by' => $user->usr_id,
                'svca_date_modified' => null,
                'svca_modified_by' => null,
                'svca_active' => 1,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $fileName = uniqid() . '_' . $image->getClientOriginalName();
                    $folderPath = public_path('images/client_images');

                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }

                    $path = $folderPath . '/' . $fileName;
                    $ext = strtolower($image->getClientOriginalExtension());

                    if (in_array($ext, ['jpg', 'jpeg'])) {
                        $source = imagecreatefromjpeg($image->getPathname());
                        imagejpeg($source, $path, 75);
                        imagedestroy($source);
                    } elseif ($ext === 'png') {
                        $source = imagecreatefrompng($image->getPathname());

                        if ($source && imageistruecolor($source) === false) {
                            imagepalettetotruecolor($source);
                        }

                        if ($source) {
                            imagepng($source, $path, 7);
                            imagedestroy($source);
                        }
                    } elseif ($ext === 'webp') {
                        $source = imagecreatefromwebp($image->getPathname());
                        imagewebp($source, $path, 75);
                        imagedestroy($source);
                    }

                    DB::table('service_appointment_images')->insert([
                        'svcap_uuid' => Str::uuid(),
                        'svca_id' => $appointmentId,
                        'svcap_image' => $fileName,
                        'svcap_date_created' => now(),
                        'svcap_created_by' => $user->usr_id,
                        'svcap_date_modified' => null,
                        'svcap_modified_by' => null,
                        'svcap_active' => 1,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service appointment request submitted successfully.',
                'svc_id' => $serviceId,
                'svca_id' => $appointmentId,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Unable to submit service appointment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTermiteAreaSizes($branchId)
    {
        $data = DB::table('service_package_area_termites')
            ->where('branch_id', $branchId)
            ->where('svcpat_active', 1)
            ->select(
                'svcpat_id',
                'svcpat_sqm_details',
                'svcpat_cost'
            )
            ->orderBy('svcpat_id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}