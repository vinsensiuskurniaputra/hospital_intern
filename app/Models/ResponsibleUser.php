<?php

namespace App\Models;

use App\Models\User;
use App\Models\Stase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResponsibleUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'telp',
    ];

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
            'telp' => $data['telp'] ?? $student->telp,
        ]);

        return $responsibleUser;
    }

    public static function deleteResponsibleUser($id)
    {
        self::where('id', $id)->delete();
    }
}
