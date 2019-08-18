<div class="form-group">
    <label for="{{$id}}">{{$text}}</label>
    <input name="{{$name}}" type="text" class="form-control" id="{{$id}}" value="{{$value}}" @foreach($attributes as $key => $value) {{$key}}='{{$value}}' @endforeach>
  </div>