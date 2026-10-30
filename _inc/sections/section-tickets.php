<?php
$features = $event['features_enabled'] ?? [];
if (empty($features['ticketing']) || $status === 'cancelled') {
    return;
}

$tickets = $tickets ?? MockData::getTicketsForEvent($event['id']);
if (empty($tickets)) {
    return;
}
?>

<section id="tickets" class="scroll-mt-24 space-y-4">
  <div class="flex items-center justify-between border-b border-gray-200/80 pb-3">
    <div>
      <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Tickets & Table Packages</h2>
      <p class="text-xs text-gray-500 mt-0.5">Select your passes or reserve a private table for your group</p>
    </div>
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-[#320E3B] border border-purple-200">
      <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
      <span>Official Ticketing</span>
    </span>
  </div>

  <form action="/checkout.php" method="GET" class="space-y-4">
    <input type="hidden" name="event" value="<?= htmlspecialchars($event['slug']) ?>">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <?php foreach ($tickets as $idx => $ticket): ?>
        <?php
          $available = max(0, ($ticket['total_inventory'] ?? 100) - ($ticket['sold_count'] ?? 0));
          $isSoldOut = ($available === 0);
          $isTable = (($ticket['type'] ?? 'ticket') === 'table');
        ?>
        <div class="bg-white rounded-3xl border border-gray-200/80 p-5 sm:p-6 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between space-y-4 relative <?= $isSoldOut ? 'opacity-60 grayscale' : '' ?>">
          
          <?php if ($isTable): ?>
            <span class="absolute top-4 right-4 text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-purple-100 text-[#320E3B] border border-purple-200">
              Table Package
            </span>
          <?php endif; ?>

          <div class="space-y-2">
            <div class="flex items-center gap-2">
              <i data-lucide="<?= $isTable ? 'users' : 'ticket' ?>" class="w-4 h-4 text-[#320E3B]"></i>
              <h3 class="text-base font-extrabold text-gray-900 leading-tight"><?= htmlspecialchars($ticket['name']) ?></h3>
            </div>
            
            <p class="text-xs text-gray-600 leading-relaxed min-h-[3rem]">
              <?= htmlspecialchars($ticket['description']) ?>
            </p>

            <div class="pt-2">
              <div class="flex items-baseline gap-1">
                <span class="text-2xl font-black text-gray-900">₦<?= number_format($ticket['price_ngn']) ?></span>
                <span class="text-xs text-gray-400 font-semibold">/ <?= $isTable ? 'table' : 'person' ?></span>
              </div>
              <span class="text-[11px] <?= $available < 15 ? 'text-rose-600 font-bold' : 'text-gray-400' ?>">
                <?= $isSoldOut ? 'Sold Out' : ($available . ' passes remaining') ?>
              </span>
            </div>

            <?php if (!empty($ticket['perks'])): ?>
              <ul class="pt-3 border-t border-gray-100 space-y-1.5 text-xs text-gray-600">
                <?php foreach ($ticket['perks'] as $perk): ?>
                  <li class="flex items-start gap-2">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                    <span><?= htmlspecialchars($perk) ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>

          <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <span class="text-xs font-bold text-gray-700">Quantity</span>
            
            <?php if ($isSoldOut): ?>
              <span class="text-xs font-bold text-gray-400">Unavailable</span>
            <?php else: ?>
              <div class="flex items-center gap-2">
                <input type="radio" id="ticket_choice_<?= $ticket['id'] ?>" name="ticket_id" value="<?= htmlspecialchars($ticket['id']) ?>" <?= $idx === 0 ? 'checked' : '' ?> class="text-[#320E3B] focus:ring-[#320E3B]">
                <label for="ticket_choice_<?= $ticket['id'] ?>" class="text-xs font-bold text-gray-800 cursor-pointer">Select</label>
              </div>
            <?php endif; ?>
          </div>

        </div>
      <?php endforeach; ?>
    </div>

    <div class="bg-gray-50 rounded-2xl border border-gray-200 p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <label for="ticket-quantity" class="text-xs font-bold text-gray-800">Select Quantity:</label>
        <select id="ticket-quantity" name="quantity" class="text-xs font-bold bg-white border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#320E3B] focus:outline-none cursor-pointer">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="8">8</option>
          <option value="10">10</option>
        </select>
        <span class="text-[11px] text-gray-500">Instant digital delivery with verification QR</span>
      </div>

      <button type="submit" class="inline-flex items-center justify-center gap-2 bg-[#320E3B] hover:bg-[#4A1556] text-white text-xs font-bold px-6 py-3 rounded-xl shadow-md transition transform active:scale-95 shrink-0">
        <span>Proceed to Secure Checkout</span>
        <i data-lucide="arrow-right" class="w-4 h-4"></i>
      </button>
    </div>
  </form>
</section>
