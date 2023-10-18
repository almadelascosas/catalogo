<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
//define('PUBLIC_KEY_STRIPE', 'pk_test_zGQX2GkUSU7V8OdY9HZgtyWv');
//define('PRIVATE_KEY_STRIPE', 'sk_test_lSjOnIqHW34lEIksiBt7mVYg');
$CI =& get_instance();

require_once(APPPATH.'third_party/stripe_sca/init.php');
\Stripe\Stripe::setApiKey(PRIVATE_KEY_STRIPE);

function deleteCard($customer,$card_id){
  $return_data = array();
  $return_data['result'] = 0;
  $return_data['error'] = array();
  $return_data['card'] = array();
    try {
      $customer->sources->retrieve($card_id)->delete();

    } catch(\Stripe\Error\Card $e) {
      // Since it's a decline, \Stripe\Error\Card will be caught
      $return_data['error'] = $e->getJsonBody()['error'];
      return $return_data;
    } catch (\Stripe\Error\RateLimit $e) {
      // Too many requests made to the API too quickly
      return $return_data;
    } catch (\Stripe\Error\InvalidRequest $e) {
      // Invalid parameters were supplied to Stripe's API
      return $return_data;
    } catch (\Stripe\Error\Authentication $e) {
      // Authentication with Stripe's API failed
      // (maybe you changed API keys recently)
      return $return_data;
    } catch (\Stripe\Error\ApiConnection $e) {
      // Network communication with Stripe failed
      return $return_data;
    } catch (\Stripe\Error\Base $e) {
      // Display a very generic error to the user, and maybe send
      // yourself an email
      return $return_data;
    } catch (Exception $e) {
      // Something else happened, completely unrelated to Stripe
      return $return_data;
    }
    $return_data['result'] = 1;
    return $return_data;
}

function retriveCard($customer,$token_card){
  $return_data = array();
  $return_data['result'] = 0;
  $return_data['error'] = array();
  $return_data['card'] = array();
    try {
      $return_data['card'] =$customer->sources->retrieve($token_card);

    } catch(\Stripe\Error\Card $e) {
      // Since it's a decline, \Stripe\Error\Card will be caught

      $return_data['error'] = $e->getJsonBody()['error'];
      return $return_data;
    } catch (\Stripe\Error\RateLimit $e) {
      // Too many requests made to the API too quickly
      return $return_data;
    } catch (\Stripe\Error\InvalidRequest $e) {
      // Invalid parameters were supplied to Stripe's API
      return $return_data;
    } catch (\Stripe\Error\Authentication $e) {
      // Authentication with Stripe's API failed
      // (maybe you changed API keys recently)
      return $return_data;
    } catch (\Stripe\Error\ApiConnection $e) {
      // Network communication with Stripe failed
      return $return_data;
    } catch (\Stripe\Error\Base $e) {
      // Display a very generic error to the user, and maybe send
      // yourself an email
      return $return_data;
    } catch (Exception $e) {
      // Something else happened, completely unrelated to Stripe
      return $return_data;
    }
    $return_data['result'] = 1;
    return $return_data;
}

function addCard($customer,$token_card)
{

  $return_data = array();
  $return_data['result'] = 0;
  $return_data['error'] = array();
  $return_data['card'] = array();
    try {
      $return_data['card'] = $customer->sources->create(array("source" => $token_card));

    } catch(\Stripe\Error\Card $e) {
      // Since it's a decline, \Stripe\Error\Card will be caught
      $return_data['error'] = $e->getJsonBody()['error'];
      return $return_data;
    } catch (\Stripe\Error\RateLimit $e) {
      // Too many requests made to the API too quickly
      return $return_data;
    } catch (\Stripe\Error\InvalidRequest $e) {
      // Invalid parameters were supplied to Stripe's API
      return $return_data;
    } catch (\Stripe\Error\Authentication $e) {
      // Authentication with Stripe's API failed
      // (maybe you changed API keys recently)
      return $return_data;
    } catch (\Stripe\Error\ApiConnection $e) {
      // Network communication with Stripe failed
      return $return_data;
    } catch (\Stripe\Error\Base $e) {
      // Display a very generic error to the user, and maybe send
      // yourself an email
      return $return_data;
    } catch (Exception $e) {
      // Something else happened, completely unrelated to Stripe
      return $return_data;
    }
    $return_data['result'] = 1;
    return $return_data;
}


