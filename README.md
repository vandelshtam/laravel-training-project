# Учебный  проект выполненный с помощью  Laravel

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# О проекте

В этом проекте реализован web сайт простого социального сообщества. Сайт имеет три основных раздела:

- Раздел "Пользователи".
- Раздел "Посты"(статьи).
- Раздел "Чаты.

## Краткое описание функциональных возможностей ресурса

### Раздел "Пользователи"
В разделе пользователи выполняется функционал по регистрации и редактированию данных пользователей, вывод информации о всех пользователя. Вывод профилей пользователей.
В этом разделе пользователи имеют возможность:
- регистрироваться.
- редактировать свои данные(аватар, контактные данные, информацию о себе).
- удалить свой профиль.
- редактировать свои данные безопасности(имя, email- он же "логин", пароль).
- просматривать свой профиль и профили зарегистрированных пользователей.
- изменять свой статус (онлайн, недоступен, отошел).


 Пользователь с правом "admin" имеет такие же, как выше перечисленные возможности, но по отношению к любому пользователю, в том числе создавать профили новых пользователей. Admin имеет возможность изменять роли пользователей, предоставляя и отзывая роли "admin".

### Раздел "Посты"

В этом разделе, зарегистрированные и авторизованные пользователи могут:
- просматривать свои посты и посты других пользователей, оставлять к ним комментарии.
- создавать новые посты, редактировать свои посты.

Авторы поста или пользователь "admin"  имеют возможность управлять следующими данными:
- аватаром поста.
- фотографиями в галерее поста.
- заголовком поста.
- текстом поста.
- комментариями к посту.

"admin" дополнительно имеет возможность в любом посте:
- блокировать/разблокировать/удалять посты.
- блокировать/разблокировать/удалять чаты.

### Раздел "Чаты"

В этом разделе, зарегистрированные и авторизованные пользователи могут:
- просматривать список чатов участником которых является пользователь.
- открывать чаты участником которых является пользователь, отправлять и получать сообщения в чате.
- управлять чатами, автором которых является пользователь(менять участников, изменять роли участников, управлять аватаром, управлять названием).
- по умолчанию, автор чата имеет роль 'author' со всеми выше перечисленными возможностями, другие участники по умолчанию имеют роль 'participant', автор чата может предоставлять и отменять участникам роль 'moderator'. 'Moderator' получает все возможностями автора чата.

Пользователь "admin"  наделен правом  управлять любыми чатами и сообщениями в чате:
- создавать/удалять/редактировать чаты.
- блокировать/разблокировать чаты.
- создавать/удалять сообщения в любом чате.


## Краткая документация к проекту

### Проект реализован на фреймворке Laravel 8

#### Краткое описание важных файлов проекта

##### Дерриктория database/migrations 
В этой папке содержатся файлы создания таблиц БД проекта, в проекте создаются ниже перечисленные таблицы:
- Users в которой содержиться регистрационная информация о пользователе(name, email, password).
- Infos хранит дополнительную информацию о пользователе(avatar,phone, location и др).
- Socials содержит информацию и социальных сетях пользователя(vk, telegram, instagram).
- Posts должна хранить информацию о постах проекта.
- Comments хранение информации о комментариях к постам в проекте.
- Images хранение картинок к постам.
- Chats хранение данные о чатах.
- Messages запись данных сообщений в чатах.
- Userlists хранение данных связывающих пользователей уаствующих в чате, и данные чатов.

##### Дерриктория database/factories 
В этой папке создержатся файлы заполнения таблиц БД проекта фейковыми данными:

##### Дерриктория public/css, public/js 
В данной папке лежат файлы стилей для верстки проекта

##### Дерриктория database/img 
В  дерриктории находятся картинки для оформления проекта и базового заполнения сведений о пользователях, чатах, постах.

##### Дерриктория database/uploads 
Хранение картинок пользователей используемых для аваторов пользователей, аваторов поста и чата, картинок галереи паста.

