<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Helpers\JsonResponseHelper;
use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use App\Repositories\TransactionRepository;
use App\Services\Payment\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{

    public function __construct(private TransactionRepository $transactionRepository, private SubscriptionRepository $subscriptionRepository)
    {
    }

    /**
     * @param int $userid
     * @param Request $request
     * @return JsonResponse
     */
    public function create(int $userid, Request $request): JsonResponse
    {
        $fields = $request->validate([
            'subscription_id'=> 'required|int'
        ]);

        $authorized_userid = auth()->user()->id;

        if($authorized_userid != $userid)
        {
            return JsonResponseHelper::error("Gönderilen kullanıcı id'si yetkilendirilmiş kullanıcı ile eşleşmiyor.", 404);
        }

        $subscription = $this->subscriptionRepository->show($fields['subscription_id'], $userid);

        if ($subscription === null){
            return JsonResponseHelper::error("Gönderilen kullanıcı id'si ne ait bir abonelik bulunamadı.", 404);
        }

        //Abonelik süresi dolmadan yenileme yapabileceğinden dolayı expired zamanının geçmesini dikkate almıyoruz.
        $payment = new Payment();
        $payment_result = $payment->pay(Constants::SUBSCRIPTION_PRICE);
        $expired_at = Carbon::parse($subscription->expired_at);

        if ($payment_result['success']){
            $transaction = $this->transactionRepository->create($subscription->id);

            $newEndDate = $expired_at->addMonth(); // 1 ay uzat
            $subscription->update(['expired_at' => $newEndDate->format("Y-m-d H:i:s")]);

            $response = [
                'subscription_id'=> $subscription->id,
                'expired_at'=> $subscription->expired_at
            ];

            return JsonResponseHelper::success($response, "Ödeme başarılı.", 200);
        }

        return JsonResponseHelper::error("Ödeme işlemi başarısız. Lütfen kartınızı kontrol ediniz.", 422);
    }
}
