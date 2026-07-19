<?php

namespace App\Actions\Doctor\Appointment;

use App\Models\User\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ListDoctorsAction
{
    public function execute(int $perPage = 20): LengthAwarePaginator
    {
        return QueryBuilder::for(User::class)
            ->whereHas('roles', fn($q) => $q->where('name', 'doctor'))
            ->whereHas('doctorProfile', fn($q) => $q->whereNotNull('video_fee')->orWhereNotNull('in_person_fee'))
            ->join('doctor_profiles', 'users.id', '=', 'doctor_profiles.user_id')
            ->leftJoinSub(
                DB::table('reviews')
                    ->select('doctor_profile_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as reviews_count'))
                    ->groupBy('doctor_profile_id'),
                'review_stats',
                'review_stats.doctor_profile_id',
                '=',
                'doctor_profiles.id'
            )
            ->with(
                'doctorProfile.specialization',
                'doctorProfile.clinic'
            )
            ->allowedFilters([
                AllowedFilter::callback('specialization_id', fn($q, $v) => $q->whereHas('doctorProfile', fn($q2) => $q2->where('specialization_id', $v))),
                AllowedFilter::callback('min_fee', fn($q, $v) => $q->whereHas('doctorProfile', fn($q2) => $q2->whereRaw('LEAST(COALESCE(video_fee, 999999), COALESCE(in_person_fee, 999999)) >= ?', [$v]))),
                AllowedFilter::callback('max_fee', fn($q, $v) => $q->whereHas('doctorProfile', fn($q2) => $q2->whereRaw('LEAST(COALESCE(video_fee, 999999), COALESCE(in_person_fee, 999999)) <= ?', [$v]))),
            ])
            ->allowedSorts([
                'first_name',
                AllowedSort::field('experience', 'doctor_profiles.years_of_experience'),
                AllowedSort::callback('fee', fn($query, $direction) => $query->orderByRaw('LEAST(COALESCE(doctor_profiles.video_fee, 999999), COALESCE(doctor_profiles.in_person_fee, 999999)) ' . ($direction ? 'ASC' : 'DESC'))),
                AllowedSort::callback('rating', fn($query, $direction) => $query->orderByRaw('COALESCE(review_stats.avg_rating, 0) ' . ($direction ? 'ASC' : 'DESC'))),
            ])
            ->select('users.*', 'review_stats.avg_rating', 'review_stats.reviews_count')
            ->paginate($perPage)
            ->appends(request()->query());
    }
}