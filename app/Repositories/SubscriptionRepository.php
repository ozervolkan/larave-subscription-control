<?php

namespace App\Repositories;

use  App\Models\Subscription;

class SubscriptionRepository
{


    public function create(int $userid, array $fields): Subscription
    {
        $data = [
            'user_id'=> $userid,
            'renewed_at'=> $fields['renewed_at'],
            'expired_at'=> $fields['expired_at']
        ];

        return Subscription::create($data);
    }

    /**
     * Veritabanından aboneliği getirir.
     *
     * @param int $id Abonelğin id'si
     * @param int $userid Kullanıcının id'si
     * @return mixed Abonelik getirme başarılıysa aboneliği, başarısızsa null döner
     */
    public function show(int $id, int $userid) :mixed
    {
        return Subscription::where('id', $id)
            ->where('user_id', $userid)
            ->first();
    }

    /**
     * Veritabanında aboneliği günceller.
     *
     * @param int $id Abonelğin id'si
     * @param array $fields Abonelik bilgileri renewed_at(abonelik yenileme tarihi) ve expired_at(abonelik bitiş tarihi) içermelidir.
     * @return Subscription Abonelik güncelleme başarılıysa aboneliği, başarısızsa null döner
     */
    public function update(int $id, array $fields): Subscription
    {
        $subscription = Subscription::find($id);
        $subscription->updated_at = now();
        $subscription->update($fields);

        return $subscription;
    }

    /**
     * Veritabanında aboneliği siler.
     *
     * @param int $id Abonelğin id'si
     * @return bool Abonelik silme başarılıysa aboneliği, başarısızsa null döner
     */
    public function delete(int $id): bool
    {
        $subscription = Subscription::find($id);
        return $subscription?->delete();
    }

    /**
     * Kullanıcılara ait tüm abonelikleri getirir.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllSubs(): \Illuminate\Database\Eloquent\Collection
    {
        return Subscription::all();
    }



}
