<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Info;
use App\Models\User;
use App\Models\Message;
use App\Models\Userlist;
use Database\Factories\ChatFactory;
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

    
    

    //страница всех чатов  где участвует пользователь
    public function chats(){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        //вывод всех чатов для пользователя с правами Админ
        if(auth::check() &&  auth()->user()->admin){
            $chats = Chat::all();
        }

        //вывод чатов где участвует авторизованный пользователь
        if(auth::check() && !(auth()->user()->admin)){
            $chats = Chat::get();
            $userlists = Userlist::where('user_id', auth()->user()->id)->get();

            $user_list=[];
            foreach($userlists as $userlist){
                $user_list[] = $userlist->chat_id;
            }    
            $chats = $chats->intersect(Chat::whereIn('id', $user_list)->get());  
        }

        $navigate = [
            'myChats' => 0,
            'favorites' => 0,
            'chatsAll' => 1,
            'searchChats' => 0];

        return view('chats', 
        [
         'chats' => $chats,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         'navigate' => $navigate,]);
    }



    //вывод на страницу чатов со статусом избранные 
    public function chatsFavorites(){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        //вывод избранных чатов для пользователя с правами Админ
        if(auth::check() &&  auth()->user()->admin){
            $chats = Chat::where('favorites', 1)->get();
        }
        
        //вывод избранных чатов где участвует авторизованный пользователь
        if(auth::check() && !(auth()->user()->admin)){
            $chats = Chat::get();
            $userlists = Userlist::where('user_id', auth()->user()->id)
            ->get();

            $user_list=[];
            foreach($userlists as $userlist){
                $user_list[] = $userlist->chat_id;
            }    
            $chats = $chats->intersect(Chat::whereIn('id', $user_list)->where('favorites', 1)->get()); 
            
        }
        
        $navigate = [
            'myChats' => 0,
            'favorites' => 1,
            'chatsAll' => 0,
            'searchChats' => 0];


        return view('chats', 
        [
         'chats' => $chats,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         'navigate' => $navigate, ]);
    }




    //вывод на страницу  чатов, которые создал автор (админ)
    public function chatsMy(){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
   
        $chats = Chat::where('author_user_id', auth()->user()->id)->get();
       
        $navigate = [
            'myChats' => 1,
            'favorites' => 0,
            'chatsAll' => 0,
            'searchChats' => 0];

        return view('chats', 
        [
         'chats' => $chats,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         'navigate' => $navigate, ]);
    }

    //поиск чатов 
    public function searchChats(){
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        $key = trim($this->request->get('filter_contacts'));
        $chats = Chat::where('name', 'like', "%{$key}%")->get();
            
        $navigate = [
            'myChats' => 0,
            'favorites' => 0,
            'chatsAll' => 0,
            'searchChats' => 1];

            return view('chats', 
            [
             'chats' => $chats,
             'flash_message_success' => $flash_message_success,
             'flash_message_danger' => $flash_message_danger,
             'navigate' => $navigate,  ]);
    }

    //страница вывода форма создания нового чата 
    public function addChatShow(){
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        $users = User::get();
          
        $navigate = [
                'myChats' => 0,
                'favorites' => 0,
                'chatsAll' =>0,
                'searchChats' => 0];

        return view('addChatShow', [
            
            'users' => $users,
            
             'flash_message_success' => $flash_message_success,
             'flash_message_danger' => $flash_message_danger,
             'navigate' => $navigate, 
             ]);
    }

    //добавление в БД нового чата
    public function addChat($author_user_id){

        //валидация введенного текста
        $this->request->validate(
            [   'name_chat'  => 'required|min:6|max:30'    
            ]);

        //получение массива id всех выбранных пользователей
        $arrey_user_new_chat = $this->listsIdUserInChat();

        //проверка добавления в чат пользователей, если никто не добвален вернуть пользователя назад и вывести сообщение
        if(count($arrey_user_new_chat)==0){
            $this->request->session()->flash('flash_message_danger','Вы  не выбрали ни одного участника нового чата!');
            return redirect('/addChatShow');
        }
        
        
        //запись в таблицу чатов данных нового чата
        $id_new_chat = DB::table('chats')->insertGetId([
            'name' => $this->request->name_chat,
            'name_chat' => $this->request->name_chat,
            'author_user_id' => $author_user_id,
            'user_id' => $author_user_id,
            'location' => User::find(auth()->user()->id)->info->location,
        ]);

        //запись аватара в таблицу чатов  если он добавлен в форму
        if($this->request->file('avatar_chat')){
            Chat::where('id', $id_new_chat)->update([
                'chat_avatar' => $this->request->file('avatar_chat')->store('uploads')   
        ]);
        }
        
        //формирование массива данных о пользователях и чате в котором они участвуют, для групповой записи в таблицу ''userlists'
        $arrey_db = [];
            foreach($arrey_user_new_chat as $user_id)
        {
            $arrey_db[] = ['user_id' => $user_id, 'info_id' => $author_user_id, 'chat_id' => $id_new_chat,'userlistable_id' => $id_new_chat, 'userlistable_type' => 'App\Models\Chat','name' => $this->request->name_chat, 'role' => 'participant'];
        }
        
        //запись в таблицу 'userlusts' информации о чате и  о пользователях и авторе участвующих в чате
        DB::table('userlists')->insert($arrey_db);
        Userlist::create(['user_id' => $author_user_id, 'info_id' => $author_user_id, 'chat_id' => $id_new_chat,'userlistable_id' => $id_new_chat, 'userlistable_type' => 'App\Models\Chat','name' => $this->request->name_chat, 'role' => 'author']);
         
        $this->request->session()->flash('flash_message_success','Вы успешно создали новый чат!');
        return redirect('/openChat/'.$id_new_chat.'');
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
       if (!(auth()->user()->admin) && !(Userlist::where([
                                                            ['user_id', '=', auth()->user()->id],
                                                            ['chat_id', '=', $chat_id],
                                                          ])->get()==true))
        {
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/chats');
        }

        $chat = Chat::find($chat_id); 

        return view('openChat', 
        [
         'chat' => $chat,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         ]);
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
         'user_id' => auth()->user()->id,
         'chat_id' => $chat->id,
         'info_id' => $chat->user_id,
         'messageable_type' => 'App\Models\Chat',
         'messageable_id' => $chat->id,
         ]);
        $chat->messages()->save($message);
        
        return redirect('/openChat/'.$chat->id.'');
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
        Message::find($message_id)->delete();
        
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
        //проверка  прав действий, админ или автор чата
       if (!(auth()->user()->admin) && !(Chat::find($chat_id)->author_user_id == auth()->user()->id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/openChat/'.$chat_id.'');
        }
            
        $chat = Chat::find($chat_id);
        $users = User::get();

        //список пользователей не являющихся участниками чата
        $listNotChatUser=[];
        foreach($chat->userlists as $userIdList){
            $listNotChatUser[] = $userIdList->user_id;
        }
        $usersNotChat = $users->diff(User::whereIn('id', $listNotChatUser)->get());

        return view('editChatShow', [
            'usersNotChat' => $usersNotChat,
            'chat' => $chat,
            'flash_message_success' => $flash_message_success,
            'flash_message_danger' => $flash_message_danger,
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
            Chat::find($chat_id)->update([
            'name' => $this->request->name_chat,
            'name_chat' => $this->request->name_chat,
            
        ]);

        //обновление аватара
        if($this->request->file('avatar_chat')){
            Chat::where('id', $chat_id)->update([
            'chat_avatar' => $this->request->file('avatar_chat')->store('uploads'),
        ]);
        }
        
        //формирование массива данных  новых добавленных пользователей и чате в котором они участвуют, для групповой записи в таблицу ''userlists'
        $arrey_db = [];
            foreach($arrey_user_new_chat as $user_id)
        {
            $arrey_db[] = ['user_id' => $user_id, 'chat_id' => $chat_id, 'info_id' => $user_id, 'userlistable_id' => $chat_id, 'userlistable_type' => 'App\Models\Chat','name' => $this->request->name_chat, 'role' => 'participant'];
        }
        
        //запись в таблицу 'userlusts' информации о чате и  о пользователях и авторе участвующих в чате
        DB::table('userlists')->insert($arrey_db); 
        
        $this->request->session()->flash('flash_message_success','Вы успешно отредкатировали чат!');
        return redirect('/openChat/'.$chat_id.'');
    }


    public function deleteAvatarStorage($chat_id){
        
        $avatarChat=Chat::find($chat_id)->chat_avatar;
        if($avatarChat != 'img/demo/avatars/admin-g.png'){
            Storage::delete($avatarChat);
        }   
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

        //защита от удаления всех участников чата
        $listUserInChat = Userlist::where('chat_id', $chat_id)->get();
        
        if($listUserInChat->count() == 1){
            $this->request->session()->flash('flash_message_danger','Вы не можете удалить всех участников чата, уменьшите список для удаления!');
            return redirect('/editChatShow/'.$chat_id.'');
        }
        
        Userlist::where('user_id',$user_id)->delete();
        Message::where('user_id',$user_id)->delete();

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
        Chat::find($chat_id)->delete();
        Userlist::where('chat_id',$chat_id)->delete();
        Message::where('chat_id',$chat_id)->delete();

        $this->request->session()->flash('flash_message_success','Вы успешно удалили чат!');
        return redirect('/chats');
    }


    public function onFavorites($chat_id){
        //проверка наличия id и наличия данных с запрошенным id
        if(!$chat_id || Chat::find($chat_id)!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/chats');
        }

        //проверка  прав действий, админ или участник чата
       if (!(auth()->user()->admin) && !(Userlist::where('chat_id',$chat_id)->where('user_id', auth()->user()->id) == true)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/chats');
        }

        //добавление чата в избранные 
        Chat::find($chat_id)->update([
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

        //проверка  прав действий, админ или участник чата
       if (!(auth()->user()->admin) && !(Userlist::where('chat_id',$chat_id)->where('user_id', auth()->user()->id) == true)){
        $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
        return redirect('/chats');
    }
        //удаление чата из избранных 
        Chat::find($chat_id)->update([
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
        Chat::find($chat_id)->update([
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
        Chat::find($chat_id)->update([
            'banned' => 0   
        ]);

        $this->request->session()->flash('flash_message_success','Вы успешно разблокировали чат!');
        return redirect('/chats');
    }



    public function roleModerator($user_id, $chat_id){

        //проверка наличия id и наличия данных с запрошенным id
        if(!$user_id || Userlist::where('user_id',$user_id)->where('chat_id', $chat_id)->get()!=true ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/editChatShow/'.$chat_id.'');
        }

        //проверка  прав действий, админ или автор сообщения
        //dd(Chat::where('author_user_id', auth()->user()->id)->first()->role);
       if (!(auth()->user()->admin) && Chat::where('author_user_id', auth()->user()->id)->first()->role != 'author'){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/editChatShow/'.$chat_id.'');
        }

        //запись роли модератор 
        Userlist::where('user_id', '=', $user_id)->where('chat_id', '=', $chat_id)->update([
            'role' => 'moderator'   
        ]);   
        $this->request->session()->flash('flash_message_success','Вы успешно изменили роль пользователя!');
        return redirect('/editChatShow/'.$chat_id.'');
    }


    public function roleParticipant($user_id, $chat_id){
        //проверка наличия id и наличия данных с запрошенным id
        if(!$user_id || Userlist::where('user_id',$user_id)->where('chat_id', $chat_id)->get()!=true  ){
            $this->request->session()->flash('flash_message_danger','Такого чата нет!!!');
            return redirect('/editChatShow/'.$chat_id.'');
        }

        //проверка  прав действий, админ или автор сообщения
       if (!(auth()->user()->admin) && Chat::where('author_user_id', auth()->user()->id)->first()->role != 'author'){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/editChatShow/'.$chat_id.'');
        }

        //запись роли пользователь 
        Userlist::where('user_id', $user_id)->where('chat_id', '=', $chat_id)->update([
            'role' => 'participant'   
        ]);
           
        $this->request->session()->flash('flash_message_success','Вы успешно изменили роль пользователя!');
        return redirect('/editChatShow/'.$chat_id.'');
    }

}
