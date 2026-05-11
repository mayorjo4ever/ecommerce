<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    /**
     * DELETE /admin/products/images/{image}
     *
     * Called via AJAX from the edit product page when the × button
     * is clicked on an existing gallery image.
     *
     * Returns JSON { success, message }
     */
    public function destroy(ProductImage $image)
    {
        // Delete the physical file from storage
        if ($image->image_path) {
            ImageHelper::deleteImage($image->image_path);
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image removed.',
        ]);
    }
}