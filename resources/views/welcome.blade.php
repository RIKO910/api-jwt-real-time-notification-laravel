{{--resources/views/welcome.blade.php--}}
@component('mail::message')
    <h1>We have received your request to reset your account password</h1>
    <p>You can use the following code to recover your account:</p>

    @component('mail::panel')
        {{ $code }}  <!-- Displaying the $code passed from the Mailable class -->
    @endcomponent

    <p>The allowed duration of the code is one hour from the time the message was sent.</p>
@endcomponent
