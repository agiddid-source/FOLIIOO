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
  <div class="flex items-center justify-between border-b border-gray-200/80 pb-3">
    <div>
      <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
        <?= htmlspecialchars($fundTitle) ?>
      </h2>
      <p class="text-xs text-gray-500 mt-0.5">Send a voluntary tribute gift or memorial endowment to honor the family</p>
    </div>

    <?php if ($summary['count'] > 0): ?>
      <div class="hidden sm:flex items-center gap-2 text-xs font-bold text-gray-700 bg-gray-100 px-3 py-1.5 rounded-xl">
        <span>₦<?= number_format($summary['total_amount_ngn']) ?> raised</span>
        <span class="text-gray-300">•</span>
        <span><?= $summary['count'] ?> <?= $summary['count'] === 1 ? 'gift' : 'gifts' ?></span>
      </div>
    <?php endif; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    
    <!-- Donation Form -->
    <div class="lg:col-span-7 bg-white rounded-3xl border border-gray-200/80 p-5 sm:p-7 shadow-2xs space-y-5">
      <div id="donation-success-card" class="hidden text-center py-6 space-y-3">
        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center mx-auto shadow-xs">
          <i data-lucide="heart" class="w-6 h-6 fill-emerald-600 text-emerald-600"></i>
        </div>
        <h3 class="text-lg font-extrabold text-gray-900">Thank You for Your Generous Tribute!</h3>
        <p class="text-xs text-gray-600 max-w-sm mx-auto">
          Your gift has been recorded and acknowledged. A formal receipt has been emailed to you.
        </p>
        <button type="button" onclick="document.getElementById('donation-success-card').classList.add('hidden'); document.getElementById('donation-form').classList.remove('hidden');" class="text-xs font-bold text-[#320E3B] hover:underline pt-2 inline-block">
          Send Another Gift
        </button>
      </div>

      <form id="donation-form" onsubmit="event.preventDefault(); handleDonationSubmit(this);" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Select Donation Amount (NGN)</label>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-3">
            <?php foreach ($suggestedAmounts as $idx => $amount): ?>
              <button type="button" onclick="selectPresetAmount(<?= $amount ?>, this)" class="preset-amount-btn py-2.5 px-3 rounded-xl border text-xs font-bold transition <?= $idx === 1 ? 'border-[#320E3B] bg-[#320E3B] text-white' : 'border-gray-200 bg-gray-50 text-gray-800 hover:border-gray-400' ?>">
                ₦<?= number_format($amount) ?>
              </button>
            <?php endforeach; ?>
          </div>

          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-gray-400">₦</span>
            <input type="number" id="donation-amount" required value="<?= $suggestedAmounts[1] ?? 25000 ?>" min="1000" step="500" placeholder="Custom amount" class="w-full text-xs font-bold pl-8 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label for="donor-name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Donor Name *</label>
            <input type="text" id="donor-name" required placeholder="e.g. Chief Adeleke's Classmates '74" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition">
          </div>

          <div>
            <label for="donor-email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Address *</label>
            <input type="email" id="donor-email" required placeholder="donor@example.com" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition">
          </div>
        </div>

        <div>
          <label for="donor-message" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tribute Note or Goodwill Message</label>
          <textarea id="donor-message" rows="2" placeholder="Write a short message to accompany your gift..." class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-[#320E3B] focus:outline-none transition"></textarea>
        </div>

        <div class="flex items-center gap-2 pt-1">
          <input type="checkbox" id="donor-anonymous" class="rounded text-[#320E3B] focus:ring-[#320E3B]">
          <label for="donor-anonymous" class="text-xs text-gray-600 cursor-pointer">
            Make my donation anonymous on the public tribute wall
          </label>
        </div>

        <div class="pt-2">
          <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-[#320E3B] hover:bg-[#4A1556] text-white text-xs font-bold px-6 py-3 rounded-xl shadow-md transition transform active:scale-95">
            <i data-lucide="heart" class="w-4 h-4"></i>
            <span>Send Tribute Gift</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Recent Gifts & Tribute Wall -->
    <div class="lg:col-span-5 bg-white rounded-3xl border border-gray-200/80 p-5 sm:p-6 shadow-2xs space-y-4 flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
          <h3 class="text-sm font-extrabold text-gray-900">Recent Tributes & Donors</h3>
          <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Public Wall</span>
        </div>

        <div class="divide-y divide-gray-100 max-h-[360px] overflow-y-auto no-scrollbar pt-1">
          <?php if (empty($donations)): ?>
            <p class="text-xs text-gray-400 py-6 text-center">Be the first to honor the host with a tribute gift.</p>
          <?php else: ?>
            <?php foreach ($donations as $gift): ?>
              <?php
                $isAnon = !empty($gift['is_anonymous']);
                $name = $isAnon ? 'Anonymous Well-Wisher' : ($gift['donor_name'] ?? 'Kind Donor');
              ?>
              <div class="py-3 space-y-1">
                <div class="flex items-center justify-between text-xs">
                  <span class="font-bold text-gray-900"><?= htmlspecialchars($name) ?></span>
                  <span class="font-extrabold text-[#320E3B]">₦<?= number_format($gift['amount_ngn'] ?? 0) ?></span>
                </div>
                <?php if (!empty($gift['message'])): ?>
                  <p class="text-[11px] text-gray-500 italic leading-snug">
                    "<?= htmlspecialchars($gift['message']) ?>"
                  </p>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="pt-3 border-t border-gray-100 text-[11px] text-gray-400 flex items-center justify-between">
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
    b.classList.remove('border-[#320E3B]', 'bg-[#320E3B]', 'text-white');
    b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-800');
  });
  btn.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-800');
  btn.classList.add('border-[#320E3B]', 'bg-[#320E3B]', 'text-white');
}

function handleDonationSubmit(form) {
  form.classList.add('hidden');
  document.getElementById('donation-success-card').classList.remove('hidden');
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
}
</script>
