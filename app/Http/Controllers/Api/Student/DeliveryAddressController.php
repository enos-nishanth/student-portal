<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class DeliveryAddressController extends Controller
{
    
    public function index()
    {
        try {
            $addresses = auth()->user()
                ->deliveryAddresses()
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Delivery addresses retrieved successfully.',
                'data' => $addresses,
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to retrieve delivery addresses.', [
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve delivery addresses at this time.',
            ], 500);
        }
    }

    public function show(DeliveryAddress $deliveryAddress)
    {
        if ($deliveryAddress->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access this address.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Delivery address retrieved successfully.',
            'data' => $deliveryAddress,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => ['required', 'string', 'max:500'],
            'town' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'digits:6'],
            'type' => ['required', 'in:home,office,other'],
            'is_default' => ['sometimes', 'boolean'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $validated = $validator->validated();
            $user = auth()->user();

            $address = DB::transaction(function () use ($validated, $user) {
                $isDefault = (bool) ($validated['is_default'] ?? false);

                if ($isDefault) {
                    $user->deliveryAddresses()->update([
                        'is_default' => false,
                    ]);
                }

                $validated['is_default'] = $isDefault;

                return $user->deliveryAddresses()->create($validated);
            });

            return response()->json([
                'success' => true,
                'message' => 'Delivery address created successfully.',
                'data' => $address,
            ], 201);

        } catch (Throwable $exception) {
            Log::error('Failed to create delivery address.', [
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to save the delivery address. Please try again.',
            ], 500);
        }
    }

    /**
     * Update a delivery address.
     * PUT requires all address fields.
     * PATCH allows partial updates.
     */
    public function update(Request $request, DeliveryAddress $deliveryAddress)
    {
        if ($deliveryAddress->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this address.',
            ], 403);
        }

        $isPatch = $request->isMethod('patch');

        $validator = Validator::make($request->all(), [
            'address' => [$isPatch ? 'sometimes' : 'required', 'string', 'max:500'],
            'town' => [$isPatch ? 'sometimes' : 'required', 'string', 'max:100'],
            'city' => [$isPatch ? 'sometimes' : 'required', 'string', 'max:100'],
            'pincode' => [$isPatch ? 'sometimes' : 'required', 'digits:6'],
            'type' => [$isPatch ? 'sometimes' : 'required', 'in:home,office,other'],
            'is_default' => ['sometimes', 'boolean'],
            'latitude' => [$isPatch ? 'sometimes' : 'required', 'numeric', 'between:-90,90'],
            'longitude' => [$isPatch ? 'sometimes' : 'required', 'numeric', 'between:-180,180'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        if (empty($validated)) {
            return response()->json([
                'success' => false,
                'message' => 'Provide at least one field to update.',
            ], 422);
        }

        try {
            DB::transaction(function () use ($validated, $deliveryAddress) {
                if (
                    array_key_exists('is_default', $validated)
                    && (bool) $validated['is_default']
                ) {
                    auth()->user()
                        ->deliveryAddresses()
                        ->where('id', '!=', $deliveryAddress->id)
                        ->update([
                            'is_default' => false,
                        ]);
                }

                $deliveryAddress->update($validated);
            });

            return response()->json([
                'success' => true,
                'message' => 'Delivery address updated successfully.',
                'data' => $deliveryAddress->fresh(),
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to update delivery address.', [
                'address_id' => $deliveryAddress->id,
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update the delivery address. Please try again.',
            ], 500);
        }
    }

    /**
     * Delete a delivery address.
     */
    public function destroy(DeliveryAddress $deliveryAddress)
    {
        if ($deliveryAddress->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this address.',
            ], 403);
        }

        try {
            $deliveryAddress->delete();

            return response()->json([
                'success' => true,
                'message' => 'Delivery address deleted successfully.',
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to delete delivery address.', [
                'address_id' => $deliveryAddress->id,
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to delete the delivery address. Please try again.',
            ], 500);
        }
    }
}
