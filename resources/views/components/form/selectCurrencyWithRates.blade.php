<div class="input-group mb-3">
    <div class="input-group-prepend">
        <label class="input-group-text" for="{{$id}}">{{$text}}</label>
    </div>
    <select name="{{$name}}" class="custom-select" id="{{$id}}">
        @if(!isset($attributes['nooptgroup']))
            <optgroup label="Most Popular">
        @endif

            @foreach($rates as $rate)
                @if($rate->code == 'EUR' || $rate->code == 'USD' || $rate->code == 'CHF' || $rate->code == 'GBP' || $rate->code == 'JPY' || $rate->code == 'AUD')
                    <option value="{{$rate->rate}}" @if($rate->code == $selectedByDefault) selected @endif>{{$rate->code}} | {{$rate->currency->name}}</option>
                @endif   
            @endforeach

        @if(!isset($attributes['nooptgroup']))
            </optgroup>
        
            
                @foreach($rates as $rate)
                    @if(!isset($firstLetter))
                        <optgroup label="{{$firstLetter = $rate->code[0]}}">
                    @endif
                
                    @if($rate->code[0] != $firstLetter)
                        </optgroup>
                        <optgroup label="{{$firstLetter = $rate->code[0]}}">
                    @endif

                    <option value="{{$rate->rate}}">{{$rate->code}} | {{$rate->currency->name}}</option>
                @endforeach
            </optgroup>
        @endif
    </select>
</div>