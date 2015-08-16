@extends($document->attr('layout'))

@section('pageTitle', $document->attr('title'))
@section('pageSubtitle', $document->attr('subtitle', null))
@section('breadcrumb')
    @parent
    @if(isset($breadcrumb))
        @foreach($breadcrumb as $item)
            @if($item->getId() !== 'root')
            <li>
                <a {!! $item->parseAttributes() !!}>{{ $item->getValue() }}</a>
                @if($item->hasChildren())
                    <i class="fa fa-arrow-right"></i>
                @endif
            </li>
            @endif
        @endforeach
    @endif
@stop

@section('sidebar-menu')
    {!! $project->getDocumentsMenu()->render() !!}
@stop

@section('header-actions')
    @parent
    @include('codex::partials/header-actions')
@stop

@section('content')
    {!! $content !!}
@stop
