<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Role\UserRoleEnum;
use App\Enums\User\TokenAbilityEnum;
use App\Enums\User\UserStatusEnum;
use App\Filters\CreatedAtFilter;
use App\Models\Appointment;
use App\Models\Conversation;
use App\Models\DoctorAvailabilitySlot;
use App\Models\DoctorProfile;
use App\Models\FcmToken;
use App\Models\HealthCard;
use App\Models\Message;
use App\Models\Notification;
use App\Models\PatientProfile;
use App\Models\PaymentMethod;
use App\Models\Prescription;
use App\Models\PrescriptionRequest;
use App\Models\Review;
use App\Models\Specialization;
use App\Models\UserBlock;
use App\Models\UserMeasurement;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use CreatedAtFilter;
    use InteractsWithMedia;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use UserAccessor;
    use UserAction;

    protected $guard_name = 'web';
    public const MEDICAL_CERTIFICATE = 'medical_certificate';
public const LAB_REPORTS = 'lab_reports';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'specialization_id',
        'medical_certificate',
        'status',
        'google_id',
        'phone',
        'doctor_id',
        'last_login_at',
        'rejection_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'abilities' => TokenAbilityEnum::class,
            'status' => UserStatusEnum::class,
            'last_login_at' => 'datetime',
        ];
    }

    public function Specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class);
    }

    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class, 'user_id');
    }

    public function createToken(string $name, array $abilities = ['*'], $expiresAt = null)
    {
        $expiration = (int) config('sanctum.expiration');
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(240)),
            'abilities' => $abilities,
            'expires_at' => Carbon::now()->addMinutes((int) $expiresAt ?? $expiration),
        ]);

        return new NewAccessToken($token, $token->id . '|' . $plainTextToken);
    }

    public function availabilitySlots()
    {
        return $this->hasMany(DoctorAvailabilitySlot::class, 'doctor_id');
    }

    public function prescriptionRequests()
    {
        return $this->hasMany(PrescriptionRequest::class, 'patient_id');
    }
    public function appointmentsAsPatient()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function isDoctor(): bool
    {
        return $this->hasRole(UserRoleEnum::DOCTOR->value);
    }

    public function isPatient(): bool
    {
        return $this->hasRole(UserRoleEnum::PATIENT->value);
    }

    public function isActive(): bool
    {
        return $this->status === UserStatusEnum::ACTIVE;
    }

    public function patientProfile()
    {
        return $this->hasOne(PatientProfile::class);
    }

    public function healthCard()
    {
        return $this->hasOne(HealthCard::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    //Chat
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
            ->withPivot('joined_at', 'unread_count');
    }

    public function sendMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    //Block
    public function blockedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_blocks', 'blocker_id', 'blocked_id')->withTimestamps('blocked_at');
    }
    public function blockedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_blocks', 'blocked_id', 'blocker_id')->withTimestamps('blocked_at');
    }

    public function hasBlocked(int $userId): bool
    {
        return $this->blockedUsers()->where('blocked_id', $userId)->exists();
    }

    public function isBlockedBy(int $userId): bool
    {
        return $this->blockedByUsers()->where('blocker_id', $userId)->exists();
    }

    public function hasBlockRelationWith(int $userId): bool
    {
        return UserBlock::where(function ($q) use ($userId) {
            $q->where('blocker_id', $this->id)
                ->where('blocked_id', $userId);
        })
            ->orWhere(function ($q) use ($userId) {
                $q->where('blocker_id', $userId)
                    ->where('blocked_id', $this->id);
            })
            ->exists();
    }

    // Helpers for chat
    public function getDisplayInfo(): array
    {
        $role = $this->roles->first()?->name ?? 'patient';
        return [
            'id'              => $this->id,
            'name'            => $this->full_name,
            'profile_picture' => $this->getProfilePicture(),
            'role'            => $role,
            ...($role === UserRoleEnum::DOCTOR->value ? ['specialization' => $this->doctorProfile?->specialization?->name] : []),
        ];
    }
    public function getProfilePicture(): string
    {
        $isDoctor = $this->isDoctor();

        if ($isDoctor) {
            return $this->doctorProfile?->getFirstMediaUrl('profile_picture')
                ?? '/images/default-doctor.jpg';
        }

        return $this->patientProfile?->getFirstMediaUrl('profile_picture')
            ?? '/images/default-patient.jpg';
    }

    //notification
    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    //doctor_wallet

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'doctor_id');
    }
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function measurements()
    {
        return $this->hasMany(UserMeasurement::class);
    }
    //assistant
    public function assistants()
    {
        return $this->hasMany(User::class, 'doctor_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function scopeSearchName($query, $name)
    {
        return $query->where(function ($q) use ($name) {
            $q->where('first_name', 'like', "%{$name}%")
                ->orWhere('last_name', 'like', "%{$name}%")
                ->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$name}%"]);
        });
    }


    public function getGreeting(): string
    {
        $hour = now()->hour;

        if ($hour < 12) return 'Good Morning';
        if ($hour < 17) return 'Good Afternoon';

        return 'Good Evening';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeRole($query, string $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function patientPrescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    public function upcomingAppointments()
    {
        return $this->appointmentsAsPatient()
            ->whereIn('status', [
                AppointmentStatusEnum::CONFIRMED->value
            ])
            ->whereHas('slot', function ($q) {
                $q->whereRaw(
                    "ADDTIME(CONCAT(date, ' ', start_time), SEC_TO_TIME(duration_minutes * 60)) > ?",
                    [now()]
                );
            });
    }

    public function getNextAppointmentAttribute()
    {
        return $this->upcomingAppointments()
            ->with([
                'doctor',
                'slot'
            ])
            ->get()
            ->sortBy(function ($appointment) {

                return $appointment->slot->date->format('Y-m-d')
                    . ' '
                    . $appointment->slot->start_time;
            })
            ->first();
    }
}