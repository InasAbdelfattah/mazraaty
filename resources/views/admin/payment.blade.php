
<?php 
$lang = $_GET['lang'];
$order = $_GET['orderId'];
$amount = $_GET['amount'];

?>
<link rel="stylesheet" href="https://www.paytabs.com/express/express.css">
<script src="https://www.paytabs.com/theme/express_checkout/js/jquery-1.11.1.min.js"></script>
<script src="https://www.paytabs.com/express/express_checkout_v3.js"></script>
<!-- Button Code for PayTabs Express Checkout -->
<div class="PT_express_checkout"></div>
<script type="text/javascript">

    Paytabs("#express_checkout").expresscheckout({
        settings:{
            //secret_key:"7Q4Ii7s9YcMoyOcJiRvs6ngDAO5sbA8iqc1IjtRiGDGesSYKKucQRturiAUta3LAfNizg9SXNycfra0cFc2wFwYY6TrgWcQcZ9Lg",
            secret_key:"P6PyP9e93y1fb2Y9ff9uLykd0gTX9WoY2exSnM1rl3Gx5EGkUPPFoaToqNmAJKyMY6EmTFNfJtMO8t92jYIH8B6zos98dIUcOzyI",

            merchant_id: "10027701",
            //merchant_id: "10028405",
            amount: "{{$amount}}",
            currency: "SAR",
            title: "{{$order}}",
            product_names: "{{$order}}",
            order_id: '{{$order}}',
            url_redirect: "https://etapromotion.com/api/confirm-payment?lang={{$locale}}",
            display_billing_fields: 0,
            display_shipping_fields:0,
            display_customer_info: 1,
            language: '{{$lang}}',
            redirect_on_reject: 1,
            // style:{
            //     css: "custom",
            //     //linktocss: "https://www.yourstore.com/css/style.css",
            //     linktocss : "https://etapromotion.com/assets/admin/css/payment.css"
                

            // },
            is_iframe:{
                load: "onbodyload",
                show: 0,
            },

        },
        customer_info:{
            first_name: "",
            last_name: "",
            phone_number: "",
            country_code: "966",
            email_address: ""            
        },
        billing_address:{
            full_address: "SAU",
            city: "SAU",
            state: "SAU",
            country: "SAU",
            postal_code: "966"
        },
        shipping_address:{
            shipping_first_name: "John",
            shipping_last_name: "Smith",
            full_address_shipping: "SAU",
            city_shipping: "SAU",
            state_shipping: "SAU",
            country_shipping: "SAU",
            postal_code_shipping: "966"
        },
        checkout_button:{
            width: 150,
            height: 30,
           // img_url: "https://www.YOURWEBSITE.com/image/yourimage.jpg"
        },
        pay_button:{
            width: 150,
            height: 30,
            //img_url: "https://www.YOURWEBSITE.com/image/yourimage.jpg"
        }
    });
</script>

