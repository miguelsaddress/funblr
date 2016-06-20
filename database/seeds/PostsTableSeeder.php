<?php

use Illuminate\Database\Seeder;
use Funblr\Post;
use Funblr\User;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = [
            [
                'title' => 'Where to stay in Amsterdam?', 
                'name' => 'Where_to_stay_Amsterdam.jpg',
                'image_url' => 'http://c50039.r39.cf3.rackcdn.com/uploads/landing_page/article_widget/2007/Where_to_stay_Amsterdam.jpg',
            ],
            [
                'title' => 'Amsterdam image',
                'name' => 'Amsterdam.jpg',
                'image_url' => 'http://www.republica.com/wp-content/uploads/2016/05/Amsterdam.jpg',
            ],
            [
                'image_url' => 'http://blog.waynabox.com/wp-content/uploads/2016/05/amsterdam-1.jpg',
                'name' => 'amsterdam-1.jpg',
            ],
            [
                'title' => 'I AMsterdam', 
                'name' => 'YoungPrints-2013-0009-I-amsterdam-L.jpg',
                'image_url' => 'http://loftofdreams.com/wp-content/uploads/2014/06/YoungPrints-2013-0009-I-amsterdam-L.jpg',
            ],
        ];
        
        $user = User::find(1);
        foreach ($posts as $p) {
            $p['user_id'] = 1;
            $user->posts()->save(Post::create($p));
            //we need them apart in time....
            sleep(1);
        }
    }
}
