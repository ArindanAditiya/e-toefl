<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the official academic landing page for e-TOEFL Ma'had Aly Tebuireng
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $adminWa = config('services.admin.wa') ?: env('ADMIN_WA', '@mahadalytbi');
        $adminEmail = config('services.admin.email') ?: env('ADMIN_EMAIL', 'mahadaly@tebuireng.ac.id');
        $adminAddress = config('services.admin.address') ?: env('ADMIN_ADDRESS', 'Jl. Irian Jaya No. 10, Tebuireng, Cukir, Kec. Diwek, Kabupaten Jombang, Jawa Timur 61471');
        $appName = config('app.name') ?: env('APP_NAME', "e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng");

        // Format tautan WhatsApp resmi
        $adminWaLink = 'https://wa.me/';
        if (strpos($adminWa, 'http://') === 0 || strpos($adminWa, 'https://') === 0) {
            $adminWaLink = $adminWa;
        } elseif (strpos($adminWa, '@') === 0) {
            $adminWaLink = 'https://wa.me/' . substr($adminWa, 1);
        } else {
            $adminWaClean = preg_replace('/[^0-9]/', '', $adminWa);
            if (!empty($adminWaClean)) {
                $adminWaIntl = (substr($adminWaClean, 0, 1) === '0') ? ('62' . substr($adminWaClean, 1)) : $adminWaClean;
                $adminWaLink = 'https://wa.me/' . $adminWaIntl;
            }
        }

        return view('landing', compact('adminWa', 'adminEmail', 'adminAddress', 'appName', 'adminWaLink'));
    }
}
