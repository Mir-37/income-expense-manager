<?php
require __DIR__ . '/vendor/autoload.php';

use Hellm\ExpenseApp\FileManager;
use Hellm\ExpenseApp\IncomeExpenseTracker;
use Symfony\Component\Console\Application;

$application = new Application("Income Expense Tracker", "1.0.0");
// $auth = new AuthManager();
// $tracker = new IncomeExpenseTracker();

// if ($auth->authenticate()) {
//     // then set the user name in IncomeExpenseTracker
// }
// $file = new FileManager("user");
// print_r($file->createFile("users", null));
// $file->insert([$file->getId(), "200", "Bazar", "Expense", "2020-12-08"], "user");
// $file->update(5, ["200", "UnBAxar", "Expense", "2920-12-08"], "user");
// $file->delete(2, "user");
// echo $tracker->getIncome();

// get input from user data

// register user

// login user

// show main menu:
// Add category, Add new info, Update info, Delete info, Delete category
// Show whole history in main menu, if datas exceed 10+ press 2 see all history
// Search date wise, category wise, income wise, expense wise
// Show current savings

// log out user

$application->run();
