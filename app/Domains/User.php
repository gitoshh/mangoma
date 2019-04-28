<?php


namespace App\Domains;
use App\User as UserModel;
use Illuminate\Support\Facades\Hash;


class User
{
    /**
     * Creates a new user instance in the database;
     *
     * @param string $name
     * @param string $email
     * @param string $password
     *
     * @return array
     */
    public function newUser(string $name, string $email, string $password): array
    {
        $user = new UserModel();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);

        if ($user->save()) {
            return $user->toArray();
        }

        return [];
    }

}