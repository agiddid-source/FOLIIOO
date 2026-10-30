<?php
$features = $event['features_enabled'] ?? [];
if (empty($features['rsvp']) || $status === 'cancelled') {
    return;
}

$guestLimit = $guestLimit ?? MockData::getGuestLimitForEvent($event['id']);
$isFull = $guestLimit['is_full'] ?? false;
$remainingSeats = $guestLimit['remaining_seats'] ?? 50;
?>

<section id="rsvp" class="scroll-mt-24 space-y-4">
  <div class="flex items-center justify-between border-b border-gray-200/80 pb-3">
    <div>
      <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">RSVP & Guest Attendance</h2>
      <p class="text-xs text-gray-500 mt-0.5">Please confirm your attendance so the host can finalize seating and catering</p>
    </div>

    <?php if ($isFull): ?>
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
        <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-600"></i>
        <span>Event Capacity Reached</span>
      </span>
    <?php else: ?>
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
        <span><?= number_format($remainingSeats) ?> Seats Remaining</span>
      </span>
    <?php endif; ?>
  </div>

  <div class="bg-white rounded-3xl border border-gray-200/80 p-5 sm:p-8 shadow-2xs">
    <div id="rsvp-success-card" class="hidden text-center py-8 space-y-4">
      <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center mx-auto shadow-xs">
        <i data-lucide="check-circle-2" class="w-8 h-8"></i>
      </div>
      <div class="space-y-1">
        <h3 class="text-xl font-extrabold text-gray-900">Your RSVP is Confirmed!</h3>
        <p class="text-xs text-gray-600 max-w-md mx-auto">
          Thank you for confirming your attendance. A digital entry pass and calendar invitation have been registered for your party.
        </p>
      </div>

      <div class="inline-block bg-gray-50 border border-gray-200 rounded-2xl p-4 text-left max-w-sm w-full mx-auto space-y-2 text-xs">
        <div class="flex justify-between border-b border-gray-200 pb-2">
          <span class="text-gray-500">Guest Pass Code:</span>
          <span id="rsvp-pass-code" class="font-mono font-bold text-gray-900">FOL-2026-VIP</span>
        </div>
        <div class="flex justify-between border-b border-gray-200 pb-2">
          <span class="text-gray-500">Reserved Seats:</span>
          <span id="rsvp-pass-party" class="font-bold text-gray-900">1 Seat</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-500">Door Check-In:</span>
          <span class="font-bold text-emerald-700">Digital QR Enabled</span>
        </div>
      </div>

      <div class="pt-2">
        <button type="button" onclick="document.getElementById('rsvp-success-card').classList.add('hidden'); document.getElementById('rsvp-form').classList.remove('hidden');" class="text-xs font-bold text-[#320E3B] hover:underline">
          Edit RSVP Details
        </button>
      </div>
    </div>

    <form id="rsvp-form" onsubmit="event.preventDefault(); handleRsvpSubmit(this);" class="space-y-5">
      <?php if ($isFull): ?>
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
          <p class="font-bold">Notice: General seating for this event has been filled.</p>
          <p class="text-amber-800">You may still submit your details to join the host's standby waiting list in case seats open up.</p>
        </div>
      <?php endif; ?>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="rsvp-name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Full Name *</label>
          <input type="text" id="rsvp-name" required placeholder="e.g. Dr. Ngozi Adeleke" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition">
        </div>

        <div>
          <label for="rsvp-phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">WhatsApp Phone Number *</label>
          <input type="tel" id="rsvp-phone" required placeholder="+234 803 123 4567" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="rsvp-email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Address</label>
          <input type="email" id="rsvp-email" placeholder="guest@example.com" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition">
        </div>

        <div>
          <label for="rsvp-party-size" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Total Party Size (Seats)</label>
          <select id="rsvp-party-size" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition cursor-pointer">
            <option value="1">1 Person (Just Me)</option>
            <option value="2">2 Persons (Me + 1 Guest)</option>
            <option value="3">3 Persons</option>
            <option value="4">4 Persons</option>
            <option value="5">5 Persons (Family)</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Will you be attending?</label>
        <div class="grid grid-cols-2 gap-3 max-w-md">
          <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:border-[#320E3B] cursor-pointer transition has-checked:border-[#320E3B] has-checked:bg-[#320E3B]/5">
            <input type="radio" name="rsvp_status" value="attending" checked class="text-[#320E3B] focus:ring-[#320E3B]">
            <span class="text-xs font-bold text-gray-900">Joyfully Attending</span>
          </label>
          <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:border-gray-400 cursor-pointer transition has-checked:border-gray-500 has-checked:bg-gray-50">
            <input type="radio" name="rsvp_status" value="declined" class="text-gray-600 focus:ring-gray-600">
            <span class="text-xs font-bold text-gray-600">Regretfully Decline</span>
          </label>
        </div>
      </div>

      <div>
        <label for="rsvp-notes" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Dietary Notes or Goodwill Message</label>
        <textarea id="rsvp-notes" rows="2" placeholder="Share any dietary preferences or a warm congratulatory note for the host..." class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition"></textarea>
      </div>

      <div class="flex items-start gap-2.5 pt-1">
        <input type="checkbox" id="rsvp-reminder" checked class="mt-0.5 rounded text-[#320E3B] focus:ring-[#320E3B]">
        <label for="rsvp-reminder" class="text-xs text-gray-600 cursor-pointer">
          Send me ceremony updates, order of service changes, and venue reminders via WhatsApp
        </label>
      </div>

      <div class="pt-2 flex items-center justify-between">
        <button type="submit" class="inline-flex items-center gap-2 bg-[#320E3B] hover:bg-[#4A1556] text-white text-xs font-bold px-6 py-3 rounded-xl shadow-md transition transform active:scale-95">
          <i data-lucide="check" class="w-4 h-4"></i>
          <span>Submit RSVP Response</span>
        </button>

        <span class="text-[11px] text-gray-400">Response is recorded instantly</span>
      </div>
    </form>
  </div>
</section>

<script>
function handleRsvpSubmit(form) {
  const name = document.getElementById('rsvp-name').value;
  const partySize = document.getElementById('rsvp-party-size').value;
  const status = form.querySelector('input[name="rsvp_status"]:checked').value;
  
  const code = 'FOL-' + Math.floor(1000 + Math.random() * 9000);
  document.getElementById('rsvp-pass-code').textContent = code;
  document.getElementById('rsvp-pass-party').textContent = (status === 'attending') ? `${partySize} ${partySize > 1 ? 'Seats' : 'Seat'}` : 'Declined Attendance';
  
  form.classList.add('hidden');
  document.getElementById('rsvp-success-card').classList.remove('hidden');
  
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
}
</script>
