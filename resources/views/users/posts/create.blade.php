@extends('layouts.app')

@section('title','Create Post')

@section('content')
    <form action="{{route('post.store')}}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="category" class="form-label d-block fw-bold">
                Category <span class="text-muted fw-normal">(Up to 3)</span>
            </label>
        </div>

        @foreach($all_categories as $category)
            <div class="form-check form-check-inline">
                <input type="checkbox" name="category[]" id="{{$category->name}}" class="form-check-input" value="{{$category->id}}">
                <label for="{{$category->name}}" class="form-check-label">{{$category->name}}</label>
            </div>
        @endforeach
        @error('category')
            <div class="text-danger small">{{$message}}</div>
        @enderror

        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control" placeholder="What's on your mind">{{old('description')}}</textarea>
        @error('description')
            <div class="text-danger small">{{$message}}</div>
        @enderror
        </div>

        <div class="mb-4">
            <label for="image" class="form-label fw-bold">Image/Movie</label>
            <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info" accept="image/jpeg,image/png,image/gif,video/mp4,video/x-m4v,video/quicktime">
            <div class="form-text" id="image-info">
                The accpetable formats are jpeg,jpg,png,gif,mp4,x-m4v,and quicktime only.<br>
                Max file size is 1048Kb.
            </div>
        @error('image')
            <div class="text-danger small">{{$message}}</div>
        @enderror
        </div>
            <button type="submit" class="btn btn-primary px-5">Post</button>
    </form>
@endsection