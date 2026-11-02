<?php
$features = $event['features_enabled'] ?? [];
$donationSettings = $event['donation_settings'] ?? [];
if (empty($features['donations']) || empty($donationSettings['enabled']) || $status === 'cancelled') {
    return;
}

$donations = $donations ?? MockData::getDonationsForEvent($event['id']);
$summary = $donationSummary ?? MockData::getDonationSummary($event['id']);
$suggestedAmounts = $donationSettings['suggested_amounts'] ?? [10000, 25000, 50000, 100000];
$fundTitle = $donationSettings['title'] ?? 'Support the Tribute & Memorial Fund';
?>

<section id="donations" class="scroll-mt-24 space-y-4">
  <div class="flex items-center justify-between border-b border-[#E9E4DD] pb-3">
    <div>
      <h2 class="font-display text-xl sm:text-2xl font-bold text-[#252321] tracking-tight">
        <?= htmlspecialchars($fundTitle) ?>
      </h2>
      <p class="text-xs text-[#6B675F] mt-0.5">Send a voluntary tribute gift or memorial endowment to honor the family</p>
    </div>

    <?php if ($summary['count'] > 0): ?>
      <div class="hidden sm:flex items-center gap-2 text-xs font-semibold badge-sage px-3 py-1.5 rounded-full">
        <span>₦<?= number_format($summary['total_amount_ngn']) ?> raised</span>
        <span class="opacity-50">•</span>
        <span><?= $summary['count'] ?> <?= $summary['count'] === 1 ? 'gift' : 'gifts' ?></span>
      </div>
    <?php endif; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    
    <!-- Donation Form -->
    <div class="lg:col-span-7 card p-5 sm:p-7 space-y-5">
      <div id="donation-success-card" class="hidden text-center py-6 space-y-3">
        <div class="w-12 h-12 bg-[#EEF0EA] text-[#87927B] rounded-2xl flex items-center justify-center mx-auto">
          <i class="fa-solid fa-heart text-xl text-[#87927B]"></i>
        </div>
        <h3 class="font-display text-lg font-bold text-[#252321]">Thank You for Your Generous Tribute!</h3>
        <p class="text-xs text-[#6B675F] max-w-sm mx-auto">
          Your gift has been recorded and acknowledged. A formal receipt has been emailed to you.
        </p>
        <button type="button" onclick="document.getElementById('donation-success-card').classList.add('hidden'); document.getElementById('donation-form').classList.remove('hidden');" class="btn btn-ghost btn-sm text-[#9A5F44] hover:text-[#7E4A33] pt-2">
          Send Another Gift
        </button>
      </div>

      <form id="donation-form" onsubmit="event.preventDefault(); handleDonationSubmit(this);" class="space-y-4">
        <div>
          <label class="form-label">Select Donation Amount (NGN)</label>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-3">
            <?php foreach ($suggestedAmounts as $idx => $amount): ?>
              <button type="button" onclick="selectPresetAmount(<?= $amount ?>, this)" class="preset-amount-btn py-2.5 px-3 rounded-lg border text-xs font-semibold transition <?= $idx === 1 ? 'border-[#9A5F44] bg-[#9A5F44] text-white' : 'border-[#E9E4DD] bg-white text-[#252321] hover:border-[#9A5F44]' ?>">
                ₦<?= number_format($amount) ?>
              </button>
            <?php endforeach; ?>
          </div>

          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-[#A19B93]">₦</span>
            <input type="number" id="donation-amount" required value="<?= $suggestedAmounts[1] ?? 25000 ?>" min="1000" step="500" placeholder="Custom amount" class="form-input w-full pl-8">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="form-group">
            <label for="donor-name" class="form-label">Donor Name *</label>
            <input type="text" id="donor-name" required placeholder="e.g. Chief Adeleke's Classmates '74" class="form-input w-full">
          </div>

          <div class="form-group">
            <label for="donor-email" class="form-label">Email Address *</label>
            <input type="email" id="donor-email" required placeholder="donor@example.com" class="form-input w-full">
          </div>
        </div>

        <div class="form-group">
          <label for="donor-message" class="form-label">Tribute Note or Goodwill Message</label>
          <textarea id="donor-message" rows="2" placeholder="Write a short message to accompany your gift..." class="form-textarea w-full"></textarea>
        </div>

        <div class="flex items-center gap-2 pt-1">
          <input type="checkbox" id="donor-anonymous" class="rounded border-[#E9E4DD] text-[#9A5F44] focus:ring-[#9A5F44]">
          <label for="donor-anonymous" class="text-xs text-[#6B675F] cursor-pointer">
            Make my donation anonymous on the public tribute wall
          </label>
        </div>

        <div class="pt-2">
          <button type="submit" class="btn btn-primary btn-block">
            <i class="fa-solid fa-heart mr-2"></i>
            <span>Send Tribute Gift</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Recent Gifts & Tribute Wall -->
    <div class="lg:col-span-5 card p-5 sm:p-6 space-y-4 flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E4DD]">
          <h3 class="font-display text-sm font-bold text-[#252321]">Recent Tributes & Donors</h3>
          <span class="badge badge-accent">Public Wall</span>
        </div>

        <div class="divide-y divide-[#E9E4DD] max-h-[360px] overflow-y-auto no-scrollbar pt-1">
          <?php if (empty($donations)): ?>
            <p class="text-xs text-[#A19B93] py-6 text-center">Be the first to honor the host with a tribute gift.</p>
          <?php else: ?>
            <?php foreach ($donations as $gift): ?>
              <?php
                $isAnon = !empty($gift['is_anonymous']);
                $name = $isAnon ? 'Anonymous Well-Wisher' : ($gift['donor_name'] ?? 'Kind Donor');
              ?>
              <div class="py-3 space-y-1">
                <div class="flex items-center justify-between text-xs">
                  <span class="font-semibold text-[#252321]"><?= htmlspecialchars($name) ?></span>
                  <span class="font-bold text-[#9A5F44]">₦<?= number_format($gift['amount_ngn'] ?? 0) ?></span>
                </div>
                <?php if (!empty($gift['message'])): ?>
                  <p class="text-[11px] text-[#6B675F] italic leading-snug">
                    "<?= htmlspecialchars($gift['message']) ?>"
                  </p>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="pt-3 border-t border-[#E9E4DD] text-[11px] text-[#A19B93] flex items-center justify-between">
        <span>Verified host fund</span>
        <span>Secure settlement in NGN (₦)</span>
      </div>
    </div>

  </div>
</section>

<script>
function selectPresetAmount(amount, btn) {
  document.getElementById('donation-amount').value = amount;
  document.querySelectorAll('.preset-amount-btn').forEach(b => {
    b.classList.remove('border-[#9A5F44]', 'bg-[#9A5F44]', 'text-white');
    b.classList.add('border-[#E9E4DD]', 'bg-white', 'text-[#252321]');
  });
  btn.classList.remove('border-[#E9E4DD]', 'bg-white', 'text-[#252321]');
  btn.classList.add('border-[#9A5F44]', 'bg-[#9A5F44]', 'text-white');
}

function handleDonationSubmit(form) {
  form.classList.add('hidden');
  document.getElementById('donation-success-card').classList.remove('hidden');
}
</script>