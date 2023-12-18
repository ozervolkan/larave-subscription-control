<?php

namespace App\Repositories;

use App\Models\Subscription;
use  App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class UserRepository
{
    /**
     * @param array $fields
     * @return mixed
     */
    public function create(array $fields): mixed
    {
        return User::create([
            'name'=> $fields['name'],
            'email'=> $fields['email'],
            'password'=> bcrypt($fields['password'])
        ]);
    }

    /**
     * @param array $fields
     * @return mixed
     */
    public function show(array $fields): mixed
    {
        return User::where('email', $fields['email'])->first();
    }


    /**
     * Kullanıcılaya ait tüm abonelikleri getirir.
     *
     * @param integer $userid Kullanıcının id'si
     * @return Collection Abonelikleri getirme başarılıysa abonelikleri, başarısızsa null döner
     */
    public function getAllSubsByUser(int $userid): Collection
    {
        $user = User::find($userid);
        return $user->subscriptions;
    }
}
