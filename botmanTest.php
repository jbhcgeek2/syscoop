<?php 

//require_once "src/BotMan.php";
//require_once "src/BotManFactory.php";
//EAAHyoppyY3UBACrzTzxusnwBCc3cN9OCZCNDDQA88IZAcIlSALsi4cQtGHQSLtDzr3vv5wNNjwxdyBnYnX6UcyTDPivYJRD17O8wUhfnsmZBMftdfFPQUPWkRdnqtfjj3ZCyvPEFnZBTNRQe8uWMkCjZAPtYiAj2GH4SWVzVPfR7XKdBumE9xBJUtZB4U1ZAXTzdJdKAMZAgRqgZDZD

//EAAHyoppyY3UBACDcQ9yUoibRiv9OhxjXw6RV4rMsoy8nZB4GLzlmZAJgFPg0wxqtEkyC35iFOrdSRP3SzZAEpiPpZAZArZCn78XkqkhuWvT3kxmRcZAZAo5ZBJq5F42evgLZBgljnCFZBA9WSX4nSbg4Dbpr7TIbqgbMKo77Gp0Ny80br6LnoMpK6r03Gu5raaLvW7hpeZCYyIrZCAwZDZD


use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

$config = [
    'facebook' => [
        'token' => 'EAAHyoppyY3UBACDcQ9yUoibRiv9OhxjXw6RV4rMsoy8nZB4GLzlmZAJgFPg0wxqtEkyC35iFOrdSRP3SzZAEpiPpZAZArZCn78XkqkhuWvT3kxmRcZAZAo5ZBJq5F42evgLZBgljnCFZBA9WSX4nSbg4Dbpr7TIbqgbMKo77Gp0Ny80br6LnoMpK6r03Gu5raaLvW7hpeZCYyIrZCAwZDZD',
        'app_secret' => '301bc35cfc8d553a55e51d22e11f1c74',
        'verification'=>'123Tamarindo123Acapulco123BrizaEaEaTuboTuboUA',
  ]
];

// Load the driver(s) you want to use
//DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
DriverManager::loadDriver(BotMan\BotMan\Drivers\Facebook\FacebookDriver::class);

// Create an instance
$botman = BotManFactory::create($config);
$botman = verifyServices("123Tamarindo123Acapulco123BrizaEaEaTuboTuboUA");


// Give the bot something to listen for.
$botman->hears('hello', function (BotMan $bot) {
    $bot->reply('Hello yourself.');
});

// Start listening
$botman->listen();
?>