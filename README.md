# HBIT PHP Client

Here is a command line tool that facilitate HBIT node management and to do some blockchain queries.
It comes with a very basic PHP library to call HBIT API nodesj, that you may like to use in
you PHP projects.

Requirements: PHP5+ for the php library alone

## Installation

Download and 
unzip HBIT-php-client.zip; 
```

## To use with PHP projects

Add `require('/PATH_TO/HBIT-php-client/params.php')` and  `class Hbit extends HbitApi {}` to the top of your PHP project

Having your own class to store your own functions remove the risk to loose anything during an update.

Here is an example of a sendMoney request :

```php
$oApp = new Hbit;
$oApp->aInput = array(
				'requestType'=>'sendMoney',
				'recipient'=>'HBIT-SL44-R65Z-HMNZ-7WVJM',
				'amountDQT'=>'500000000000',
				'secretPhrase'=>'PUT_YOUR_HOT_WALLET_SECRET',
				'message'=>'Here is your payment',
				'messageIsPrunable'=>true,
				'messageToEncrypt'=>true,
				'feeDQT'=>0	// 0 default fees			
);
$oResp = $oApp->getResponse();
```

If you need to make the same query to an another node :

```php
$oApp->protocol='http';
$oApp->host='IPADDRESS';
$oApp->protocol='PORT';

$oResp = $oApp->getResponse();
```

Available Functions:

	function getSecret()

	function setSecret($sSecret)

	function getAccountId()
	'requestType'=>'getAccountId'

	function getAccount($accountId)
	'requestType'=>'getAccount'

	function getAliasList($accountId,$index=0)
	'requestType'=>'getAliases'

	function getTransaction($transactionId)
	'requestType'=>'getTransaction'

	function getBlock($blockId)
	'requestType'=>'getBlock'

	function getTime()
	'requestType'=>'getTime' 

	function sendMoney($amount, $recipient, $deadline=60,$fee=0,$reference)
	'requestType'=>'sendMoney'