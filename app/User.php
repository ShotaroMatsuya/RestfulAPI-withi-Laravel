<?php

namespace App;

use Illuminate\Support\Str;
use App\Transformers\UserTransFormer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    //softDeleteの追加
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    //transformerの実装
    public $transformer = UserTransFormer::class;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';


    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    // in that way, 'Buyer' & 'Seller' tables are going to inherit this value
    protected $table = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified', 'verification_token', 'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //JSONオブジェクトをresponseとして返すときに見せたくないattributeをここにセットする
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //defined mutator which modify an  attribute before inserting record.
    public function setNameAttribute($name)
    {
        //全部小文字
        $this->attributes['name'] = strtolower($name);
    }
    //defined accessor which modify an attribute after fetching record.
    public function getNameAttribute($name)
    {
        //最初だけ大文字
        return ucwords($name);
    }
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }
    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }
    public static function generateVerificationCode()
    {
        return Str::random(40);
    }
}
