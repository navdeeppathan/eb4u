<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsPage;

class CmsPageSeeder extends Seeder
{
    public function run()
    {
        $policies = [
            'about-us' => [
                'title' => 'About Eb4u Ltd',
                'content' => '
<h2>Welcome to Eb4u – Britain\'s Premier Electric Bike Destination</h2>
<p>Founded in London, <strong>Eb4u Ltd</strong> (Company Registration No. 14892011) was established with a singular mission: to accelerate sustainable, effortless urban mobility across the United Kingdom. Whether you are commuting through Central London, exploring regional national parks, or building a greener fleet for your business, Eb4u provides top-tier German and British electric bicycles for purchase and flexible rental.</p>

<h3>Our Core Pillars</h3>
<ul>
  <li><strong>Quality Engineering:</strong> We strictly partner with world-renowned manufacturers including Bosch, Yamaha, Shimano, Gazelle, Haibike, Specialized, and Trek. Every e-bike in our sales and rental fleet undergoes rigorous 25-point UK mechanical and electrical safety checks before dispatch.</li>
  <li><strong>Flexible Mobility Solutions:</strong> We believe riders shouldn\'t be locked into rigid ownership if they prefer flexibility. Our hybrid sales and rental model enables customers to rent by the day, week, or month with seamless options to extend or purchase at a discount.</li>
  <li><strong>Battery Safety & Eco Guarantee:</strong> All lithium-ion batteries supplied by Eb4u are UK CA / CE certified, UN 38.3 battery safety tested, and managed in compliance with UK Waste Electrical and Electronic Equipment (WEEE) directives.</li>
</ul>

<h3>Our Regent Street Showroom</h3>
<p>Visit our flagship London showroom located at <strong>142 Regent Street, London, W1B 5SE</strong> to test ride our full range, speak with certified bicycle technicians, or manage rental collections and returns in person.</p>
'
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy & UK GDPR Notice',
                'content' => '
<p><strong>Effective Date:</strong> 1st January 2026 | <strong>Last Updated:</strong> 25th August 2026</p>
<p>Eb4u Ltd ("we", "us", "our") is committed to protecting the privacy, confidentiality, and security of all personal data collected from our customers, website visitors, and rental clients in compliance with the UK General Data Protection Regulation (UK GDPR) and the Data Protection Act 2018.</p>

<h2>1. Data Controller Information</h2>
<p>Eb4u Ltd is the data controller responsible for your personal data. Registered Office: 142 Regent Street, London, W1B 5SE, United Kingdom. Company Registration Number: 14892011. ICO Registration Number: ZB904128. For data privacy inquiries or to exercise your statutory rights, please contact our Data Protection Officer at <a href="mailto:privacy@eb4u.co.uk">privacy@eb4u.co.uk</a>.</p>

<h2>2. Personal Data We Collect</h2>
<p>We collect and process the following categories of personal data:</p>
<ul>
  <li><strong>Identity & Contact Data:</strong> Full name, billing address, UK shipping address, mobile telephone number, email address, and date of birth (to verify 18+ age eligibility for rental agreements).</li>
  <li><strong>Verification & Anti-Fraud Data:</strong> For rental bookings, we collect government-issued photo ID (UK driving license or passport) and proof of address for identity verification and anti-theft compliance.</li>
  <li><strong>Financial & Payment Data:</strong> Credit/debit card details, billing address, and transaction histories. All online card transactions are processed via PCI-DSS Level 1 compliant payment gateways (Stripe / Barclays Merchant Services). Eb4u never stores raw 16-digit credit card numbers on its servers.</li>
  <li><strong>Technical & Usage Data:</strong> IP address, browser type, device information, operating system, pages visited, and interaction telemetry via cookies.</li>
  <li><strong>Fleet Telemetry & GPS (Rental Units Only):</strong> Active rental e-bikes are fitted with GPS tracking units for theft prevention, remote diagnostic battery monitoring, and geo-fence recovery. Telemetry data is only accessed during active rental periods or in cases of reported theft or emergency.</li>
</ul>

<h2>3. Legal Basis & Purposes for Processing</h2>
<p>We process your personal data under the following UK GDPR legal bases:</p>
<ul>
  <li><strong>Performance of Contract (Article 6(1)(b)):</strong> To process sales orders, fulfill rental bookings, deliver products, process security deposits, and handle customer service requests.</li>
  <li><strong>Legitimate Interests (Article 6(1)(f)):</strong> To prevent fraudulent orders, secure physical fleet assets, improve website performance, and maintain business accounting records.</li>
  <li><strong>Legal Obligation (Article 6(1)(c)):</strong> To comply with UK tax, VAT reporting (HMRC), and financial auditing regulations.</li>
  <li><strong>Consent (Article 6(1)(a)):</strong> For sending direct electronic marketing communications, newsletter updates, or promotional vouchers. You may withdraw consent at any time by clicking "unsubscribe".</li>
</ul>

<h2>4. Data Retention & Security Measures</h2>
<p>We implement robust technical and organizational security measures, including 256-bit SSL encryption, restricted administrative access controls, and firewall protection. Transactional records and tax invoices are retained for 7 years in accordance with UK statutory accounting law, after which they are securely deleted.</p>

<h2>5. Your Rights Under UK GDPR</h2>
<p>You have the right to request access to your personal data, request correction of inaccurate data, request erasure ("right to be forgotten"), restrict processing, or request data portability. If you believe your data has been handled improperly, you have the right to file a complaint with the Information Commissioner\'s Office (ICO) at <a href="https://ico.org.uk" target="_blank">www.ico.org.uk</a>.</p>
'
            ],
            'terms-and-conditions' => [
                'title' => 'Terms & Conditions of Sale & Service',
                'content' => '
<p><strong>Effective Date:</strong> 1st January 2026</p>
<p>These Terms and Conditions govern all sales of products, accessories, e-bike rentals, and related services offered by <strong>Eb4u Ltd</strong> ("Eb4u", "we", "our") through our website <a href="https://eb4u.co.uk">eb4u.co.uk</a> and physical store locations across the United Kingdom.</p>

<h2>1. Eligibility & Contract Formation</h2>
<p>By placing an order or booking a rental on our platform, you confirm that you are at least 18 years of age and legally capable of entering into binding contracts under English Law. A legally binding contract is formed when Eb4u issues an official order confirmation with a unique reference number (e.g. UK-ORD-2026-XXXX or UK-RNT-2026-XXXX).</p>

<h2>2. Pricing, UK VAT & Payment Terms</h2>
<ul>
  <li>All prices displayed on the Eb4u platform are in British Pounds Sterling (£ GBP) and include UK Value Added Tax (VAT) at the statutory rate of 20%.</li>
  <li>We accept major debit and credit cards (Visa, Mastercard, American Express), Apple Pay, and UK bank transfers.</li>
  <li>For purchase orders, full payment must be settled prior to dispatch.</li>
  <li>For rental bookings, customers may choose between paying a <strong>30% Advance Holding Payment</strong> online or paying the <strong>Full Rental Amount</strong> upfront. Any remaining balance is payable prior to e-bike collection or home delivery.</li>
</ul>

<h2>3. Security Deposits & Inspection Protocol</h2>
<p>A refundable security deposit (ranging from £120.00 to £300.00 depending on e-bike tier) is pre-authorized on the customer\'s payment card upon booking. The deposit is held to cover unreturned accessories, severe mechanical damage due to negligence, or unauthorized late returns. Security deposits are released in full within 3 to 5 business days following satisfactory post-rental inspection.</p>

<h2>4. Customer Obligations & Equipment Care</h2>
<ul>
  <li>The renter agrees to operate the e-bike safely in accordance with the UK Highway Code and Electrically Assisted Pedal Cycles (EAPC) regulations (15.5 mph motor speed limit).</li>
  <li>The renter must secure the e-bike at all times when unattended using the Gold Sold Secure rated lock provided by Eb4u, attaching the frame to an immovable fixture.</li>
  <li>In the event of loss or theft, the renter must report the incident to the Police within 2 hours to obtain a Crime Reference Number and notify Eb4u immediately at <a href="tel:+442079460912">+44 (0) 20 7946 0912</a>.</li>
</ul>

<h2>5. Limitation of Liability & Governing Law</h2>
<p>Eb4u shall not be liable for any indirect, incidental, or consequential loss arising from vehicle misuse, improper riding gear, or unauthorized mechanical alterations. These terms are governed by and construed in accordance with the laws of England and Wales, and the courts of England shall have exclusive jurisdiction.</p>
'
            ],
            'rental-policy' => [
                'title' => 'Official E-Bike Rental Policy',
                'content' => '
<h2>Comprehensive UK E-Bike Rental Terms & Fleet Guidelines</h2>
<p>This Rental Policy sets out the specific terms governing short-term daily, weekly, and monthly electric bicycle rentals provided by <strong>Eb4u Ltd</strong>.</p>

<h3>1. Driver Requirements & Age Limit</h3>
<ul>
  <li>Renters must be at least 18 years old.</li>
  <li>A valid government-issued photo ID (UK Driver\'s License or Passport) and proof of current UK address (utility bill or bank statement within 3 months) must be presented upon collection or delivery.</li>
</ul>

<h3>2. EAPC UK Road Compliance</h3>
<p>All Eb4u rental electric bikes comply strictly with UK Electrically Assisted Pedal Cycle (EAPC) rules. Motors are factory limited to 250W continuous power output with assistance cut-off at 15.5 mph (25 km/h). No driving license, road tax, or DVLA registration is legally required to ride our e-bikes on public UK roads and designated cycle paths.</p>

<h3>3. Rental Period, Extensions & Overdue Charges</h3>
<ul>
  <li><strong>Pickup & Return Times:</strong> Standard collection begins at 09:00 AM on the start date, and returns must be completed by 18:00 PM on the agreed end date.</li>
  <li><strong>Rental Extensions:</strong> Extensions can be requested directly through your Customer Dashboard or by contacting customer service at least 24 hours prior to return time, subject to fleet availability.</li>
  <li><strong>Overdue Returns:</strong> Unannounced late returns will incur a late charge equivalent to 1.5x the daily rental rate for every 24-hour period overdue.</li>
</ul>

<h3>4. Battery Charging & Maintenance</h3>
<p>Every rental e-bike is supplied with an official manufacturer smart charger (Bosch / Yamaha / Shimano). Renters must use only the supplied charger and plug directly into standard UK 230V wall sockets. Batteries must never be left charging unattended overnight or exposed to sub-zero moisture.</p>
'
            ],
            'refund-policy' => [
                'title' => 'Refund, Cancellation & Deposit Return Policy',
                'content' => '
<h2>1. Rental Booking Cancellation & Refunds</h2>
<p>We understand plans change. Our rental cancellation schedule is as follows:</p>
<ul>
  <li><strong>Cancellations 48+ Hours Before Rental Start:</strong> 100% full refund of any advance payments or full rental fees paid.</li>
  <li><strong>Cancellations 24 to 48 Hours Before Start:</strong> 50% refund of advance payment.</li>
  <li><strong>Cancellations Under 24 Hours or No-Show:</strong> Advance payment is non-refundable to cover fleet reservation costs.</li>
</ul>

<h2>2. Statutory 14-Day Return Right (E-Bike & Accessory Purchases)</h2>
<p>Under the UK Consumer Contracts Regulations 2013, you have the right to cancel your purchase order within 14 days of receiving your goods without giving any reason.</p>
<ul>
  <li>Items must be unused, unridden (odometer reading less than 5 miles for e-bikes), in their original condition, and packed securely in original packaging.</li>
  <li>To initiate a return, contact <a href="mailto:support@eb4u.co.uk">support@eb4u.co.uk</a> to obtain a Return Merchandise Authorization (RMA) label.</li>
  <li>Refunds are processed to the original payment method within 5 to 7 business days following warehouse inspection.</li>
</ul>

<h2>3. Security Deposit Refund Schedule</h2>
<p>Security deposit pre-authorizations are automatically released within 3 to 5 business days following the safe return and mechanical inspection of the rental bike. If minor damage or missing accessories occur (e.g. lost charger, flat tyre due to curb impact), itemized repair costs will be deducted prior to releasing the remaining balance.</p>
'
            ],
            'shipping-policy' => [
                'title' => 'UK Shipping & Delivery Policy',
                'content' => '
<h2>Fast UK Mainland Shipping & Fully Assembled Delivery</h2>
<p>Eb4u offers fast, reliable freight and courier shipping across England, Scotland, Wales, and Northern Ireland.</p>

<h3>1. Shipping Rates & Delivery Times</h3>
<ul>
  <li><strong>FREE UK Mainland Shipping:</strong> On all orders and e-bike purchases over £500.00.</li>
  <li><strong>Standard Accessory Shipping:</strong> £4.95 for orders under £50.00 (2-3 business days via Royal Mail Tracked 24).</li>
  <li><strong>E-Bike Freight Delivery:</strong> Delivered in 2 to 3 business days via specialized two-man delivery teams (DPD Heavy / Logistics UK).</li>
</ul>

<h3>2. E-Bike Assembly Condition</h3>
<p>All purchased e-bikes arrive <strong>95% Pre-Assembled</strong> in custom reinforced eco-friendly bike boxes. You only need to align the handlebars, attach pedals, and insert the quick-release front wheel using the complimentary Eb4u multi-tool provided in the box.</p>

<h3>3. Transit Inspection & Damage Protocol</h3>
<p>Please inspect the outer box upon delivery. In the rare event of transit damage, take photos of the box damage before signing, note "damaged package" on the courier delivery slip, and contact our team immediately at <a href="mailto:support@eb4u.co.uk">support@eb4u.co.uk</a>.</p>
'
            ],
        ];

        foreach ($policies as $slug => $data) {
            CmsPage::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('CMS Legal & Policy Pages updated successfully with real-world UK content!');
    }
}
