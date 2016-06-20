<div class="post_element row" style="margin-bottom: 20px;">
	<p> Title: {{ $post->title}} <span class="pull-right"> uploaded @ {{ date('d-m-Y, H:i:s', strtotime($post->created_at)) }} </span> </p>
	<hr>
    <img src="{{ $post->image_url }}" width="100%"></img>
</div>