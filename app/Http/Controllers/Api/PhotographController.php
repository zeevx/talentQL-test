<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photograph;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PhotographController extends Controller
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
     * @return JsonResponse
     */
    public function index()
    {
        $photographs = $this->user->photographs;

        if (count($photographs) > 0){
            return $this->success($photographs, 'Photographs fetched successfully');
        }
        return $this->error('No Photograph found', 401);
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

        if (isset($data['low_image'])){
            $data['low_image'] = $request->file('low_image')->store('/uploads/low','public');
        }

        if (isset($data['high_image'])){
            $data['high_image'] = $request->file('high_image')->store('/uploads/high','public');
        }

        $product = Product::whereId($data['product_id'])->first();

        if (!$product){
            return  $this->error('Product not found', 401);
        }

        if ($product->photograph){
            return $this->error('This product already has a photograph', 401);
        }

        $created = Photograph::create([
            'product_id' => $product->id,
            'user_id' => $this->user->id,
            'low_image' => $data['low_image'] ?? null,
            'high_image' => $data['high_image'] ?? null
        ]);

        if ($created){
            return $this->success($created, 'Photograph added successfully');
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
            'product_id' => 'required',
            'low_image' => 'required'
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
        $photograph = $this->user->photographs()->whereId($id)->first();
        if ($photograph){
            return $this->success($photograph, 'Photograph fetched successfully');
        }
        return $this->error('Photograph not found', 401);
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

        if (isset($data['low_image'])){
            $data['low_image'] = $request->file('low_image')->store('/uploads/low','public');
        }

        if (isset($data['high_image'])){
            $data['high_image'] = $request->file('high_image')->store('/uploads/high','public');
        }

        $photograph = $this->user->photographs()->whereId($id)->first();
        if ($photograph){
            $updated = $photograph->update($data);
            if ($updated){
                return $this->success($photograph, 'Photograph updated successfully');
            }
            return $this->error('Photograph not updated', 401);
        }
        return $this->error('Photograph not found', 401);
    }

    public function validateUpdate(Request $request)
    {
        return Validator::make($request->all(), [
            'product_id' => 'numeric',
            'status' => 'string'
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
        $photograph = $this->user->photographs()->whereId($id)->first();
        if ($photograph){
            $deleted = $photograph->delete();
            if ($deleted){
                return $this->success($photograph, 'Photograph deleted successfully');
            }
            return $this->error('Photograph not deleted', 401);
        }
        return $this->error('Photograph not found', 401);
    }
}
