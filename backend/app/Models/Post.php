<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        "userId",	
        "firstName",	
        "lastName",	
        "location",	
        "description",	
        "picturePath",	
        "userPicturePath",	
        "likes",	
        "comments"
        
    ];

    


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [ 
        'likes' => 'array',
        'comments' => 'array',
    ];

    public $timestamps = true; // Ensure this line is present and set to true
 
}
