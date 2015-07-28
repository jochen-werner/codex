@extends('codex::layouts.codex')

@section('sidebar')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Table of Contents</h3>
		</div>

		<div class="panel-body">
			{!! $toc['body'] !!}
		</div>
	</div>
@endsection

@section('content')
	@if (isset($content['frontmatter']['title']))
		<div class="page-header">
			<h1>{{ $content['frontmatter']['title'] }}</h1>
		</div>
	@endif

	{!! $content['body'] !!}
@endsection