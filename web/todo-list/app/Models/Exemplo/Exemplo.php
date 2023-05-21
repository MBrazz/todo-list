<?php

namespace App\Models\Exemplo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exemplo extends Model
{
    use HasFactory;

    protected $fillable = ['exemplo'];

    public function scopeByExemplo( $query, $exemplo )
    {
        return $query->where( 'exemplo', 'LIKE', "%$exemplo%" );
    }
}
