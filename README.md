# Y-api
هو موقع اجتماعي بسيط يعتمد على المجموعة او المجتمعات للتواصل والمشاركة 
تم بناء المشروع عبر 
‏ 11 laravel 

## كيف تبداء

تحميل الحزم عن طريق 
`Composer install `
ثم تهجير قاعدة البيانات 
`php artisan migrate`
وتشغيل السيرفر الخاصة ب laravel 
او استخدام اي سيرفر اخر 
`php artisan serve `
**`jjjhjj`**
## الان يمكنك تسجيل الدخول عن طريق

Post `http://127.0.0.1:8000/api/v1/login`
email = you@me.com
password = 1234

## او تسجيل ادخول عن طريق


Post ` http://127.0.0.1:8000/api/v1/register `
name = y 
email = you@me.com
password = 1234

## طلبات المستخدم 
Get All Users
Get `http://127.0.0.1:8000/api/v1/users`
Get One User
Get `http://127.0.0.1:8000/api/v1/users/1`
UpDate User  
Put `http://127.0.0.1:8000/api/v1/users/1`
Delete User 
Delete `http://127.0.0.1:8000/api/v1/users/1`


طلبات المجموعة 

Get All groups
Get `http://127.0.0.1:8000/api/v1/groups`

Get One group
Get `http://127.0.0.1:8000/api/v1/groups/1`

Create group
Post `http://127.0.0.1:8000/api/v1/groups`
user_id = 1;
name = move 
description = i love move
image  = null

UpDate group  
Put `http://127.0.0.1:8000/api/v1/groups/1`

Delete group
Delete `http://127.0.0.1:8000/api/v1/groups/1`


‎طلبات المنشورات

Get All posts from group
Get `http://127.0.0.1:8000/api/v1/group/2/posts`

Get One post 
Get `http://127.0.0.1:8000/api/v1/group/2/posts/2`

Create post
Post `http://127.0.0.1:8000/api/v1/group/2/posts`
title = Marvel 
description = i love DC
image = null

UpDate post
Put `http://127.0.0.1:8000/api/v1/group/1/posts/2`

Delete post
Delete `http://127.0.0.1:8000/api/v1/group/1/posts/2`


‎طلبات التعليقات الخاصة بالمنشور 

Get All comments from post
Get `http://127.0.0.1:8000/api/v1/post/2/comments`

Get One comment
Get `http://127.0.0.1:8000/api/v1/post/2/comments/2`

Create comment
Post `http://127.0.0.1:8000/api/v1/post/2/comments`
user id = 5 
description = this is fake 

UpDate comment
Put `http://127.0.0.1:8000/api/v1/post/1/comments/2`

Delete comment
Delete `http://127.0.0.1:8000/api/v1/post/1/comments/2`

‎طلبات التعليقات الخاصة بالتعليق 

Get All comments from comment
Get `http://127.0.0.1:8000/api/v1/comment/2/commentForComment`

Get One comment
Get `http://127.0.0.1:8000/api/v1/comments/2/`
commentForComment/3

Create comment
Post `http://127.0.0.1:8000/api/v1/comment/2/`
commentForComment
user id = 5 
description = this is fake 

UpDate comment
Put `http://127.0.0.1:8000/api/v1/comments/2/`
commentForComment/2

Delete comment
Delete `http://127.0.0.1:8000/api/v1/comment/2/commentForComment/2`

المزيد من الطلبات 

اضافة او حذف عضوى في المجموعة 

Get Add Member
Get `http://localhost:8000/api/v1/{userId}/{groupId}/addmember`
 
Get Delete Member
Get `http://localhost:8000/api/v1/{userId}/{groupId}/deletemember` 


جميع اعضاء مجموعة معين

Get All Members from group 

جميع المجموعات لمستخدم معين 

Get All groups from user 

جميع المجموعات المنضم به مستخدم معين  

Get All groups from members 

جميع المنشورات لمستخدم معين 

Get All posts from user 

جميع المنشورات

Get All user 



