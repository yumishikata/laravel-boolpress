@extends('layouts.app')

@section('content')
    
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>{{ $post->title }}</h1>
            <h3>{{ $post->subtitle}}</h3>
            <p>{{ $post->content }}</p>
            
            <p>Autore del post: <strong>{{$post->user->name}}</strong></p>
            
        </div>
    </div>
</div>

@endsection