<?php
/**
 * Foliioo Design System V1.1 — RSVP & Guest Attendance Section (Module 7)
 */
$features = $event['features_enabled'] ?? [];
if (empty($features['rsvp']) || $status === 'cancelled') {
    return;
}

$guestLimit = $guestLimit ?? MockData::getGuestLimitForEvent($event['id']);
$isFull = $guestLimit['is_full'] ?? false;
$remainingSeats = $guestLimit['remaining_seats'] ?? 50;
?>

<section id="rsvp" class="scroll-mt-24 space-y-4 font-sans">
  <div class="flex items-center justify-between border-b border-[#EAE8E2] pb-3">
    <div>
      <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#0F0F0F] tracking-tight">RSVP & Guest Attendance</h2>
      <p class="text-xs text-[#63615E] mt-0.5">Please confirm your attendance so the host can finalize seating and catering</p>
    </div>

    <?php if ($isFull): ?>
      <span class="badge badge-warning text-xs">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>Event Capacity Reached</span>
      </span>
    <?php else: ?>
      <span class="badge badge-sage text-xs">
        <span class="w-2 h-2 rounded-full bg-[#87927B]"></span>
        <span><?= number_format($remainingSeats) ?> Seats Remaining</span>
      </span>
    <?php endif; ?>
  </div>

  <div class="card p-5 sm:p-8">
    <div id="rsvp-success-card" class="hidden text-center py-8 space-y-4">
      <div class="w-14 h-14 bg-[#E8F1EA] text-[#3F6B48] rounded-xl flex items-center justify-center mx-auto shadow-xs">
        <i class="fa-solid fa-circle-check text-2xl"></i>
      </div>
      <div class="space-y-1">
        <h3 class="font-display text-xl font-bold text-[#0F0F0F]">Your RSVP is Confirmed!</h3>
        <p class="text-xs text-[#63615E] max-w-md mx-auto">
          Thank you for confirming your attendance. A digital entry pass and calendar invitation have been registered for your party.
        </p>
      </div>

      <div class="inline-block bg-[#FAFBF6] border border-[#EAE8E2] rounded-xl p-4 text-left max-w-sm w-full mx-auto space-y-2 text-xs">
        <div class="flex justify-between border-b border-[#EAE8E2] pb-2">
          <span class="text-[#63615E]">Guest Pass Code:</span>
          <span id="rsvp-pass-code" class="font-mono font-bold text-[#0F0F0F]">FOL-2026-VIP</span>
        </div>
        <div class="flex justify-between border-b border-[#EAE8E2] pb-2">
          <span class="text-[#63615E]">Reserved Seats:</span>
          <span id="rsvp-pass-party" class="font-bold text-[#0F0F0F]">1 Seat</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#63615E]">Door Check-In:</span>
          <span class="font-bold text-[#3F6B48]">Digital QR Enabled</span>
        </div>
      </div>

      <div class="pt-2">
        <button type="button" onclick="document.getElementById('rsvp-success-card').classList.add('hidden'); document.getElementById('rsvp-form').classList.remove('hidden');" class="text-xs font-bold text-[#FF502B] hover:underline">
          Edit RSVP Details
        </button>
      </div>
    </div>

    <form id="rsvp-form" onsubmit="event.preventDefault(); handleRsvpSubmit(this);" class="space-y-5">
      <?php if ($isFull): ?>
        <div class="p-4 rounded-xl bg-[#FBF1DC] border border-[#8F5F12]/20 text-xs text-[#8F5F12] space-y-1">
          <p class="font-bold">Notice: General seating for this event has been filled.</p>
          <p>You may still submit your details to join the host's standby waiting list in case seats open up.</p>
        </div>
      <?php endif; ?>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="rsvp-name" class="form-label">Full Name *</label>
          <input type="text" id="rsvp-name" required placeholder="e.g. Dr. Ngozi Adeleke" class="form-input">
        </div>

        <div>
          <label for="rsvp-phone" class="form-label">WhatsApp Phone Number *</label>
          <input type="tel" id="rsvp-phone" required placeholder="+234 803 123 4567" class="form-input">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="rsvp-email" class="form-label">Email Address</label>
          <input type="email" id="rsvp-email" placeholder="guest@example.com" class="form-input">
        </div>

        <div>
          <label for="rsvp-party-size" class="form-label">Total Party Size (Seats)</label>
          <select id="rsvp-party-size" class="form-select cursor-pointer">
            <option value="1">1 Person (Just Me)</option>
            <option value="2">2 Persons (Me + 1 Guest)</option>
            <option value="3">3 Persons</option>
            <option value="4">4 Persons</option>
            <option value="5">5 Persons (Family)</option>
          </select>
        </div>
      </div>

      <div>
        <label class="form-label mb-2">Will you be attending? *</label>
        <div class="grid grid-cols-2 gap-3 max-w-md">
          <label class="flex items-center gap-2.5 p-3 rounded-lg border border-[#EAE8E2] hover:border-[#FF502B] cursor-pointer transition has-checked:border-[#FF502B] has-checked:bg-[#FF502B]/5">
            <input type="radio" name="rsvp_status" value="attending" checked class="accent-[#FF502B]">
            <span class="text-xs font-bold text-[#0F0F0F]">Joyfully Attending</span>
          </label>
          <label class="flex items-center gap-2.5 p-3 rounded-lg border border-[#EAE8E2] hover:border-[#D8D1C8] cursor-pointer transition has-checked:border-[#63615E] has-checked:bg-[#FAFBF6]">
            <input type="radio" name="rsvp_status" value="declined" class="accent-[#63615E]">
            <span class="text-xs font-bold text-[#63615E]">Regretfully Decline</span>
          </label>
        </div>
      </div>

      <div>
        <label for="rsvp-notes" class="form-label">Goodwill Message</label>
        <textarea id="rsvp-notes" rows="2" placeholder="Share a warm congratulatory note for the host..." class="form-textarea"></textarea>
      </div>

      <div class="flex items-start gap-2.5 pt-1">
        <input type="checkbox" id="rsvp-reminder" checked class="mt-0.5 rounded accent-[#FF502B]">
        <label for="rsvp-reminder" class="text-xs text-[#63615E] cursor-pointer">
          Send me ceremony updates, order of service changes, and venue reminders via WhatsApp
        </label>
      </div>

      <div class="pt-2 flex items-center justify-between">
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-check"></i>
          <span>Submit RSVP Response</span>
        </button>

        <span class="text-[12px] text-[#A6A39D]">Response is recorded instantly</span>
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
}
</script>
