<?php

namespace App\Http\Resources\API\V1\Assistant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    { $summary = $this['payment_summary'] ?? [];
        return [

            'clinic_day' => [
                'date'  => $this['clinic_day']['date'] ?? now()->format('l, F j, Y'),
            ],


            'operational_stats' => [
                'patients_handled' => (int) ($this['operational_stats']['patients_handled'] ?? 0),
                'scheduled'        => (int) ($this['operational_stats']['scheduled'] ?? 0),
                'completed'        => (int) ($this['operational_stats']['completed'] ?? 0),
                'cancelled'        => (int) ($this['operational_stats']['cancelled'] ?? 0),
            ],


            'appointment_types' => [
                'clinic_visits'        => (int) ($this['appointment_types']['clinic_visits'] ?? 0),
                'online_consultations' => (int) ($this['appointment_types']['online_consultations'] ?? 0),
                'no_show'              => (int) ($this['appointment_types']['no_show'] ?? 0),
            ],


            'presence_status' => [
                'physically_attended' => (int) ($this['presence_status']['physically_attended'] ?? 0),
                'online_attended'     => (int) ($this['presence_status']['online_attended'] ?? 0),
            ],

          'payout_card' => [
                'amount_received'    => (float) ($this['payment_summary']['card']['amount'] ?? 0),
   'transactions_count' => (int) ($this['payment_summary']['card']['count'] ?? 0),
            ],
'cash_summary' => [
                'amount' => (float) ($this['payment_summary']['cash']['amount'] ?? 0),
                'count'  => (int) ($this['payment_summary']['cash']['count'] ?? 0),
                'label'  => 'Assistant-Handled',
            ],

           'financial_snapshot' => [
                'total_revenue'  => (float) ($summary['total_revenue'] ?? 0),
                'total_payments' => (int) ($summary['total_count'] ?? 0),
                'card_amount'    => (float) ($summary['card_amount'] ?? 0),
                'cash_amount'    => (float) ($summary['cash_amount'] ?? 0),
            ],
        ];
    }
}