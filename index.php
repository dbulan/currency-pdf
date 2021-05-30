<?php

// TODO: check for amount before submit (empty && number)
// TODO: <-> switch between exchange values
// TODO: send ajax requests
// TODO: clear
// TODO: store result in db for logging

require __DIR__ . '/vendor/autoload.php';
require 'env.php';

use Swap\Builder;

$currencyList = ['EUR', 'RUB'];

$amount = isset($_POST['amount']) ? round($_POST['amount'], 2) : 0;
$from = isset($_POST['from']) && in_array($_POST['from'], $currencyList) ? $_POST['from'] : $currencyList[0];
$to = isset($_POST['to']) && in_array($_POST['to'], $currencyList) ? $_POST['to'] : $currencyList[1];

?>

<form method="POST" action="index.php" autocomplete="off">
    <label>Amount</label>
    <input type="text" name="amount" size="5" value="<?php echo $amount > 0 ? $amount : '' ?>">
    
    <select name="from">
    <?php foreach($currencyList as $currency): ?>
        <option name="<?php echo $currency ?>" <?php echo $from == $currency ? 'selected': '' ?>><?php echo $currency ?></option>
    <?php endforeach; ?>
    </select>
    
    <button>&#8596;</button>

    <select name="to">
    <?php foreach($currencyList as $currency) { ?>
        <option name="<?php echo $currency ?>" <?php echo $to == $currency ? 'selected': '' ?>><?php echo $currency ?></option>
    <?php } ?>
    </select>

    <button type="submit">Submit</button>
</form>

<!-- Prepare View Model -->

<?php
if($amount > 0)
{
    $swap = (new Builder())
        ->add('fixer', ['access_key' => FIXER_ACCESS_KEY]) // Use the Fixer.io service as first level provider
        ->build();

    $rate = $swap->latest('EUR/RUB'); // Get the latest EUR/USD rate
    // $rate = $swap->historical('EUR/RUB', (new \DateTime())->modify('-15 years'));  // Historical Request

    $model = [
        'from' => $from,
        'to' => $to,
        'amount' => $amount,
        'new_amount' => round($amount * $rate->getValue(), 2),
        'historical' => $rate->getDate()->format('Y-m-d'),
        'value' => $rate->getValue()
    ];
    $model['result-1'] = sprintf('Today <b>1 %s</b> costs <b>%d %s</b>', $model['from'], $model['value'], $model['to']);
    $model['result-2'] = sprintf('For <b>%d %s</b> you will pay <b>%d %s</b>', $model['amount'], $model['from'], $model['new_amount'], $model['to']);

    setcookie('result-1', $model['result-1'], time() + 3600, '/');
    setcookie('result-2', $model['result-2'], time() + 3600, '/');
}
?>

<!-- Show View Model -->

<?php if(isset($model)): ?>
<div class="result"><?php echo $model['result-1'] ?></div>
<div class="result"><?php echo $model['result-2'] ?></div>

<div style="padding-top:10px"><a href="save_pdf.php">Save in PDF</a></div>
<?php endif; ?>