{{--Clickable image--}}
<div class="container p-0">
    <a href="{{route('post.show',$post->id)}}">
        @if (strpos($post->image,'data:image')===0)
            <img src="{{$post->image}}" alt="post id {{$post->id}}" class="w-100 image">
        @else
            <video controls autoplay loop class="w-100 image" src="data:video/mp4;base64{{$post->image}}" type="{{$post->image_type}}">
        @endif
        
    </a>
    <div class="card-body">
        {{--heart icon + no of likes + categories--}}
        <div class="row align-items-center">
            <div class="col-auto">

                @if ($post->isLiked())
                <form action="{{route('like.destroy',$post->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm shadow-nonw p-0"><i class="fa-solid fa-heart text-danger"></i></button>
                </form>
                @else
                <form action="{{route('like.store',$post->id)}}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-sm shadow-nonw p-0"><i class="fa-regular fa-heart"></i></button>
                </form>
                @endif
        
            </div>
            <div class="col-auto px-0">
                <span>{{$post->likes->count()}}</span>
            </div>
            <div class="col text-end">
                @forelse ($post->categoryPost as $category_post)
                    <span class="badge bg-secondary bg-opacity-50">
                        {{$category_post->category->name}}
                    </span>
                @empty
                    <div class="badge bg-dark text-wrap">Uncategorized</div>
                @endforelse
                {{-- @foreach ($post->categoryPost as $category_post)
                    <div class="badge bg-secondary bg-opacity-50">
                        {{$category_post->category->name}}
                    </div>
                @endforeach --}}
            </div>
        </div>

        {{--owner + description--}}
        <a href="" class="text-decoration-none text-dark fw-bold">{{$post->user->name}}</a>
        &nbsp;
        <p class="d-inline fw-light">{{$post->description}}</p>
        &nbsp;
        <p class="text-danger small">Posted on {{$post->created_at->diffForHumans()}}</p>
        <p class="text-uppercase text-muted xsmall">{{date('M d, Y',strtotime($post->created_at))}}</p>
        {{--strtotime()->is a builtin function in PHP use to convert the time and date into human readable format--}}

        {{-- Share Buttons --}}
        <div style="text-align: right">
            {{-- LINE Share Button --}}
            <a href="https://social-plugins.line.me/lineit/share?url={{ urlencode(route('post.show', $post->id)) }}" class="btn btn-success btn-sm" target="_blank">
                <i class="fa-brands fa-line"></i> Share on LINE
            </a>
            {{-- Twitter Share Button --}}
            <a href="https://x.com/intent/tweet?url={{ urlencode(route('post.show', $post->id)) }}" class="btn btn-dark btn-sm" target="_blank">
                <i class="fa-brands fa-x-twitter"></i> Share on X
            </a>
        </div>

        {{--Comments Section--}}

        <div class="mt-3">
            {{--Show all the comments here--}}
                @if ($post->comments->isNotEmpty())
                    <hr>
                    <ul class="list-group">
                        @foreach ($post->comments->take(3) as $comment)
                            <li class="list-group-item border-0 p-0 mb-2">
                                <a href="{{route('profile.show',$comment->user->id)}}" class="text-decoration-none text-dark fw-bold">{{$comment->user->name}}</a>
                                &nbsp;
                                <p class="d-inline fw-light">{{$comment->body}}</p>

                                <form action="{{route('comment.destroy',$comment->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <span class="text-uppercase text-muted xsmall">{{date('M d,Y',strtotime($comment->created_at))}}</span>

                                    {{--If the Auth user is the OWNER of the COMMENT,show a delete button--}}

                                    @if (Auth::user()->id===$comment->user->id)
                                        &middot;
                                        <button type="submit" class="border-0 xsmall">Delete</button>
                                    @endif
                                </form>
                            </li>
                        @endforeach

                            @if ($post->comments->count()>3)
                                <li class="list-group-item border-0 px-0 pt-0">
                                    <a href="{{route('post.show',$post->id)}}" class="text-decoration-none small">View all {{$post->comments->count()}} comments</a>
                                </li>
                            @endif
                    </ul>
                @endif
        </div>

        @include('users.posts.contents.comments')
        
    </div>
</div>