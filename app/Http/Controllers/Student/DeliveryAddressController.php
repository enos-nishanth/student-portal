<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DeliveryAddressController extends Controller
{
    /**
     * Display all delivery addresses of the logged-in student.
     */
    public function index()
    {
        $addresses = auth()->user()
            ->deliveryAddresses()
            ->latest()
            ->get();

        return view('student.addresses.index', compact('addresses'));
    }


    /**
     * Show the create address page.
     */
    public function create()
    {
        return view('student.addresses.create');
    }


    /**
     * Store a new delivery address.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => ['required', 'string', 'max:500'],

            'town' => ['required', 'string', 'max:100'],

            'city' => ['required', 'string', 'max:100'],

            'pincode' => ['required', 'digits:6'],

            'type' => ['required', 'in:home,office,other'],

            'is_default' => ['nullable', 'boolean'],

            'latitude' => ['required', 'numeric', 'between:-90,90'],

            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);


        try {

            DB::transaction(function () use ($validated) {

                /*
                |--------------------------------------------------------------------------
                | If this address is default,
                | remove default from existing addresses
                |--------------------------------------------------------------------------
                */

                if (!empty($validated['is_default'])) {

                    auth()->user()
                        ->deliveryAddresses()
                        ->update([
                            'is_default' => false,
                        ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Create Address
                |--------------------------------------------------------------------------
                */

                auth()->user()
                    ->deliveryAddresses()
                    ->create($validated);

            });


            return redirect()
                ->route('student.addresses.index')
                ->with(
                    'success',
                    'Delivery address added successfully.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to save the delivery address. Please try again.'
                );
        }
    }


    /**
     * Show the edit address page.
     */
    public function edit(DeliveryAddress $deliveryAddress)
    {
        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        | Make sure the logged-in student owns this address.
        |--------------------------------------------------------------------------
        */

        if ($deliveryAddress->user_id !== auth()->id()) {

            abort(403, 'Unauthorized access.');
        }


        return view(
            'student.addresses.edit',
            compact('deliveryAddress')
        );
    }


    /**
     * Update an existing delivery address.
     */
    public function update(
        Request $request,
        DeliveryAddress $deliveryAddress
    ) {

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if ($deliveryAddress->user_id !== auth()->id()) {

            abort(403, 'Unauthorized access.');
        }


        $validated = $request->validate([
            'address' => ['required', 'string', 'max:500'],

            'town' => ['required', 'string', 'max:100'],

            'city' => ['required', 'string', 'max:100'],

            'pincode' => ['required', 'digits:6'],

            'type' => ['required', 'in:home,office,other'],

            'is_default' => ['nullable', 'boolean'],

            'latitude' => ['required', 'numeric', 'between:-90,90'],

            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);


        try {

            DB::transaction(function () use (
                $validated,
                $deliveryAddress
            ) {

                /*
                |--------------------------------------------------------------------------
                | If this address becomes default,
                | remove default from other addresses
                |--------------------------------------------------------------------------
                */

                if (!empty($validated['is_default'])) {

                    auth()->user()
                        ->deliveryAddresses()
                        ->where('id', '!=', $deliveryAddress->id)
                        ->update([
                            'is_default' => false,
                        ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Update Address
                |--------------------------------------------------------------------------
                */

                $deliveryAddress->update($validated);

            });


            return redirect()
                ->route('student.addresses.index')
                ->with(
                    'success',
                    'Delivery address updated successfully.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update the delivery address. Please try again.'
                );
        }
    }


    /**
     * Delete a delivery address.
     */
    public function destroy(DeliveryAddress $deliveryAddress)
    {
        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if ($deliveryAddress->user_id !== auth()->id()) {

            abort(403, 'Unauthorized access.');
        }


        try {

            $deliveryAddress->delete();


            return redirect()
                ->route('student.addresses.index')
                ->with(
                    'success',
                    'Delivery address deleted successfully.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to delete the delivery address. Please try again.'
                );
        }
    }
}