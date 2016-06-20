@extends('layout')

@section('content')

	<div class="panel panel-default">
		<div class="text-center panel-heading">
			<div class="row">
			<span class="col-md-3"> # posts: <span id="postCount">{{$count}}</span> </span>
			<span class="col-md-6"> 
				<button id="csvExportBtn"   class="btn btn-primary" >CSV Export</button> 
				<button id="zipExportBtn" class="btn btn-default" >Zip file Export</button>
				<button id="excelExportBtn" class="btn btn-success" >Excel Export</button>
			</span>
			<span class="col-md-3"> # views: <span id="viewCount">{{$views}}</span> </span>
			</div>
		</div>
		<div class="panel-body">
			@include('partials.flash')
			<div class="container">
				<div class='row'>
					<div id="zipping" class="alert alert-warning alert-dismissible col-md-6 col-md-offset-2 text-center" style="display:none;" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

						Generating Zipfile for you... Please, wait and <strong>do not</strong> reload the page
					</div>
				</div>
				
				<div class="row">
					<div id="uploading" class="alert alert-info col-md-6 col-md-offset-2" style="display:none;" role="alert">
						<div class="text-center">
							<i class="icon-spin icon-refresh"></i>	Uploading		
						</div>
					</div>
				</div>
				
				<div class="row">
					<div id="errors" class="alert alert-danger col-md-6 col-md-offset-2" style="display:none;" role="alert">
						Errors:
						<ul id="errorList"></ul>
					</div>
				</div>
				
				<div class='row col-md-6 col-md-offset-2'>
					<div id ="progressbar" class="progress" style="display:none;">
						<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%"></div>
					</div>
				</div>

				<div class="row col-md-8 col-md-offset-1">
					{!! Form::open(['id' =>'uploadForm', 'route' => 'post.store', 'method' => 'post']) !!}
					
						<div class="form-group row">
					    	<label for="title">Title</label>
							{!! Form::text('title', null, ['id' => 'title', 'placeholder' => 'Your title (which is optional)', 'size' => '50', 'class' => 'form-control col-md-6']) !!}
					  	</div>
	
						<div class="form-group row">
							<button id="fileBrowseBtn" class="btn btn-primary btn-lg center-block">Upload your picture</button>
					  	</div>
					  	
						<div class="form-group row hidden">
					    	<label for="image">Browse your image</label>
							{!! Form::file('image', ['id' => 'image', 'accept' => 'image/jpeg,image/gif,image/png', 'class' => 'form-control col-md-6']) !!}
					  	</div>
						<div class="form-group row hidden">
							<button id="submitBtn" type="submit" class="btn btn-primary pull-right" disabled="disabled">Submit</button>
					  	</div>
					{!! Form::close() !!}
				</div>
			</div>	
		</div>
	</div>
	
	@include('feed.partials.posts_list')

<script type="text/javascript">
	var URL = {
		feedCountViews	: "{{ URL::route('feed.count.views', [], false) }}",
		feedCountPosts	: "{{ URL::route('feed.count.posts', [], false) }}",
		getPosts		: "{{ URL::route('posts', [], false) }}",
		storePost		: '{{ URL::route("post.store", [], false) }}',
		exportCsv		: '{{ URL::route("export.csv", [], false) }}',
		exportExcel		: '{{ URL::route("export.excel", [], false) }}',
		exportZip		: '{{ URL::route("export.zip", [], false) }}',
	};

	var csrf_token = '{{csrf_token()}}';
</script>
	{!! Html::script('js/feed/index.js', array(), false) !!}

@endsection