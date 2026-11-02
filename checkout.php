<?php
require_once __DIR__ . '/_inc/MockData.php';

$slug = $_GET['event'] ?? 'lagos-innovators-gala';
$event = MockData::getEventBySlug($slug) ?? MockData::getEventById($slug) ?? MockData::getEvents()[2];

$tickets = MockData::getTicketsForEvent($event['id']);
$ticketId = $_GET['ticket_id'] ?? ($tickets[0]['id'] ?? '');
$selectedTicket = null;
foreach ($tickets as $t) {
    if ($t['id'] === $ticketId) {
        $selectedTicket = $t;
        break;
    }
}
if (!$selectedTicket && !empty($tickets)) {
    $selectedTicket = $tickets[0];
}

$quantity = max(1, (int)($_GET['quantity'] ?? 1));
$unitPrice = $selectedTicket['price_ngn'] ?? 25000;
$subtotal = $unitPrice * $quantity;
$fee = 0;
$total = $subtotal + $fee;

$isConfirmed = !empty($_GET['confirmed']);
$orderRef = 'FOL-ORD-' . strtoupper(substr(md5($event['id'] . $ticketId . $quantity), 0, 8));

$pageTitle = 'Secure Checkout — ' . $event['title'];
require_once __DIR__ . '/_inc/header.php';
?>

