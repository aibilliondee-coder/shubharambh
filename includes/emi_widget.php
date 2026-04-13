<?php
/**
 * Reusable EMI calculator widget.
 *
 * Caller may set $emi_defaults before including:
 *   $emi_defaults = ['price' => 8800000, 'down' => 1760000, 'rate' => 8.5, 'tenure' => 20];
 */

$emi_defaults = $emi_defaults ?? [];
$ePrice  = (int)($emi_defaults['price']  ?? 8000000);   // 80 Lakh default
$eDown   = (int)($emi_defaults['down']   ?? 1600000);   // 20% default
$eRate   = (float)($emi_defaults['rate'] ?? 8.5);
$eTenure = (int)($emi_defaults['tenure'] ?? 20);
?>
<div class="emi-calc">
  <div class="emi-head">
    <div class="ic" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 10h20M6 15h4M14 15h4"/></svg>
    </div>
    <div>
      <h3>Home Loan EMI Calculator</h3>
      <p>Adjust the sliders to estimate your monthly instalment.</p>
    </div>
  </div>

  <div class="row">
    <div class="top"><span>Property Price</span><span class="val" id="emi-price-v">&#8377; 80,00,000</span></div>
    <input type="range" id="emi-price" min="1000000" max="200000000" step="500000" value="<?= (int)$ePrice ?>" aria-label="Property price">
  </div>

  <div class="row">
    <div class="top"><span>Down Payment</span><span class="val" id="emi-down-v">&#8377; 16,00,000</span></div>
    <input type="range" id="emi-down" min="0" max="100000000" step="100000" value="<?= (int)$eDown ?>" aria-label="Down payment">
  </div>

  <div class="row">
    <div class="top"><span>Interest Rate (per annum)</span><span class="val" id="emi-rate-v">8.50 %</span></div>
    <input type="range" id="emi-rate" min="5" max="15" step="0.05" value="<?= $eRate ?>" aria-label="Interest rate">
  </div>

  <div class="row">
    <div class="top"><span>Loan Tenure</span><span class="val" id="emi-tenure-v">20 Years</span></div>
    <input type="range" id="emi-tenure" min="1" max="30" step="1" value="<?= (int)$eTenure ?>" aria-label="Loan tenure in years">
  </div>

  <div class="emi-result">
    <span class="lbl">Monthly EMI</span>
    <div class="val" id="emi-out">&#8377; 0</div>
    <div class="sub">Based on the numbers above. Actual rates vary by bank and credit profile.</div>
  </div>

  <div class="emi-breakdown">
    <div><span class="k">Loan Amount</span><span class="v" id="emi-loan">&#8377; 0</span></div>
    <div><span class="k">Total Interest</span><span class="v" id="emi-interest">&#8377; 0</span></div>
    <div><span class="k">Total Payable</span><span class="v" id="emi-total">&#8377; 0</span></div>
  </div>
</div>
