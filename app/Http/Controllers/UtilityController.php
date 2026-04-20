<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UtilityController extends Controller
{
    public function category_main()
    {
        $categories = DB::table('menu_categories')
            ->where('mcat_active', '=', 1)
            ->get();

        return view('admin.utility.product_categories', compact('categories'));
    }

    public function category_create(Request $request)
    {
        // Validate the request data
        $request->validate([
            'mcat_name' => 'required|string|max:255',
        ]);

        // Insert the new category into the menu_categories table
        DB::table('menu_categories')->insert([
            'mcat_name' => $request->input('mcat_name'),
            'mcat_date_created' => Carbon::now(),
            'mcat_created_by' => session('usr_id'),
            'mcat_active' => 1
        ]);

        return redirect('admin/utility/manage-categories')->with('success', 'Category added successfully.');
    }

    public function category_update(Request $request, $mcat_id)
    {
        // Validate the request data
        $request->validate([
            'mcat_name' => 'required|string|max:255',
        ]);

        // Update the category in the database
        DB::table('menu_categories')
            ->where('mcat_id', $mcat_id)
            ->update([
                'mcat_name' => $request->input('mcat_name'),
                'mcat_date_modified' => Carbon::now(),
                'mcat_modified_by' => session('usr_id')
            ]);

        // Redirect back with a success message
        return redirect('admin/utility/manage-categories')->with('success', 'Category updated successfully.');
    }
}