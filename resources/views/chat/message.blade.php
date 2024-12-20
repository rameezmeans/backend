{{-- -------------------- The default card (white) -------------------- --}}
@if($viewType == 'default')
    @if($from_id != $to_id)

     <div class="message clearfix">
        <div class="chat-bubble from-them">
            {!! ($message == null && $attachment != null && @$attachment[2] != 'file') ? $attachment[1] : nl2br($message) !!}
            
            @if(@$attachment[2] == 'file')
            <a style="display: block;" href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment[0], 'type' => $viewType]) }}" style="color: #595959;" class="file-download">
                <span class="fas fa-file"></span> {{$attachment[1]}}</a>
            @endif
            @if(@$attachment[2] == 'image')
            <div class="image-file chat-image">
                <img width="100%;" src="{{ Chatify1::getAttachmentUrl($attachment[0], $viewType) }}">
            </div>
            @endif
            <sub style="font-size: 8px; display: block; margin-bottom: 5px;  margin-top: 10px;" title="{{ $fullTime }}">{{ $time }}</sub>
        </div>
    </div> 

    {{-- <div class="message-card hide" data-id="{{ $id }}">
        <div class="chatify-d-flex chatify-align-items-center" style="flex-direction: row-reverse; justify-content: flex-end;">
            <p>{!! ($message == null && $attachment != null && @$attachment[2] != 'file') ? $attachment[1] : nl2br($message) !!}
                <sub title="{{ $fullTime }}">{{ $time }}</sub>
                
                @if(@$attachment[2] == 'file')
                <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment[0], 'type' => $viewType]) }}" style="color: #595959;" class="file-download">
                    <span class="fas fa-file"></span> {{$attachment[1]}}</a>
                @endif
            </p>
        </div>
        
        @if(@$attachment[2] == 'image')
        <div class="image-file chat-image" style="width: 250px; height: 150px;background-image: url('{{ Chatify1::getAttachmentUrl($attachment[0], $viewType) }}')">
        </div>
        @endif
    </div> --}}

    @endif
@endif

{{-- -------------------- Sender card (owner) -------------------- --}}
@if($viewType == 'sender')

    <div class="message clearfix">
        <div class="chat-bubble from-me">
            {!! ($message == null && $attachment != null && @$attachment[2] != 'file') ? $attachment[1] : nl2br($message) !!}
               
               
                @if(@$attachment[2] == 'file')
                <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment[0], 'type' => $viewType]) }}" class="file-download">
                    <span class="fas fa-file"></span> {{$attachment[1]}}</a>
                @endif
                @if(@$attachment[2] == 'image')
                <div class="image-file chat-image">
                    <img width="100%;" src="{{ Chatify1::getAttachmentUrl($attachment[0], $viewType) }}">
                </div>
                @endif
                <sub style="font-size: 8px; display: block; margin-bottom: 5px;  margin-top: 10px;" title="{{ $fullTime }}" class="message-time">
                    <span class="fas fa-{{ $seen > 0 ? 'check-double' : 'check' }} seen"></span> {{ $time }}
                </sub>
        </div>
    </div>
    {{-- <div class="message-card mc-sender hide" title="{{ $fullTime }}" data-id="{{ $id }}">
        <div class="chatify-d-flex chatify-align-items-center" style="flex-direction: row-reverse; justify-content: flex-end;">
            <i class="fas fa-trash chatify-hover-delete-btn" data-id="{{ $id }}"></i>
            <p style="margin-left: 5px;">
                {!! ($message == null && $attachment != null && @$attachment[2] != 'file') ? $attachment[1] : nl2br($message) !!}
                <sub title="{{ $fullTime }}" class="message-time">
                    <span class="fas fa-{{ $seen > 0 ? 'check-double' : 'check' }} seen"></span> {{ $time }}</sub>
                </sub>
                {{-- If attachment is a file --}}
                {{-- @if(@$attachment[2] == 'file')
                <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment[0], 'type' => $viewType]) }}" class="file-download">
                    <span class="fas fa-file"></span> {{$attachment[1]}}</a>
                @endif
            </p>
        </div> --}}
        {{-- If attachment is an image --}}
        {{-- @if(@$attachment[2] == 'image')
        <div class="image-file chat-image" style="margin-top:10px;width: 250px; height: 150px;background-image: url('{{ Chatify1::getAttachmentUrl($attachment[0],  $viewType) }}')">
        </div>
        @endif --}}
    {{-- </div>  --}}
@endif
