<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Student;
use App\Models\ResponsibleUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'photo_profile_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
    
    public function responsibleUser()
    {
        return $this->hasOne(ResponsibleUser::class);
    }

    public static function addUser($data)
    {
        return self::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($data['password']), // Hash password
            'photo_profile_url' => $data['photo_profile_url'] ?? null,
        ]);
    }

    public static function updateUser($id, $data)
    {
        $user = self::findOrFail($id); // Cari user berdasarkan ID

        $user->update([
            'username' => $data['username'] ?? $user->username,
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password, // Hash jika ada perubahan password
            'photo_profile_url' => $data['photo_profile_url'] ,
        ]);

        return $user;
    }

    public static function deleteUser($id)
    {
        $user = self::find($id);

        if (!empty($user->photo_profile_url)) {
            Storage::disk('public')->delete($user->photo_profile_url);
        }

        return $user->delete();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token, $this->email));
    }

}
