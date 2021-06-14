<?php

namespace Modules\Ticket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Ticket extends Model
{

    use SoftDeletes;



    // public function company()
    // {
    //     return $this->hasMany(Company::class);
    // }
    // public function contract()
    // {
    //     return $this->hasMany(Contract::class);
    // }
    // public function contractCount($cate_id)
    // {
    //     return Contract::where(['Ticket_id'=>$cate_id])->count();
    // }
    // public function consultationCount($cate_id)
    // {
    //     return Consulting::where(['Ticket_id'=>$cate_id])->count();
    // }
    // public function companyCount($cate_id)
    // {
    //     return Company::where(['Ticket_id'=>$cate_id])->count();
    // }
    // public function language()
    // {
    //     return $this->belongsTo(Language::class,'language_id');
    // }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'title',
        'content',
        'attachment',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

}
