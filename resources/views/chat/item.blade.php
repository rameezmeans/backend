@if($get == 'users')
<li class="messenger-list-item chat-user-list @if($user->front_end_id == 1) chat-user-list-ecu-tech @else chat-user-list-tuningx @endif clearfix" data-contact="{{ $user->id }}">
    <a data-view-animation="push-parrallax" data-view-port="#chat" data-navigate="view" href="#" data-contact="{{ $user->id }}">

        <span class="thumbnail-wrapper d32 circular bg-success" style="position: relative;">
            @if($user->active_status)
                <span class="activeStatus"></span>
            @endif
            <img class="avatar" width="50" height="50" alt="" data-src-retina="/users-avatar/avatar.png" data-src="/users-avatar/avatar.png" src="/users-avatar/avatar.png" class="col-top">
        </span>

        <p class="p-l-10 full-width">
        <span class="text-master" data-id="{{ $user->id }}" data-type="user">{{ strlen($user->name) > 15 ? trim(substr($user->name,0,15)).'..' : $user->name }}</span>
        <span class="text-master hint-text fs-12" id="time" style="float:right">
            {{$lastMessage->created_at->diffForHumans()}}
        </span>
        <span class="block text-master hint-text fs-12">
            <span id="last-message-text">
            {{-- Last Message user indicator --}}
            {!!
                $lastMessage->from_id == env('CHAT_USER_ID')
                ? '<span class="lastMessageIndicator">You :</span>'
                : ''
            !!}
            {{-- Last message body --}}
            @if($lastMessage->attachment == null)
            {!!
                strlen($lastMessage->body) > 15
                ? trim(substr($lastMessage->body, 0, 15)).'..'
                : $lastMessage->body
            !!}
            @else
            
            <span class="fas fa-file"></span> Attachment
            @endif
            </span>
            @if($unseenCounter > 0)
            <b class="count" >{!! $unseenCounter > 0 ? "<b>".$unseenCounter."</b>" : '' !!}</b>
            @endif
        </span>
        </p>
    </a>
</li>
@endif