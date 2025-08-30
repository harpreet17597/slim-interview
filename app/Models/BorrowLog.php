<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowLog extends Model
{
    protected $table = 'borrowlog';
    protected $primaryKey = 'borrowLogId';
    public $timestamps = false;

    protected $fillable = ['bookId', 'userId', 'borrowLogDateTime'];

    // relations
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'bookId', 'bookId');
    }
}
