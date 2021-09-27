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
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostsController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $request;

    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    
    
    public function posts(){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');

        $posts = Post::get();
        
        $navigate = [
            'myPosts' => 0,
            'favorites' => 0,
            'postsAll' => 1,
            'searchPosts' => 0];

        return view('posts',
         ['posts' => $posts,
          'flash_message_success' => $flash_message_success, 
          'flash_message_danger' => $flash_message_danger,
          'navigate' => $navigate,]);
    }


    //вывод поста пользователя
    public function post($post_id){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
    
        //получение информации для вывода поста
        $post=Post::find($post_id);
       
        return view('post', [
            'post' => $post,
            'flash_message_success' => $flash_message_success,
            'flash_message_danger' => $flash_message_danger    
            ]);
    }


    public function addNewComment($post_id){
        //запись комментария к посту
        if($this->request->comment){
            $comment = new Comment(['comment' => $this->request->comment,
                                    'user_id' => $this->request->user_id,
                                    'post_id' => $post_id]);

            $post = Post::find($post_id);
            $post->comments()->save($comment); 
        }
        return redirect('/post/'.$post_id.'');
    }



    //вывод формы ввода данных для  добавления нового поста
    public function addPost(){
    
        //проверка  прав действий, админ или собственный профиль пользователя
        if (!(auth()->user()->admin) && !(auth()->check())){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/posts');
        }

        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        return view('addPost', ['flash_message_success' => $flash_message_success, 'flash_message_danger' => $flash_message_danger]);
    }



    //запись данных нового поста
    public function addNewPost($user_id){

        //проверка  прав действий, админ или собственный профиль пользователя
       if (!(auth()->user()->admin) && auth()->user()->id != $user_id){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/posts');
        }
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');       
        
        //валидация переданных данных   
        $this->request->validate(
            [   'name_post'  => 'required|min:4|max:30',
                'title_post'  => 'required|min:10|max:60',
                'text'  => 'required|min:6|max:300',
                'avatar_post' =>  'required|image',
            ]);

        //запись в таблицу пост в БД с получением id нового поста
        $user = User::find($user_id);
        $new_post = Post::create([
            'user_id' => $user_id,    
            'text' => $this->request->text,
            'avatar_post' => $this->request->file('avatar_post')->store('uploads'),
            'name_post' => $this->request->name_post,
            'title_post' => $this->request->title_post,
            'postable_id' => $user_id,
            'info_id' => $user->info->id,
            'social_id' => $user->social->id,
        ]);
        $post = Post::find($new_post->id);
        $post->post_id = $new_post->id;
        $post->save();    

        //запись картинки в таблицу картинок в БД
        if($this->request->file('image_post')){

            //валидация переданных данных   
             $this->request->validate(
            [   
                'image_post' =>  'required|image',
            ]);

            $image = new Image([
            'user_id' => $user_id,
            'post_id' => $new_post->id,
            'image' => $this->request->file('image_post')->store('uploads'),
            'imageable_id' => $new_post->id,
            'imageable_type' => 'App\Models\Post',
            ]);
            $image->save();
        }
        

        //сообщение об успешном добавлении нового поста
        $this->request->session()->flash('flash_message_success','Вы успешно добавили, новый пост!');
        return redirect('/post/'.$new_post->id.'');
    }



    //форма редактирования поста
    public function editPost($post_id){

        //проверка наличия post_id  поста
        if(!$post_id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/posts');
        }

        $user_id=Post::find($post_id)->user_id;//получение id пользователя владельца поста
        //проверка  прав действий, админ или собственный профиль пользователя
        if (!(auth()->user()->admin) && auth()->user()->id != $user_id){
            //dd(auth()->user()->id);
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/posts');
        }

        //запись в переменную флеш сообщений
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');

        //запросы текущей информации поста для вывода в форму изменения данных
        $post=Post::find($post_id);
        
        return view('editPost', [
            'post' => $post,
            'flash_message_success' => $flash_message_success,
            'flash_message_danger' => $flash_message_danger
            ]);   
        }


        //запись новой информации в пост
        public function editInsertPost($post_id){
            
                //валидация переданных данных   
                $this->request->validate(
                [   'name_post'  => 'required|min:4|max:30',
                    'title_post'  => 'required|min:10|max:60',
                    'text'  => 'required|min:6|max:300',
                ]);
           
                Post::create([
                'name_post' => $this->request->name_post,
                'title_post' => $this->request->title_post,
                'text' => $this->request->text 
                ]);
    
                return redirect('/post/'.$post_id.'');
            
        }
        
        //добавление картинки в галерею поста
        public function downloadImage($post_id,$user_id){
            
            //валидация переданных данных   
            $this->request->validate(
            [   
                'image' =>  'required|image',
            ]);
            //сохранение новой фотографии в галерею  поста
            $image = new Image(['image' => $this->request->file('image')->store('uploads'),
            'user_id' => $user_id,
            'post_id' => $post_id]);
            $post = Post::find($post_id);
            $post->images()->save($image); 
            
            //сообщение об изменении картинки поста
            $this->request->session()->flash('flash_message_success','Вы успешно изменили содержимое галереи!');
            $flash_message_success = session('flash_message_success');
            return redirect('/editPost/'.$post_id.'');
        
        }

        //обновление  аватара поста
        public function changeAvatar($post_id){
            
            //валидация переданных данных   
            $this->request->validate(
            [   
                'avatar_post' =>  'required|image',
            ]);

            //удаление текущего аватара поста из папки хранения
            $avatarPost=Post::find($post_id)->avatar;
            if($avatarPost != 'img/demo/avatars/admin-g.png'){
                Storage::delete($avatarPost);
            }
            
            //обновление ссылки на ваватар в БД
            Post::where('id', $post_id)
            ->update([
            'avatar_post' => $this->request->file('avatar_post')->store('uploads') 
            ]);

            //флеш сообщение об изменении аватара
            $this->request->session()->flash('flash_message_success','Вы успешно изменили аватар!');
            $flash_message_success = session('flash_message_success');
            return redirect('/editPost/'.$post_id.'');
        }
       
        


    //удаление картинки в галерее поста
    public function delete_image($image_id,$post_id){

        //проверка наличия post_id  поста
        if(!$post_id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/post/'.$post_id.'');
        }
        $user_id=Post::find($post_id)->user_id;//получение id пользователя владельца поста
        //проверка  прав действий, админ или собственный профиль пользователя
        if (!(auth()->user()->admin) && !(auth()->user()->id == $user_id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/post/'.$post_id.'');
        }
        
            //удаление картинки в папке проекта
            $imageNamePost=Image::find($image_id)->image;
            Storage::delete($imageNamePost);

            //удаление ссылки на картинку в БД
            Image::where('id', $image_id)->delete();

            //флеш сообщение об удалении фотографии
            $this->request->session()->flash('flash_message_success','Вы успешно удалили фотографию!');
            return redirect('/editPost/'.$post_id.'');

    }



    //удаление поста
    public function deletePost($post_id){

        //проверка наличия post_id  поста
        if(!$post_id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/posts');
        }
        $user_id=Post::find($post_id)->user_id;//получение id пользователя владельца поста
        //проверка  прав действий, админ или собственный профиль пользователя
       if (!(auth()->user()->admin) && auth()->user()->id != $user_id){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/posts');
        }

        //удаление аватара поста из папки хранения
        $avatarPost=Post::find($post_id)->avatar;
        Storage::delete($avatarPost);
        
        //удаление данных пользователя из таблиц БД
        Post::where('id', $post_id)->delete();
        Image::where('post_id', $post_id)->delete();
        Comment::where('commentable_id', $post_id)->delete();

        $this->request->session()->flash('flash_message_success','Вы успешно удалили пост!');
        return redirect('/posts');
    }



    //поиск поста
    public function searchPosts(){
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        $key = trim($this->request->get('filter_contacts'));
        
        $posts=Post::where('name_post', 'like', "%{$key}%")->get();
        
        $navigate = [
            'myPosts' => 0,
            'favorites' => 0,
            'postsAll' => 0,
            'searchPosts' => 1];

        return view('posts', 
        ['posts' => $posts,
         'flash_message_success' => $flash_message_success, 
         'flash_message_danger' => $flash_message_danger, 
         'navigate' => $navigate,
         ]);
    }



    //вывод всех избранных постов
    public function favoritesPosts(){
        //проверка  прав действий, админ или собственный профиль пользователя
        if (!(auth()->user()->admin) && !(auth()->check())){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/posts');
        }

        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');

        $posts = Post::where('favorites', 1)->get();
            
        $navigate = [
            'myPosts' => 0,
            'favorites' => 1,
            'postsAll' => 0,
            'searchPosts' => 0];

        
        return view('posts',
         [  'posts' => $posts,
            'flash_message_success' => $flash_message_success,
            'flash_message_danger' => $flash_message_danger,
            'navigate' => $navigate]);
    }


    //вывод всех постов где пользователь является их автором
    public function myPosts(){
        //проверка  прав действий, админ или собственный профиль пользователя
        if (!(auth()->user()->admin) && !(auth()->check())){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/posts');
        }
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');

        $posts = Post::where( 'postable_id', auth()->user()->id)->get();

        $navigate = [
            'myPosts' => 1,
            'favorites' => 0,
            'postsAll' => 0,
            'searchPosts' => 0];

        
        return view('posts', 
        ['posts' => $posts,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         'navigate' => $navigate]);
    }



    //добавление поста в избранные
    public function addFavorites($post_id){
        Post::where('id', $post_id)
        ->update([
        'favorites' => 1,
        ]);
        return redirect('/posts');  
    }



    //удаление поста из избранных
    public function deleteFavorites($post_id){
        
        Post::where('id', $post_id)
        ->update([
        'favorites' => 0,
        ]);
        return redirect('/posts');  
    }



    //блокировка поста
    public function bannedPost($post_id){
        //dd($post_id);
        Post::where('id', $post_id)
        ->update([
        'banned' => 1,
        ]);
        return redirect('/posts');  
    }



    ///разблокирование поста
    public function unBannedPost($post_id){
        
        Post::where('id', $post_id)
        ->update([
        'banned' => 0,
        ]);
        return redirect('/posts');  
    }



    //разблокировка комментария к посту
    public function unBannedComment($comment_id, $post_id){
        
        Comment::where('id', $comment_id)
        ->update([
        'banned' => 0,
        ]);
        return redirect('/post/'.$post_id.'');  
    }



    //блокировка поста
    public function bannedComment($comment_id, $post_id){
        
        Comment::where('id', $comment_id)
        ->update([
        'banned' => 1,
        ]);
        return redirect('/post/'.$post_id.'');  
    }



    //удаление комментария
    public function deleteComment($comment_id, $post_id){

        //проверка  прав действий, админ или собственный профиль пользователя
        if (!(auth()->user()->admin) && !(auth()->check())){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/posts');
        }
        Comment::where('id', $comment_id)->delete();
        return redirect('/post/'.$post_id.'');  
    }
    
    
    //открытие картинки из поста
    public function imagePostShow($image_id){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        $image = Image::find($image_id);
        
        $navigate = [
            'myPosts' => 0,
            'favorites' => 0,
            'postsAll' => 0,
            'searchPosts' => 0];


        return view('imagePostShow',
         ['image' => $image,
          //'post' => $post,
          'flash_message_success' => $flash_message_success, 
          'flash_message_danger' => $flash_message_danger,
          'navigate' => $navigate]);
    }
}
