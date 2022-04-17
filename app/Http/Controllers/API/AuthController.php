<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request['email'])->first();

        if($user){
            if(password_verify($request->password, $user->password)){
                return response()->json([
                    'success' => 1,
                    'message' => 'Selamat Datang'.$user->name,
                    'user' => $user
                ]);
            }
            return $this->error('Password SALAH');
        }
        return $this->error('Email TIDAK Terdaftar');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:8'
        ]);


        if($validator->fails()){
            $validator = $validator->errors()->all();
            return $this->error($val[0]);
        }

        $user = User::create(array_merge($request->all(), [
            'password' => Hash::make($request->password)
         ]));

         if($user){
            
                return response()->json([
                    'success' => 1,
                    'message' => 'Selamat Datang Registrasi berhasil',
                    'user' => $user
                ]);
            }

        return $this->error('Registrasi Gagal');
    }
    public function error($pesan){
        return response()->json([
            'success' => 0,
            'message' => $pesan,
        ]);
    }

}