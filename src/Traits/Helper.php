<?php

namespace Hellm\ExpenseApp\Traits;

use Hellm\ExpenseApp\AuthManager;
use Hellm\ExpenseApp\FileManagement\InfoFile;
use Hellm\ExpenseApp\Constant;
use Hellm\ExpenseApp\FileManagement\UserFile;


trait Helper
{
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
        // print_r($csvArray);
        foreach ($csvArray as $key => $value) {
            if (
                strtolower($value[Constant::NAME]) === strtolower($nameOrEmail) ||
                $value[Constant::EMAIL] === $nameOrEmail
            ) {
                return [
                    'id' =>  $value[Constant::ID],
                    'name' => $value[Constant::NAME],
                    'email' => $value[Constant::EMAIL],
                    'password' => $value[Constant::PASSWORD],
                    'created_at' => $value[Constant::CREATED_AT],
                ];
            }
        }
        return null;
    }

    function getInfoFromUser(AuthManager $auth_manager): array
    {
        $infos = new InfoFile();
        $users_data = $infos->getAllDataFromFile();

        return array_filter($users_data, function ($record) use ($auth_manager): bool {
            if (strtolower((int)$record[Constant::USER_ID]) === $auth_manager->getUserId()) {
                return true;
            }
            return false;
        });
    }
}
