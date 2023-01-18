@php

@endphp

<div class="row mb-2 mt-2 justify-content-center">
    <div class="col-md-7">
        <div class="input-group has-validation">
            @csrf
                <span class="input-group-text">
                    <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                </span>
            <textarea class="form-control" id="username" placeholder="Новая статья" required=""></textarea>
            <span class="input-group-text">
                <button>Загрузить фото</button>
                <button>Опубликовать</button>
            </span>
            <div class="invalid-feedback">
                Your username is required.
            </div>
        </div>
    </div>
</div>

@if(!empty($posts))
    @foreach($posts as $post)
        <div class="row mb-2 justify-content-center">
            <div class="col-md-7">
                <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col p-4 d-flex flex-column position-static">
                        <strong class="d-inline-block mb-2 text-success">{{ $post->author->name }}</strong>
                        <h3 class="mb-0">{{ $post->title }}</h3>
                        <div class="mb-1 text-muted">{{ date_support()->mainFormat($post->created_at) }}</div>
                        <p class="mb-auto">{{ $post->content }}</p>
                        <a href="#" class="stretched-link"></a>
                    </div>
                    <div class="col-auto d-none d-lg-block">
{{--                        <svg class="bd-placeholder-img" width="250" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em"></text></svg>--}}
                        <img src="https://picsum.photos/200/300" width="250" height="250" alt="">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif