<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentalExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $rentalItem;
    public $customNote;
    public $isExpired;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $user, $rentalItem = null, string $customNote = '')
    {
        $this->order = $order;
        $this->user = $user;
        $this->rentalItem = $rentalItem ?: ($order->items ? $order->items->first() : null);
        $this->customNote = $customNote;

        $endDate = $this->rentalItem && $this->rentalItem->rental_end_date ? \Carbon\Carbon::parse($this->rentalItem->rental_end_date) : null;
        $this->isExpired = $endDate && $endDate->isPast();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isExpired
            ? "🚨 URGENT: Your eb4u E-Bike Rental Has Expired (Order #{$this->order->order_number})"
            : "⏰ Action Required: Your eb4u E-Bike Rental is Expiring Soon (Order #{$this->order->order_number})";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.rental_expiring',
        );
    }
}
