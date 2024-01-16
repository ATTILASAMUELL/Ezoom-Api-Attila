<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    use HasFactory;

    protected $fillable = ["title", "check", "user_id"];
    protected $guarded = ["id"];
    protected $table = 'lists'; // Corrigido para o plural "lists"
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
            $model->updated_at = now();
        });
    }

    // Definindo o relacionamento com o modelo de usuário
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
