<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $shopName = Setting::get('shop_name', 'Apotek Super');
        $shopAddress = Setting::get('shop_address', 'Jl. Laragon No. 1, Kota Laragon');
        $shopPhone = Setting::get('shop_phone', '0812-3456-7890');
        $shopEmail = Setting::get('shop_email', 'info@apoteksuper.com');

        return view('settings.index', compact('shopName', 'shopAddress', 'shopPhone', 'shopEmail'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'required|string',
            'shop_phone' => 'required|string|max:50',
            'shop_email' => 'nullable|email|max:255',
        ]);

        Setting::set('shop_name', $request->shop_name);
        Setting::set('shop_address', $request->shop_address);
        Setting::set('shop_phone', $request->shop_phone);
        Setting::set('shop_email', $request->shop_email);

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan apotek berhasil diperbarui!');
    }
}
