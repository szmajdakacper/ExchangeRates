<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('bsSelectCurrency', 'components.form.selectCurrency', ['name', 'text', 'currencies' => null, 'id' => '', 'selectedByDefault' => 0, 'attributes' => []]);
        Form::component('bsSelectCurrencyWithRates', 'components.form.selectCurrencyWithRates', ['name', 'text', 'rates' => null, 'id' => '', 'selectedByDefault' => 0, 'attributes' => []]);
        Form::component('bsSelectPeriod', 'components.form.selectPeriod', ['name', 'id' => '', 'activePeriod', 'attributes' => []]);
        Form::component('bsButton', 'components.form.button', ['value' => 'Send', 'attributes' => []]);
        Form::component('bsText', 'components.form.text', ['name', 'text', 'id' => '', 'value' => 1, 'attributes' => []]);
        Form::component('bsTextPreApp', 'components.form.textPreApp', ['name', 'text', 'id' => '', 'value' => 1, 'attributes' => []]);
        Form::component('bsTextWithButton', 'components.form.textWithButton', ['name', 'text', 'placeholder']);
        Form::component('hidden', 'components.form.hidden', ['name', 'value']);
    }
}
