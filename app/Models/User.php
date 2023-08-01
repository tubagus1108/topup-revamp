<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'whatsapp',
        'username',
        'balance',
        'role',
        'token',
        'password',
        'pin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'email_verified_at',
        'password',
        'remember_token',
        'token',
        'otp',
        'whitelist_ip',
        'deleted_at',
        'pin',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function getUsersDatatable($start, $length, $column, $order)
    {
        return User::offset($start)
                     ->limit($length)
                     ->orderBy($column, $order)
                     ->get();
    }

    public static function createNewUser(array $request)
    {
        $user = new User([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'whatsapp' => $request['whatsapp'],
            'username' => $request['username'],
            'balance' => $request['balance'],
            'role' => $request['role'],
            'token' => static::generateCustomToken(),
            'pin' => $request['pin'],
        ]);
        
        $user->save();

        return $user;
    }

    public static function getDetails($id)
    {
        return User::findOrFail($id);
    }

    public static function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return true;
    }

    public static function editUser($request, $id)
    {
        $user = User::findOrFail($id);
        $user->fill($request->all());
        $user->save();

        return $user;
    }

    public function checkBalance($price){
        return $this->balance > $price;
    }

    public static function generateCustomToken()
    {
        $token = base64_encode(random_bytes(40)); // Menghasilkan token acak sepanjang 40 byte dan di-encode dengan base64

        return $token;
    }
}
