<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

        /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'id_card' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            $imageName = ''; // Default to an empty string or a placeholder image name
            $imageName = time() . '.' . $data['id_card']->extension();
            $data['id_card']->move(public_path('id_cards'), $imageName);
            
            // Create the user with the image name
            return User::create([
                'name' => $data['name'],
                'contact' => $data['contact'],
                'email' => $data['email'],
                'status' => 'pending',
                'password' => Hash::make($data['password']),
                'id_card' => $imageName,
            ]);

            return redirect()->route('/')->with('success', 'Your account has been created successfully. Please wait for the admin to approve your account.');

        } catch (\Exception $e) {
            session()->flash('debug', 'Failed to create user: ' . $e->getMessage());
            throw $e;
        }
    }

    
}
