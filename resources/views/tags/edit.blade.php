@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Tag</h1>
    <form action="{{ route('tags.update', $tag->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nama Tag</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tag->name) }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $tag->slug) }}" required>
            @error('slug')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('tags.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection 