<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ProductPicture;
use App\Http\Requests\ProductRequest;


class ProductController extends Controller
{
    function get_products_by_user_id($user_id) {
        $products = Product::where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(8);

        foreach ($products as $product) {
            $product->picture = $product->pictures()->where('status', 'main')->first()->img_name;
            $product->purchases = count($product->complete_orders());
            $product->category = $product->category()->name;
        }

        return $products;
    }
    function create(ProductRequest $request) {
        $request["user_id"] = auth()->user()->id;
        $product = Product::create($request->all());
        $this->save_pictures($request["pictures"], $product->id);

        return $product;
    }
    function save_pictures($pictures, $product_id): array {
        $product_pictures = [];

        foreach ($pictures as $picture) {
            $picture->storePublicly('public/img/products');
            $product_pictures[] = ProductPicture::create([
                'img_name' => $picture->hashName(),
                'product_id' => $product_id,
                'status' => 'simple'
            ]);
        }

        $product_pictures[0]->update(['status' => 'main']);

        return $product_pictures;
    }
    function delete_pictures($product) {
        $pictures = $product->pictures()->get();

        foreach ($pictures as $picture) {
            $path = storage_path('app/public/img/products/' . $picture->img_name);
            if (file_exists($path)) unlink($path);
            $picture->delete();
        }
    }
    function change(Request $request, $product_id) {
        $product = Product::find($product_id);
        if (empty($product)) return response('Product not found', 404);

        if ($product->user_id != auth()->user()->id) return response('Access denied', 403);

        if (isset($request->pictures)) {
            $this->delete_pictures($product);
            $this->save_pictures($request->pictures, $product_id);
        }
        $product->update($request->all());

        return $product;
    }
    function delete($product_id) {
        $product = Product::find($product_id);

        if (empty($product)) return response('Product not found', 404);
        $this->delete_pictures($product);

        return $product->delete();
    }
    function get_by_id($product_id) {
        $product = Product::find($product_id);

        if(empty($product)) return response('Product not found!', 404);
        $product->pictures =$product->pictures()->get();

        return $product;
    }
    function getAll(ProductFilter $request) {
        $products = Product::filter($request)->paginate(8);

        foreach ($products as $product) {
            $product->picture = $product->pictures()->where('status', 'main')->first()->img_name;
            $product->category = $product->category()->name;
        }

        return $products;
    }

}
