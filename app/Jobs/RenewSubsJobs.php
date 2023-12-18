<?php

namespace App\Jobs;

use App\Helpers\Constants;
use App\Http\Controllers\TransactionController;
use App\Repositories\SubscriptionRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Payment\Payment;

class RenewSubsJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * @param SubscriptionRepository $subscriptionRepository
     * @return void
     */
    public function handle(SubscriptionRepository $subscriptionRepository, TransactionRepository $transactionRepository): void
    {
        $subscriptions = $subscriptionRepository->getAllSubs();

        foreach ($subscriptions as $subscription) {
            $expired_at = Carbon::parse($subscription->expired_at);
            $now = Carbon::now();

            if ($now->gte($expired_at)) {
                $payment = new Payment();
                $payment_result = $payment->pay(Constants::SUBSCRIPTION_PRICE);
                if ($payment_result['success']){
                    $newExpiredAt = $expired_at->addMonth(); // 1 ay uzat
                    $subscription->update(['expired_at' => $newExpiredAt->format('Y-m-d H:i:s')]);
                    $transactionRepository->create($subscription->id);
                    echo $subscription->id . " id'li abonelik yenilendi. Bitiş Tarihi : ". $subscription->expired_at;
                } else {
                    echo $subscription->id . " id'li abonelik için Ödeme başarısız";
                }
            } else {
                echo $subscription->id ." id'li Aboneliğin son tarihi henüz gelmemiş.";
            }
        }
    }
}
