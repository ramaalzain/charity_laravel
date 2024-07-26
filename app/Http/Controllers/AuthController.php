<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Account;
use App\Models\Employee;
use App\Models\Doner;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller

{

    public function __construct(){
        $this->middleware('auth:api',['except'=>['register','login','logout','profile','reset_password']]);
    }
    public function register(Request $request)  {

        $validator=Validator::make($request->all(),[
            
            'email'=>'required|string|email|unique:accounts',
            'password'=>'required|string|confirmed|min:6',
            'type'=>'required|in:0,1,2,3'//0=> user, 1=> admin  ,2=>employee , 3=>supporter
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()->first()
            ], 400);
        }

        $account=Account::create(array_merge(
            $validator->validated(),
            ['password'=>bcrypt($request->password)]
       ));
       $account->makeVisible('type');

       return response()->json(
        [ 'status'=>true,
          'message'=>'User reigster Successfully',
          'account'=>$account
         ],201);


        

    }
    public function login(Request $request)  {
        
        $validator=Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|string|min:6',
            // 'type'=>'required|in:0,1,2,3'//0=> user, 1=> admin  ,2=>employee , 3=>supporter
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()->first()
            ], 400);
        }
        $account=Account::where('email',$request->email)->first();
        if($account) {
          
            if(Hash::check($request->password, $account->password)){
                
                $token=auth()->attempt($validator->validated());
                return $this->createNewToken($token);}
            else{
                return response()->json([
                    'status' => false,
                    'message'=>'Password  is not correct',
                    'errors' =>  'Password  is not correct',

                    ], 400);
            
            
                
            }}
        
        else{
            return response()->json([
                'status' => false,
                'message'=>'Email is not correct',
                'errors' =>  'Email is not correct',

                ], 400);
        }

    }
    protected function createNewToken($token) {
        $account=auth()->user();
        $result=null;
        if($account->type=='0')$result=User::where('account_id',$account->id)->first();
        if($account->type=='2')$result=Employee::where('account_id',$account->id)->first();
        if($account->type=='3')$result=Doner::where('account_id',$account->id)->first();
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>auth()->factory()->getTTL()*1200,
            'message'=>'Logged in successfully',
            'account'=>auth()->user(),
            'user'=>$result
        ]);


    }
    public function logout() {
        auth()->logout();
        return response()->json(
            [ 'message'=>'User Logged out Successfully'

             ]);


    }
    public function profile(){
        $account=auth()->user();
        if($account->type==1)$result=User::with('account')->find($account->id);
        if($account->type==2)$result=Employee::with('account')->find($account->id);
        return response()->json($result);
    }
    public function refresh(){

        return $this->createNewToken(auth()->refresh());

    }
    public function reset_password(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()->first(),
            ], 400);
        }

        $user = auth()->user();
       
        if ( $user && Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => bcrypt($request->new_password),
            ]);
             
            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully',
                'user'=>$user
            ],200);
        } 
        else {
            return response()->json([
                'status' => false,
                'errors' => 'Old password is incorrect',
            ], 400);
        }
    }

}