<div class="input-group col-lg-4 col-md-12 mb-3">
    <div class="input-group-prepend">
        <label class="input-group-text" for="{{$id}}">{{$text}}</label>
    </div>
    <select class="custom-select" name="{{$name}}" id="{{$id}}">
    @if(!isset($attributes['nooptgroup']))
        <optgroup label="Most Popular">
            <!-- {{$i = 0}} -->
    @endif
            @foreach($currencies as $currency)
                @if($currency->code == 'EUR' || $currency->code == 'USD' || $currency->code == 'CHF' || $currency->code == 'GBP' || $currency->code == 'JPY' || $currency->code == 'AUD')
                    <option value="{{$currency->code}}">{{$currency->code}} | {{$currency->name}}</option>
                @endif   
            @endforeach

    @if(!isset($attributes['nooptgroup']))
        </optgroup>
    
        
            @foreach($currencies as $currency)
                @if(!isset($firstLetter))
                    <optgroup label="{{$firstLetter = $currency->code[0]}}">
                @endif
            
                @if($currency->code[0] != $firstLetter)
                    </optgroup>
                    <optgroup label="{{$firstLetter = $currency->code[0]}}">
                @endif

                <option value="{{$currency->code}}" @if($currency->code == $selectedByDefault) selected @endif>{{$currency->code}} | {{$currency->name}}</option>
            @endforeach
        </optgroup>
    @endif
    </select>
</div>