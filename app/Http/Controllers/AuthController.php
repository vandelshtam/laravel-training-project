<?php

namespace App\Http\Controllers;

use App\Models\Info;

use App\Models\User;
use App\Mail\Feedback;
use App\Models\Social;

use App\Mail\OrderShipped;
use Illuminate\Http\Request;
use App\Mail\User\PasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $request;


    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }




    //переход на форму регистрации
    public function register(){
        return view('register');
    }


    //запись информации о регистрации нового пользователя
    public function registerNewUser(){
        //валидация почты и пароля
        $this->request->validate(
            [   'name'  => 'required|min:3|max:30',
                'email' => 'required|email:rfc|unique:users|max:255',
                'password' => 'required|min:6|max:16']
        );
        //запись нового пользователя в БД: таблицы users,User_infos,user_socials
        $user = User::create([
            'name' => $this->request->name,
            'email' => $this->request->email,
            'password' => Hash::make($this->request->password)
        ]);
        $info = Info::create([
            'occupation' => '',
            'location' => '',
            'position' => '',
            'phone' => '',
            'avatar' => 'img/demo/avatars/avatar-f.png',
            'user_id' => $user->id,
            'infosable_id' => $user->id,

        ]);
        
        $social = Social::create([
            'telegram' => '',
            'instagram' => '',
            'vk' => '',
            'user_id' => $user->id
        ]);

        User::where('id', $user->id)
              ->update(['info_id' => $info->id,
                        'social_id' => $social->id]);

        
        //сообщение об успешной регистрации нового пользователя
        if($user && $info && $social){
            event(new Registered($user));
            $login = $this->request->email;
            Mail::to($user->email)->send(new PasswordMail($login));

            $this->request->session()->flash('flash_message_success','Вы успешно зарегестрированы, вам отправлено письмо об успешной регистрации и письмо для подтверждения электронной почты, пожалуйста авторизуйтесь и подтвердите почту!');
            $flash_message_success = session('flash_message_success');
            return redirect('login');
        }
          
        if(!$user){
            $this->request->session()->flash('flash_message_danger','Данные не сохранились, попробуйте еще раз, приносим извинения.');
            $flash_message_danger = session('flash_message_danger');
            return view('register', ['flash_message_danger' => $flash_message_danger]);
        }
    }




    //переход на страницу входа в учетную запись
    public function login(){
        //запись флеш-сообщения в переменную
        $flash_message_success = session('flash_message_success');
        return view('login', ['flash_message_success' => $flash_message_success]);
    }



    //Вход в учетную запись
    public function loginUser(){
        //валидация почты и пароля
        $this->request->validate(
            [   
                'email' => 'required|email:rfc|max:100',
                'password' => 'required|min:6|max:16']
        );

        $credentials = $this->request->only('email', 'password');

        if($this->request->rememberme == "on"){
            $remember = true;
        } else {
            $remember = false;
        }

        if (Auth::attempt($credentials, $remember) ) {
            $this->request->session()->regenerate();
            $this->request->session()->flash('flash_message_success','Вы успешно авторизованы!');
            return redirect()->intended('/');
        }

        if (Auth::attempt($credentials, $remember)) {
            $this->request->session()->regenerate();
            $this->request->session()->flash('flash_message_success','Вы успешно авторизованы!');
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'danger' => 'The provided credentials do not match our records.',   
        ]);
    }



    //форма повторного подтверждения пароля при выполнении важного действия
    public function confirmPassword($id,$comment){
       // $this->middleware('auth')->name('password.confirm');
        $this->request->session()->flash('flash_message_success','Пожалуйста подтвердите пароль');
        $flash_message_success = session('flash_message_success');
    
        return view('confirm-password', ['flash_message_success' => $flash_message_success, 'id' => $id, 'comment' => $comment]);
    }



    //выполнение повтроного подтверждения пароля
    public function confirmPasswordUser($id,$comment){
   
        if (! Hash::check($this->request->password, $this->request->user()->password)) {
            return back()->withErrors([
                'password' => ['The provided password does not match our records.']
            ]);
        }
    
        $this->request->session()->passwordConfirmed();
    
        return redirect()->intended('/'.$comment.'/'.$id.'');
    }




    //выход пользователя из системы
    public function logout()
    {
    Auth::logout();

        $this->request->session()->invalidate();

        $this->request->session()->regenerateToken();

        $this->request->session()->flash('flash_message_success','Вы успешно вышли из системы');

        return redirect('/');
    }


    //форма авторизации пользователя
    public function security($id){

        //проверка наличия id
        if(!$id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/');
        }
        //проверка  прав действий, админ или собственный профиль пользователя
       if (!(auth()->user()->admin) && !(auth()->user()->id == $id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/');
        }

        $this->request->session()->flash('flash_message_success','Вы на странице смены данных безопасности');
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = null;

        //проверка наличия id
        if(!$id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/');
        }

        //получение данных пользователя 
        $user = User::find($id)->get();
        
        return view('security', ['user' => $user[0], 'id' => $id, 'flash_message_success' => $flash_message_success, 'flash_message_danger' => $flash_message_danger]);
    }



    //изменение данных безопасности пользователя
    public function securityUser($id){
        
        //проверка наличия id
        if(!$id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/');
        }
        //проверка  прав действий, админ или собственный профиль пользователя
       if (!(auth()->user()->admin) && !(auth()->user()->id == $id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/');
        }

        //текущий  email
        $email = User::find($id)->email;
        
        if($email != $this->request->new_email && $this->request->new_email != null){
            //если почта изменена проводим ее валидацию
            $this->request->validate([
                'email' => 'required|email:rfc|unique:users|min:6']);
            }
        
            $this->request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);
        
        if($this->request->new_email == null){
            $result_security = User::where('id', $id)
            ->update(['password' => Hash::make($this->request->password)]);
        }
        else{
            $result_security = User::where('id', $id)
              ->update(['email' => $this->request->new_email, 'password' => Hash::make($this->request->password)]);

        }

        if($result_security !=true){
            $this->request->session()->flash('flash_message_danger','Данные не сохранились, попробуйте еще раз, приносим извинения.');
            $flash_message_danger = session('flash_message_danger');
            return view('security', ['flash_message_danger' => $flash_message_danger]);
        }

        $this->request->session()->flash('flash_message_success','Вы успешно изменили данные безопасности!');
        return redirect()->intended('/');       
        } 
       
}
