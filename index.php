<?php
$currencies = [
   ["code" => "EUR", "name" => "Euro", "flag" => "🇪🇺"],
   ["code" => "USD", "name" => "US Dollar", "flag" => "🇺🇸"],
   ["code" => "JPY", "name" => "Japanese Yen", "flag" => "🇯🇵"],
   ["code" => "BGN", "name" => "Lev Bulgare", "flag" => "🇧🇬"],
   ["code" => "CZK", "name" => "Couronne Tchèque", "flag" => "🇨🇿"],
   ["code" => "DKK", "name" => "Couronne Danoise", "flag" => "🇩🇰"],
   ["code" => "GBP", "name" => "British Pound", "flag" => "🇬🇧"],
   ["code" => "HUF", "name" => "Forint Hongrois", "flag" => "🇭🇺"],
   ["code" => "PLN", "name" => "Złoty Polonais", "flag" => "🇵🇱"],
   ["code" => "RON", "name" => "Leu Roumain", "flag" => "🇷🇴"],
   ["code" => "SEK", "name" => "Couronne Suédoise", "flag" => "🇸🇪"],
   ["code" => "CHF", "name" => "Swiss Franc", "flag" => "🇨🇭"],
   ["code" => "ISK", "name" => "Couronne Islandaise", "flag" => "🇮🇸"],
   ["code" => "NOK", "name" => "Couronne Norvégienne", "flag" => "🇳🇴"],
   ["code" => "TRY", "name" => "Livre Turque", "flag" => "🇹🇷"],
   ["code" => "AUD", "name" => "Australian Dollar", "flag" => "🇦🇺"],
   ["code" => "BRL", "name" => "Brazilian Real", "flag" => "🇧🇷"],
   ["code" => "CAD", "name" => "Canadian Dollar", "flag" => "🇨🇦"],
   ["code" => "CNY", "name" => "Chinese Yuan", "flag" => "🇨🇳"],
   ["code" => "HKD", "name" => "Dollar de Hong Kong", "flag" => "🇭🇰"],
   ["code" => "IDR", "name" => "Roupie Indonésienne", "flag" => "🇮🇩"],
   ["code" => "ILS", "name" => "Israeli Shekel", "flag" => "🇮🇱"],
   ["code" => "INR", "name" => "Indian Rupee", "flag" => "🇮🇳"],
   ["code" => "KRW", "name" => "South Korean Won", "flag" => "🇰🇷"],
   ["code" => "MXN", "name" => "Mexican Peso", "flag" => "🇲🇽"],
   ["code" => "MYR", "name" => "Ringgit Malaisien", "flag" => "🇲🇾"],
   ["code" => "NZD", "name" => "Dollar Néo-Zélandais", "flag" => "🇳🇿"],
   ["code" => "PHP", "name" => "Peso Philippin", "flag" => "🇵🇭"],
   ["code" => "SGD", "name" => "Dollar de Singapour", "flag" => "🇸🇬"],
   ["code" => "THB", "name" => "Baht Thaïlandais", "flag" => "🇹🇭"],
   ["code" => "ZAR", "name" => "South African Rand", "flag" => "🇿🇦"],
];

/*
DONNEES NON RECUPEREES PAR LA BCE
   ["code" => "DZD", "name" => "Algerian Dinar", "flag" => "🇩🇿"],
   ["code" => "EGP", "name" => "Egyptian Pound", "flag" => "🇪🇬"],
   ["code" => "MAD", "name" => "Moroccan Dirham", "flag" => "🇲🇦"],
   ["code" => "RUB", "name" => "Russian Ruble", "flag" => "🇷🇺"],
   ["code" => "TND", "name" => "Tunisian Dinar", "flag" => "🇹🇳"],
*/

// Tri des monnaies par nom
usort($currencies, function ($a, $b) {
   return strcmp($a["name"], $b["name"]);
});

?>

<script>
function swapCurrencies() {
      let fromCurrency = document.getElementById("currency_from");
      let toCurrency = document.getElementById("currency_to");

      // Ajout de la classe d'animation
      fromCurrency.classList.add("swapping");
      toCurrency.classList.add("swapping");

      // Après 500ms (durée de l'animation), on swap et on enlève l'effet
      setTimeout(() => {
         let tempValue = fromCurrency.value;
         fromCurrency.value = toCurrency.value;
         toCurrency.value = tempValue;

         // Suppression de la classe pour réinitialiser l'effet
         fromCurrency.classList.remove("swapping");
         toCurrency.classList.remove("swapping");
      }, 400);
   }

   function validateForm() {
      let amount = document.getElementById("amount").value;

      if (amount.trim() === "" || isNaN(amount) || parseFloat(amount) <= 0 || !/^\d*\.?\d{0,2}$/.test(amount)) {
         Swal.fire({
         icon: "warning",
         title: "Invalid Amount",
         text: "Please enter a valid positive amount with at most 2 decimal places.",
         confirmButtonColor: "#3085d6",
         confirmButtonText: "OK",
         backdrop: true,
         scrollbarPadding: false, // Désactive le recalcul du padding
         customClass: {
            title: "swal-title",
            popup: "swal-popup",
            confirmButton: "swal-button"
         }
         });
         return false; // Empêche l’envoi du formulaire
      }
      return true; // Formulaire valide
   }
</script>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">   
   <title>Currency converter</title>
   <link rel="stylesheet" href="/assets/style.css">
   <script src="/assets/updateChecker.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <!-- Icônes pour Mobiles -->
   <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon_32x32.png">
   <link rel="apple-touch-icon" sizes="32x32" href="/assets/images/favicon_32x32.png">
   <link rel="manifest" href="/assets/manifest.json">

   <script>
      //Bloque le retour en arrière dans l'historique des pages
      window.addEventListener("popstate", function (event) {
      history.pushState(null, null, location.href); // Remet la même page dans l'historique
      });
      history.pushState(null, null, location.href); // Ajoute un état initial
   </script>

</head>

<body>
<form method="get" action="/src/target.php" onsubmit="return validateForm()" novalidate>
   <h1 class="flame-text">Pick your currency!</h1>

   <label for="currency_from">From currency:</label>
   <select name="currency_from" id="currency_from">
      <?php foreach ($currencies as $currency) { ?>
         <option value="<?php echo $currency['code']; ?>"><?php echo $currency["flag"] . " " . $currency["name"]; ?></option>
      <?php } ?>    
   </select>

   <button type="button" class="swap-button" onclick="swapCurrencies()" aria-label="Swap currencies">🔄 Swap</button>

   <label for="currency_to">To currency:</label>
   <select name="currency_to" id="currency_to">
      <?php foreach ($currencies as $currency) { ?>
         <option value="<?php echo $currency['code']; ?>"
         <?php echo ($currency['code'] === 'EUR') ? 'selected' : ''; ?>>
         <?php echo $currency["flag"] . " " . $currency["name"]; ?>
         </option>
      <?php } ?>               
   </select>

   <label for="amount">Enter amount in local currency:</label>
   <input type="number" name="amount" id="amount" placeholder="Enter amount (e.g. 10.50)">
   <button type="submit">Submit</button>
</form>

</body>
</html>