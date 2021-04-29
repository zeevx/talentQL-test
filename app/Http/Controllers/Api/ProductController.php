<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    use ApiResponser;


    /**
     * @var Authenticatable|null
     */
    private $user_data;

    public function __construct()
    {
        if (Auth::guard('api')->user()){
            $this->user = Auth::guard('api')->user();
            $this->user_data = [];
            $user_data = $this->user->only(['name', 'email']);
            $this->user_data = $user_data;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $products = $this->user->products;
        if (count($products) > 0){
            return $this->success($products, 'Products fetched successfully');
        }
         return $this->error('No product found', 401);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $attr = $this->validateStore($request);
        if ($attr->fails()){
            return $this->error($attr->messages()->first(), '401');
        }
        $data = $request->all();
        $created = $this->user->products()->create($data);

        if ($created){
            return $this->success($created, 'Product added successfully');
        }
        return $this->error('An error occurred', 401);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateStore(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $product = $this->user->products()->whereId($id)->first();
        if ($product){
            return $this->success($product, 'Product fetched successfully');
        }
        return $this->error('Product not found', 401);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $attr = $this->validateUpdate($request);
        if ($attr->fails()){
            return $this->error($attr->messages()->first(), '401');
        }
        $data = $request->all();
        $product = $this->user->products()->whereId($id)->first();
        if ($product){
            $updated = $product->update($data);
                if ($updated){
                    return $this->success($product, 'Product updated successfully');
                }
            return $this->error('Product not updated', 401);
        }
        return $this->error('Product not found', 401);
    }

    public function validateUpdate(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $product = $this->user->products()->whereId($id)->first();
        if ($product){
            $product->delete();
            return $this->success(null, 'Product deleted successfully');
        }
        return $this->error('Product not found', 401);
    }
}