##### Дерриктория resources/views 
Файлы верстки проекта:
- 'users.blade.php' страница просмотра всех пользователей.
- 'user_profile.blade.php' страница профиля пользоватиеля.
- 'create_users.blade.php' страница создания нового пользователя.
- 'login.blade.php' страница авторизации пользовтеля.
- 'edit.blade.php' страница редактирования информации о пользователе.
- 'layout.blade.php' основная страница подключения визуализации.
- 'register.blade.php' страница регистрации нового пользователя.
- 'verify_email.blade.php' страница вывода информации для повторного подтверждения email при верификации после агистрации пользователя.
- 'security.blade.php' страница изменения  данных безопасности пользователя (email, name, password).
- 'media.blade.php' страница управления аватаром пользователя.
- 'status.blade.php' страница управления статусом пользователя.
- 'statusAdmin.blade.php' страница предоставления статуса Админ пользователям.
- 'confirm_password.blade.php' страница повторного подтверждения пароля при выполнении важных изменений(смена почты, пароля, имя, удаления профиля пользователя).
- 'welcome.blade.php' страница информации Laravel.
- 'posts.blade.php' страница вывода всех постов.
- 'post.blade.php' страница просмотра поста.
- 'addPost.blade.php' страница добавления нового поста.
- 'editPost.blade.php' страница редактирования поста.
- 'imagePostShow.blade.php' страница просмотра фотографий из галереи поста, просмотр реализован по одной фотографии.
- 'search.blade.php' страница вывода найденных пользователей, при выполнении поиска пользователя.
- 'statusAdmin.blade.php' страница предоставления статуса 'admin' пользователям.
- 'confirm_password.blade.php' страница повторного подтверждения пароля при выполнении важных изменений(смена почты, пароля, имя, удаления профиля пользователя).
- 'chats.blade.php' страница вывода всех чатов.
- 'openChat.blade.php' страница просмотра чата.
- 'addChatShow.blade.php' страница добавления нового чата.
- 'editChatShow.blade.php' страница редактирования чата.

##### Дерриктория  app/http/controllers
Все действия связанные с  пользователями и их ресурсами производятся в данной дерриктории.

##### 'AuthControllers.php' этот файл (класс) выполняет:
- регистрацию пользователей.
- авторизацию пользователей.
- смену информации безопасности пользователей(смена почты, смена пароля, смена имени).
- выход из системы.
- повторное подтверждение пароля при выполнении важных действий.

##### 'UsersControllers.php' в этом классе выполняются следующие действия:
- вывод страниц со списком пользователей, вывод страницы профиля пользователей.
- вывод страницы  и добавление нового пользователя.
- вывод страницы и выполнение изменения статуса пользователя.
- вывод страницы и редактирование информации о пользователе.
- вывод страницы и изменение роли пользователя(предоставление роли администратора).
- вывод страницы и изменение аватара пользователя.
- вывод страницы и выполнение удаления пользователя.
- вывод страницы и выполнение поиска пользователя.
- блокировка/разблокировка пользователей

##### 'PostsControllers.php' в этом классе выполняются следующие действия:
- вывод страниц со списком  постов.
- вывод страницы  поста.
- добавление нового поста.
- редактирование поста.
- удаление поста.
- поиск постов.
- добавление/удаление постов в избранное/из избранного.
- блокировка/разблокировка постов.
- добавление/удаление комментариев к посту.
- блокировка/разблокировка коментариев к постам.
- вывод избранных постов.
- вывод моих (принадлежащих автору) постов.

##### 'ChatsControllers.php' в этом классе выполняются следующие действия:
- вывод страниц со списка чатов.
- вывод страницы  чата.
- добавление нового чата.
- редактирование чата.
- удаление чата.
- поиск чатов.
- добавление/удаление чатов в избранное/из избранного.
- блокировка/разблокировка чатов.
- добавление/удаление сообщений в чате.
- вывод избранных чатов.
- вывод моих, принадлежащих автору, чатов.


##### Тестовые пользователи проекта со статусом "админ":
- otto@otto.
- viky@viky.
- morzav@morzav.
- sandra@sandra.
- vladi@vladi.

Пароль у всех пользователей 123aaa
