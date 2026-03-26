<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class User extends Authenticatable
{
    use Notifiable;

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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {   
    $this->notify(new class($token) extends ResetPassword {
        public function toMail($notifiable)
        {
            return (new MailMessage)
                ->subject('【重要】パスワード再設定のご案内')
                ->greeting('こんにちは')
                ->line('パスワード再設定のリクエストを受け付けました。')
                ->action('パスワードを再設定する', url(route('password.reset', $this->token, false)))
                ->line('※このリンクの有効期限は60分です。')   
                ->line('このメールに心当たりがない場合は無視してください。');
        }
    });
    }
    public function questions()
    {
        return $this->hasMany(\App\Models\Question::class);
    }

    public function answers()
    {
        return $this->hasMany(\App\Models\Answer::class);
    }

    
}
