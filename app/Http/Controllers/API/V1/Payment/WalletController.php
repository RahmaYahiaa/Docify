<?php

namespace App\Http\Controllers\API\V1\Payment;

use App\Actions\Payment\Withdraw\GetStripeBankInfoAction;
use App\Actions\Payment\Withdraw\WithdrawMoneyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Doctor\Withdraw\WithdrawRequest;
use App\Http\Resources\API\V1\Payment\WalletTransactionCollection;
use App\Http\Resources\API\V1\Payment\WithdrawalResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WalletController extends Controller
{
public function withdraw(
        WithdrawRequest $request,
        WithdrawMoneyAction $withdrawAction,
        GetStripeBankInfoAction $getBankInfoAction
    ): JsonResponse {

        try {
            $user = $request->user();
            $transaction = $withdrawAction->execute($user, $request->amount);
            $bankInfo = $getBankInfoAction->execute($user);


  return $this->ok(  message: __('messages.withdrawal_initiated_successfully'), data: WithdrawalResource::make($transaction, $bankInfo)  );

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة عملية السحب.',
                'error' => $e->getMessage(),
            ], 400);
        }}
public function transactionsHistory(Request $request)
{
    $wallet = auth()->user()->wallet;

    $transactions = QueryBuilder::for($wallet->transactions())
        ->allowedFilters([
            AllowedFilter::exact('type'),
        ])
        ->latest()
        ->macroPaginate();

    return new WalletTransactionCollection($transactions);
}
}
