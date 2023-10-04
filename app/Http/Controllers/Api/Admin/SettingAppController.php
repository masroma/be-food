<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingApp;
use App\Http\Resources\SettingAppResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SettingAppController extends Controller
{
    // get detail data
    public function index(){
        $data = SettingApp::first();
        return new SettingAppResource(true, 'Detail Settingapp', $data);
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'title'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = SettingApp::first();
        if ($request->file('logo')) {

            //remove old image
            Storage::disk('local')->delete('public/categories/'.basename($category->image));

            //upload new image
            $image = $request->file('logo');
            $image->storeAs('public/settingapp', $image->hashName());

            //update category with new image
            $data->update([
                'logo'=> $image->hashName(),
                'title' => $request->title,
                'RAJAONGKIR_API_KEY' => $request->RAJAONGKIR_API_KEY,
                'MIDTRANS_SERVERKEY' => $request->MIDTRANS_SERVERKEY,
                'MIDTRANS_CLIENTKEY' => $request->MIDTRANS_CLIENTKEY,
                'ZENZIVA_USERKEY' => $request->ZENZIVA_USERKEY,
                'ZENZIVA_PASSKEY' => $request->ZENZIVA_PASSKEY,
                'email_outlet' => $request->email_outlet,
                'whatsapp_outlet' => $request->whatsapp_outlet,
                'alamat_outlet' => $request->alamat_outlet,
            ]);

        }else{
            $data->update([
                'title' => $request->title,
                'RAJAONGKIR_API_KEY' => $request->RAJAONGKIR_API_KEY,
                'MIDTRANS_SERVERKEY' => $request->MIDTRANS_SERVERKEY,
                'MIDTRANS_CLIENTKEY' => $request->MIDTRANS_CLIENTKEY,
                'ZENZIVA_USERKEY' => $request->ZENZIVA_USERKEY,
                'ZENZIVA_PASSKEY' => $request->ZENZIVA_PASSKEY,
                'email_outlet' => $request->email_outlet,
                'whatsapp_outlet' => $request->whatsapp_outlet,
                'alamat_outlet' => $request->alamat_outlet,
            ]);
        }

        //update category without image


        if($data) {
            //return success with Api Resource
            return new SettingAppResource(true, 'Data Setting App Berhasil Diupdate!', $data);
        }

        //return failed with Api Resource
        return new SettingAppResource(false, 'Data Setting App Gagal Diupdate!', null);
    }
}
