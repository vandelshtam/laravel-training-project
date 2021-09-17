<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Image;
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
        $posts = DB::table('posts')
            ->join('users', 'posts.id', '=', 'users.id')
            ->join('infos', 'infos.user_id', '=', 'users.id')
            ->get();

            $myPosts = 0;
            $favorites = 0;
            $postsAll = 1;
            $searchPosts = 0;

        return view('posts',
         ['posts' => $posts,
          'flash_message_success' => $flash_message_success, 
          'flash_message_danger' => $flash_message_danger,
           'myPosts' => $myPosts, 
           'favorites' => $favorites,
            'postsAll' => $postsAll,
            'searchPosts' => $searchPosts,]);
    }


    //вывод поста пользователя
    public function post($post_id){
        
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
    
        //получение информации для заполнения формы показа поста
        $post=Post::find($post_id);
        $id_user=Post::find($post_id)->user_id;
        $user=User::find($id_user);
        foreach($user->infos as $info){
            $info;
        }
        foreach($user->socials as $social){
             $social;
        }
        foreach($post->comments as $comment){
           $comment->comment;
        }
       $comment_user = Comment::find(1)->user;

        //запись комментария к посту
        if($this->request->comment){
            $comment = new Comment(['comment' => $this->request->comment,
                                    'user_id' => $this->request->user_id,
                                    'post_id' => $post_id]);

            $post = Post::find($post_id);
            $post->comments()->save($comment); 
        }

        return view('post', [
            'post' => $post,
            'social' => $social,
            'info' => $info,
            'user' => $user,
            'comment_user' => $comment_user,
            'flash_message_success' => $flash_message_success,
            'flash_message_danger' => $flash_message_danger    
            ]);
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
        
        //запись в таблицу пост в БД с получением id нового поста
        $post_id = DB::table('posts')->insertGetId([
            'user_id' => $user_id,
            'text' => $this->request->text,
            'avatar_post' => $this->request->file('avatar_post')->store('uploads'),
            'name_post' => $this->request->name_post,
            'title_post' => $this->request->title_post,
            'postable_id' => $user_id,
        ]);
        
        //запись картинки в таблицу картинок в БД
        DB::table('images')->insert([
            'user_id' => $user_id,
            'post_id' => $post_id,
            'image' => $this->request->file('image_post')->store('uploads'),
            'imageable_id' => $post_id,
            'imageable_type' => 'App\Models\Post',
        ]);

        //сообщение об успешном добавлении нового поста
        $this->request->session()->flash('flash_message_success','Вы успешно добавили, новый пост!');
        return redirect('/post/'.$post_id.'');
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
        $id_user=Post::find($post_id)->user_id;
        $user=User::find($id_user);
        $comment_user = Comment::find(1)->user;
        foreach($user->infos as $info){
            $info;
        }
        foreach($user->socials as $social){
             $social;
        }
        foreach($post->comments as $comment){
           $comment->comment;
        }
       
        //обновление  аватара поста
        if($this->request->file('avatar_post')){

            //удаление текущего аватара поста из папки хранения
            $avatarPost=Post::find($post_id)->avatar;
            Storage::delete($avatarPost);

            //обновление ссылки на ваватар в БД
            DB::table('posts')
            ->where('id', $post_id)
            ->update([
            'avatar_post' => $this->request->file('avatar_post')->store('uploads') 
            ]);

            //флеш сообщение об изменении аватара
            $this->request->session()->flash('flash_message_success','Вы успешно изменили аватар!');
            $flash_message_success = session('flash_message_success');
            return redirect('/editPost/'.$post_id.'');
        }
       
        //добавление картинки в галерею поста
        if($this->request->file('image')){
       
            //сохранение новой фотографии в галерею  поста
            $image = new Image(['image' => $this->request->file('image')->store('uploads'),
            'user_id' => $id_user,
            'post_id' => $post_id]);
            $post = Post::find($post_id);
            $post->images()->save($image); 
            
            //сообщение об изменении аватара поста
            $this->request->session()->flash('flash_message_success','Вы успешно изменили аватар!');
            $flash_message_success = session('flash_message_success');
            return redirect('/editPost/'.$post_id.'');
        }
       
        if($this->request->text || $this->request->name_post || $this->request->title_post){
        DB::table('posts')
            ->where('id', $post_id)
            ->update([
            'name_post' => $this->request->name_post,
            'title_post' => $this->request->title_post,
            'text' => $this->request->text 
            ]);

            return redirect('/post/'.$post_id.'');
        }

        return view('editPost', [
            'post' => $post,
            'info' => $info,
            'user' => $user,
            'comment_user' => $comment_user,
            'flash_message_success' => $flash_message_success,
            'flash_message_danger' => $flash_message_danger
            ]);   
        }




    //удаление картинки в галерее поста
    public function delete_image($image_id,$post_id){

        //проверка наличия post_id  поста
        if(!$post_id){
            $this->request->session()->flash('flash_message_danger','Такого пользователя нет!!!');
            return redirect('/posts');
        }
        $user_id=Post::find($post_id)->user_id;//получение id пользователя владельца поста
        //проверка  прав действий, админ или собственный профиль пользователя
        if (!(auth()->user()->admin) || !(auth()->user()->id == $user_id)){
            $this->request->session()->flash('flash_message_danger','You do not have permission to edit user profile!');
            return redirect('/posts');
        }
        
            //удаление картинки в папке проекта
            $imageNamePost=Image::find($image_id)->image;
            Storage::delete($imageNamePost);

            //удаление ссылки на картинку в БД
            DB::table('images')->where('id', $image_id)->delete();

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
        DB::table('posts')->where('id', $post_id)->delete();
        DB::table('images')->where('post_id', $post_id)->delete();
        DB::table('comments')->where('commentable_id', $post_id)->delete();

        $this->request->session()->flash('flash_message_success','Вы успешно удалили пост!');
        return redirect('/posts');
    }



    //поиск поста
    public function searchPosts(){
        $flash_message_success = session('flash_message_success');
        $flash_message_danger = session('flash_message_danger');
        
        $key = trim($this->request->get('filter_contacts'));
        $posts = DB::table('posts')->select()
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->join('infos', 'posts.user_id', '=', 'infos.user_id')
            ->where('name_post', 'like', "%{$key}%")
            ->orWhere('title_post', 'like', "%{$key}%")
            ->get();
        
            $myPosts = 0;
            $favorites = 0;
            $postsAll = 0;
            $searchPosts = 1;

        return view('posts', 
        ['posts' => $posts,
         'flash_message_success' => $flash_message_success, 
         'flash_message_danger' => $flash_message_danger, 
         'myPosts' => $myPosts, 'favorites' => $favorites, 
         'postsAll' => $postsAll,
         'searchPosts' => $searchPosts,
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

        $posts = DB::table('posts')
            ->join('users', 'posts.id', '=', 'users.id')
            ->join('infos', 'infos.user_id', '=', 'users.id')
            ->where('favorites', 1)
            ->get();
            
            $favorites=1;
            $myPosts = 0;
            $postsAll = 0;
            $searchPosts = 0;
        //return view('postsFavorites', ['posts' => $posts, 'flash_message_success' => $flash_message_success, 'flash_message_danger' => $flash_message_danger]);
        return view('posts',
         [  'posts' => $posts,
            'flash_message_success' => $flash_message_success,
            'flash_message_danger' => $flash_message_danger,
            'myPosts' => $myPosts,
            'favorites' => $favorites,
            'postsAll' => $postsAll,
            'searchPosts' => $searchPosts]);
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
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->join('infos', 'infos.user_id', '=', 'users.id')
            ->where( 'postable_id', auth()->user()->id)
            ->get();
            
            $myPosts = 1;
            $favorites = 0;
            $postsAll = 0;
            $searchPosts = 0;
        
        return view('posts', 
        ['posts' => $posts,
         'flash_message_success' => $flash_message_success,
         'flash_message_danger' => $flash_message_danger,
         'myPosts' => $myPosts, 
         'favorites' => $favorites, 
         'postsAll' => $postsAll,
         'searchPosts' => $searchPosts]);
    }



    //добавление поста в избранные
    public function addFavorites($post_id){
        DB::table('posts')
        ->where('id', $post_id)
        ->update([
        'favorites' => 1,
        ]);
        return redirect('/posts');  
    }



    //удаление поста из избранных
    public function deleteFavorites($post_id){
        
        DB::table('posts')
        ->where('id', $post_id)
        ->update([
        'favorites' => 0,
        ]);
        return redirect('/posts');  
    }



    //блокировка поста
    public function bannedPost($post_id){
        
        DB::table('posts')
        ->where('id', $post_id)
        ->update([
        'banned' => 1,
        ]);
        return redirect('/posts');  
    }



    ///разблокирование поста
    public function unBannedPost($post_id){
        
        DB::table('posts')
        ->where('id', $post_id)
        ->update([
        'banned' => 0,
        ]);
        return redirect('/posts');  
    }



    //разблокировка комментария к посту
    public function unBannedComment($comment_id, $post_id){
        
        DB::table('comments')
        ->where('id', $comment_id)
        ->update([
        'banned' => 0,
        ]);
        return redirect('/post/'.$post_id.'');  
    }



    //блокировка поста
    public function bannedComment($comment_id, $post_id){
        
        DB::table('comments')
        ->where('id', $comment_id)
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
        DB::table('comments')->where('id', $comment_id)->delete();
        return redirect('/post/'.$post_id.'');  
    }   
}
