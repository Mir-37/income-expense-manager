<?php

use Hellm\ExpenseApp\FileManagement\InfoFile;
use Constant;
use Hellm\ExpenseApp\FileManagement\UserFile;

trait Helper
{
    public int $current_user_id;
    public function getDataOf(int $user_id): array
    {
        $info = new InfoFile();

        $csvArray = $info->getAllDataFromFile();

        return array_filter($csvArray, function ($record) use ($user_id) {
            if ((int)$record[Constant::USER_ID] === $user_id) {
                return true;
            }
            return false;
        });
    }

    public function findUser(string $nameOrEmail): ?array
    {
        $user = new UserFile();
        $csvArray = $user->getAllDataFromFile();

        foreach ($csvArray as $key => $value) {
            if (
                strtolower($value[Constant::NAME]) === strtolower($nameOrEmail) ||
                strtolower($value[Constant::EMAIL]) === strtolower($nameOrEmail)
            ) {
                return [
                    'name' => $value[Constant::NAME],
                    'email' => $value[Constant::EMAIL],
                    'password' => $value[Constant::PASSWORD],
                    'created_at' => $value[Constant::CREATED_AT],
                ];
            }
            return null;
        }
    }
}
