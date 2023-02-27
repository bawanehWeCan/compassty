<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\PasswordChangeRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ResponseTrait;

    /**
     * @var UserRepository
     */
    protected $userRepositry;

    public function __construct()
    {
        $this->userRepositry = new UserRepository(app(User::class));
    }

    public function login(AuthRequest $request)
    {


        $user = User::where( 'email', $request->email )->first();

        if( !$user ){
            return response()->json([
                'status' => false,
                'code' => 500,
                'msg' => __('User Not Found!'),
            ], 500);

        }



        if (Hash::check($user->password,$request->password)) {

            return response()->json([
                'status' => false,
                'code' => 500,
                'msg' => __('Invalid credentials!'),
            ], 500);
        }

        Auth::login($user);

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response(['status' => true, 'code' => 200, 'msg' => __('Log in success'), 'data' => [
            'token' => $accessToken,
            'user' => UserResource::make(Auth::user()),
        ]]);





    }


    public function store(UserRequest $request)
    {
        try {
            $user = $this->userRepositry->save($request);

            // if ($request->has('image')) {
            //     $this->userRepositry->insertImage($request->image,$user);
            // }

            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => "https://api.releans.com/v2/otp/send",
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => "",
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => "POST",
            //     CURLOPT_POSTFIELDS => "sender=Bright Life&mobile=" . $request->phone . "&channel=sms",
            //     CURLOPT_HTTPHEADER => array(
            //         "Authorization: Bearer 54531079199db631db9651b454a74ee6"
            //     ),
            // ));

            // $response = curl_exec($curl);

            // curl_close($curl);

            Auth::login($user);

            $accessToken = Auth::user()->createToken('authToken')->accessToken;

            if ($user) {
                // return $this->returnData( 'user', UserResource::make($user), '');

                return response(['status' => true, 'code' => 200, 'msg' => __('User created succesfully'), 'data' => [
                    'token' => $accessToken,
                    'user' => UserResource::make(Auth::user()),
                ]]);
            }
        } catch (\Exception $e) {
            return $e;
            return $this->returnError('Sorry! Failed in creating user');
        }
    }

    public function sociallogin(Request $request)
    {

        $user = User::where([
            ['email', $request->email]
        ])->first();

        if ($user) {

            $accessToken = $user->createToken('authToken')->accessToken;

            //$user->token = $request->token;
            $user->save();
            Auth::login($user);

            return response(['status' => true, 'code' => 200, 'msg' => 'success', 'data' => [
                'token' => $accessToken,
                'user' => $user
            ]]);
        }


        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make('1234'),
        ]);



        Auth::login($user);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['status' => true, 'code' => 200, 'msg' => 'success', 'data' => [
            'token' => $accessToken,
            'user' => UserResource::make(Auth::user()),
        ]]);
    }


    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $old_pw =$request->old_password;

    //   if ($user->password == $old_pw)

        if(Hash::check($old_pw, $user->password)){


                $user->update([
                    'password' => Hash::make($request->new_password),
                ]);

            return $this->returnSuccessMessage('Password has been changed');
        }

        return $this->returnError('Password not matched!');
    }


    public function sendOTP($email)
    {
        $otp = 5555;
        // $otp = mt_rand(1000, 9999);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "://82.212.81.40:8080/websmpp/websms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "user=Wecan&pass=Suh12345&sid=WayToDoctor&mno=" . $email . "&text=Your OTP is " . $otp . " for your account&type=1&respformat=json",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer 2c1d0706b21b715ff1e5a480b8360d90"
            ),
        ));

        curl_exec($curl);

        curl_close($curl);

        return $otp;
    }

    public function profile(Request $request)
    {
        return $this->returnData('user', UserResource::make(Auth::user()), 'successful');
    }

    public function password(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {

            $otp = $this->sendOTP($request->email);

            $user->otp = $otp;
            $user->save();

            return $this->returnSuccessMessage('Code was sent');
        }

        return $this->returnError('Code not sent User not found');
    }

    public function checkOTP($email, $otp)
    {
        $user = User::where('email', $email)->first();

        if ((string)$user->otp == (string)$otp) {
            return true;
        }

        return false;
    }

    public function check(Request $request)
    {
        if ($this->checkOTP($request->email, $request->code)) {
            return $this->returnSuccessMessage('success');
        } else {
            return $this->returnError('Sorry! code not correct');
        }
    }

    public function changePassword(PasswordChangeRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {

            User::find($user->id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);

            return $this->returnSuccessMessage('Password has been changed');
        }

        return $this->returnError('Password not matched!');
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            // dd( Auth::user() );
            $user = Auth::user();
            if ($user) {
                // check unique email except this user
                if (isset($request->email)) {
                    $check = User::where('email', $request->email)
                        ->first();

                    if ($check) {

                        return $this->returnError('The email address is already used!');
                    }
                }

                $this->userRepositry->edit($request, $user);
                return $this->returnData('user', new UserResource($user), 'User updated successfully');
            }

            // if ($request->has('image') && $user->has('image')) {
            //     $image = $this->userRepositry->insertImage($request->image, $user, true);
            // } elseif ($request->has('image')) {
            //     $image = $this->userRepositry->insertImage($request->image, $user);
            // }

            DB::commit();
            // unset($user->image);

            return $this->returnError('Sorry! Failed to find user');
        } catch (\Exception$e) {
            DB::rollback();
            //return $e;

            return $this->returnError('Sorry! Failed in updating user');
        }
    }

    public function delete($id)
    {
        $user = User::find($id);

        $user->delete();



        return $this->returnSuccessMessage('Done!');
    }

    public function activate(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user) {

            $user->update([
                'active'=>1
            ]);
            return $this->returnSuccessMessage('User Activated');
        }

        return $this->returnError('Failed to activate user');
    }




    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();

        return $this->returnSuccessMessage('Logged out succesfully!');
    }
}
