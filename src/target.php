<?php 

/*Récupération des taux de change en cache,
ou récupération depuis la BCE si le cache a expiré (24h) */

function getExchangeRates() {

   $cache_file = "/src/exchange_rates.json"; //Fichier du cache
   $cache_lifetime = 24*60*60; //On garde les taux en cache pendant 24 heures

   //On vérifie si on a les taux en cache et si ils sont encore valides
   if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_lifetime)) {
      $cache_content = file_get_contents($cache_file);
      var_dump($cache_content);

      return json_decode(file_get_contents($cache_file), true);
   }

   //Url de l'API de la BCE
   $api_url = "https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
   
   //Récupération des données via l'api (au format XML)
   $xml = simplexml_load_file($api_url);

   //Vérifie si on a récupéré les données
   if ($xml === false) {
      echo "<div class='error-message'>Error: Could not fetch exchange rates. Try again later.</div>";
      return ["rates" => [], "date" => "Unavailable"];
   }

   //Extraction de la date et des taux dans le tableau "rates"
   $namespace = $xml->Cube->Cube->attributes();
   $date = (string)$namespace["time"];

   $rates = ["EUR" => 1.0]; //L'euro est la base
   foreach($xml->Cube->Cube->Cube as $rate) {
      $currency = (string) $rate["currency"];
      $value = (float) $rate["rate"];
      $rates[$currency] = $value;
   }

   //Mise en cache des taux
   $new_cache_data = [
      "timestamp" => time(),
      "date" => $date,
      "rates" => $rates
   ];

   //On écrase les données du cache uniquement si la BCE a retourné des données
   if (!empty($rates)) {
      file_put_contents($cache_file, json_encode($new_cache_data));
   }

   return $new_cache_data;

}

// Convertit un montant d'une devise vers une autre
function convertCurrency($amount, $currency_from, $currency_to, $exchange_rates) {
   if (!isset($exchange_rates[$currency_from]) || !isset($exchange_rates[$currency_to])) {
      die("<div class='error-message'>Error: Unknown currency selected.</div>");
  }

   $converted_amount = $amount * ($exchange_rates[$currency_to] / $exchange_rates[$currency_from]);
   
   return number_format($converted_amount, 2, ',', ' ') . " " . $currency_to;
}

//Récupération des taux (cache + BCE si nécessaire)
$bce_data = getExchangeRates();
$exchange_rates = $bce_data["rates"];
$date_update = $bce_data["date"];

// Vérification des données utilisateur
if (!isset($_GET['currency_from'], $_GET['currency_to'], $_GET['amount']) || !is_numeric($_GET['amount'])) {
   die("<div class='error-message'>Error: Invalid or missing data.</div>");
}

$currency_from = strtoupper($_GET['currency_from']);
$currency_to = strtoupper($_GET['currency_to']);
$amount = floatval($_GET['amount']); //Conversion pour calcul
$conversion_result = convertCurrency($amount, $currency_from, $currency_to, $exchange_rates);
$formatted_amount = number_format($amount, 2, ',', ' '); //Conversion pour affichage
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Currency converter result</title>
   <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<form>
<div class="container">
   <h1>💰 Currency Conversion 💰</h1>
   <div class="result-box">
      <p class="result-text">
         <?php echo "{$formatted_amount} {$currency_from} = <strong>{$conversion_result}</strong>"; ?>
      </p>
   </div>   
   <a href="/src/index.php" class="back-button">⬅ Back to Conversion</a>
   <p class="update-info">Rate updated on : <strong><?php echo $date_update; ?></strong></p>
</div>
</form>
</body>
</html>
