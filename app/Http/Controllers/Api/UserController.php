<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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

    public function index()
    {
        $this->user_data['role'] = $this->user->getRoleNames();
        return $this->success($this->user_data, 'User information fetched successfully');
    }

    public function update(Request $request)
    {
        $attr = $this->validateUpdate($request);
        if ($attr->fails()){
            return $this->error($attr->messages()->first(), '401');
        }
        $data = $request->except(['id','email','password']);
        $user = Auth::guard('api')->user();
        $user->syncRoles($data['role']);
        $update = $user->update($data);
        $this->user_data['role'] = $user->getRoleNames();
        if ($update){
            return $this->success($this->user_data, 'User information updated successfully');
        }
        return $this->error('Error updating user profile', 401);
    }

    public function validateUpdate($request)
    {
        return Validator::make($request->all(), [
            'role' => 'required|string'
        ]);
    }

}
