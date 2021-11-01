<?php

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\User;
use App\Models\Social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        $users = User::paginate(5);
        
        return view('users', ['users' => $users, 'flash_message_success' => $flash_message_success, 'flash_message_danger' => $flash_message_danger]);
    }




    //страница профиля пользователя
    public function user_profile($id){
        if(!$id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/');
        }
        $user = User::find($id);
            if($user) {
                return view('user_profile', ['user' => $user]);
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
                'password' => Hash::make($this->request->password),
                'search' => strtolower($_POST['name'])
            ]);
            
            
            User::where('id', $id)
              ->update(['c' => 'c_'.$id]);


            $info = Info::create([
                'occupation' => $this->request->occupation,
                'location' => $this->request->location,
                'position' => $this->request->position,
                'phone' => $this->request->phone,
                'status' => $status,
                'avatar' => $this->request->file('avatar')->store('uploads'),
                'user_id' => $id,
                'infosable_id' =>$id
            ]);
            
            $social = new Social([
                'telegram' => $this->request->telegram,
                'instagram' => $this->request->instagram,
                'vk' => $this->request->vk,
                'user_id' => $id
            ]);
            $social->save(); 
            $user = User::find($id);
             
           $user = User::find($id);
           $user->info_id = $info->id;
           $user->social_id = $social->id;
           $user->save();

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
        
        $user = User::find($id);

        if(!$user){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/');
        }
        return view('edit', ['user' => $user]);
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
            
            //запись новых данных в БД
            User::where('id', $id)
              ->update(['name' => $this->request->name]);

            Info::where('user_id', $id)
              ->update([
                  'occupation' => $this->request->occupation,
                  'phone' => $this->request->phone,
                  'location' => $this->request->location,
                ]);

        $this->request->session()->flash('flash_message_success','Вы успешно изменили информацию!');
        return redirect('/profile/'.$id.'');
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

        $user = User::find($id);

            $statuses = [
                0 => 'Онлайн',
                1 => 'Не беспокоить',
                2 => 'Отошел'
            ];

        return view('status', ['user' => $user, 'statuses' => $statuses]);
    }



    //запись нового статуса пользователя
    public function statusUser($id){

        $statuses = [
            'Онлайн' => 0,
            'Не беспокоить' => 1,
            'Отошел' => 2
        ];
        $status_user = $statuses[$this->request->status];

        Info::where('id', $id)
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

        $user = User::find($id);
        return view('media', ['user' => $user]);

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
        //удаление текущего аватара
        $imageName = Info::find($id)->avatar;
        if($imageName != 'img/demo/avatars/admin-g.png'){
            Storage::delete($imageName); 
        }
        

        //запись нового аватара
            Info::where('id', $id)
              ->update([
                'avatar' => $this->request->file('avatar')->store('uploads') 
                ]);

        $this->request->session()->flash('flash_message_success','Вы успешно заменили аватар!');
        return redirect('/profile/'.$id.'');
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
        $imageName = Info::find($id)->avatar;
        Storage::delete($imageName); 
        
        
        //удаление данных пользователя из таблиц БД
        User::where('id', $id)->delete();
        Info::where('user_id', $id)->delete();
        Social::where('user_id', $id)->delete();
        
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

        $users = User::where('name', 'like', "%{$key}%")
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

        $user = User::find($id);

            $statuses_admin = [
                0 => 'Пользователь',
                1 => 'Админ'
            ];

        return view('statusAdmin', ['user' => $user, 'statuses_admin' => $statuses_admin]);
    }



    //запись статуса-роли пользователя в БД
    public function statusAdmin($id){

        $statuses_admin = [
            'Пользователь' => 0,
            'Админ' => 1
        ];
        $status_admin = $statuses_admin[$this->request->admin_status];
        User::where('id', $id)
              ->update([
                  'admin' => $status_admin
                ]);

        $this->request->session()->flash('flash_message_success','Вы успешно изменили роль пользователя!');
        return redirect('/');

    }   
}
