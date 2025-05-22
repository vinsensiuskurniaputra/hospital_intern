<?php

namespace App\Models;

use App\Models\User;
use App\Models\Stase;
use App\Models\ResponsibleStase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Tambahkan import ini

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

    public function responsibleStases()
    {
        return $this->hasMany(ResponsibleStase::class);
    }

    // Perbarui method stases untuk menggunakan ResponsibleStase
    public function stases(): BelongsToMany
    {
        return $this->belongsToMany(Stase::class, 'responsible_stase')
            ->using(ResponsibleStase::class);
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
        $responsibleUser = self::findOrFail($id); // Cari responsible user berdasarkan ID

        $responsibleUser->update([
            'telp' => $data['telp'] ?? $responsibleUser->telp, // Gunakan $responsibleUser bukan $student
        ]);

        return $responsibleUser;
    }

    public static function deleteResponsibleUser($id)
    {
        self::where('id', $id)->delete();
    }
}
