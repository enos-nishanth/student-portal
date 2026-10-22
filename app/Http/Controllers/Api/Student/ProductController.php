<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::where('status', 1)
                ->latest()
                ->get([
                    'id',
                    'name',
                    'description',
                    'type',
                    'price',
                    'image',
                    'status',
                    'created_at',
                    'updated_at',
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully.',
                'data' => $products,
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to retrieve student products.', [
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve products at this time.',
            ], 500);
        }
    }


    public function show(Product $product)
    {
        try {
            if (!$product->status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.',
                ], 404);
            }

            $product->makeHidden([
                'deleted_at',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully.',
                'data' => $product->only([
                    'id',
                    'name',
                    'description',
                    'type',
                    'price',
                    'image',
                    'status',
                    'created_at',
                    'updated_at',
                ]),
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to retrieve student product.', [
                'product_id' => $product->id,
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve this product at this time.',
            ], 500);
        }
    }
}
