<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://js.stripe.com/v3/"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Laravel 10 - Stripe Payment!💲</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <h1>Laravel 10 - Stripe Payment</h1>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Stripe Payment
                    </div>
                    <div class="card-body">
                        @if(Session::has('error'))
                            <font color="red">{{Session::get('error')}}</font>
                        @endif
                        <form action="{!!route ('addmoney.stripe')!!}" method="post" id="payment-form" class="form-horizontal">
                            @csrf
                            <div class="mb-3">
                                <label class="control-label">Detalles de tarjeta</label>
                               <div id="card-element" class="form-control">
                                    <!-- A Stripe Element will be inserted here. -->
                               </div>
                            </div>
                            <div class="card-errors" role="alert"></div>
                            <div class="mb-3">
                                <h5 class="total">Total:<span class="amount">$100</span></h5>
                            </div>

                            <div class="mb-3">
                                <button id="myButton" type="submit" class="form-control btn btn-success submit-button">Pagar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    
    // Create an instance of the card Element.
    var card = elements.create('card', {
        'placeholder': '',
    });
    
    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');
    
    card.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    // Handle form submission.
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
    
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                Swal.fire({
                icon: 'success',
                title: 'Error de pago',
                text: 'Por favor, verifique los datos de su tarjeta!',
                showConfirmButton: false,
                })
            } else {
                // Send the token to your server.
                
                stripeTokenHandler(result.token);
                Swal.fire({
                icon: 'success',
                title: 'Pago exitoso',
                text: 'Gracias por su compra!',
                showConfirmButton: false,
                })
            }
        });
    });
    
    // Submit the form with the token ID.
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
        // Submit the form
        form.submit();
    }
    </script>
</html>