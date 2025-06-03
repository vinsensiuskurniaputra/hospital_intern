<?php

namespace App\Models;

use App\Models\User;
use App\Models\Stase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Tambahkan import ini

class ResponsibleUser extends Model
{
    use HasFactory;

    protected $table = 'responsible_users';

    protected $fillable = [
        'user_id',
        'telp',
        'nip',
        'specialization',
        'status'
    ];

    /**
     * Get the user that owns the responsible user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stases(): BelongsToMany
    {
        return $this->belongsToMany(Stase::class, 'responsible_stase');
    }


    public static function createResponsibleUser($data)
    {
        return self::create([
            'user_id' => $data['user_id'],
            'telp' => $data['telp'],
        ]);
    }

    public static function updateResponsibleUser($id, $data)
    {
        $responsibleUser = self::findOrFail($id); // Cari student berdasarkan ID

        $responsibleUser->update([
            'telp' => $data['telp'] ?? $responsibleUser->telp,
        ]);

        return $responsibleUser;
    }

    public static function deleteResponsibleUser($id)
    {
        self::where('id', $id)->delete();
    }
}
