<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;//消息通知
use Illuminate\Foundation\Auth\User as Authenticatable;//授权
use App\Notifications\ResetPassword;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        # code...
        parent::boot();

        static::creating(function($user)
        {
            # code...
            $user->activation_token = str_random(30);
        });
    }

    public function gravatar($size='100')
    {
        # code...
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function statuses()
    {
        # code...
        return $this->hasMany(Status::class);
    }

    public function feed()
    {
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids, Auth::user()->id);

        // return $this->statuses()
        //             ->orderBy('created_at', 'desc');
        
        return Status::whereIn('user_id', $user_ids)
                              ->with('user')
                              ->orderBy('created_at', 'desc');
    }

    /**
     * 獲取粉絲關係列表
     * @return [type] [description]
     */
    public function followers()
    {
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }

    /**
     * 获取用户关注人列表
     * @return [type] [description]
     */
    public function followings()
    {
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }

    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }

    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
