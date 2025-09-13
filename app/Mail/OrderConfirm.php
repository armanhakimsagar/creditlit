<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirm extends Mailable
{
    use Queueable, SerializesModels;

    protected $emailData;
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    public function build()
    {

        return $this->subject('Order Placed Successfully')
            ->view('Order::mail.orderPlaceMail', [
                'OrderID' => $this->emailData['OrderID'],
                'OrderDate' => $this->emailData['OrderDate'],
                'CompanyName' => $this->emailData['CompanyName'],
                'CountryName' => $this->emailData['CountryName'],
            ]);
    }



    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    // public function envelope()
    // {
    //     return new Envelope(
    //         subject: 'Order Confirm',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  *
    //  * @return \Illuminate\Mail\Mailables\Content
    //  */
    // public function content()
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array
    //  */
    // public function attachments()
    // {
    //     return [];
    // }
}
