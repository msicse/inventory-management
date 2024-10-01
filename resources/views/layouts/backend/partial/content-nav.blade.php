<div class="container-fluid">
    <ol class="breadcrumb breadcrumb-bg-teal">

        <li><a href="{{ route('admin.dashboard') }}"><i class="material-icons">dashboard</i> Dashboard</a></li>
        @if(Request::is('admin/posts*'))
            <li><a href="{{ route('admin.posts.index') }}"><i class="material-icons">event_note</i> Posts</a></li>
            @if(Request::is('admin/posts/create'))
                <li><a href="{{ route('admin.posts.create') }}"><i class="material-icons">create</i> Create</a></li>
            @endif
        @endif
        @if(Request::is('admin/tags*'))
            <li><a href="{{ route('admin.tags.index') }}"><i class="material-icons">label</i> Tags</a></li>

        @endif
        @if(Request::is('admin/categories*'))
            <li><a href="{{ route('admin.categories.index') }}"><i class="material-icons">view_comfy</i> Categories</a></li>

        @endif
        @if(Request::is('admin/pending/posts*'))
            <li><a href="{{ route('admin.pending.post') }}"><i class="material-icons">event_note</i> Pending Posts</a></li>

        @endif
        @if(Request::is('admin/subcribers*'))
            <li><a href="{{ route('admin.subcribers') }}"><i class="material-icons">subscriptions</i> Subcribers</a></li>

        @endif
        @if(Request::is('admin/comments*'))
            <li><a href="{{ route('admin.comments') }}"><i class="material-icons">comment</i> Comments</a></li>

        @endif
        @if(Request::is('admin/favorite/posts*'))
            <li><a href="{{ route('admin.posts.favorite') }}"><i class="material-icons">favorite</i> Favorite Posts</a></li>

        @endif
        @if(Request::is('admin/authors*'))
            <li><a href="{{ route('admin.authors') }}"><i class="material-icons">account_circle</i> Authors</a></li>

        @endif

    </ol>
</div>