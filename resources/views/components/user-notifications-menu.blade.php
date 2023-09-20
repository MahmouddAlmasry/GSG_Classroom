<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        Notification
        <span class="badge bg-danger">{{$unreadNotificationsCount}}</span>
    </a>
    <ul class="dropdown-menu">
        @foreach ($notifications as $notification)
        <li><a class="dropdown-item" href="{{ $notification->data['link'] }}?nid={{$notification->id}}">
            @if ($notification->unread())
                <b style="color: red">*</b>    
            @endif
            {{$notification->data['body']}}
            <br>
            <small class="text-muted">{{$notification->created_at->diffForHumans()}}</small>
        </a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        @endforeach
    </ul>
</li>