<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingApp extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'title',
        'RAJAONGKIR_API_KEY',
        'MIDTRANS_SERVERKEY',
        'MIDTRANS_CLIENTKEY',
        'ZENZIVA_USERKEY',
        'ZENZIVA_PASSKEY',
        'email_outlet',
        'whatsapp_outlet',
        'alamat_outlet',
    ];

    public function getImageAttribute($image)
    {
        return $image ? asset('storage/settingapp/' . $image) : 'https://ui-avatars.com/api/?name=' . str_replace(' ', '+', $this->name) . '&background=4e73df&color=ffffff&size=100';
    }
}
