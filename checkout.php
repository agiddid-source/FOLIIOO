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

<div class="min-h-screen flex flex-col bg-[#F8FAFC]">

  <!-- Minimal Checkout Header -->
  <header class="bg-white border-b border-gray-200/80 sticky top-0 z-30">
    <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
      <a href="/event.php?slug=<?= urlencode($event['slug']) ?>" class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 hover:text-[#320E3B] transition">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Back to Event</span>
      </a>

      <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-lg bg-[#320E3B] flex items-center justify-center text-white font-black text-xs">
          F
        </div>
        <span class="text-xs font-black tracking-tight text-gray-900">FOLIIOO CHECKOUT</span>
      </div>

      <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
        <span>256-Bit Encrypted</span>
      </div>
    </div>
  </header>

  <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-8">
    
    <?php if ($isConfirmed): ?>
      <!-- Order Confirmation Screen -->
      <div class="bg-white rounded-3xl border border-gray-200/80 p-6 sm:p-10 shadow-sm space-y-8 text-center max-w-2xl mx-auto">
        <div class="w-16 h-16 bg-emerald-100 text-emerald-700 rounded-3xl flex items-center justify-center mx-auto shadow-xs">
          <i data-lucide="check-circle-2" class="w-9 h-9"></i>
        </div>

        <div class="space-y-2">
          <span class="text-[10px] font-black uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">Payment Successful</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">You're Going to <?= htmlspecialchars($event['title']) ?>!</h1>
          <p class="text-xs text-gray-500 max-w-md mx-auto leading-relaxed">
            Your simulated payment of <strong>₦<?= number_format($total) ?></strong> was confirmed. A receipt and digital ticket pass have been generated below.
          </p>
        </div>

        <!-- Ticket Card Mockup -->
        <div class="bg-gradient-to-br from-gray-900 via-stone-900 to-[#320E3B] text-white rounded-3xl p-6 sm:p-8 text-left shadow-xl relative overflow-hidden">
          <div class="flex items-start justify-between gap-4 border-b border-white/10 pb-5">
            <div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-amber-300">Official Admission Pass</span>
              <h3 class="text-lg font-black mt-1"><?= htmlspecialchars($event['title']) ?></h3>
              <p class="text-xs text-gray-300 mt-0.5"><?= htmlspecialchars($selectedTicket['name'] ?? 'General Access') ?></p>
            </div>
            <div class="bg-white p-2 rounded-xl shrink-0">
              <i data-lucide="qr-code" class="w-12 h-12 text-black"></i>
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-5 text-xs">
            <div>
              <span class="text-gray-400 block text-[10px] uppercase font-bold">Order Ref</span>
              <span class="font-mono font-bold"><?= $orderRef ?></span>
            </div>
            <div>
              <span class="text-gray-400 block text-[10px] uppercase font-bold">Quantity</span>
              <span class="font-bold"><?= $quantity ?> <?= $quantity === 1 ? 'Pass' : 'Passes' ?></span>
            </div>
            <div>
              <span class="text-gray-400 block text-[10px] uppercase font-bold">Date</span>
              <span class="font-bold"><?= date('M j, Y', strtotime($event['date'] ?? 'now')) ?></span>
            </div>
            <div>
              <span class="text-gray-400 block text-[10px] uppercase font-bold">Door Status</span>
              <span class="text-emerald-400 font-bold">Valid & Active</span>
            </div>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
          <button type="button" onclick="window.print();" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold transition">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Print or Save Ticket</span>
          </button>
          <a href="/event.php?slug=<?= urlencode($event['slug']) ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#320E3B] hover:bg-[#4A1556] text-white text-xs font-bold transition">
            <span>Return to Event Page</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
          </a>
        </div>
      </div>

    <?php else: ?>
      <!-- Checkout Form -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Buyer Details & Payment Method -->
        <div class="lg:col-span-7 space-y-6">
          
          <div class="bg-white rounded-3xl border border-gray-200/80 p-6 sm:p-7 shadow-2xs space-y-5">
            <div class="border-b border-gray-100 pb-3">
              <h2 class="text-base font-extrabold text-gray-900">Buyer & Attendee Information</h2>
              <p class="text-xs text-gray-500 mt-0.5">Tickets will be delivered to this email and phone</p>
            </div>

            <form id="checkout-form" action="/checkout.php" method="GET" class="space-y-4">
              <input type="hidden" name="event" value="<?= htmlspecialchars($event['slug']) ?>">
              <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($selectedTicket['id'] ?? '') ?>">
              <input type="hidden" name="quantity" value="<?= $quantity ?>">
              <input type="hidden" name="confirmed" value="1">

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="buyer-name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Full Name *</label>
                  <input type="text" id="buyer-name" required placeholder="e.g. Chief Adeleke" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition">
                </div>

                <div>
                  <label for="buyer-phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Phone Number *</label>
                  <input type="tel" id="buyer-phone" required placeholder="+234 803 123 4567" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition">
                </div>
              </div>

              <div>
                <label for="buyer-email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Address for Ticket Delivery *</label>
                <input type="email" id="buyer-email" required placeholder="attendee@example.com" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition">
              </div>

              <!-- Payment Method Selector -->
              <div class="pt-4 border-t border-gray-100 space-y-3">
                <span class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Simulated Payment Method</span>
                
                <div class="grid grid-cols-2 gap-3">
                  <label class="flex items-center gap-2.5 p-3.5 rounded-xl border border-gray-200 hover:border-[#320E3B] cursor-pointer transition has-checked:border-[#320E3B] has-checked:bg-[#320E3B]/5">
                    <input type="radio" name="payment_method" value="card" checked class="text-[#320E3B] focus:ring-[#320E3B]">
                    <div>
                      <span class="text-xs font-bold text-gray-900 block">Debit / Credit Card</span>
                      <span class="text-[10px] text-gray-400 block">Mastercard, Visa, Verve</span>
                    </div>
                  </label>

                  <label class="flex items-center gap-2.5 p-3.5 rounded-xl border border-gray-200 hover:border-[#320E3B] cursor-pointer transition has-checked:border-[#320E3B] has-checked:bg-[#320E3B]/5">
                    <input type="radio" name="payment_method" value="transfer" class="text-[#320E3B] focus:ring-[#320E3B]">
                    <div>
                      <span class="text-xs font-bold text-gray-900 block">Bank Transfer / USSD</span>
                      <span class="text-[10px] text-gray-400 block">Instant account credit</span>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Mock Card Inputs -->
              <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3">
                <div class="flex items-center justify-between text-xs text-gray-500">
                  <span>Test Card Sandbox</span>
                  <span class="text-[10px] font-bold bg-gray-200 text-gray-700 px-2 py-0.5 rounded">Mock Mode</span>
                </div>
                <div class="space-y-2">
                  <input type="text" readonly value="4084 •••• •••• 1289" class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-700">
                  <div class="grid grid-cols-2 gap-2">
                    <input type="text" readonly value="12/28" class="text-xs font-mono bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-700">
                    <input type="text" readonly value="••• (CVV)" class="text-xs font-mono bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-700">
                  </div>
                </div>
              </div>

              <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-[#320E3B] hover:bg-[#4A1556] text-white text-sm font-bold px-6 py-3.5 rounded-xl shadow-md transition transform active:scale-95">
                <i data-lucide="lock" class="w-4 h-4"></i>
                <span>Complete Payment (₦<?= number_format($total) ?>)</span>
              </button>
            </form>
          </div>

        </div>

        <!-- Right: Order Summary Card -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-gray-200/80 p-6 sm:p-7 shadow-2xs space-y-5 sticky top-24">
          <div class="border-b border-gray-100 pb-3">
            <h2 class="text-base font-extrabold text-gray-900">Order Summary</h2>
            <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($event['title']) ?></p>
          </div>

          <div class="space-y-3">
            <div class="flex items-start justify-between gap-3 text-xs">
              <div>
                <span class="font-extrabold text-gray-900 block"><?= htmlspecialchars($selectedTicket['name'] ?? 'Ticket Pass') ?></span>
                <span class="text-gray-500">₦<?= number_format($unitPrice) ?> × <?= $quantity ?></span>
              </div>
              <span class="font-bold text-gray-900">₦<?= number_format($subtotal) ?></span>
            </div>

            <div class="flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 pt-3">
              <span>Foliioo Platform Fee</span>
              <span class="text-emerald-700 font-bold">₦0 (Included)</span>
            </div>

            <div class="flex items-center justify-between text-sm font-extrabold text-gray-900 border-t border-gray-200 pt-3">
              <span>Total Due</span>
              <span class="text-lg text-[#320E3B]">₦<?= number_format($total) ?></span>
            </div>
          </div>

          <div class="pt-4 border-t border-gray-100 space-y-2 text-[11px] text-gray-400">
            <div class="flex items-center gap-2">
              <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i>
              <span>Instant digital confirmation via SMS & Email</span>
            </div>
            <div class="flex items-center gap-2">
              <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i>
              <span>Non-transferable verified QR entry pass</span>
            </div>
            <div class="flex items-center gap-2">
              <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i>
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
