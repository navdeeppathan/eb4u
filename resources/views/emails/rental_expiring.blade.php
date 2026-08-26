<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Bike Rental Expiration Reminder</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f5f7fb; margin: 0; padding: 0; color: #0f172a; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 20px; overflow: hidden; border: 1px solid #dde4f0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .header { background: #0f172a; padding: 24px; text-align: center; border-bottom: 3px solid #f97316; }
        .logo { font-size: 24px; font-weight: 800; color: #ffffff; text-decoration: none; }
        .logo span { color: #f97316; }
        .badge { display: inline-block; padding: 4px 12px; background: rgba(249,115,22,0.15); color: #f97316; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 12px; }
        .content { padding: 32px 28px; }
        .h1 { font-size: 22px; font-weight: 800; margin-top: 0; margin-bottom: 12px; color: #0f172a; }
        .p { font-size: 14px; line-height: 1.6; color: #445568; margin-bottom: 18px; }
        .card { background: #f5f7fb; border: 1px solid #dde4f0; border-radius: 14px; padding: 18px; margin-bottom: 24px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
        .label { color: #8898b0; font-weight: 500; }
        .value { color: #0f172a; font-weight: 700; }
        .highlight-value { color: #f97316; font-weight: 800; }
        .btn { display: inline-block; background: #f97316; color: #ffffff !important; text-decoration: none; padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 14px; text-align: center; }
        .footer { background: #edf1f8; padding: 20px; text-align: center; font-size: 11px; color: #8898b0; border-top: 1px solid #dde4f0; }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <a href="{{ route('home') }}" class="logo">eb<span>4</span>u</a>
        </div>

        <!-- Main Body -->
        <div class="content">
            <div class="badge">
                {{ $isExpired ? '🚨 Rental Expired' : '⏰ Rental Expiring Soon' }}
            </div>

            <h1 class="h1">
                {{ $isExpired ? 'Your E-Bike Rental Period Has Expired' : 'Your E-Bike Rental is Expiring Soon' }}
            </h1>

            <p class="p">
                Dear <strong>{{ $user->name ?? 'Valued Cyclist' }}</strong>,
            </p>

            <p class="p">
                {{ $isExpired 
                    ? "This is an important reminder from eb4u UK that your electric bike rental lease for Order #{$order->order_number} has reached its end date. Please extend your lease online or return the bike to avoid late charges."
                    : "We hope you are enjoying your electric bike ride! This is a courteous notice that your rental period for Order #{$order->order_number} is concluding soon."
                }}
            </p>

            @if($customNote)
                <div style="background: #fff7ed; border-left: 4px solid #f97316; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 10px 10px 0; font-size: 13px; color: #9a3412;">
                    <strong>Note from Store Manager:</strong> {{ $customNote }}
                </div>
            @endif

            <!-- Rental Details Card -->
            <div class="card">
                <div class="row">
                    <span class="label">Order Reference:</span>
                    <span class="value">#{{ $order->order_number }}</span>
                </div>
                <div class="row">
                    <span class="label">E-Bike Model:</span>
                    <span class="value">{{ $rentalItem ? $rentalItem->product_name : 'Electric Bike' }}</span>
                </div>
                <div class="row">
                    <span class="label">Rental End Date:</span>
                    <span class="highlight-value">
                        {{ $rentalItem && $rentalItem->rental_end_date ? \Carbon\Carbon::parse($rentalItem->rental_end_date)->format('d M Y') : 'Today' }}
                    </span>
                </div>
                <div class="row">
                    <span class="label">Fulfillment Choice:</span>
                    <span class="value">{{ ucfirst($order->fulfillment_type) }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div style="text-align: center; margin-top: 24px; margin-bottom: 24px;">
                <a href="{{ route('customer.rentals') }}" class="btn">
                    Extend Rental or View Options &rarr;
                </a>
            </div>

            <p class="p" style="font-size: 12px;">
                <strong>What would you like to do?</strong><br>
                1. <strong>Extend Online:</strong> You can add extra days to your lease directly in your Customer Dashboard.<br>
                2. <strong>Return to Hub:</strong> Return your bike to our flagship store at 142 Regent Street, London, W1B 5SE to receive your instant security deposit refund.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} eb4u Ltd. Registered in England & Wales (No. 12849201).<br>
            142 Regent Street, London, W1B 5SE, UK | support@eb4u.co.uk
        </div>
    </div>

</body>
</html>
