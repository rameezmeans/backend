{{-- -------------------- The default card (white) -------------------- --}}
{{-- @if($viewType == 'default')
    @if($from_id != $to_id) --}}
    <div class="message-card" data-id="{{ $id }}">
        <div class="chatify-d-flex chatify-align-items-center" style="flex-direction: row-reverse; justify-content: flex-end;">
        <p>{!! ($message == null && $attachment != null && @$attachment[2] != 'file') ? $attachment[1] : nl2br($message) !!}
            <sub title="{{ $fullTime }}">{{ $time }}</sub>
            {{-- If attachment is a file --}}
            @if(@$attachment[2] == 'file')
            
                <a href="{{ env('FRONTEND_URL').'download_chat/'.$attachment[0].'/'. $viewType }}" style="color: #595959;" class="file-download">
                    <span class="fas fa-file"></span> {{$attachment[1]}}</a>
            
            @endif
        </p>
        </div>
        {{-- If attachment is an image --}}
        @if(@$attachment[2] == 'image')
        <div class="image-file chat-image" style="">
            <img src="{{ Chatify1::getAttachmentOtherSide($attachment[0]) }}" style="width: 100%;">
        </div>
        @endif
    </div>
    {{-- @endif --}}
{{-- @endif --}}

{{-- -------------------- Sender card (owner) -------------------- --}}
{{-- @if($viewType == 'sender')
    <div class="message-card mc-sender" title="{{ $fullTime }}" data-id="{{ $id }}">
        <div class="chatify-d-flex chatify-align-items-center" style="flex-direction: row-reverse; justify-content: flex-end;">
            <i class="fas fa-trash chatify-hover-delete-btn" data-id="{{ $id }}"></i>
            <p style="margin-left: 5px;">
                {!! ($message == null && $attachment != null && @$attachment[2] != 'file') ? $attachment[1] : nl2br($message) !!}
                <sub title="{{ $fullTime }}" class="message-time">
                    <span class="fas fa-{{ $seen > 0 ? 'check-double' : 'check' }} seen"></span> {{ $time }}</sub>
                </sub>
                
                @if(@$attachment[2] == 'file')
                <div>
                    <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment[0], 'type' => $viewType]) }}" class="file-download">
                        <span class="fas fa-file"></span> {{$attachment[1]}}</a>
                </div>
                @endif
            </p>
        </div>
        
        @if(@$attachment[2] == 'image')
        <div class="image-file chat-image" style="">
            <img src="{{ Chatify1::getAttachmentUrl($attachment[0], $viewType) }}" style="width: 100%;">
        </div>
        @endif
    </div>
@endif --}}
