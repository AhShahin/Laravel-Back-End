<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'telephone', 'postal_code', 'salary', 'province_id'
    ];

    public function province() {
        return $this->belongsTo(Province::class);
    }

    /**
     * Set the user's salary.
     *
     * @param  string  $value
     * @return void
     */
    public function setSalaryAttribute($value)
    {
        $newValue = preg_replace('/[ ,]+/', '', $value);
        if (strlen($newValue) == 5) {
            $newValue .= '.00';
        }else {
            str_replace(',', '.', $newValue);
        }

        $this->attributes['salary'] = $newValue;
    }

    /**
     * Set the user's telephone.
     *
     * @param  string  $value
     * @return void
     */
    public function setTelephoneAttribute($value) {
        //  ,()-
        $newValue = preg_replace('/[^\d]+/', '', $value);
        $this->attributes['telephone'] = $newValue;
    }

    /**
     * Get the user's salary.
     *
     * @param  string  $value
     * @return void
     */
    public function getSalaryAttribute($value)
    {
        $province = $this->province->name;
        if ($province == 'Québec') {
            $newValue = number_format( $value, 2, ',', ' ' );
        }else {
            $newValue = number_format( $value, 2);
        }
        return $newValue;
    }
    /**
     * Get the user's telephone.
     *
     * @param  string  $value
     * @return void
     */
    public function getTelephoneAttribute($value) {
        $newValue = preg_match( '/^(\d{3})(\d{3})(\d{4})$/', $value,  $matches);
        $result = '(' . $matches[1] . ') ' .$matches[2] . '-' . $matches[3];
        return $result;
    }
}