<div class="min-h-screen flex flex-col bg-[#FAF8F5]">

  <!-- Minimal Checkout Header -->
  <header class="bg-white border-b border-[#E9E4DD] sticky top-0 z-30">
    <div class="container-main px-4 h-16 flex items-center justify-between">
      <a href="/event.php?slug=<?= urlencode($event['slug']) ?>" class="btn btn-ghost btn-sm text-[#252321] hover:text-[#9A5F44]">
        <i class="fa-solid fa-arrow-left mr-1.5"></i>
        <span>Back to Event</span>
      </a>

      <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-lg bg-[#9A5F44] flex items-center justify-center text-white font-bold text-xs">
          F
        </div>
        <span class="text-xs font-bold tracking-tight text-[#252321]">FOLIIOO CHECKOUT</span>
      </div>

      <div class="badge badge-success">
        <i class="fa-solid fa-shield-halved mr-1.5"></i>
        <span>256-Bit Encrypted</span>
      </div>
    </div>
  </header>

  <main class="flex-1 container-main px-4 py-8">
    
    <?php if ($isConfirmed): ?>
      <!-- Order Confirmation Screen -->
      <div class="card p-6 sm:p-10 max-w-2xl mx-auto space-y-6 text-center">
        <div class="w-16 h-16 bg-[#E8F1EA] text-[#3F6B48] rounded-2xl flex items-center justify-center mx-auto">
          <i class="fa-solid fa-circle-check text-3xl"></i>
        </div>

        <div class="space-y-2">
          <span class="badge badge-success">Payment Successful</span>
          <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#252321]">You're Going to <?= htmlspecialchars($event['title']) ?>!</h1>
          <p class="text-xs text-[#6B675F] max-w-md mx-auto leading-relaxed">
            Your simulated payment of <strong>₦<?= number_format($total) ?></strong> was confirmed. A receipt and digital ticket pass have been generated below.
          </p>
        </div>

        <!-- Ticket Card Mockup -->
        <div class="bg-[#252321] text-white rounded-2xl p-6 sm:p-8 text-left relative overflow-hidden">
          <div class="flex items-start justify-between gap-4 border-b border-white/10 pb-5">
            <div>
              <span class="eyebrow text-[#87927B]">Official Admission Pass</span>
              <h3 class="font-display text-lg font-bold mt-1 text-white"><?= htmlspecialchars($event['title']) ?></h3>
              <p class="text-xs text-[#FAF8F5]/70 mt-0.5"><?= htmlspecialchars($selectedTicket['name'] ?? 'General Access') ?></p>
            </div>
            <div class="bg-white p-2.5 rounded-xl shrink-0 text-[#252321]">
              <i class="fa-solid fa-qrcode text-4xl"></i>
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-5 text-xs">
            <div>
              <span class="text-[#A19B93] block text-[10px] uppercase font-bold">Order Ref</span>
              <span class="font-mono font-bold text-white"><?= $orderRef ?></span>
            </div>
            <div>
              <span class="text-[#A19B93] block text-[10px] uppercase font-bold">Quantity</span>
              <span class="font-bold text-white"><?= $quantity ?> <?= $quantity === 1 ? 'Pass' : 'Passes' ?></span>
            </div>
            <div>
              <span class="text-[#A19B93] block text-[10px] uppercase font-bold">Date</span>
              <span class="font-bold text-white"><?= date('M j, Y', strtotime($event['date'] ?? 'now')) ?></span>
            </div>
            <div>
              <span class="text-[#A19B93] block text-[10px] uppercase font-bold">Door Status</span>
              <span class="text-[#87927B] font-bold">Valid & Active</span>
            </div>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
          <button type="button" onclick="window.print();" class="btn btn-secondary w-full sm:w-auto">
            <i class="fa-solid fa-print mr-2"></i>
            <span>Print or Save Ticket</span>
          </button>
          <a href="/event.php?slug=<?= urlencode($event['slug']) ?>" class="btn btn-primary w-full sm:w-auto">
            <span>Return to Event Page</span>
            <i class="fa-solid fa-arrow-right ml-2"></i>
          </a>
        </div>
      </div>

    <?php else: ?>
      <!-- Checkout Form -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Buyer Details & Payment Method -->
        <div class="lg:col-span-7 space-y-6">
          
          <div class="card p-6 sm:p-7 space-y-5">
            <div class="border-b border-[#E9E4DD] pb-3">
              <h2 class="font-display text-base font-bold text-[#252321]">Buyer & Attendee Information</h2>
              <p class="text-xs text-[#6B675F] mt-0.5">Tickets will be delivered to this email and phone</p>
            </div>

            <form id="checkout-form" action="/checkout.php" method="GET" class="space-y-4">
              <input type="hidden" name="event" value="<?= htmlspecialchars($event['slug']) ?>">
              <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($selectedTicket['id'] ?? '') ?>">
              <input type="hidden" name="quantity" value="<?= $quantity ?>">
              <input type="hidden" name="confirmed" value="1">

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-group">
                  <label for="buyer-name" class="form-label">Full Name *</label>
                  <input type="text" id="buyer-name" required placeholder="e.g. Chief Adeleke" class="form-input w-full">
                </div>

                <div class="form-group">
                  <label for="buyer-phone" class="form-label">Phone Number *</label>
                  <input type="tel" id="buyer-phone" required placeholder="+234 803 123 4567" class="form-input w-full">
                </div>
              </div>

              <div class="form-group">
                <label for="buyer-email" class="form-label">Email Address for Ticket Delivery *</label>
                <input type="email" id="buyer-email" required placeholder="attendee@example.com" class="form-input w-full">
              </div>

              <!-- Payment Method Selector -->
              <div class="pt-4 border-t border-[#E9E4DD] space-y-3">
                <span class="form-label">Simulated Payment Method</span>
                
                <div class="grid grid-cols-2 gap-3">
                  <label class="flex items-center gap-2.5 p-3.5 rounded-lg border border-[#E9E4DD] hover:border-[#9A5F44] cursor-pointer transition has-checked:border-[#9A5F44] has-checked:bg-[#9A5F44]/5">
                    <input type="radio" name="payment_method" value="card" checked class="text-[#9A5F44] focus:ring-[#9A5F44]">
                    <div>
                      <span class="text-xs font-semibold text-[#252321] block">Debit / Credit Card</span>
                      <span class="text-[10px] text-[#6B675F] block">Mastercard, Visa, Verve</span>
                    </div>
                  </label>

                  <label class="flex items-center gap-2.5 p-3.5 rounded-lg border border-[#E9E4DD] hover:border-[#9A5F44] cursor-pointer transition has-checked:border-[#9A5F44] has-checked:bg-[#9A5F44]/5">
                    <input type="radio" name="payment_method" value="transfer" class="text-[#9A5F44] focus:ring-[#9A5F44]">
                    <div>
                      <span class="text-xs font-semibold text-[#252321] block">Bank Transfer / USSD</span>
                      <span class="text-[10px] text-[#6B675F] block">Instant account credit</span>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Mock Card Inputs -->
              <div class="p-4 bg-[#F3EDE5] rounded-xl border border-[#E9E4DD] space-y-3">
                <div class="flex items-center justify-between text-xs text-[#6B675F]">
                  <span class="font-medium">Test Card Sandbox</span>
                  <span class="badge badge-accent">Mock Mode</span>
                </div>
                <div class="space-y-2">
                  <input type="text" readonly value="4084 •••• •••• 1289" class="form-input w-full font-mono text-xs">
                  <div class="grid grid-cols-2 gap-2">
                    <input type="text" readonly value="12/28" class="form-input font-mono text-xs">
                    <input type="text" readonly value="••• (CVV)" class="form-input font-mono text-xs">
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fa-solid fa-lock mr-2"></i>
                <span>Complete Payment (₦<?= number_format($total) ?>)</span>
              </button>
            </form>
          </div>

        </div>

        <!-- Right: Order Summary Card -->
        <div class="lg:col-span-5 card p-6 sm:p-7 space-y-5 sticky top-24">
          <div class="border-b border-[#E9E4DD] pb-3">
            <h2 class="font-display text-base font-bold text-[#252321]">Order Summary</h2>
            <p class="text-xs text-[#6B675F] mt-0.5"><?= htmlspecialchars($event['title']) ?></p>
          </div>

          <div class="space-y-3">
            <div class="flex items-start justify-between gap-3 text-xs">
              <div>
                <span class="font-bold text-[#252321] block"><?= htmlspecialchars($selectedTicket['name'] ?? 'Ticket Pass') ?></span>
                <span class="text-[#6B675F]">₦<?= number_format($unitPrice) ?> × <?= $quantity ?></span>
              </div>
              <span class="font-bold text-[#252321]">₦<?= number_format($subtotal) ?></span>
            </div>

            <div class="flex items-center justify-between text-xs text-[#6B675F] border-t border-[#E9E4DD] pt-3">
              <span>Foliioo Platform Fee</span>
              <span class="text-[#3F6B48] font-bold">₦0 (Included)</span>
            </div>

            <div class="flex items-center justify-between text-sm font-bold text-[#252321] border-t border-[#E9E4DD] pt-3">
              <span>Total Due</span>
              <span class="text-lg text-[#9A5F44]">₦<?= number_format($total) ?></span>
            </div>
          </div>

          <div class="pt-4 border-t border-[#E9E4DD] space-y-2 text-[11px] text-[#6B675F]">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-circle-check text-[#3F6B48]"></i>
              <span>Instant digital confirmation via SMS & Email</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-circle-check text-[#3F6B48]"></i>
              <span>Non-transferable verified QR entry pass</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-circle-check text-[#3F6B48]"></i>
              <span>Host-guaranteed refund if event is cancelled</span>
            </div>
          </div>
        </div>

      </div>
    <?php endif; ?>

  </main>

  <?php require __DIR__ . '/_inc/footer/footer-marketing.php'; ?>

</div>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>