<div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title display-4 text-center">Currency Conventer</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <h5 class="display-5">Currency I Have</h5>
                {{ Form::open() }}
                {{ Form::bsSelectCurrencyWithRates('currency1', 'Currency', $latestrates, 'ihaveselect', 'EUR') }}
                {{ Form::bsText('amountihave', 'Amount', 'ihavetext') }}
                {{ Form::close() }}
            </div>
            <div class="col-6">
                <h5 class="display-5">Currency I Want</h5>
                {{ Form::open() }}
                {{ Form::bsSelectCurrencyWithRates('currency2', 'Currency', $latestrates, 'iwantselect', 'USD') }}
                {{ Form::bsText('amountireceive', 'Amount', 'iwanttext') }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>