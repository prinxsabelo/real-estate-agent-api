<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Response::json([
            'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function handleCallbackProvider($provider)
    {   
       
        try {
            $agentUser = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }
        
        if(isset($agentUser->email))
        {
            return $this->ContinueAccount($agentUser,$provider);
        }

       
    }


    public function ContinueAccount($checkUser, $provider)
    {
        $agentUser = User::where('email',$checkUser->getEmail())->first();
        if(!$agentUser)
        {
            $agentUser = User::create([
                        'name' => $checkUser->getName(),
                        'email' => $checkUser->getEmail(),
                        'avatar' => $checkUser->getAvatar(),
                        'is_agent' => true,
                        'provider' => $provider,
                        'provider_id' => $checkUser->getId()
            ]);
            $agent = Agent::create([
                'user_id' => $agentUser->user_id,
                'agency' => 'x',
                'logo' => 'x',
                'description' => 'x',
                'phone_no1' => 'x',
                'address' => 'x'
            ]);
            if($agent)
            {
                $agentUser->agent = $agent;
            }
            
        }else{
            $agentUser->update([
                        'avatar' => $checkUser->getAvatar(),
                        'provider' => $provider,
                        'provider_id' => $checkUser->getId()
            ]);
            $agent = Agent::where('user_id',$agentUser->user_id)->first();
            $agentUser->agent = $agent;
            $agentUser->agent_id = $agent->agent_id;

        }
        $token = $agentUser->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60,
            'agent_user' => $agentUser,
            'ok' => true
        ]);
      
     }


    public function me(Request $request)
    {
        $agentUser = $request->user();
        $agent =  Agent::where('user_id',$agentUser->user_id)->first();
        $agentUser->agent =$agent;
        $agentUser->agent_id = $agent->agent_id;
        return $agentUser;
    }

    public function saveAgency(Request $request)
    {
        $user_id =  $request->user()->user_id;
        $agent = Agent::where('user_id',$user_id)->first();
        if($agent)
        {
            if($agent->user_id == $user_id)
            {
                $agent_id = $agent->update($request->all());
                if($agent_id){
                    return Response::json('success');
                }
                
            }
        }
    }

    public function uploadAgencyLogo(Request $request)
    {
        $user_id =  $request->user()->user_id;
        
        $logo = $request->file('logo');
        $filename = time().rand(3,100). '.'.$logo->getClientOriginalExtension();
        $logo->move('uploads/', $filename);
        $agent = Agent::where('user_id',$user_id)->first();
        if($agent)
        {
            if($agent->user_id == $user_id)
            {
                $agent->logo = $filename;
                $agent->save();
                return Response::json('success');
            }
        }
       
    }

}


