<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Repositories\UserRepository;
use http\Client\Response;
use Illuminate\Http\Request;
use App\Repositories\SubscriptionRepository;
use Illuminate\Support\Facades\Hash;
use Psy\Util\Json;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{

    public function __construct(private SubscriptionRepository $subscriptionRepository, private UserRepository $userRepository) //php 8 features
    {

    }

    /**
     * Abonelik ekleme methodu
     *
     * @param integer $userid Kullanıcının id'si
     * @param Request $request İstek Request bilgisi
     * @return JsonResponse JSON Ekleme işleminin sonucunu döndürür.
     */
    public function create (int $userid, Request $request): JsonResponse
    {
        $fields = $request->validate([
            'renewed_at'=> 'required|date',
            'expired_at'=> 'required|date'
        ]);

        $authorized_userid = auth()->user()->id;

        if($authorized_userid != $userid)
        {
            return JsonResponseHelper::error("Gönderilen kullanıcı id'si yetkilendirilmiş kullanıcı ile eşleşmiyor.", 404);
        }

        $result = $this->subscriptionRepository->create($userid, $fields);

        if($result !== null) {
            $data = [
                'id'=> $result->id,
                'renewed_at'=> $result->renewed_at,
                'expired_at'=>  $result->expired_at
            ];
            return JsonResponseHelper::success($data, "Abonelik oluşturma işlemi başarılı", 200);
        }

        return JsonResponseHelper::error("Abonelik oluşturma işlemi başarısız oldu.", 404);
    }

    /**
     * Abonelik güncelleme methodu
     *
     * @param integer $userid Kullanıcının id'si
     * @param integer $id Abonelik id'si
     * @param Request $request İstek Request bilgisi
     * @return JsonResponse JSON Güncelleme işleminin sonucunu döndürür.
    */
    public function update(int $userid, int $id, Request $request ): JsonResponse
    {
        $fields = $request->validate([
            'renewed_at'=> 'required|date',
            'expired_at'=> 'required|date'
        ]);

        $authorized_userid = auth()->user()->id;

        if($authorized_userid != $userid)
        {
            return JsonResponseHelper::error("Gönderilen kullanıcı id'si yetkilendirilmiş kullanıcı ile eşleşmiyor.", 404);
        }

        $subscription = $this->subscriptionRepository->show($id, $userid);

        if ($subscription === null){
            return JsonResponseHelper::error("Gönderilen kullanıcı id'si ne ait bir abonelik bulunamadı.", 404);
        }

        $subscription = $this->subscriptionRepository->update($id, $fields);

        //REsources ile response düzenle

        $data = [
            'id'=> $subscription->id,
            'renewed_at'=> $subscription->renewed_at,
            'expired_at'=>  $subscription->expired_at
        ];

        return JsonResponseHelper::success($data, "Abonelik güncelleme başarılı", 200);
    }

    /**
     * Abonelik silme methodu
     *
     * @param integer $userid Kullanıcının id'si
     * @param int $id Abonelik id'si
     * @return JsonResponse
     */
    public function delete(int $userid, int $id): JsonResponse
    {
        $authorized_userid = auth()->user()->id;

        if($authorized_userid != $userid)
        {
            return JsonResponseHelper::error("Gönderilen kullanıcı id'si yetkilendirilmiş kullanıcı ile eşleşmiyor.", 404);
        }

        $subscription = $this->subscriptionRepository->show($id, $userid);
        if ($subscription === null){
            return JsonResponseHelper::error("Gönderilen kullanıcı id'si ne ait bir abonelik bulunamadı.", 404);
        }

        $result = $this->subscriptionRepository->delete($id);

        if ($result){
            //silme başarılı
            //REsources ile response düzenle

            return JsonResponseHelper::success([], "Silme işlemi başarılı oldu.", 200);
        }

        return JsonResponseHelper::error("Silme işlemi başarısız oldu. Lütfen daha sonra tekrar deneyin.", 500);
    }

    /**
     * Abonelikleri listeme methodu
     *
     * @param int $userid Kullanıcının id'si
     * @return JsonResponse JSON Abonelikleri listeler
     */
    public function list(int $userid): JsonResponse
    {
        $authorized_userid = auth()->user()->id;

        if($authorized_userid != $userid)
        {
            return JsonResponseHelper::error("Gönderilen kullanıcı id'si yetkilendirilmiş kullanıcı ile eşleşmiyor.", 404);
        }

        $subscriptions = $this->userRepository->getAllSubsByUser($userid);

        if ($subscriptions !== null){
            $response = [];
            foreach ($subscriptions as $subscription) {
                $temp2 = [];
                foreach ($subscription->transactions as $transaction) {
                    $temp2[] = [
                        "id" => $transaction->id,
                        "status" => $transaction->status
                    ];
                }
                $temp = [
                    "id"=> $subscription->id,
                    "renewed_at"=> $subscription->renewed_at,
                    "expired_at"=> $subscription->expired_at,
                    "transactions"=> $temp2
                ];

                $response[] = $temp;
            }

            return JsonResponseHelper::success($response, "Abonelik listeleme başarılı", 200);
        }

        return JsonResponseHelper::error("Abonelik listeleme başarısız oldu", 500);
    }
}
