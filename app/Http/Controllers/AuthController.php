<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;

class AuthController extends Controller
{
    protected $auth;

    public function __construct()
    {
        $firebaseConfig = config('firebase.projects.app');
        $factory = (new Factory)
            ->withServiceAccount($firebaseConfig['credentials'])
            ->withDatabaseUri($firebaseConfig['database']['url'])
            ->withProjectId(config('firebase.default'));

        $this->auth = $factory->createAuth();
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            session(['firebase_user' => $signInResult->data()]);

            return redirect()->intended('/home');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => 'These credentials do not match our records.']);
        }
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $user = $this->auth->createUserWithEmailAndPassword($request->email, $request->password);
            $this->auth->updateUser($user->uid, ['displayName' => $request->name]);

            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            session(['firebase_user' => $signInResult->data()]);

            return redirect('/home');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => 'Failed to create a new user.']);
        }
    }

    public function logout()
    {
        session()->forget('firebase_user');
        return redirect('/login');
    }
}
