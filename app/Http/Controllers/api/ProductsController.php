<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductsController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        # By default we are using here auth:api middleware
        $this->middleware('auth:api', ['except' => ['store']]);

    }

    public function getAll()
    {
        $user = auth()->user();
        if ($user->role != "admin")
            return response()->json(['message' => "User is not an admin"], 401);

        $products = Product::all();
        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $token = JWTAuth::parseToken();

        // $user = auth()->user();
        $h_token = $request->bearerToken();

        if (!$token || $token->getToken() !== $h_token) {
            return response()->json(['message' => 'User not valid'], 400);
        }

        $data = $request->validate([
            'vendor_id' => 'required|integer',
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'stock' => 'required|integer',
            'is_on_sale' => 'boolean',
        ]);

        // Create a new product record
        $product = Product::create([
            'vendor_id' => $data['vendor_id'],
            'product_name' => $data['product_name'],
            'price' => $data['price'],
            'discount' => ($data['discount'] ?? $data['discount']),
            'stock' => $data['stock'],
            'is_on_sale' => ($data['is_on_sale'] ?? $data['is_on_sale']),
        ]);

        // You can return a response or redirect as needed
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

}
