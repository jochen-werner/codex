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
    <div class="btn-group">
        @if(isset($projectsList))
        <div class="btn-group">
            <a href="#" type="button" data-toggle="dropdown" aria-expanded="false" class="btn dropdown-toggle btn-primary btn-sm">
                @yield('projectName', $projectName)
            </a>
            <ul class="dropdown-menu">
                @foreach($projectsList as $displayName => $url)
                    <li><a href="{{ $url }}">{{ $displayName }}</a></li>
                @endforeach
            </ul>
        </div>
        @endif
        @if(isset($projectRefList))
        <div class="btn-group">
            <a href="#" type="button" data-toggle="dropdown" aria-expanded="false" class="btn dropdown-toggle btn-primary btn-sm">
                @yield('projectRef', $projectRef)
            </a>
            <ul class="dropdown-menu">
                @foreach($projectRefList as $ref => $url)
                    <li><a href="{{ $url }}">{{ $ref }}</a></li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
@stop

@section('content')
    {!! $content !!}
@stop
