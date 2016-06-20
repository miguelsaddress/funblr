<div class="panel panel-default" id="imagesPanel">
	<div class="text-center panel-heading">
		<div class="row">
			<span class="center-text">	These are your awesomic images </span>
		</div>
	</div>
	<div class="panel-body">
		<div id="images" class="row col-md-offset-3 col-md-6" style="clear:both">
				@foreach($posts as $post)
					@include('feed.partials.post')
				@endforeach
			</div>
	</div>	
</div>	
