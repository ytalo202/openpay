<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Pay</title>
    <link rel="stylesheet" href="{{ asset("open_pay/styles.css")}}">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://js.openpay.pe/openpay.v1.min.js"></script>
    <script type='text/javascript' src="https://js.openpay.pe/openpay-data.v1.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--    <script src="{{ asset('js/backend/ajax/_js_sale_point.js') }}"></script>--}}
</head>
<body>
<div class="main-container">
    <div class="method-card__inputs" id="formularioPago_1">
        <input type="text" data-openpay-card="holder_name" placeholder="Nombre del Titular" value="nombre apellido"/>
        <input type="text" data-openpay-card="expiration_month" placeholder="MM" value="10"/>
        <input type="text" data-openpay-card="expiration_year" placeholder="AA" value="25"/>
        <input type="text" data-openpay-card="card_number" placeholder="Número de la Tarjeta" value="4111111111111111"/>
        <input type="text" data-openpay-card="cvv2" placeholder="Código de seguridad" value="122"/>


        <input type="text" id="amount" placeholder="Amount" value="100"/>
        <input type="text" id="descripcion" placeholder="Descripción" value="descripcion base prueba"/>
        <select name="" id="" placeholder="Nº de Cuotas sin intereses">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
        <input type="text" id="deviceIdHiddenFieldName" name="deviceIdHiddenFieldName"/>
        <input type="text" id="token_id" name="token_id"/>
    </div>
    <button type="submit" class="method-card__button" id="pay-button" name="pay-button">Pagar</button>


    <div class="method-digital" id="method-digital" style="display: none;">
        <div class="method-digital__images">
            <img src="{{ asset("open_pay/images/bbva.svg")}}" alt="bbva">
            <img src="{{ asset("open_pay/images/bcp.svg")}}" alt="bcp">
            <img src="{{ asset("open_pay/images/interbank.svg")}}" alt="interbank">
            <img src="{{ asset("open_pay/images/scotiabank.svg")}}" alt="scotiabank">
        </div>
        <p class="method-digital__description">
            En la App o Web de tu banco ingresa a la opción <span>"Pago de Servicios"</span>,
            busca <span>"Kashio"</span>, coloca el código de pago que llegará a tu correo
            electrónico y realiza el pago.
        </p>
        <button class="method-digital__button">
            Genera tu código
        </button>
    </div>

    <div class="method-digital" id="method-agent" style="display: none;">
        <div class="method-digital__images">
            <img src="{{ asset("open_pay/images/bbva.svg")}}" alt="bbva">
            <img src="{{ asset("open_pay/images/bcp.svg")}}" alt="bcp">
            <img src="{{ asset("open_pay/images/interbank.svg")}}" alt="interbank">
            <img src="{{ asset("open_pay/images/scotiabank.svg")}}" alt="scotiabank">
            <img src="{{ asset("open_pay/images/tambo.svg")}}" alt="tambo">
            <img src="{{ asset("open_pay/images/kasnet.svg")}}" alt="kasnet">
        </div>
        <p class="method-digital__description">
            Paga en agentes, agencias o establecimientos afiliados con el código de
            referencia que llegará a tu correo electrónico, indicando que deseas
            pagar <span>"Kashio"</span>.
        </p>
        <button class="method-digital__button">
            Genera tu código
        </button>
    </div>
</div>

{{--<script src="{{ asset("open_pay/main.js")}}"></script>--}}

<script type="text/javascript">
    $(document).ready(function () {
        var openpayConfig = {
            merchant_id: "mel8zy7mctoszlhuv9zv",
            private_key: "sk_cf87aac9942441f3921c687a32f440b3",
            public_key: "pk_22415ad1975e4ebe84c3b79158f953aa",
        }

        OpenPay.setId(openpayConfig.merchant_id);
        OpenPay.setApiKey(openpayConfig.public_key);
        OpenPay.setSandboxMode(true);
        var deviceSessionId = OpenPay.deviceData.setup("formularioPago_1", "deviceIdHiddenFieldName");
        console.log("**********************")
        console.log("deviceSessionId:" + deviceSessionId)
        console.log("**********************")
        $('#deviceIdHiddenFieldName').val(deviceSessionId);
    });
    //pago con tarjeta:
    $('#pay-button').on('click', function (event) {
        //alert("Prueba")
        event.preventDefault();
        $("#pay-button").prop("disabled", true);
        OpenPay.token.extractFormAndCreate('formularioPago_1', success_callbak, error_callbak);
    });

    var success_callbak = function (response) {
        var token_id = response.data.id;
        $('#token_id').val(token_id);
        var device_session_id = $('#deviceIdHiddenFieldName').val();
        // alert(token_id)
        // $('#formularioPago_1').submit();
        console.log('device_session_id',device_session_id)
        if (true){
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('checkout') }}',
                data: {token_id: token_id,device_session_id:device_session_id},
                success: function (response) {
                    console.log('response', response)
                }, error(e) {
                    console.log('error:', e)
                }
            })
        }


    };
    var error_callbak = function (response) {
        var desc = response.data.description != undefined ? response.data.description : response.message;
        alert("ERROR [" + response.status + "] " + desc);
        $("#pay-button").prop("disabled", false);
    };

</script>

</body>
</html>
