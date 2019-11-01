<?php

require 'Credit.php';

$data = '{
  "loan": {
  "base": 8000,
  "date": "2018-07-27 10:20:37",
  "percent": 2,
  "duration": 30
},
"payments": [
  { "amount": 5000.00, "date": "2018-07-28 12:35:22" },
  { "amount": 1433.17, "date": "2018-07-29 23:55:08" },
  { "amount": 500.00, "date": "2018-08-11 00:05:01" }
],
  "atDate": "2018-08-06 12:00:00"
}';

$credit = new Credit();
print_r($credit->calculationDebt($data));
echo "\n";






