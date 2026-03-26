@extends('layouts.app')

@section('content')
<div class="container">

<h2>回答編集</h2>

<form method="POST" action="/answer/update/{{ $answer->id }}">
    @csrf

    <textarea name="content"
              class="form-control mb-2"
              required>{{ $answer->content }}</textarea>

    <button type="submit"
            class="btn btn-primary">
        更新
    </button>
</form>

</div>
@endsection