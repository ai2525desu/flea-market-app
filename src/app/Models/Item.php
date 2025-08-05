<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // enum型の定数をconstで定数として明記する。
    // 定義したいもの'良好','目立った傷や汚れなし','やや傷や汚れあり','状態が悪い'
    const CONDITION = [
        1 => '良好',
        2 => '目立った傷や汚れなし',
        3 => 'やや傷や汚れあり',
        4 => '状態が悪い'
    ];
}
