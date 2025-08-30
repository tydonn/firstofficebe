<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Http\Resources\Api\BookingTransactionResource;
use App\Http\Resources\Api\ViewBookingResource;
use App\Models\BookingTransaction;
use App\Models\OfficeSpace;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class BookingTransactionController extends Controller
{
    //Method untuk menampilkan detail transaksi booking
    public function booking_details(Request $request)
    {

        $request->validate([
            'phone_number' => 'required|string',
            'booking_trx_id' => 'required|string',
        ]);

        $booking = BookingTransaction::where('phone_number', $request->phone_number)
            ->where('booking_trx_id', $request->booking_trx_id)
            ->with(['officeSpace', 'officeSpace.city'])
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return new ViewBookingResource($booking);
    }

    //Method untuk menyimpan transaksi booking
    public function store(StoreBookingTransactionRequest $request)
    {

        $validatedData = $request->validated();

        $officeSpace = OfficeSpace::find($validatedData['office_space_id']);
        if (!$officeSpace) {
            return response()->json([
                'error' => 'Office space tidak ditemukan.',
            ], 404);
        }

        $validatedData['is_paid'] = false;

        $validatedData['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();

        $validatedData['duration'] = $officeSpace->duration;

        $validatedData['ended_at'] = (new \DateTime($validatedData['started_at']))->modify("+{$officeSpace->duration} days")->format('Y-m-d');

        $bookingTransaction = BookingTransaction::create($validatedData);

        //mengirim notif melalui whatsapp dan sms dengan twilio
        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        /* $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $messageBody = "Hi {$bookingTransaction->name}, Terimakasih telah booking kantor di FirstOffice.\n\n";
        $messageBody = "Pesanan kantor {$bookingTransaction->office->name}, Anda sedang kami proses dengan Booking TRX ID {$bookingTransaction->booking_trx_id}.\n\n";
        $messageBody = "Kami akan menginformasikan status pesanan Anda secepat mungkin.";

        //kirim fitur sms
        $twilio->messages->create(
            //"+689675594343"
            "+{$bookingTransaction->phone_number}",
            [
                "body" => $messageBody,
                "from" => getenv("TWILIO_PHONE_NUMBER")
            ]
            ); */
        
        //kirim fitur whatsapp
        /* $message = $twilio->messages
        ->create(
            "+{$bookingTransaction->phone_number}",
            array(
                "from" => "whatsapp:+14155238886",
                "body" => $messageBody,
            )
            ); */

        //mengembalikan response hasil transaksi
        $bookingTransaction->load('officeSpace');
        return new BookingTransactionResource($bookingTransaction);

        return response()->json([
            'message' => 'Booking berhasil dibuat',
            'data' => $bookingTransaction
        ], 201);
    }
}
