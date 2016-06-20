<?php

namespace Funblr;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = [ 'username', 'password' ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'apikey',
    ];
    
    /**
     * Relationship with posts
     */
    public function posts() {
        return $this->hasMany('Funblr\Post');
    }
    
    /**
     * Generates an apikey for the user
     * 
     * @var $str a string to help on generation
     */
    
    public function assignApiKey() 
    {
        //nothing fancy here, just making as unique as possible
        $this->apikey = bcrypt( $this->id . rand(1, 1024) . $this->username. time() . md5(rand(1,1024)));
        $this->save();
    }
    
    /**
     * Avoid rememberToken
     */
    
    public function getRememberToken() { return '';}

    public function setRememberToken($value) {}

    public function getRememberTokenName()
    {
        // just anything that's not actually on the model
        return 'trash_attribute';
    }

    /**
     * Fake attribute setter so that Guard doesn't complain about
     * a property not existing that it tries to set.
     *
     * Does nothing, obviously.
     */
    public function setTrashAttributeAttribute($value) {}
}
