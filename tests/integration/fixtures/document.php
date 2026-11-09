<?php
$kind = isset($_GET['kind']) && $_GET['kind'] === 'contract' ? 'Contract' : 'Offer';
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Synthetic PDF fixture</title>
<style>
body { margin:0; color:#333; font:13px Arial,sans-serif; }
.document-page { width:720px; padding:0 12px; page-break-after:always; }
.last { page-break-after:auto; }
h1 { font-size:24px; margin:16px 0; }
h2 { font-size:18px; margin:16px 0; }
p { line-height:1.5; }
table { border-collapse:collapse; width:100%; }
th, td { border-bottom:1px solid #bbb; padding:9px 6px; text-align:left; }
th { background:#eee; }
.number { text-align:right; }
.signature { margin-top:45px; padding-top:8px; border-top:1px solid #333; width:280px; }
</style></head><body>
<?php for ($page = 1; $page <= 3; $page++): ?>
<div class="document-page <?= $page === 3 ? 'last' : '' ?>">
<h1><?= $kind ?> fixture</h1>
<h2>Section <?= $page ?></h2>
<p>Example organisation<br>Example street 12<br>8000 Zürich</p>
<p>Umlauts and accents: ä ö ü Ä Ö Ü é è ê ç. Currency: CHF 1'234.50 and € 99.00.</p>
<p>This document contains synthetic data only. Header and footer must repeat on every page.</p>
<table><thead><tr><th>Description</th><th class="number">Quantity</th><th class="number">Amount CHF</th></tr></thead><tbody>
<?php for ($row = 1; $row <= 12; $row++): ?>
<tr><td>Example service <?= $page ?>.<?= $row ?></td><td class="number"><?= $row ?></td><td class="number"><?= number_format(12.50 * $row, 2) ?></td></tr>
<?php endfor; ?>
</tbody></table>
<p>Text after table on section <?= $page ?>. All amounts are examples.</p>
<div class="signature">Example signature</div>
<p>END OF SECTION <?= $page ?></p>
</div>
<?php endfor; ?>
</body></html>
