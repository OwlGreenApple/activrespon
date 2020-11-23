@if(count($messages) > 0)
  @foreach($messages as $row)
    @if($row->from_user_id == Auth::id())
      <div id="chat-id-{{ $row->id }}" class="alert alert-primary col-md-6 chat-text">{{ $row->chat_message }}</div>
    @endif
    
    @if($row->to_user_id == Auth::id())
      <div id="chat-id-{{ $row->id }}" class="alert alert-success col-md-6 chat-text ml-auto">{{ $row->chat_message }}</div>
    @endif
  @endforeach
@endif
