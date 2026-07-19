<?php

namespace App\Http\Requests\API\V1\Doctor\Availability;

use App\Models\DoctorAvailabilitySlot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isDoctor();
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'after_or_equal:' . Carbon::today()->toDateString()],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:120'],
            'type' => ['required', Rule::in(['video', 'in_person'])],
            'clinic_id' => [
                'nullable',
                'exists:clinics,id',
                Rule::requiredIf($this->input('type') === 'in_person'),
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $doctorId = $this->user()->id;
            $date = $this->date;
            $start = Carbon::parse("{$date} {$this->start_time}");
            $end = Carbon::parse("{$date} {$this->end_time}");

            $overlapping = DoctorAvailabilitySlot::where('doctor_id', $doctorId)
                ->where('date', $date)
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end->format('H:i:s'))
                        ->whereRaw("ADDTIME(start_time, SEC_TO_TIME(duration_minutes * 60)) > ?", [$start->format('H:i:s')]);
                })
                ->exists();

            if ($overlapping) {
                $validator->errors()->add('time_range', 'The selected time range overlaps with an existing appointment (video or in-person). Please choose a different time.');
            }
        });
    }
}