<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::latest()->get();

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully.',
                'data' => $products->map(
                    fn ($product) => $this->formatProduct($product)
                ),
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to retrieve products.', [
                'exception' => $exception,
            ]);

            return $this->serverError(
                'Unable to retrieve products right now.'
            );
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','min:3','max:255','regex:/^[A-Za-z0-9\s\-]+$/',],
            'description' => ['nullable', 'string', 'max:2000'],
            'type' => ['required', 'in:digital,course,laptop,event'],
            'price' => ['required','numeric','min:0','max:99999999.99',],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048',],
            'status' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->toArray());
        }

        $validated = $validator->validated();
        $imagePath = null;

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')
                    ->store('products', 'public');

                if (!$imagePath) {
                    return $this->serverError(
                        'Unable to upload the product image.'
                    );
                }

                $validated['image'] = $imagePath;
            }

            $product = DB::transaction(
                fn () => Product::create($validated)
            );

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'data' => $this->formatProduct($product),
            ], 201);

        } catch (Throwable $exception) {
            if ($imagePath) {
                try {
                    Storage::disk('public')->delete($imagePath);
                } catch (Throwable $cleanupException) {
                    Log::error('Failed to clean up product image.', [
                        'path' => $imagePath,
                        'exception' => $cleanupException,
                    ]);
                }
            }

            Log::error('Failed to create product.', [
                'exception' => $exception,
            ]);

            return $this->serverError(
                'Unable to create the product right now.'
            );
        }
    }

    public function show(Product $product)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully.',
                'data' => $this->formatProduct($product),
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to retrieve product.', [
                'product_id' => $product->id,
                'exception' => $exception,
            ]);

            return $this->serverError(
                'Unable to retrieve the product right now.'
            );
        }
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','min:3','max:255','regex:/^[A-Za-z0-9\s\-]+$/',],
            'description' => ['nullable', 'string', 'max:2000'],
            'type' => ['required', 'in:digital,course,laptop,event'],
            'price' => ['required','numeric','min:0','max:99999999.99',],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048',],
            'status' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->toArray());
        }

        $validated = $validator->validated();
        $oldImage = $product->image;
        $newImage = null;

        try {
            if ($request->hasFile('image')) {
                $newImage = $request->file('image')
                    ->store('products', 'public');

                if (!$newImage) {
                    return $this->serverError(
                        'Unable to upload the replacement image.'
                    );
                }

                $validated['image'] = $newImage;
            }

            DB::transaction(function () use ($product, $validated) {
                $product->update($validated);
            });

            if ($newImage && $oldImage) {
                try {
                    $deleted = Storage::disk('public')->delete($oldImage);

                    if (!$deleted) {
                        Log::warning(
                            'Old product image could not be deleted.',
                            [
                                'product_id' => $product->id,
                                'path' => $oldImage,
                            ]
                        );
                    }
                } catch (Throwable $cleanupException) {
                    Log::error('Failed to delete old product image.', [
                        'product_id' => $product->id,
                        'path' => $oldImage,
                        'exception' => $cleanupException,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
                'data' => $this->formatProduct($product->fresh()),
            ], 200);

        } catch (Throwable $exception) {
            if ($newImage) {
                try {
                    Storage::disk('public')->delete($newImage);
                } catch (Throwable $cleanupException) {
                    Log::error('Failed to clean up replacement image.', [
                        'product_id' => $product->id,
                        'path' => $newImage,
                        'exception' => $cleanupException,
                    ]);
                }
            }

            Log::error('Failed to update product.', [
                'product_id' => $product->id,
                'exception' => $exception,
            ]);

            return $this->serverError(
                'Unable to update the product right now.'
            );
        }
    }

    public function destroy(Product $product)
    {
        $image = $product->image;

        try {
            DB::transaction(function () use ($product) {
                $product->delete();
            });

        } catch (Throwable $exception) {
            Log::error('Failed to delete product.', [
                'product_id' => $product->id,
                'exception' => $exception,
            ]);

            return $this->serverError(
                'Unable to delete the product right now.'
            );
        }

        if ($image) {
            try {
                $deleted = Storage::disk('public')->delete($image);

                if (!$deleted) {
                    Log::warning('Product was deleted, but its image remains.', [
                        'product_id' => $product->id,
                        'path' => $image,
                    ]);
                }
            } catch (Throwable $exception) {
                Log::error('Failed to delete product image.', [
                    'product_id' => $product->id,
                    'path' => $image,
                    'exception' => $exception,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ], 200);
    }

    private function formatProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'type' => $product->type,
            'price' => $product->price,
            'image' => $product->image,
            'image_url' => $product->image
                ? Storage::disk('public')->url($product->image)
                : null,
            'status' => $product->status,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
    }

    private function validationError(array $errors)
    {
        return response()->json([
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => $errors,
        ], 422);
    }

    private function serverError(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 500);
    }
}
