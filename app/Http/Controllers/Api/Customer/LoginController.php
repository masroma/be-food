<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    /**
     * index
     *
     * @param  mixed $request
     * @return void
     */
    public function index(Request $request)
    {
        //set validasi
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        //response error validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //get "email" dan "password" dari input
        $credentials = $request->only('email', 'password');

        //check jika "email" dan "password" tidak sesuai
        if(!$token = auth()->guard('api_customer')->attempt($credentials)) {

            //response login "failed"
            return response()->json([
                'success' => false,
                'message' => 'Email or Password is incorrect'
            ], 401);

        }

        //response login "success" dengan generate "Token"
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api_customer')->user(),
            'token'   => $token
        ], 200);
    }

    /**
     * getUser
     *
     * @return void
     */
    public function getUser()
    {
        //response data "user" yang sedang login
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api_customer')->user()
        ], 200);
    }

    /**
     * refreshToken
     *
     * @param  mixed $request
     * @return void
     */
    public function refreshToken(Request $request)
    {
        //refresh "token"
        $refreshToken = JWTAuth::refresh(JWTAuth::getToken());

        //set user dengan "token" baru
        $user = JWTAuth::setToken($refreshToken)->toUser();

        //set header "Authorization" dengan type Bearer + "token" baru
        $request->headers->set('Authorization','Bearer '.$refreshToken);

        //response data "user" dengan "token" baru
        return response()->json([
            'success' => true,
            'user'    => $user,
            'token'   => $refreshToken,
        ], 200);
    }

    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        //remove "token" JWT
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        //response "success" logout
        return response()->json([
            'success' => true,
        ], 200);

    }

    public function updateProfile(Request $request){

        $auth = auth()->guard('api_customer')->user();
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|unique:customers,email,'.$auth->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $update = Customer::find($auth->id);
        $update->name = $request->name;
        $update->email = $request->email;
        $update->save();

        if($update) {
            //return with Api Resource
            return new CustomerResource(true, 'update Customer Berhasil', $auth);
        }

        //return failed with Api Resource
        return new CustomerResource(false, 'Register Customer Gagal!', null);
    }

    public function updatePassword(Request $request)
    {
        // Validasi request
        $request->validate([
            'oldpassword' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->guard('api_customer')->user();

        // Periksa apakah password lama sesuai
        if (!Hash::check($request->oldpassword, $user->password)) {
            return response()->json(['error' => 'Password lama tidak sesuai'], 422);
        }

        // Update password pengguna
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return new CustomerResource(true, 'password berhasil diubah', $user);
    }
}
