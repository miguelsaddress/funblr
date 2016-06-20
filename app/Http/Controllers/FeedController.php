<?php namespace Funblr\Http\Controllers;

use Funblr\Post;

class FeedController extends ApiController
{
    public function index()
    {
        $this->incrementFeedViewsCount();

        return view('feed.index')
                ->with('posts', $this->getPosts())
                ->with('count', $this->getPostCount())
                ->with('views', $this->getViewCount());
    }
    
    public function viewCount()
    {
        return response()->json(['count' => $this->getViewCount()]);
    }

    public function postCount()
    {
        return response()->json(['count' => $this->getPostCount()]);
    }

    private function incrementFeedViewsCount()
    {
        $this->user->feed_views = $this->getViewCount() + 1;
        $this->user->save();
    }

    private function getPosts()
    {
        $posts = $this->user->posts()->orderBy('created_at', 'DESC')->get();
        return $posts;
    }

    private function getPostCount()
    {
        $count = count($this->getPosts());
        return $count;
    }

    private function getViewCount()
    {
        $views = $this->user->feed_views;
        return $views;
    }

}
