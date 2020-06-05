@component('mail::message')
# Dear {{$firstName}},

### We are happy to see you at eDrug!

Thank you for registration, Once you've completed mobile verification, you can access your Control Panel to buy new products and check all your transactions and more!

Thanks,<br>
{{ config('app.name') }}
@endcomponent