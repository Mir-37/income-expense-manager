#!/usr/bin/env php
<?php


require './bootstrap.php';

use Hellm\ExpenseApp\Commands\MainMenu;


$app = new App("Income Expense Tracker", "1.0.0");
$app->add(new MainMenu());
$app->run();
// use Hellm\ExpenseApp\IncomeExpenseTracker;
// use Symfony\Component\Console\Application;
// use Hellm\ExpenseApp\FileManagement\FileManager;

// $application = new Application("Income Expense Tracker", "1.0.0");
// // $auth = new AuthManager();
// // $tracker = new IncomeExpenseTracker();

// // if ($auth->authenticate()) {
// //     // then set the user name in IncomeExpenseTracker
// // }
// // $file = new FileManager("user");
// // print_r($file->createFile("users", null));
// // $file->insert([$file->getNextId(), "20023", "Bazar12", "Expense", "2020-12-19"]);
// // $file->update(id: 5, amount: "300", category: "Goru", type: "Expense", date: "2920-12-08");
// // $file->delete(1);
// // echo $tracker->getIncome();

// // get input from user data

// // register user

// // login user

// // show main menu:
// // Add category, Add new info, Update info, Delete info, Delete category
// // Show whole history in main menu, if datas exceed 10+ press 2 see all history
// // Search date wise, category wise, income wise, expense wise
// // Show current savings

// // log out user

// $application->run();
