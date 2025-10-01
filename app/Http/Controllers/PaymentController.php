<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Mpesa;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function generateToken()
    {
        $consumer_key = env('MPESA_CONSUMER_KEY');
        $consumer_secret = env('MPESA_CONSUMER_SECRET');
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $res = Http::withBasicAuth($consumer_key, $consumer_secret)
            ->get($url);
        $response = json_decode($res, true);
        return $response['access_token'];
    }
    public function lipaNaMpesaPassword()
    {
        $passkey = env('MPESA_PASSKEY');
        $BusinessShortCode = env('MPESA_SHORT_CODE');
        $timestamp = date('YmdHis');
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode . $passkey . $timestamp);
        return $lipa_na_mpesa_password;
    }
    public function Callback($id)
    {
        $res = request();
        // Log::channel('mpesaSuccess')->info(json_encode(['whole' => $res['Body']]));
        $message = $res['Body']['stkCallback']['ResultDesc'];
        $amount = $res['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
        $TransactionId = $res['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
        $phne = $res['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];
        Log::channel('mpesaSuccess')->info(json_encode(['whole' => $res['Body']]));
        Mpesa::create([
            'TransactionType' => 'Paybill',
            'account_id' => $id,
            'TransAmount' => $amount,
            'MpesaReceiptNumber' => $TransactionId,
            'TransactionDate' => date('d-m-Y'),
            'PhoneNumber' => '+' . $phne,
            'response' => $message
        ]);
        Payment::where('reference', $id)->update(['status' => 'completed']);
        $response = new Response();
        $response->headers->set("Content-Type", "text/xml; charset=utf-8");
        $response->setContent(json_encode(["C2BPaymentConfirmationResult" => "Success"]));
        return $response;
    }
    public function Pay($amount, $contact, $id)
    {
        $url = (env('MPESA_ENV') == 'live') ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest' : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $data = [
            'BusinessShortCode' => env('MPESA_SHORT_CODE'),
            'Password' => $this->lipaNaMpesaPassword(),
            'Timestamp' => date('YmdHis'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $contact,
            'PartyB' => env('MPESA_SHORT_CODE'),
            'PhoneNumber' => $contact,
            'CallBackURL' => 'https://cms.apektechinc.com/api/payment/callback/' . $id,
            'AccountReference' => 'Payment of Offering from CMS',
            'TransactionDesc' => 'Payment of Offering from CMS',
        ];
        $response = Http::withToken($this->generateToken())
            ->post($url, $data);
        $res = $response->json();
        return $res;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();
        if (request()->is('api/*')) {
            return response()->json(['data' => $payments]);
        }
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $accounts = Account::all();

        if (request()->is('api/*') && request('total_amount') != null && request('contact') != null) {
            $uniqid = strtoupper(uniqid());
            foreach ($accounts as $account) {
                if (request($account->name) != null) {
                    try {
                        Payment::create([
                            'account_id' => $account->id,
                            'amount' => request($account->name),
                            'status' => 'pending',
                            'payment_method' => request('payment_method') ?? 'Mobile Money',
                            'reference' => $uniqid,
                            'user_id' => Auth::user()->id,
                            'logged_by' => Auth::user()->id,
                        ]);
                    } catch (\Exception $e) {
                        return response()->json(['message' => 'Error logging payment for account: ' . $account->name, 'error' => $e->getMessage()], 500);
                    }
                }
            }
            $contact = request('contact');
            $phone = ltrim($contact, 0);
            $phone = '254' . $phone;
            $resp = $this->Pay(request('total_amount'), $phone, $uniqid);
            if ($resp['ResponseCode'] == 0) {
                return response()->json(['message' => 'Payments logged successfully']);
            } else {
                return response()->json(['message' => 'Error initiating payment. Proceed to your transactions and try repaying', 'error' => $resp], 202);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
