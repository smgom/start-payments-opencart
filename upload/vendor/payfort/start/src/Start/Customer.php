<?php
class Start_Customer
{
  /**
  * Create a new customer for given $data
  *
  * @param array $data the data for the new customer
  * @return array the result of the customer
  * @throws Start_Error_Card if the card could not be accepted
  * @throws Start_Error_Parameters if any of the parameters is invalid
  * @throws Start_Error_Authentication if the API Key is invalid
  * @throws Start_Error if there is a general error in the API endpoint
  * @throws Exception for any other errors
  */
  public static function create(array $data)
  {
    $url = Start::getEndPoint('customer');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CAINFO, Start::getCaPath());
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, Start::getApiKey() . ':');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = json_decode(curl_exec($ch), true);
    // Check for errors and such.
    $info = curl_getinfo($ch);
    $errno = curl_errno($ch);
    if( $result === false || $errno != 0 ) {
      // Do error checking
      throw new Exception(curl_error($ch));
    } else if($info['http_code'] < 200 || $info['http_code'] > 299) {
      // Got a non-200 error code.
      Start::handleErrors($result, $info['http_code']);
    }
    curl_close($ch);
    return $result;
  }
  /**
  * List all created customers
  *
  * @return array list of customers
  * @throws Start_Error_Parameters if any of the parameters is invalid
  * @throws Start_Error_Authentication if the API Key is invalid
  * @throws Start_Error if there is a general error in the API endpoint
  * @throws Exception for any other errors
  */
  public static function all()
  {
    $url = Start::getEndPoint('customer_list');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CAINFO, Start::getCaPath());
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, Start::getApiKey() . ':');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Start/PHP/' . Start::VERSION);
    $result = json_decode(curl_exec($ch), true);

    // Check for errors and such.
    $info = curl_getinfo($ch);
    $errno = curl_errno($ch);
    if( $result === false || $errno != 0 ) {
      // Do error checking
      throw new Exception(curl_error($ch));
    } else if($info['http_code'] < 200 || $info['http_code'] > 299) {
      // Got a non-200 error code.
      Start::handleErrors($result, $info['http_code']);
    }
    curl_close($ch);
    return $result;
  }
}
