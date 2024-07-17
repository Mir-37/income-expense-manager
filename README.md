# Income Expense Manager

## Description

Income Expense Manager App (version 1.0.0) is a simple console-based application that helps users track their incomes and expenses. Users can add entries for income, expenses, and categories, view total savings, and manage their financial data efficiently.
It is a test project. I will try to scale it to test my skills.

## Requirements

-   PHP 7.4 or higher
-   Composer
-   Symfony Console ^6.4

## Installation

1. **Clone the repository Or Download**

    ```bash
    git clone [https://github.com/Mir-37/income-expense-manager.git]
    ```

2. **Change directory to the project**

    ```bash
    cd expense_app
    ```

3. **Install dependencies using Composer**

    ```bash
    composer install
    ```

## Usage

Run the application using the following command:

```bash
php index.php manage-income-expense
```

This will start the console application, and you can interact with the main menu to add income, add expenses, view savings, and manage your financial data.

## Main Menu Options

-   **View Savings:** View the total savings calculated as total income minus total expenses.
-   **Add Income:** Add an entry for income, specifying the category and amount.
-   **Add Expense:** Add an entry for expense, specifying the category and amount.
-   **View Incomes:** View a list of all income entries.
-   **View Expenses:** View a list of all expense entries.
-   **View Categories:** View a list of all categories used in income and expense entries.
-   **Exit:** Exit the application.

## Example

1. **Add Income**

    Follow the prompts to enter the category and amount of income.

2. **Add Expense**

    Follow the prompts to enter the category and amount of expense. You can choose from existing categories or enter a new category.

3. **View Savings**

    The application will display the total savings.

## License

This project is licensed under the MIT License.
