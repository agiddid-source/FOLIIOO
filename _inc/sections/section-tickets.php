<?php
/**
 * Foliioo Design System V1.1 — Tickets & Table Packages Section (Module 8)
 */
$features = $event['features_enabled'] ?? [];
if (empty($features['ticketing']) || $status === 'cancelled') {
    return;
}

$tickets = $tickets ?? MockData::getTicketsForEvent($event['id']);
if (empty($tickets)) {
    return;
}
?>

<section id="tickets" class="scroll-mt-24 space-y-4 font-sans">
  <div class="flex items-center justify-between border-b border-[#E9E4DD] pb-3">
    <div>
      <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#252321] tracking-tight">Tickets & Table Packages</h2>
      <p class="text-xs text-[#6B675F] mt-0.5">Select your passes or reserve a private table for your group</p>
    </div>
    <span class="badge badge-sage text-xs">
      <i class="fa-solid fa-shield-halved"></i>
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
        <div class="card card-hover p-5 sm:p-6 flex flex-col justify-between space-y-4 relative <?= $isSoldOut ? 'opacity-60 grayscale' : '' ?>">
          
          <?php if ($isTable): ?>
            <span class="absolute top-4 right-4 badge badge-accent text-[10px]">
              Table Package
            </span>
          <?php endif; ?>

          <div class="space-y-2">
            <div class="flex items-center gap-2">
              <i class="fa-solid <?= $isTable ? 'fa-users' : 'fa-ticket' ?> text-[#9A5F44]"></i>
              <h3 class="text-base font-bold text-[#252321] leading-tight"><?= htmlspecialchars($ticket['name']) ?></h3>
            </div>
            
            <p class="text-xs text-[#6B675F] leading-relaxed min-h-[3rem]">
              <?= htmlspecialchars($ticket['description']) ?>
            </p>

            <div class="pt-2">
              <div class="flex items-baseline gap-1">
                <span class="font-display text-2xl font-bold text-[#252321]">₦<?= number_format($ticket['price_ngn']) ?></span>
                <span class="text-xs text-[#6B675F] font-medium">/ <?= $isTable ? 'table' : 'person' ?></span>
              </div>
              <span class="text-[12px] <?= $available < 15 ? 'text-[#B54747] font-semibold' : 'text-[#6B675F]' ?>">
                <?= $isSoldOut ? 'Sold Out' : ($available . ' passes remaining') ?>
              </span>
            </div>

            <?php if (!empty($ticket['perks'])): ?>
              <ul class="pt-3 border-t border-[#E9E4DD] space-y-1.5 text-xs text-[#6B675F]">
                <?php foreach ($ticket['perks'] as $perk): ?>
                  <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-[#3F6B48] shrink-0 mt-0.5 text-xs"></i>
                    <span><?= htmlspecialchars($perk) ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>

          <div class="pt-4 border-t border-[#E9E4DD] flex items-center justify-between gap-3">
            <span class="text-xs font-semibold text-[#6B675F]">Selection</span>
            
            <?php if ($isSoldOut): ?>
              <span class="text-xs font-semibold text-[#A19B93]">Unavailable</span>
            <?php else: ?>
              <div class="flex items-center gap-2">
                <input type="radio" id="ticket_choice_<?= $ticket['id'] ?>" name="ticket_id" value="<?= htmlspecialchars($ticket['id']) ?>" <?= $idx === 0 ? 'checked' : '' ?> class="accent-[#9A5F44]">
                <label for="ticket_choice_<?= $ticket['id'] ?>" class="text-xs font-semibold text-[#252321] cursor-pointer">Choose</label>
              </div>
            <?php endif; ?>
          </div>

        </div>
      <?php endforeach; ?>
    </div>

    <div class="card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[#FAF8F5]">
      <div class="flex items-center gap-3">
        <label for="ticket-quantity" class="text-xs font-bold text-[#252321]">Select Quantity:</label>
        <select id="ticket-quantity" name="quantity" class="form-select w-auto text-xs py-1.5 px-3 cursor-pointer">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="8">8</option>
          <option value="10">10</option>
        </select>
        <span class="text-[12px] text-[#6B675F]">Instant digital pass delivery</span>
      </div>

      <button type="submit" class="btn btn-primary shrink-0">
        <span>Proceed to Secure Checkout</span>
        <i class="fa-solid fa-arrow-right text-xs"></i>
      </button>
    </div>
  </form>
</section>
