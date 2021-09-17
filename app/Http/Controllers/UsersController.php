<?php

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\Post;
use App\Models\User;
use App\Models\Image;

use App\Models\Social;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\DocBlock\Tag;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UsersController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $request;


    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }




    //главная страница - вывода на экран пользователей
    public function home(){
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        $users = DB::table('users')
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->join('socials', 'users.id', '=', 'socials.user_id')
            ->paginate(5);

           // dd($users);
            return view('users', ['users' => $users, 'flash_message_success' => $flash_message_success, 'flash_message_danger' => $flash_message_danger]);
    }




    //страница профиля пользователя
    public function user_profile($id){
        if(!$id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/');
        }
        $user = DB::table('users')
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->join('socials', 'users.id', '=', 'socials.user_id')
            ->where('users.id', $id)
            ->get()->toArray();
            if($user) {
                return view('user_profile', ['user' => $user[0]]);
            }
            else{
                $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
                return redirect('/');
            }   
            
    }

    


    //форма создания нового пользователя
    public function create(){
        return view('create_user');
    }



    //запись введенных в форму данных нового пользователя в БД
    public function createUser(){
  
        //проверка что действия совершает админ
       if (!(auth()->user()->admin)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('home');
        }

        //валидация переданных данных   
        $this->request->validate(
            [   'name'  => 'required|min:4|max:30',
                'email' => 'required|email:rfc|unique:users|max:255',
                'password' => 'required|min:6|max:18',
                'occupation'  => 'required|min:3|max:30',
                'location' => 'required|min:3|max:30',
                'position' => 'required|min:3|max:30',
                'phone' => 'required|min:3|max:12',
                'avatar' =>  'required|image',
                'telegram' => 'required|min:3',
                'instagram' => 'required|min:3',
                'vk' => 'required|min:3'
            ]);

            //добавление данных нового пользователя в теблицы БД
            $statuses = [
                'Онлайн' => 0,
                'Не беспокоить' => 1,
                'Отошел' => 2
            ];
            $status=$statuses[$this->request->status];
            
            $id = DB::table('users')->insertGetId([
                'name' => $this->request->name,
                'email' => $this->request->email,
                'password' => Hash::make($this->request->password)
            ]);
            
            DB::table('infos')->insert([
                'occupation' => $this->request->occupation,
                'location' => $this->request->location,
                'position' => $this->request->position,
                'phone' => $this->request->phone,
                'status' => $status,
                'avatar' => $this->request->file('avatar')->store('uploads'),
                'user_id' => $id,
                'infosable_id' =>$id
            ]);
            
            DB::table('socials')->insert([
                'telegram' => $this->request->telegram,
                'instagram' => $this->request->instagram,
                'vk' => $this->request->vk,
                'user_id' => $id
            ]);

            //сообщение об успешной регистрации нового пользователя
            $this->request->session()->flash('flash_message_success','Вы успешно добавили, нового пользователя!');
            return redirect('/');
            
    }



    //форма редактирования информации пользователя 
    public function edit($id){
        //проверка наличия id
        if(!$id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/');
        }
        //проверка  прав действий, админ или собственный профиль пользователя
       if (!(auth()->user()->admin) &&  !(auth()->user()->id == $id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/');
        }
        
        $user = DB::table('users')
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->join('socials', 'users.id', '=', 'socials.user_id')
            ->where('users.id', $id)
            ->get()->toArray();

        if(!$user){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/');
        }
        return view('edit', ['user' => $user[0]]);
    }


    //запись в БД отредактированной информации о пользователе
    public function editUser($id){

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

        //валидация переданных данных   
        $this->request->validate(
            [   'name'  => 'required|min:4|max:30',
                'occupation'  => 'required|min:3|max:30',
                'location' => 'required|min:3|max:30',
                'phone' => 'required|min:3|max:16'
            ]);
            
//dd($this->request->name);
            //запись новых данных в БД
            $afacted = DB::table('users')
              ->where('id', $id)
              ->update(['name' => $this->request->name]);
//dd($afacted);
            DB::table('infos')
              ->where('user_id', $id)
              ->update([
                  'occupation' => $this->request->occupation,
                  'phone' => $this->request->phone,
                  'location' => $this->request->location,
                ]);

        $this->request->session()->flash('flash_message_success','Вы успешно изменили информацию!');
        return redirect('/');
    }



    //форма вывода статуса пользователя 
    public function status($id){

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

        $user = DB::table('users')
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->where('users.id', $id)
            ->get()->toArray();

            $statuses = [
                0 => 'Онлайн',
                1 => 'Не беспокоить',
                2 => 'Отошел'
            ];

        return view('status', ['user' => $user[0], 'statuses' => $statuses]);
    }



    //запись нового статуса пользователя
    public function statusUser($id){

        $statuses = [
            'Онлайн' => 0,
            'Не беспокоить' => 1,
            'Отошел' => 2
        ];
        $status_user = $statuses[$this->request->status];

        DB::table('infos')
              ->where('id', $id)
              ->update([
                  'status' => $status_user
                ]);

        $this->request->session()->flash('flash_message_success','Вы успешно изменили статус пользователя!');
        return redirect('/');
    }



    //форма загрузки аватара пользователя
    public function media($id){

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

        $user = DB::table('users')
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->where('users.id', $id)
            ->get()->toArray();
            //dd($user[0]->id);
        return view('media', ['user' => $user[0]]);

    }



    //запись аватара пользователя
    public function mediaUser($id){

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

            DB::table('infos')
              ->where('id', $id)
              ->update([
                'avatar' => $this->request->file('avatar')->store('uploads') 
                ]);

        $this->request->session()->flash('flash_message_success','Вы успешно заменили аватар!');
        return redirect('/');
    }


    

    public function deleteUser($id){
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

        //удаление аватара
        $imageName = DB::table('infos')
        ->where('user_id', $id)
        ->select('*')
        ->first()
        ->avatar;
        
        Storage::delete($imageName); 
        
        //удаление данных пользователя из таблиц БД
        DB::table('users')->where('id', $id)->delete();
        DB::table('infos')->where('user_id', $id)->delete();
        DB::table('socials')->where('user_id', $id)->delete();

        //удаление сессий авторизации
        if(auth()->user()->id == $id){
            $this->request->session()->invalidate();
            $this->request->session()->regenerateToken();
        }
        
        $this->request->session()->flash('flash_message_success','Вы успешно удалили профиль пользователя!');
        return redirect()->intended();
    }



    //поиск профиля пользователя
    public function search(){
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        $key = trim($this->request->get('filter_contacts'));

        $users = DB::table('users')->select()
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->join('socials', 'users.id', '=', 'socials.user_id')
            ->where('name', 'like', "%{$key}%")
            ->orWhere('email', 'like', "%{$key}%")
            ->paginate(3);

        return view('search', [
            'users' => $users,
            'flash_message_success' => $flash_message_success,
            'flash_message_danger' => $flash_message_danger,
        ]);
    }



    //форрмы изменения статуса-роли пользователя
    public function statusShow($id){

        //проверка наличия id
        if(!$id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/');
        }
        //проверка  прав действий, только админ
       if (!(auth()->user()->admin)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/');
        }

        $user = DB::table('users')
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->where('users.id', $id)
            ->get()->toArray();

            $statuses_admin = [
                0 => 'Пользователь',
                1 => 'Админ'
            ];

        return view('statusAdmin', ['user' => $user[0], 'statuses_admin' => $statuses_admin]);
    }



    //запись статуса-роли пользователя в БД
    public function statusAdmin($id){

        $statuses_admin = [
            'Пользователь' => 0,
            'Админ' => 1
        ];
        $status_admin = $statuses_admin[$this->request->admin_status];
        DB::table('users')
              ->where('id', $id)
              ->update([
                  'admin' => $status_admin
                ]);

        $this->request->session()->flash('flash_message_success','Вы успешно изменили роль пользователя!');
        return redirect('/');

    }   
}
