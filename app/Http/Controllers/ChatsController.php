<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Info;
use App\Models\User;
use App\Models\Message;
use App\Models\Userlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChatsController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    private $request;

    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    
    

    //страница всех где участвует пользователь
    public function chats(){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        //проверка  прав действий, админ или авторизованный пользователь
       if (!(auth()->user()->admin) && !(Auth::check())){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/');
        }
        
        if(auth::check() &&  auth()->user()->admin){
            $chats = DB::table('chats') ->get();
        }

        if(auth::check() && !(auth()->user()->admin)){
            $chats = Chat::get();
            $users= User::get();
            $userlists = Userlist::where('user_id', auth()->user()->id)->get();

            $user_list=[];
            foreach($userlists as $userlist){
                $user_list[] = $userlist->chat_id;
            }
            
            $chats = $chats->intersect(Chat::whereIn('id', $user_list)->get());   
        }
        
        
        $myChats = 0;
        $favorites = 0;
        $chatsAll = 1;
        $searchChats = 0;

        return view('chats', 
        [
         'chats' => $chats,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         'myChats' => $myChats, 
         'favorites' => $favorites,
         'chatsAll' => $chatsAll,
         'searchChats' => $searchChats, ]);
    }

    //вывод на страницу чатов со статусом избранные 
    public function chatsFavorites(){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        //проверка  прав действий, админ или авторизованный пользователь
       if (!(auth()->user()->admin) && !(Auth::check())){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/chats');
        }

        $chats = DB::table('chats')->where('favorites', 1)->get();

        $myChats = 0;
        $favorites = 1;
        $chatsAll = 0;
        $searchChats = 0;

        return view('chats', 
        [
         'chats' => $chats,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         'myChats' => $myChats, 
         'favorites' => $favorites,
         'chatsAll' => $chatsAll,
         'searchChats' => $searchChats, ]);
    }




    //вывод на страницу  чатов, которые создал автор (админ)
    public function chatsMy(){
        
        //проверка  прав действий, админ или авторизованный пользователь
       if (!(auth()->user()->admin) && !(Auth::check())){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/chats');
        }

        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        $chats = DB::table('chats')->where('author_user_id', auth()->user()->id)->get();
       
        $myChats = 1;
        $favorites = 0;
        $chatsAll = 0;
        $searchChats = 0;
        return view('chats', 
        [
         'chats' => $chats,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         'myChats' => $myChats, 
         'favorites' => $favorites,
         'chatsAll' => $chatsAll,
         'searchChats' => $searchChats, ]);
    }

    //поиск чатов 
    public function searchChats(){
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        $key = trim($this->request->get('filter_contacts'));
        $chats = DB::table('chats')->select()
            ->where('name', 'like', "%{$key}%")
            ->get();
            
            $myChats = 0;
            $favorites = 0;
            $chatsAll = 0;
            $searchChats = 1;

            return view('chats', 
            [
             'chats' => $chats,
             'flash_message_success' => $flash_message_success,
             'flash_message_danger' => $flash_message_danger,
             'myChats' => $myChats, 
             'favorites' => $favorites,
             'chatsAll' => $chatsAll,
             'searchChats' => $searchChats, ]);
    }

    //страница вывода форма создания нового чата 
    public function addChatShow(){
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        //проверка  прав действий, админ или авторизованный пользователь
       if (!(auth()->user()->admin) && !(Auth::check())){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/chats');
        }

        $users = DB::table('users')
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->get();
       
        $myChats = 0;
        $favorites = 0;
        $chatsAll = 1;
        $searchChats = 0;

        return view('addChatShow', [
            
            'users' => $users,
            
             'flash_message_success' => $flash_message_success,
             'flash_message_danger' => $flash_message_danger,
             'myChats' => $myChats, 
             'favorites' => $favorites,
             'chatsAll' => $chatsAll,
             'searchChats' => $searchChats,
             ]);
    }

    //добавление в БД нового чата
    public function addChat($author_user_id){

        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(Auth::check())){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/chats');
        }
        //валидация введенного текста
        $this->request->validate(
            [   'name_chat'  => 'required|min:6|max:30'    
            ]);

        //получение id всех пользователей
        $arrey_users_id=[];
        $users = User::get(); 
        foreach($users as $user){
             $arrey_users_id[]=$user->id;
        }
        // создание массива id  выбранных пользователей 
        $arrey_user_new_chat=[];

        
            foreach($arrey_users_id as $user_id){
                 $rem = 'rememberme_'.$user_id;
                 $user = 'user_'.$user_id;
            if($this->request->$rem == 'on'){
                $arrey_user_new_chat[]=($this->request->$user);
            }
        }
        
        if(count($arrey_user_new_chat)==0){
            // флеш сообщение 
            $this->request->session()->flash('flash_message_danger','Вы  не выбрали ни одного участника нового чата!');
            return redirect('/addChatShow');
        }
        
        
        //получение  локации пользователей добавленных в чат
        $infos = User::find($author_user_id)->infos()->where('user_id', $author_user_id)->first();
        
        //запись в таблицу чатов данные нового чата
        $id_new_chat = DB::table('chats')->insertGetId([
            'name' => $this->request->name_chat,
            'name_chat' => $this->request->name_chat,
            'author_user_id' => $author_user_id,
            'user_id' => $author_user_id,
            //'chat_avatar' => $this->request->file('avatar_chat')->store('uploads'),
            'location' => $infos->location,
        ]);

        //запись аватара в таблицу чатов  если он добавлен в форму
        if($this->request->file('avatar_chat')){
            DB::table('chats')->where('id', $id_new_chat)->update([
                'chat_avatar' => $this->request->file('avatar_chat')->store('uploads')   
        ]);
        }
        
        //запись в таблицу чатов дополнительного поля  chat_id
        DB::table('chats')->where('id', $id_new_chat)->update([
            'chat_id' => $id_new_chat   
        ]);

        //формирование массива данных о пользователях и чате в котором они участвуют, для групповой записи в таблицу ''userlists'
        $arrey_db = [];
            foreach($arrey_user_new_chat as $user_id)
        {
            $arrey_db[] = ['user_id' => $user_id, 'info_id' => $author_user_id, 'chat_id' => $id_new_chat,'userlistable_id' => $user_id, 'userlistable_type' => 'App\Modules\User','name' => $this->request->name_chat, 'role' => 'participant'];
        }
        
        //запись в таблицу 'userlusts' информации о чате и  о пользователях и авторе участвующих в чате
        DB::table('userlists')->insert($arrey_db);
        DB::table('userlists')->insert(['user_id' => $author_user_id, 'info_id' => $author_user_id, 'chat_id' => $id_new_chat,'userlistable_id' => $author_user_id, 'userlistable_type' => 'App\Modules\User','name' => $this->request->name_chat, 'role' => 'author']);
         
        $this->request->session()->flash('flash_message_success','Вы успешно создали новый чат!');
        return redirect('/openChat/'.$id_new_chat.'');
    }



    //вывод  страницы избранных чатов 
    public function chatsFavoritess(){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        //проверка  прав действий, админ или автор сообщения
        if (!(auth()->user()->admin) && !(Auth::check())){
        $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
        return redirect('/chats');
    }

        $users = User::find(auth()->user()->id); 
        $chats = null;   
           

        $myChats = 0;
        $favorites = 1;
        $chatsAll = 0;
        $searchChats = 0;
        return view('chats', 
        ['users' => $users,
         'chats' => $chats,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         'myChats' => $myChats, 
         'favorites' => $favorites,
         'chatsAll' => $chatsAll,
         'searchChats' => $searchChats, ]);
    }



    //страница  чата
    public function openChat($chat_id){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
       
        //проверка наличия id и наличия данных с запрошенным id
        if(!$chat_id || Chat::find($chat_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/chats');
        }
        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(Chat::find($chat_id)->author_user_id == auth()->user()->id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/chats');
        }

        $chat = Chat::find($chat_id); 
        $info = Info::get();
        $messages = Message::get()->where('chat_id', $chat_id);
        
        $myChats = 0;
        $favorites = 0;
        $chatsAll = 1;
        $searchChats = 0;

        return view('openChat', 
        [
         'chat' => $chat,
         'messages' => $messages,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         'myChats' => $myChats, 
         'favorites' => $favorites,
         'chatsAll' => $chatsAll,
         'searchChats' => $searchChats, ]);
    }




    public function message($chat_id){

        //проверка  прав действий, админ или автор сообщения
        if (!(auth()->user()->admin) && !(Userlist::where('user_id', auth()->user()->id)->get()== true)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/openChat/'.$chat_id.'');
        }

        //валидация введенного текста
        $this->request->validate(
            [   'message'  => 'required|min:1|max:200'
                
            ]);

        $new_message=$this->request->message;
        $chat = Chat::find($chat_id);

        $message = new Message([
         'message' => $new_message,
         'user_id' => $chat->user_id,
         'chat_id' => $chat->id,
         'info_id' => $chat->user_id,
         'messageable_type' => 'App\Models\Chat',
         'messageable_id' => $chat->id,
         ]);

        
        $chat->messages()->save($message);
        return redirect('/openChat/'.$chat->chat_id.'');
    }




    public function delete_message($message_id, $user_id, $chat_id){

        //проверка наличия id и наличия сообщения с запрошенным id
        if(!$message_id || Message::find($message_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого сообщения нет!!!');
            return redirect('/openChat/'.$chat_id.'');
        }
        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(auth()->user()->id == $user_id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/openChat/'.$chat_id.'');
        }
        
        //удаление  сообщения из таблицы БД
        DB::table('messages')->where('id', $message_id)->delete();
        
        $this->request->session()->flash('flash_message_success','Вы успешно удалили сообщение!');
        return redirect('/openChat/'.$chat_id.'');
    }




    //страница вывода формы редактирования чата 
    public function editChatShow($chat_id){
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');

        //проверка наличия id и наличия данных с запрошенным id
        if(!$chat_id || Chat::find($chat_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого сообщения нет!!!');
            return redirect('/openChat/'.$chat_id.'');
        }
        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(Chat::find($chat_id)->author_user_id == auth()->user()->id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/openChat/'.$chat_id.'');
        }
        
        
        $chat = Chat::find($chat_id); 
        $users_list_all = User::get();
        $info =Info::get();

        //список записей данных пользователей которые участвуют в выбраном чате из таблицы 'userlists'
        $userlists = Userlist::get()->where('chat_id', $chat_id);

        //запись id пользователей учатсвующих в чате в массив
        $user=[];
        foreach($userlists as $userlist){
            $userss = $userlist->user_id;
            $user[]=$userss;
        }
        
        //вывод данных всех пользователей участвующих в чате
        $usersInChat = $users_list_all->intersect(User::whereIn('id', $user)->get());

        //список всех  пользователей 
        $users = DB::table('users')
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->get();
       
        //маркеры состояний для навигационной строки
        $myChats = 0;
        $favorites = 0;
        $chatsAll = 1;
        $searchChats = 0;

        return view('editChatShow', [
            
            'users' => $users,
            'chat' => $chat,
            'usersInChat' => $usersInChat,
            'userlists' => $userlists,
             'flash_message_success' => $flash_message_success,
             'flash_message_danger' => $flash_message_danger,
             'myChats' => $myChats, 
             'favorites' => $favorites,
             'chatsAll' => $chatsAll,
             'searchChats' => $searchChats,
             ]);
    }





    //редактирование чата
    public function editChat($chat_id){

        //проверка наличия id и наличия данных с запрошенным id
        if(!$chat_id || Chat::find($chat_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/openChat/'.$chat_id.'');
        }
        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(Chat::find($chat_id)->author_user_id == auth()->user()->id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/openChat/'.$chat_id.'');
        }

        //валидация названия чата
        $this->request->validate(
            [   'name_chat'  => 'required|min:6|max:30'
                
            ]);

        //получение массива добавляемых в чат пользователей
        $arrey_user_new_chat = $this->listsIdUserInChat();
        
        //удаление  аватара поста из файла
        if($this->request->file('avatar_post')){
            $this->deleteAvatarStorage($chat_id);
        }
        
        //обновление информации  чата
            DB::table('chats')->where('id', $chat_id)->update([
            'name' => $this->request->name_chat,
            'name_chat' => $this->request->name_chat,
            
        ]);

        //обновление аватара
        if($this->request->file('avatar_chat')){
            DB::table('chats')->where('id', $chat_id)->update([
            'chat_avatar' => $this->request->file('avatar_chat')->store('uploads'),
        ]);
        }
        
        //формирование массива данных о новых добавленных пользователях и чате в котором они участвуют, для групповой записи в таблицу ''userlists'
        $arrey_db = [];
            foreach($arrey_user_new_chat as $user_id)
        {
            $arrey_db[] = ['user_id' => $user_id, 'chat_id' => $chat_id, 'info_id' => $user_id, 'userlistable_id' => $user_id, 'userlistable_type' => 'App\Modules\User','name' => $this->request->name_chat, 'role' => 'participant'];
        }
        
        //запись в таблицу 'userlusts' информации о чате и  о пользователях и авторе участвующих в чате
        DB::table('userlists')->insert($arrey_db); 
        
        $this->request->session()->flash('flash_message_success','Вы успешно отредкатировали чат!');
        return redirect('/openChat/'.$chat_id.'');
    }




    public function deleteAvatarStorage($chat_id){
        
        $avatarChat=Chat::find($chat_id)->chat_avatar;
        Storage::delete($avatarChat);
    }




    public function listsIdUserInChat(){
        //получение id всех пользователей
        $arrey_users_id=[];
        $users = User::get(); 
        foreach($users as $user){
             $arrey_users_id[]=$user->id;
        }

        // создание массива id  выбранных пользователей 
        $arrey_user_new_chat=[];
            foreach($arrey_users_id as $user_id){
                 $rem = 'rememberme_'.$user_id;
                 $user = 'user_'.$user_id;
            if($this->request->$rem == 'on'){
                $arrey_user_new_chat[]=($this->request->$user);
            }
        }
        return $arrey_user_new_chat;
    }




    public function deleteUsersIsChat($user_id, $chat_id){
        
        //проверка наличия id и наличия данных с запрошенным id
        if(!$chat_id || Chat::find($chat_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого сообщения нет!!!');
            return redirect('/editChatShow/'.$chat_id.'');
        }
        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(Chat::find($chat_id)->author_user_id == auth()->user()->id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/editChatShow/'.$chat_id.'');
        }

        $listUserInChat = Userlist::where('chat_id', $chat_id)->get();
        if(count($listUserInChat) == 1){
            $this->request->session()->flash('flash_message_danger','Вы не можете удалить всех рлльзователей чата, уменьшите список для удаления!');
            return redirect('/editChatShow/'.$chat_id.'');
        }
        
        DB::table('userlists')->where('user_id',$user_id)->delete();
        DB::table('messages')->where('user_id',$user_id)->delete();

        $this->request->session()->flash('flash_message_success','Вы успешно удалили пользователя из чата!');
        return redirect('/editChatShow/'.$chat_id.'');
    }



    public function deleteChat($chat_id){
        
        //проверка наличия id и наличия данных с запрошенным id
        if(!$chat_id || Chat::find($chat_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/chats');
        }
        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(Chat::find($chat_id)->author_user_id == auth()->user()->id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/chats');
        }

        $this->deleteAvatarStorage($chat_id);
        DB::table('chats')->where('id',$chat_id)->delete();
        DB::table('userlists')->where('chat_id',$chat_id)->delete();
        DB::table('messages')->where('chat_id',$chat_id)->delete();

        $this->request->session()->flash('flash_message_success','Вы успешно удалили чат!');
        return redirect('/chats');
    }


    public function onFavorites($chat_id){
        //проверка наличия id и наличия данных с запрошенным id
        if(!$chat_id || Chat::find($chat_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/chats');
        }

        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(Chat::find($chat_id)->author_user_id == auth()->user()->id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/chats');
        }

        //добавление чата в избранные 
        DB::table('chats')->where('id', $chat_id)->update([
            'favorites' => 1   
        ]);

        $this->request->session()->flash('flash_message_success','Вы успешно добавили чат в избранные!');
        return redirect('/chats');
    }

    public function offFavorites($chat_id){
        //проверка наличия id и наличия данных с запрошенным id
        if(!$chat_id || Chat::find($chat_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/chats');
        }

        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(Chat::find($chat_id)->author_user_id == auth()->user()->id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/chats');
        }

        //удаление чата из избранных 
        DB::table('chats')->where('id', $chat_id)->update([
            'favorites' => 0   
        ]);

        $this->request->session()->flash('flash_message_success','Вы успешно удалили чат из избранных!');
        return redirect('/chats');
    }


    public function onBannedChat($chat_id){
        //проверка наличия id и наличия данных с запрошенным id
        if(!$chat_id || Chat::find($chat_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/chats');
        }

        //блокировка чата 
        DB::table('chats')->where('id', $chat_id)->update([
            'banned' => 1   
        ]);

        $this->request->session()->flash('flash_message_success','Вы успешно заблокировали чат!');
        return redirect('/chats');
    }

    public function offBannedChat($chat_id){
        //проверка наличия id и наличия данных с запрошенным id
        if(!$chat_id || Chat::find($chat_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/chats');
        }

        //блокировка чата 
        DB::table('chats')->where('id', $chat_id)->update([
            'banned' => 0   
        ]);

        $this->request->session()->flash('flash_message_success','Вы успешно разблокировали чат!');
        return redirect('/chats');
    }

    public function roleModerator($user_id, $chat_id){
        //проверка наличия id и наличия данных с запрошенным id
        if(!$user_id || Userlist::find($user_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/editChatShow/'.$chat_id.'');
        }

        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(Userlist::find($user_id)->user_id == auth()->user()->id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/editChatShow/'.$chat_id.'');
        }

        //запись роли модератор 
        DB::table('userlists')->where('user_id', '=', $user_id)->where('chat_id', '=', $chat_id)->update([
            'role' => 'moderator'   
        ]);   
        $this->request->session()->flash('flash_message_success','Вы успешно изменили роль пользователя!');
        return redirect('/editChatShow/'.$chat_id.'');
    }

    public function roleParticipant($user_id, $chat_id){
        //проверка наличия id и наличия данных с запрошенным id
        if(!$user_id || Userlist::find($user_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/editChatShow/'.$chat_id.'');
        }

        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && !(Userlist::find($user_id)->user_id == auth()->user()->id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/editChatShow/'.$chat_id.'');
        }

        //запись роли пользователь 
        DB::table('userlists')->where('user_id', $user_id)->where('chat_id', '=', $chat_id)->update([
            'role' => 'participant'   
        ]);
        $chat_id = Userlist::find($user_id)->chat_id;    
        $this->request->session()->flash('flash_message_success','Вы успешно изменили роль пользователя!');
        return redirect('/editChatShow/'.$chat_id.'');
    }

}
