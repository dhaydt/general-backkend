<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Xendit\Xendit;

class PaymentController extends Controller
{
    public function index()
    {
        return view('pages.payment');
    }

    public function createVa()
    {
    }

    public function listVa()
    {
    }

    public function invoice(Request $request)
    {
        $customer = auth('customer')->user();
        $value = 20000;
        Xendit::setApikey(config('xendit.apikey'));

        $user = [
            'given_names' => $customer->f_name,
            'email' => $customer->email,
            'mobile_number' => $customer->phone,
            // 'address' => $customer->district.', '.$customer->city.', '.$customer->province,
            'address' => 'Solok',
        ];
        $params = [
            'external_id' => 'ws'.$customer->id,
            'amount' => $value,
            'payer_email' => $customer->email,
            'description' => 'WSHOPEDIA',
            // 'payment_methods' => $request->type,
            'fixed_va' => true,
            'should_send_email' => true,
            // 'customer' => $user,
            // 'items' => $products,
            'success_redirect_url' => env('APP_URL').'/payment-success',
        ];
        // dd($params);

        $checkout_session = \Xendit\Invoice::create($params);

        return redirect()->away($checkout_session['invoice_url'].'#'.$request->type);
    }

    public function success()
    {
        // session()->put('payment', $request['payment_method']);

        $data = [
            'name' => 'Transaksi berhasil',
        ];
        session()->put('category', $data);

        return view('web-views.payment-complete');
    }

    public function expire()
    {
    }
}