function generatePaymentResponse($intent) {
  $return_data = array();
  $return_data['result'] = 0;
  $return_data['requires_action'] = 0;
  $return_data['payment_intent_client_secret'] = '';
    if ($intent->status == 'requires_source_action' &&
        $intent->next_action->type == 'use_stripe_sdk') {

          $return_data['result'] = 2;
          $return_data['requires_action'] = 1;
          $return_data['payment_intent_client_secret'] = $intent->client_secret;
        # Tell the client to handle the action

        /*echo json_encode([
            'requires_action' => true,
            'payment_intent_client_secret' => $intent->client_secret
        ]);
        */
    } else if ($intent->status == 'succeeded') {
        # The payment didn’t need any additional actions and completed!
        # Handle post-payment fulfillment
        $return_data['result'] = 1;
        $return_data['requires_action'] = 0;
        $return_data['payment_intent_client_secret'] = '';
    } else {
        # Invalid status
        //http_response_code(500);
        //echo json_encode(['error' => 'Invalid PaymentIntent status']);
        $return_data['result'] = 0;
        $return_data['requires_action'] = 0;
        $return_data['payment_intent_client_secret'] = '';
    }
    return $return_data;
}
function confirmPayment($payment_id)
{
  $return_data = array();
  $return_data['result'] = 0;
  $return_data['error'] = array();
  $return_data['charge'] = array();
  try {
    $intent = \Stripe\PaymentIntent::retrieve($payment_id);
    $return_data['charge'] = $intent->confirm();
    return $return_data;
  } catch (Exception $e) {
    $return_data['error_type'] = "Exception";
    $return_data['error'] = $e->getJsonBody()['error'];
    return $return_data;
  }
}
function createPay($order_id,$token,$amount,$description="")
{

  $return_data = array();
  $return_data['result'] = 0;
  $return_data['error'] = array();
  $return_data['charge'] = array();
  $return_data['test'] = $order_id." ".$token." ".$amount." ".$description."FIN";
  $return_data['private'] = PRIVATE_KEY_STRIPE;
    try {
      /*
      $charge = \Stripe\Charge::create(array(
       "amount" => $amount,
       "currency" => "eur",
       "description" => $description,
       "metadata" => array("order_id" => $order_id),
       "source" => $token
      ));
      */


      $intent = \Stripe\PaymentIntent::create([
          'payment_method' => $token,
           "amount" => $amount,
          'currency' => 'eur',
          "description" => $description,
          "metadata" => array("order_id" => $order_id),
          'confirmation_method' => 'manual',
          'confirm' => true,
      ]);

       $respuesta = generatePaymentResponse($intent);

       $respuesta['charge'] = $intent;
       return $respuesta;

    } catch(\Stripe\Error\Card $e) {
      // Since it's a decline, \Stripe\Error\Card will be caught
      $return_data['error_type'] = "Card";
      $return_data['error'] = $e->getJsonBody()['error'];
      return $return_data;
    } catch (\Stripe\Error\RateLimit $e) {
      $return_data['error_type'] = "RateLimit";
      $return_data['error'] = $e->getJsonBody()['error'];
      return $return_data;

    } catch (\Stripe\Error\InvalidRequest $e) {
      $return_data['error_type'] = "InvalidRequest";
      $return_data['error'] = $e->getJsonBody()['error'];
      return $return_data;
    } catch (\Stripe\Error\Authentication $e) {
      $return_data['error_type'] = "Authentication";
      $return_data['error'] = $e->getJsonBody()['error'];
      return $return_data;
    } catch (\Stripe\Error\ApiConnection $e) {
      $return_data['error_type'] = "ApiConnection";
       $return_data['error'] = $e->getJsonBody()['error'];
        return $return_data;
    } catch (\Stripe\Error\Base $e) {
      $return_data['error_type'] = "Base";
      $return_data['error'] = $e->getJsonBody()['error'];
      return $return_data;
    } catch (Exception $e) {

      $return_data['error_type'] = "Exception";
      $return_data['error'] = $e->getJsonBody()['error'];
      return $return_data;
    }
    $return_data['result'] = 1;
    $return_data['charge'] = $charge;
    return $return_data;
}




?>
